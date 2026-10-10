<?php
/**
 * Class BookmarkSyncService
 *
 * Handles dual-storage synchronization between Local MySQL (MAMP)
 * and Cloud Supabase (public.user_bookmarks).
 *
 * File: home/BookmarkSyncService.php
 */

if (!class_exists('SupabaseFeedService')) {
    $servicePath = __DIR__ . '/SupabaseFeedService.php';
    if (file_exists($servicePath)) {
        require_once $servicePath;
    }
}

class BookmarkSyncService {

    private static ?PDO $pdo = null;

    /**
     * Get or initialize PDO database connection
     *
     * @return PDO
     */
    public static function getDb(): PDO {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = defined('HOST_NAME') ? HOST_NAME : '127.0.0.1';
        $user = defined('DB_USERNAME') ? DB_USERNAME : 'root';
        $pass = defined('DB_PASSWORD') ? DB_PASSWORD : 'root';
        $name = defined('DB_NAME') ? DB_NAME : 'nearby_db';

        $ports = [3306, 8889];
        $lastException = null;

        foreach ($ports as $port) {
            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                self::$pdo = $pdo;
                return self::$pdo;
            } catch (PDOException $e) {
                $lastException = $e;
            }
        }

        throw new Exception("Cannot connect to MySQL database: " . ($lastException ? $lastException->getMessage() : 'Unknown error'));
    }

    /**
     * Ensure bookmarks table exists in MySQL
     *
     * @return void
     */
    public static function ensureTableExists(): void {
        try {
            $db = self::getDb();
            $sql = "CREATE TABLE IF NOT EXISTS `bookmarks` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `supabase_id` VARCHAR(64) DEFAULT NULL COMMENT 'UUID อ้างอิงจากตาราง public.user_bookmarks บน Supabase',
              `device_id` VARCHAR(128) NOT NULL COMMENT 'รหัสประจำเครื่อง Device ID หรือ admin-web',
              `article_id` VARCHAR(255) NOT NULL COMMENT 'รหัสอ้างอิงข่าว Article ID',
              `title` VARCHAR(500) NOT NULL COMMENT 'หัวข้อข่าว',
              `link` VARCHAR(1000) NOT NULL COMMENT 'ลิงก์ข่าวต้นฉบับ',
              `description` TEXT DEFAULT NULL COMMENT 'เนื้อหาย่อ',
              `content` LONGTEXT DEFAULT NULL COMMENT 'เนื้อหาข่าวฉบับเต็ม',
              `image_url` VARCHAR(1000) DEFAULT NULL COMMENT 'URL รูปภาพหน้าปก',
              `author` VARCHAR(255) DEFAULT NULL COMMENT 'ผู้เขียนหรือแหล่งข่าว',
              `source` VARCHAR(255) DEFAULT NULL COMMENT 'สำนักข่าว',
              `category` VARCHAR(100) DEFAULT NULL COMMENT 'หมวดหมู่ข่าว',
              `published_millis` BIGINT DEFAULT NULL COMMENT 'เวลาที่เผยแพร่',
              `reading_time` INT DEFAULT 1 COMMENT 'เวลาอ่านโดยประมาณ (นาที)',
              `images_json` TEXT DEFAULT NULL COMMENT 'JSON Array เก็บรูปภาพประกอบ',
              `sync_status` ENUM('synced', 'pending', 'failed', 'local_only') NOT NULL DEFAULT 'pending' COMMENT 'สถานะการซิงค์',
              `sync_error` TEXT DEFAULT NULL COMMENT 'ข้อความแจ้งเตือนกรณีซิงค์ล้มเหลว',
              `synced_at` DATETIME DEFAULT NULL COMMENT 'วันเวลาที่ซิงค์สำเร็จ',
              `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              UNIQUE KEY `uniq_device_article` (`device_id`, `article_id`),
              INDEX `idx_sync_status` (`sync_status`),
              INDEX `idx_device_id` (`device_id`),
              INDEX `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->exec($sql);
        } catch (Exception $e) {
            error_log("Error ensuring bookmarks table: " . $e->getMessage());
        }
    }

    /**
     * Save bookmark locally to MySQL with status 'pending'
     *
     * @param array $data
     * @return array
     */
    public static function saveBookmarkLocal(array $data): array {
        self::ensureTableExists();
        $db = self::getDb();

        $deviceId   = !empty($data['device_id']) ? trim($data['device_id']) : 'admin-web';
        $articleId  = !empty($data['article_id']) ? trim($data['article_id']) : md5($data['link'] ?? uniqid());
        $title      = !empty($data['title']) ? trim($data['title']) : 'ไม่มีหัวข้อข่าว';
        $link       = !empty($data['link']) ? trim($data['link']) : '#';
        $desc       = $data['description'] ?? null;
        $content    = $data['content'] ?? null;
        $imageUrl   = $data['image_url'] ?? null;
        $author     = $data['author'] ?? null;
        $source     = $data['source'] ?? 'เว็บข่าว';
        $category   = $data['category'] ?? 'ทั่วไป';
        $pubMillis  = !empty($data['published_millis']) ? (int)$data['published_millis'] : (time() * 1000);
        $readTime   = !empty($data['reading_time']) ? (int)$data['reading_time'] : 2;
        $imagesJson = !empty($data['images_json']) ? (is_array($data['images_json']) ? json_encode($data['images_json']) : $data['images_json']) : null;
        $syncStatus = !empty($data['sync_status']) ? $data['sync_status'] : 'pending';
        $supabaseId = $data['supabase_id'] ?? null;

        $sql = "INSERT INTO `bookmarks` 
            (`supabase_id`, `device_id`, `article_id`, `title`, `link`, `description`, `content`, `image_url`, `author`, `source`, `category`, `published_millis`, `reading_time`, `images_json`, `sync_status`)
            VALUES 
            (:sb_id, :dev_id, :art_id, :title, :link, :desc, :content, :img, :author, :source, :category, :pub_millis, :read_time, :imgs, :sync_status)
            ON DUPLICATE KEY UPDATE 
            `title` = VALUES(`title`),
            `link` = VALUES(`link`),
            `description` = VALUES(`description`),
            `content` = VALUES(`content`),
            `image_url` = VALUES(`image_url`),
            `author` = VALUES(`author`),
            `source` = VALUES(`source`),
            `category` = VALUES(`category`),
            `reading_time` = VALUES(`reading_time`),
            `images_json` = VALUES(`images_json`),
            `sync_status` = VALUES(`sync_status`),
            `updated_at` = NOW()";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':sb_id'       => $supabaseId,
            ':dev_id'      => $deviceId,
            ':art_id'      => $articleId,
            ':title'       => $title,
            ':link'        => $link,
            ':desc'        => $desc,
            ':content'     => $content,
            ':img'         => $imageUrl,
            ':author'      => $author,
            ':source'      => $source,
            ':category'    => $category,
            ':pub_millis'  => $pubMillis,
            ':read_time'   => $readTime,
            ':imgs'        => $imagesJson,
            ':sync_status' => $syncStatus,
        ]);

        $id = $db->lastInsertId();
        if (!$id) {
            // Find existing row id
            $find = $db->prepare("SELECT id FROM `bookmarks` WHERE `device_id` = :dev AND `article_id` = :art LIMIT 1");
            $find->execute([':dev' => $deviceId, ':art' => $articleId]);
            $id = $find->fetchColumn();
        }

        return [
            'success' => true,
            'id' => (int)$id,
            'message' => 'บันทึกข้อมูลบุ๊กมาร์กลง MySQL สำเร็จเรียบร้อย'
        ];
    }

    /**
     * Sync single bookmark row from MySQL up to Supabase (public.user_bookmarks)
     *
     * @param int $bookmarkId
     * @return array
     */
    public static function syncSingleToSupabase(int $bookmarkId): array {
        self::ensureTableExists();
        $db = self::getDb();

        $stmt = $db->prepare("SELECT * FROM `bookmarks` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $bookmarkId]);
        $row = $stmt->fetch();

        if (!$row) {
            return ['success' => false, 'error' => 'ไม่พบบุ๊กมาร์ก ID: ' . $bookmarkId];
        }

        $payload = [
            'device_id'        => $row['device_id'],
            'article_id'       => $row['article_id'],
            'title'            => $row['title'],
            'link'             => $row['link'],
            'description'      => $row['description'],
            'content'          => $row['content'],
            'image_url'        => $row['image_url'],
            'author'           => $row['author'],
            'source'           => $row['source'],
            'category'         => $row['category'],
            'published_millis' => (int)$row['published_millis'],
            'reading_time'     => (int)$row['reading_time'],
        ];

        if (!empty($row['supabase_id'])) {
            $payload['id'] = $row['supabase_id'];
        }

        // Call SupabaseFeedService
        $res = SupabaseFeedService::upsertUserBookmark($payload);

        if ($res['success']) {
            $supabaseId = $res['supabase_id'] ?? ($row['supabase_id'] ?? null);

            $update = $db->prepare("UPDATE `bookmarks` 
                SET `sync_status` = 'synced', 
                    `synced_at` = NOW(), 
                    `sync_error` = NULL, 
                    `supabase_id` = COALESCE(:sb_id, `supabase_id`) 
                WHERE `id` = :id");
            $update->execute([
                ':sb_id' => $supabaseId,
                ':id'    => $bookmarkId
            ]);

            return [
                'success'     => true,
                'supabase_id' => $supabaseId,
                'message'     => 'ซิงค์ข้อมูลกับ Supabase สำเร็จเรียบร้อย!'
            ];
        } else {
            $errMsg = $res['error'] ?? 'เกิดข้อผิดพลาดในการเชื่อมต่อ Supabase';
            $update = $db->prepare("UPDATE `bookmarks` 
                SET `sync_status` = 'failed', 
                    `sync_error` = :err 
                WHERE `id` = :id");
            $update->execute([
                ':err' => $errMsg,
                ':id'  => $bookmarkId
            ]);

            return [
                'success' => false,
                'error'   => $errMsg,
                'message' => 'ซิงค์ไม่สำเร็จ: ' . $errMsg
            ];
        }
    }

    /**
     * Sync all pending or failed bookmarks up to Supabase
     *
     * @return array
     */
    public static function syncPendingToSupabase(): array {
        self::ensureTableExists();
        $db = self::getDb();

        $stmt = $db->query("SELECT id FROM `bookmarks` WHERE `sync_status` IN ('pending', 'failed') ORDER BY id ASC");
        $rows = $stmt->fetchAll();

        $total = count($rows);
        $successCount = 0;
        $failedCount = 0;

        foreach ($rows as $r) {
            $res = self::syncSingleToSupabase((int)$r['id']);
            if ($res['success']) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success'       => true,
            'total'         => $total,
            'success_count' => $successCount,
            'failed_count'  => $failedCount,
            'message'       => "ซิงค์ข้อมูลสำเร็จ {$successCount} รายการ (ล้มเหลว {$failedCount} รายการ)"
        ];
    }

    /**
     * Pull bookmarks from Supabase Cloud down to Local MySQL
     *
     * @param int $limit
     * @return array
     */
    public static function pullFromSupabase(int $limit = 200): array {
        self::ensureTableExists();
        $db = self::getDb();

        $cloudList = SupabaseFeedService::getUserBookmarks($limit);
        if (empty($cloudList)) {
            return [
                'success'      => true,
                'pulled_count' => 0,
                'message'      => 'ไม่มีข้อมูลบุ๊กมาร์กบน Supabase ให้ดึง'
            ];
        }

        $pulledCount = 0;
        foreach ($cloudList as $cb) {
            $sql = "INSERT INTO `bookmarks` 
                (`supabase_id`, `device_id`, `article_id`, `title`, `link`, `description`, `content`, `image_url`, `author`, `source`, `category`, `published_millis`, `reading_time`, `sync_status`, `synced_at`)
                VALUES 
                (:sb_id, :dev_id, :art_id, :title, :link, :desc, :content, :img, :author, :source, :category, :pub_millis, :read_time, 'synced', NOW())
                ON DUPLICATE KEY UPDATE 
                `supabase_id` = VALUES(`supabase_id`),
                `title` = VALUES(`title`),
                `link` = VALUES(`link`),
                `description` = VALUES(`description`),
                `content` = VALUES(`content`),
                `image_url` = VALUES(`image_url`),
                `author` = VALUES(`author`),
                `source` = VALUES(`source`),
                `category` = VALUES(`category`),
                `reading_time` = VALUES(`reading_time`),
                `sync_status` = 'synced',
                `synced_at` = NOW()";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':sb_id'      => $cb['id'] ?? null,
                ':dev_id'     => !empty($cb['device_id']) ? $cb['device_id'] : 'mobile-user',
                ':art_id'     => !empty($cb['article_id']) ? $cb['article_id'] : md5($cb['link'] ?? uniqid()),
                ':title'      => !empty($cb['title']) ? $cb['title'] : 'ไม่มีหัวข้อข่าว',
                ':link'       => !empty($cb['link']) ? $cb['link'] : '#',
                ':desc'       => $cb['description'] ?? null,
                ':content'    => $cb['content'] ?? null,
                ':img'        => $cb['image_url'] ?? null,
                ':author'     => $cb['author'] ?? null,
                ':source'     => $cb['source'] ?? 'เว็บข่าว',
                ':category'   => $cb['category'] ?? 'ทั่วไป',
                ':pub_millis' => !empty($cb['published_millis']) ? (int)$cb['published_millis'] : (time() * 1000),
                ':read_time'  => !empty($cb['reading_time']) ? (int)$cb['reading_time'] : 2,
            ]);
            $pulledCount++;
        }

        return [
            'success'      => true,
            'pulled_count' => $pulledCount,
            'message'      => "ดึงข้อมูลจาก Supabase ลง MySQL สำเร็จจำนวน {$pulledCount} รายการ!"
        ];
    }

    /**
     * Get list of bookmarks from MySQL with filtering
     *
     * @param int $limit
     * @param string|null $category
     * @param string|null $syncStatus
     * @param string|null $search
     * @return array
     */
    public static function getLocalBookmarks(int $limit = 200, ?string $category = null, ?string $syncStatus = null, ?string $search = null): array {
        self::ensureTableExists();
        $db = self::getDb();

        $where = [];
        $params = [];

        if (!empty($category) && $category !== 'all') {
            $where[] = "`category` = :cat";
            $params[':cat'] = $category;
        }

        if (!empty($syncStatus) && $syncStatus !== 'all') {
            $where[] = "`sync_status` = :status";
            $params[':status'] = $syncStatus;
        }

        if (!empty($search)) {
            $where[] = "(`title` LIKE :q OR `source` LIKE :q OR `device_id` LIKE :q)";
            $params[':q'] = '%' . $search . '%';
        }

        $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        $sql = "SELECT * FROM `bookmarks` {$whereSql} ORDER BY `created_at` DESC LIMIT " . (int)$limit;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get count statistics for sync statuses
     *
     * @return array ['total' => int, 'synced' => int, 'pending' => int, 'failed' => int, 'local_only' => int]
     */
    public static function getSyncCounts(): array {
        self::ensureTableExists();
        $db = self::getDb();

        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN `sync_status` = 'synced' THEN 1 ELSE 0 END) as synced,
            SUM(CASE WHEN `sync_status` = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN `sync_status` = 'failed' THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN `sync_status` = 'local_only' THEN 1 ELSE 0 END) as local_only
            FROM `bookmarks`";

        $res = $db->query($sql)->fetch();
        return [
            'total'      => (int)($res['total'] ?? 0),
            'synced'     => (int)($res['synced'] ?? 0),
            'pending'    => (int)($res['pending'] ?? 0),
            'failed'     => (int)($res['failed'] ?? 0),
            'local_only' => (int)($res['local_only'] ?? 0),
        ];
    }

    /**
     * Delete local bookmark and optionally delete from Supabase
     *
     * @param int $id
     * @param bool $deleteFromSupabase
     * @return array
     */
    public static function deleteBookmark(int $id, bool $deleteFromSupabase = false): array {
        self::ensureTableExists();
        $db = self::getDb();

        $stmt = $db->prepare("SELECT `supabase_id` FROM `bookmarks` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($deleteFromSupabase && !empty($row['supabase_id'])) {
            SupabaseFeedService::deleteUserBookmark($row['supabase_id']);
        }

        $del = $db->prepare("DELETE FROM `bookmarks` WHERE `id` = :id LIMIT 1");
        $del->execute([':id' => $id]);

        return [
            'success' => true,
            'message' => 'ลบรายการบุ๊กมาร์กสำเร็จเรียบร้อย!'
        ];
    }
}
