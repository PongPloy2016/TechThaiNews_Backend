-- ========================================================
-- Schema: bookmarks for Local MySQL (MAMP / phpMyAdmin)
-- Project: PHP-source-nearby / Nearby IT News
-- File: home/mysql_bookmarks_schema.sql
-- ========================================================

CREATE TABLE IF NOT EXISTS `bookmarks` (
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
  `source` VARCHAR(255) DEFAULT NULL COMMENT 'สำนักข่าว (เช่น Blognone, Beartai)',
  `category` VARCHAR(100) DEFAULT NULL COMMENT 'หมวดหมู่ข่าว',
  `published_millis` BIGINT DEFAULT NULL COMMENT 'เวลาที่เผยแพร่ (Timestamp Milliseconds)',
  `reading_time` INT DEFAULT 1 COMMENT 'เวลาอ่านโดยประมาณ (นาที)',
  `images_json` TEXT DEFAULT NULL COMMENT 'JSON Array เก็บรูปภาพประกอบ',
  `sync_status` ENUM('synced', 'pending', 'failed', 'local_only') NOT NULL DEFAULT 'pending' COMMENT 'สถานะการซิงค์: synced (สำเร็จ), pending (รอซิงค์), failed (ผิดพลาด), local_only (เก็บเฉพาะเครื่อง)',
  `sync_error` TEXT DEFAULT NULL COMMENT 'ข้อความแจ้งเตือนกรณีซิงค์ล้มเหลว',
  `synced_at` DATETIME DEFAULT NULL COMMENT 'วันเวลาที่ซิงค์กับ Supabase สำเร็จล่าสุด',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่บันทึก',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'เวลาที่แก้ไขล่าสุด',
  
  -- Constraints & Indexes
  UNIQUE KEY `uniq_device_article` (`device_id`, `article_id`),
  INDEX `idx_sync_status` (`sync_status`),
  INDEX `idx_device_id` (`device_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
