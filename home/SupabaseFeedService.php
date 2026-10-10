<?php
/**
 * Supabase Feed Service
 * Handles CRUD operations for public.feed_groups and public.feed_sources
 */

require_once __DIR__ . '/config.php';

class SupabaseFeedService {

    /**
     * Perform HTTP request to Supabase REST API
     *
     * @param string $endpoint
     * @param string $method
     * @param array|null $payload
     * @return array ['code' => int, 'data' => mixed, 'error' => string|null]
     */
    public static function request($endpoint, $method = 'GET', $payload = null) {
        $url = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . ltrim($endpoint, '/');
        $apiKey = SUPABASE_KEY;

        $headers = [
            'apikey: ' . $apiKey,
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];

        $jsonPayload = $payload !== null ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null;

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($jsonPayload !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $raw = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($raw === false) {
                return ['code' => 500, 'data' => null, 'error' => 'cURL Error: ' . $err];
            }

            $decoded = json_decode($raw, true);
            return ['code' => $code, 'data' => $decoded, 'error' => null];
        } else {
            $httpHeaders = implode("\r\n", $headers);
            $options = [
                'http' => [
                    'header'  => $httpHeaders . "\r\n",
                    'method'  => $method,
                    'content' => $jsonPayload,
                    'timeout' => 15,
                    'ignore_errors' => true
                ]
            ];
            $context = stream_context_create($options);
            $raw = @file_get_contents($url, false, $context);

            $code = 200;
            if (isset($http_response_header) && is_array($http_response_header)) {
                if (preg_match('#HTTP/\S+\s+(\d+)#', $http_response_header[0], $m)) {
                    $code = (int)$m[1];
                }
            }

            $decoded = $raw ? json_decode($raw, true) : null;
            return ['code' => $code, 'data' => $decoded, 'error' => null];
        }
    }

    /**
     * Get feed group status map from local cache
     *
     * @return array
     */
    public static function getFeedGroupStatusMap() {
        $statusFile = dirname(__DIR__) . '/home/cache/feed_groups_status.json';
        if (file_exists($statusFile)) {
            $json = @file_get_contents($statusFile);
            $map = json_decode($json, true);
            if (is_array($map)) return $map;
        }
        return [];
    }

    /**
     * Save feed group status map to local cache
     *
     * @param array $map
     */
    public static function saveFeedGroupStatusMap($map) {
        $dir = dirname(__DIR__) . '/home/cache';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        $statusFile = $dir . '/feed_groups_status.json';
        @file_put_contents($statusFile, json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Get all Feed Groups ordered by order_index with is_active flag
     *
     * @param bool $onlyActive If true, only returns active feed groups
     * @return array
     */
    public static function getFeedGroups($onlyActive = false) {
        $res = self::request('feed_groups?select=*&order=order_index.asc,label.asc', 'GET');
        if ($res['code'] >= 200 && $res['code'] < 300 && is_array($res['data'])) {
            $statusMap = self::getFeedGroupStatusMap();
            $items = [];
            foreach ($res['data'] as $group) {
                $id = $group['id'] ?? '';
                $key = $group['key'] ?? '';
                
                // Determine is_active status: DB column if exists, otherwise statusMap, defaults to true
                if (isset($group['is_active'])) {
                    $isActive = (bool)$group['is_active'];
                } elseif (isset($statusMap[$key])) {
                    $isActive = (bool)$statusMap[$key];
                } elseif (isset($statusMap[$id])) {
                    $isActive = (bool)$statusMap[$id];
                } else {
                    $isActive = true;
                }
                $group['is_active'] = $isActive;

                if ($onlyActive && !$isActive) {
                    continue;
                }
                $items[] = $group;
            }
            return $items;
        }
        return [];
    }

    /**
     * Toggle Feed Group open/close (is_active) status
     *
     * @param string $idOrKey
     * @param bool|int|string $isActive
     * @return array
     */
    public static function toggleFeedGroupStatus($idOrKey, $isActive) {
        $isActive = ($isActive === '1' || $isActive === true || $isActive === 'true' || $isActive === 1);
        
        $groups = self::getFeedGroups(false);
        $targetGroup = null;
        foreach ($groups as $g) {
            if (($g['id'] ?? '') === $idOrKey || ($g['key'] ?? '') === $idOrKey) {
                $targetGroup = $g;
                break;
            }
        }

        $id = $targetGroup['id'] ?? $idOrKey;
        $key = $targetGroup['key'] ?? $idOrKey;
        $label = $targetGroup['label'] ?? $key;

        // 1. Update local status map
        $statusMap = self::getFeedGroupStatusMap();
        $statusMap[$key] = $isActive;
        $statusMap[$id] = $isActive;
        self::saveFeedGroupStatusMap($statusMap);

        // 2. Try updating directly to Supabase if column is_active exists
        $supabaseUpdated = false;
        $res = self::request('feed_groups?id=eq.' . urlencode($id), 'PATCH', ['is_active' => $isActive]);
        if ($res['code'] >= 200 && $res['code'] < 300) {
            $supabaseUpdated = true;
        }

        $stateText = $isActive ? 'เปิดใช้งาน' : 'ปิดการใช้งาน';
        return [
            'success' => true,
            'message' => "{$stateText}หมวดหมู่ '{$label}' สำเร็จเรียบร้อย!",
            'is_active' => $isActive,
            'supabase_updated' => $supabaseUpdated
        ];
    }

    /**
     * Update an existing Feed Group in public.feed_groups
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public static function updateFeedGroup($id, $data) {
        $key = trim($data['key'] ?? '');
        $label = trim($data['label'] ?? '');
        $color = trim($data['color'] ?? '#2F6FED');
        $orderIndex = isset($data['order_index']) && $data['order_index'] !== '' ? (int)$data['order_index'] : 0;

        if (empty($label)) {
            return ['success' => false, 'message' => 'กรุณาระบุชื่อกลุ่มฟีด (Label)'];
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#2F6FED';
        }

        $payload = [
            'label'       => $label,
            'color'       => $color,
            'order_index' => $orderIndex
        ];
        if (!empty($key)) {
            $payload['key'] = $key;
        }

        if (isset($data['is_active'])) {
            $isActive = !empty($data['is_active']) && ($data['is_active'] === '1' || $data['is_active'] === true || $data['is_active'] === 'true');
            $statusMap = self::getFeedGroupStatusMap();
            if (!empty($key)) $statusMap[$key] = $isActive;
            $statusMap[$id] = $isActive;
            self::saveFeedGroupStatusMap($statusMap);

            $payloadWithActive = $payload;
            $payloadWithActive['is_active'] = $isActive;
            $res = self::request('feed_groups?id=eq.' . urlencode($id), 'PATCH', $payloadWithActive);
            if ($res['code'] >= 200 && $res['code'] < 300) {
                return [
                    'success' => true,
                    'message' => "บันทึกการแก้ไขหมวดหมู่ '{$label}' เรียบร้อยแล้ว!"
                ];
            }
        }

        $res = self::request('feed_groups?id=eq.' . urlencode($id), 'PATCH', $payload);
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return [
                'success' => true,
                'message' => "บันทึกการแก้ไขหมวดหมู่ '{$label}' เรียบร้อยแล้ว!"
            ];
        }

        return [
            'success' => false,
            'message' => 'ไม่สามารถแก้ไขหมวดหมู่ได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'
        ];
    }

    /**
     * Delete a Feed Group from public.feed_groups
     *
     * @param string $id
     * @return array
     */
    public static function deleteFeedGroup($id) {
        $res = self::request('feed_groups?id=eq.' . urlencode($id), 'DELETE');
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return [
                'success' => true,
                'message' => 'ลบหมวดหมู่ข่าวสำเร็จเรียบร้อย!'
            ];
        }
        return [
            'success' => false,
            'message' => 'ไม่สามารถลบหมวดหมู่ได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'
        ];
    }

    /**
     * Get all Feed Sources with optional group filter and active status filter
     *
     * @param string|null $groupKey
     * @param bool $onlyActive If true, only returns active sources
     * @return array
     */
    public static function getFeedSources($groupKey = null, $onlyActive = false) {
        $endpoint = 'feed_sources?select=*&order=order_index.asc,created_at.desc';
        if (!empty($groupKey) && $groupKey !== 'all') {
            $endpoint .= '&group_key=eq.' . urlencode($groupKey);
        }
        if ($onlyActive) {
            $endpoint .= '&is_active=eq.true';
        }
        $res = self::request($endpoint, 'GET');
        if ($res['code'] >= 200 && $res['code'] < 300 && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * Add a new Feed Group into public.feed_groups
     *
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'item' => array|null]
     */
    public static function addFeedGroup($data) {
        $key = trim($data['key'] ?? '');
        $label = trim($data['label'] ?? '');
        $color = trim($data['color'] ?? '#2F6FED');
        $orderIndex = isset($data['order_index']) && $data['order_index'] !== '' ? (int)$data['order_index'] : 0;
        $isActive = !isset($data['is_active']) || ($data['is_active'] === '1' || $data['is_active'] === true || $data['is_active'] === 'true');

        // Auto-slugify key if empty
        if (empty($key) && !empty($label)) {
            $key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $label));
            $key = trim($key, '-');
        }

        if (empty($key)) {
            return ['success' => false, 'message' => 'กรุณาระบุรหัสกลุ่ม (Key) หรือชื่อกลุ่ม'];
        }

        if (empty($label)) {
            return ['success' => false, 'message' => 'กรุณาระบุชื่อกลุ่มฟีด (Label)'];
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#2F6FED';
        }

        $payload = [
            'key'         => $key,
            'label'       => $label,
            'color'       => $color,
            'order_index' => $orderIndex
        ];

        // Try insert with is_active first
        $payloadWithActive = $payload;
        $payloadWithActive['is_active'] = $isActive;
        $res = self::request('feed_groups', 'POST', $payloadWithActive);

        if (!($res['code'] >= 200 && $res['code'] < 300)) {
            // Retry without is_active if column doesn't exist
            $res = self::request('feed_groups', 'POST', $payload);
        }

        if ($res['code'] >= 200 && $res['code'] < 300) {
            $item = is_array($res['data']) && isset($res['data'][0]) ? $res['data'][0] : $res['data'];
            // Save status in map
            $statusMap = self::getFeedGroupStatusMap();
            $statusMap[$key] = $isActive;
            if (!empty($item['id'])) $statusMap[$item['id']] = $isActive;
            self::saveFeedGroupStatusMap($statusMap);

            return [
                'success' => true,
                'message' => "เพิ่มกลุ่มฟีด '{$label}' (Key: {$key}) สำเร็จเรียบร้อย!",
                'item'    => $item
            ];
        }

        $errorMsg = 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง';
        if (isset($res['data']['message'])) {
            $errorMsg .= ' (' . $res['data']['message'] . ')';
        }
        if ($res['code'] === 409) {
            $errorMsg = "รหัสกลุ่ม '{$key}' มีอยู่ในระบบแล้ว กรุณาใช้รหัสอื่น";
        }

        return ['success' => false, 'message' => $errorMsg];
    }

    /**
     * Add a new Feed Source into public.feed_sources
     *
     * @param array $data
     * @return array ['success' => bool, 'message' => string, 'item' => array|null]
     */
    public static function addFeedSource($data) {
        $key        = trim($data['key'] ?? '');
        $label      = trim($data['label'] ?? '');
        $url        = trim($data['url'] ?? '');
        $homepage   = trim($data['homepage'] ?? '');
        $type       = trim($data['type'] ?? 'rss');
        $groupKey   = trim($data['group_key'] ?? '');
        $orderIndex = isset($data['order_index']) && $data['order_index'] !== '' ? (int)$data['order_index'] : 0;
        $isActive   = isset($data['is_active']) ? (bool)$data['is_active'] : true;

        if (empty($label)) {
            return ['success' => false, 'message' => 'กรุณาระบุชื่อแหล่งข่าว (Label)'];
        }

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'กรุณาระบุ URL ฟีด RSS/Atom ที่ถูกต้อง (ขึ้นต้นด้วย http:// หรือ https://)'];
        }

        // Auto-extract homepage if empty
        if (empty($homepage)) {
            $parsed = parse_url($url);
            if (isset($parsed['scheme']) && isset($parsed['host'])) {
                $homepage = $parsed['scheme'] . '://' . $parsed['host'];
            } else {
                $homepage = $url;
            }
        } elseif (!filter_var($homepage, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'กรุณาระบุ Homepage URL ของเว็บไซต์ให้ถูกต้อง'];
        }

        // Auto-slugify key if empty
        if (empty($key)) {
            $key = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $label));
            $key = trim($key, '-');
            if (empty($key)) {
                $key = 'source-' . substr(md5($url), 0, 8);
            }
        }

        if (!in_array($type, ['rss', 'atom', 'json', 'api'])) {
            $type = 'rss';
        }

        $payload = [
            'key'         => $key,
            'label'       => $label,
            'url'         => $url,
            'homepage'    => $homepage,
            'type'        => $type,
            'group_key'   => !empty($groupKey) ? $groupKey : null,
            'order_index' => $orderIndex,
            'is_active'   => $isActive
        ];

        $res = self::request('feed_sources', 'POST', $payload);

        if ($res['code'] >= 200 && $res['code'] < 300) {
            $item = is_array($res['data']) && isset($res['data'][0]) ? $res['data'][0] : $res['data'];
            return [
                'success' => true,
                'message' => "เพิ่มแหล่งข่าว '{$label}' (Key: {$key}) สำเร็จเรียบร้อย!",
                'item'    => $item
            ];
        }

        $errorMsg = 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง';
        if (isset($res['data']['message'])) {
            $errorMsg .= ' (' . $res['data']['message'] . ')';
        }
        if ($res['code'] === 409) {
            $errorMsg = "รหัสแหล่งข่าว '{$key}' มีอยู่ในระบบแล้ว กรุณาใช้รหัสอื่น";
        }

        return ['success' => false, 'message' => $errorMsg];
    }

    /**
     * Toggle Feed Source open/close (is_active) status
     *
     * @param string $id
     * @param bool|int|string $isActive
     * @return array
     */
    public static function toggleFeedSourceStatus($id, $isActive) {
        $isActive = ($isActive === '1' || $isActive === true || $isActive === 'true' || $isActive === 1);
        $res = self::request('feed_sources?id=eq.' . urlencode($id), 'PATCH', ['is_active' => $isActive]);
        if ($res['code'] >= 200 && $res['code'] < 300) {
            $stateText = $isActive ? 'เปิดใช้งาน' : 'ปิดการใช้งาน';
            return [
                'success' => true,
                'message' => "{$stateText}แหล่งข่าวสำเร็จเรียบร้อย!",
                'is_active' => $isActive
            ];
        }
        return [
            'success' => false,
            'message' => 'ไม่สามารถเปลี่ยนสถานะได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'
        ];
    }

    /**
     * Update an existing Feed Source in public.feed_sources
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public static function updateFeedSource($id, $data) {
        $key        = trim($data['key'] ?? '');
        $label      = trim($data['label'] ?? '');
        $url        = trim($data['url'] ?? '');
        $homepage   = trim($data['homepage'] ?? '');
        $type       = trim($data['type'] ?? 'rss');
        $groupKey   = trim($data['group_key'] ?? '');
        $orderIndex = isset($data['order_index']) && $data['order_index'] !== '' ? (int)$data['order_index'] : 0;
        $isActive   = isset($data['is_active']) ? ($data['is_active'] === '1' || $data['is_active'] === true || $data['is_active'] === 'true' || $data['is_active'] === 1) : true;

        if (empty($label)) {
            return ['success' => false, 'message' => 'กรุณาระบุชื่อแหล่งข่าว (Label)'];
        }
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'กรุณาระบุ URL ฟีด RSS/Atom ที่ถูกต้อง (ขึ้นต้นด้วย http:// หรือ https://)'];
        }

        if (empty($homepage)) {
            $parsed = parse_url($url);
            if (isset($parsed['scheme']) && isset($parsed['host'])) {
                $homepage = $parsed['scheme'] . '://' . $parsed['host'];
            } else {
                $homepage = $url;
            }
        } elseif (!filter_var($homepage, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'กรุณาระบุ Homepage URL ของเว็บไซต์ให้ถูกต้อง'];
        }

        if (!in_array($type, ['rss', 'atom', 'json', 'api'])) {
            $type = 'rss';
        }

        $payload = [
            'label'       => $label,
            'url'         => $url,
            'homepage'    => $homepage,
            'type'        => $type,
            'group_key'   => !empty($groupKey) ? $groupKey : null,
            'order_index' => $orderIndex,
            'is_active'   => $isActive
        ];
        if (!empty($key)) {
            $payload['key'] = $key;
        }

        $res = self::request('feed_sources?id=eq.' . urlencode($id), 'PATCH', $payload);
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return [
                'success' => true,
                'message' => "บันทึกการแก้ไขแหล่งข่าว '{$label}' เรียบร้อยแล้ว!"
            ];
        }

        return [
            'success' => false,
            'message' => 'ไม่สามารถแก้ไขข้อมูลได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'
        ];
    }

    /**
     * Delete a Feed Source from public.feed_sources
     *
     * @param string $id
     * @return array
     */
    public static function deleteFeedSource($id) {
        $res = self::request('feed_sources?id=eq.' . urlencode($id), 'DELETE');
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return [
                'success' => true,
                'message' => 'ลบแหล่งข่าวสำเร็จเรียบร้อย!'
            ];
        }
        return [
            'success' => false,
            'message' => 'ไม่สามารถลบแหล่งข่าวได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'
        ];
    }

    /**
     * Test an RSS/Atom Feed URL to check connectivity and extract metadata
     *
     * @param string $url
     * @return array
     */
    public static function testRssFeed($url) {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return ['success' => false, 'message' => 'URL ไม่ถูกต้อง'];
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $raw = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false || $httpCode < 200 || $httpCode >= 400) {
            return ['success' => false, 'message' => "เชื่อมต่อไม่สำเร็จ (HTTP {$httpCode}: {$err})"];
        }

        // Try parsing XML
        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml !== false) {
            $title = (string)($xml->channel->title ?? $xml->title ?? 'ฟีดไม่มีชื่อ');
            $count = count($xml->channel->item ?? $xml->entry ?? []);
            return [
                'success' => true,
                'message' => "เชื่อมต่อฟีดสำเร็จ! (HTTP {$httpCode})",
                'title' => (string)$title,
                'items_count' => $count
            ];
        }

        return [
            'success' => true,
            'message' => "เชื่อมต่อสำเร็จ (HTTP {$httpCode}) แต่โครงสร้างอาจไม่ใช่ RSS/Atom มาตรฐาน",
            'title' => 'Raw Feed',
            'items_count' => 0
        ];
    }

    /**
     * Get app settings from public.app_settings
     *
     * @return array
     */
    public static function getAppSettings() {
        $res = self::request('app_settings?id=eq.default&select=*', 'GET');
        if ($res['code'] >= 200 && $res['code'] < 300 && is_array($res['data']) && !empty($res['data'])) {
            return $res['data'][0];
        }
        // Fallback default structure
        return [
            'id' => 'default',
            'is_ai_enabled' => true,
            'ai_model_name' => 'gemini-1.5-flash',
            'ai_max_tokens' => 400,
            'tts_default_rate' => 0.95,
            'ads_enabled' => false,
            'banner_ads_enabled' => false,
            'interstitial_ads_enabled' => false,
            'interstitial_interval_clicks' => 4,
            'interstitial_min_delay_sec' => 60,
            'admob_banner_id_android' => '',
            'admob_interstitial_id_android' => '',
            'min_supported_version' => '1.0.0',
            'latest_version' => '1.0.0',
            'force_update_title' => 'มีเวอร์ชันใหม่พร้อมใช้งาน',
            'force_update_message' => 'กรุณาอัปเดตเป็นเวอร์ชันล่าสุดเพื่อการใช้งานที่ราบรื่นและปลอดภัย',
            'is_maintenance' => false,
            'maintenance_message' => 'ระบบกำลังปิดปรับปรุงชั่วคราว ทีมงานกำลังเร่งแก้ไขครับ',
            'privacy_policy_url' => 'https://pongploydev.github.io/privacy-policy',
            'terms_url' => 'https://pongploydev.github.io/terms',
            'support_email' => 'pongku71@gmail.com',
            'play_store_url' => 'https://play.google.com/store/apps/details?id=com.pongploydev.technewsth.app',
            'announcement_active' => false,
            'announcement_message' => '',
            'breaking_news_active' => false,
            'breaking_news_text' => '',
            'breaking_news_url' => '',
            'default_feed_key' => 'tech-business',
            'cache_ttl_minutes' => 15,
            'default_card_layout' => 'magazine',
            'default_theme_mode' => 'system',
            'default_link_open_mode' => 'in_app',
            'default_font_size' => 'medium',
            'data_saver_default' => false,
            'cloud_sync_enabled' => true,
            'related_news_limit' => 10,
            'rss_source_name' => 'Blognone',
            'rss_source_url' => 'https://www.blognone.com',
            'contact_custom_url' => ''
        ];
    }

    /**
     * Update app settings in public.app_settings
     *
     * @param array $input
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public static function updateAppSettings($input) {
        $booleanFields = [
            'is_ai_enabled', 'ads_enabled', 'banner_ads_enabled', 'interstitial_ads_enabled',
            'is_maintenance', 'announcement_active', 'breaking_news_active', 'data_saver_default',
            'cloud_sync_enabled'
        ];
        $integerFields = [
            'ai_max_tokens', 'interstitial_interval_clicks', 'interstitial_min_delay_sec',
            'cache_ttl_minutes', 'related_news_limit'
        ];
        $numericFields = [
            'tts_default_rate'
        ];

        $notNullDefaults = [
            'ai_model_name' => 'gemini-1.5-flash',
            'min_supported_version' => '1.0.0',
            'latest_version' => '1.0.0',
            'default_feed_key' => 'tech-business',
            'default_card_layout' => 'magazine',
            'default_theme_mode' => 'system',
            'default_link_open_mode' => 'in_app',
            'default_font_size' => 'medium',
            'rss_source_name' => 'Blognone',
            'rss_source_url' => 'https://www.blognone.com',
            'ai_max_tokens' => 400,
            'interstitial_interval_clicks' => 4,
            'interstitial_min_delay_sec' => 60,
            'cache_ttl_minutes' => 15,
            'related_news_limit' => 10,
            'tts_default_rate' => 0.95
        ];

        $payload = [];
        $allowedFields = [
            'is_ai_enabled', 'ai_model_name', 'ai_max_tokens', 'tts_default_rate',
            'ads_enabled', 'banner_ads_enabled', 'interstitial_ads_enabled',
            'interstitial_interval_clicks', 'interstitial_min_delay_sec',
            'admob_banner_id_android', 'admob_interstitial_id_android',
            'min_supported_version', 'latest_version', 'force_update_title', 'force_update_message',
            'is_maintenance', 'maintenance_message',
            'privacy_policy_url', 'terms_url', 'support_email', 'play_store_url',
            'announcement_active', 'announcement_message',
            'breaking_news_active', 'breaking_news_text', 'breaking_news_url',
            'default_feed_key', 'cache_ttl_minutes',
            'default_card_layout', 'default_theme_mode', 'default_link_open_mode', 'default_font_size',
            'data_saver_default', 'cloud_sync_enabled', 'related_news_limit',
            'rss_source_name', 'rss_source_url', 'contact_custom_url'
        ];

        foreach ($allowedFields as $field) {
            if (in_array($field, $booleanFields)) {
                $payload[$field] = !empty($input[$field]) && ($input[$field] === '1' || $input[$field] === true || $input[$field] === 'true' || $input[$field] === 'on');
            } elseif (in_array($field, $integerFields)) {
                $payload[$field] = isset($input[$field]) && $input[$field] !== '' ? (int)$input[$field] : ($notNullDefaults[$field] ?? 0);
            } elseif (in_array($field, $numericFields)) {
                $payload[$field] = isset($input[$field]) && $input[$field] !== '' ? (float)$input[$field] : ($notNullDefaults[$field] ?? 0.95);
            } else {
                $val = isset($input[$field]) ? trim($input[$field]) : null;
                if ($val === null || $val === '') {
                    if (isset($notNullDefaults[$field])) {
                        $payload[$field] = $notNullDefaults[$field];
                    } else {
                        $payload[$field] = null;
                    }
                } else {
                    $payload[$field] = $val;
                }
            }
        }

        $payload['updated_at'] = date('c');

        // Update via PATCH
        $res = self::request('app_settings?id=eq.default', 'PATCH', $payload);

        if ($res['code'] >= 200 && $res['code'] < 300) {
            return [
                'success' => true,
                'message' => 'บันทึกการตั้งค่าแอปพลิเคชันลง Supabase สำเร็จเรียบร้อย!',
                'data'    => $res['data']
            ];
        }

        // If not found, try inserting default row
        $payload['id'] = 'default';
        $resInsert = self::request('app_settings', 'POST', $payload);
        if ($resInsert['code'] >= 200 && $resInsert['code'] < 300) {
            return [
                'success' => true,
                'message' => 'สร้างและบันทึกการตั้งค่าเริ่มต้นลง Supabase สำเร็จ!',
                'data'    => $resInsert['data']
            ];
        }

        $errMsg = 'HTTP ' . $res['code'];
        if (!empty($res['data']['message'])) {
            $errMsg = $res['data']['message'];
        } elseif (!empty($res['error'])) {
            $errMsg = $res['error'];
        }

        return [
            'success' => false,
            'message' => 'ไม่สามารถบันทึกไปยัง Supabase ได้ (' . $errMsg . ')'
        ];
    }

    /**
     * Get user feedbacks from public.app_feedbacks
     *
     * @param string|null $category
     * @param string|null $platform
     * @return array
     */
    public static function getFeedbacks($category = null, $platform = null) {
        $endpoint = 'app_feedbacks?select=*&order=created_at.desc';
        if (!empty($category) && $category !== 'all') {
            $endpoint .= '&category=eq.' . urlencode($category);
        }
        if (!empty($platform) && $platform !== 'all') {
            $endpoint .= '&device_platform=eq.' . urlencode($platform);
        }
        $res = self::request($endpoint, 'GET');
        if ($res['code'] >= 200 && $res['code'] < 300 && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * Delete a feedback message
     *
     * @param string $id
     * @return array
     */
    public static function deleteFeedback($id) {
        $res = self::request('app_feedbacks?id=eq.' . urlencode($id), 'DELETE');
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return ['success' => true, 'message' => 'ลบข้อความข้อเสนอแนะสำเร็จเรียบร้อย!'];
        }
        return ['success' => false, 'message' => 'ไม่สามารถลบข้อความได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'];
    }

    /**
     * Get user bookmarks from public.user_bookmarks
     *
     * @param int $limit
     * @param string|null $category
     * @return array
     */
    public static function getUserBookmarks($limit = 100, $category = null) {
        $endpoint = 'user_bookmarks?select=*&order=created_at.desc&limit=' . (int)$limit;
        if (!empty($category) && $category !== 'all') {
            $endpoint .= '&category=eq.' . urlencode($category);
        }
        $res = self::request($endpoint, 'GET');
        if ($res['code'] >= 200 && $res['code'] < 300 && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * Delete a user bookmark
     *
     * @param string $id
     * @return array
     */
    public static function deleteUserBookmark($id) {
        $res = self::request('user_bookmarks?id=eq.' . urlencode($id), 'DELETE');
        if ($res['code'] >= 200 && $res['code'] < 300) {
            return ['success' => true, 'message' => 'ลบรายการบุ๊กมาร์กสำเร็จเรียบร้อย!'];
        }
        return ['success' => false, 'message' => 'ไม่สามารถลบได้ (' . ($res['data']['message'] ?? 'HTTP ' . $res['code']) . ')'];
    }

    /**
     * Upsert a user bookmark to Supabase (public.user_bookmarks)
     *
     * @param array $payload
     * @return array
     */
    public static function upsertUserBookmark($payload) {
        // If supabase UUID id is present, update the existing row
        if (!empty($payload['id'])) {
            $supabaseId = $payload['id'];
            $patchData = $payload;
            unset($patchData['id']);
            $res = self::request('user_bookmarks?id=eq.' . urlencode($supabaseId), 'PATCH', $patchData);
            if ($res['code'] >= 200 && $res['code'] < 300) {
                return [
                    'success'     => true,
                    'supabase_id' => $supabaseId,
                    'message'     => 'อัปเดตข้อมูลบน Supabase เรียบร้อยแล้ว'
                ];
            }
        }

        // Check if row exists by device_id and article_id
        if (!empty($payload['device_id']) && !empty($payload['article_id'])) {
            $check = self::request('user_bookmarks?device_id=eq.' . urlencode($payload['device_id']) . '&article_id=eq.' . urlencode($payload['article_id']) . '&select=id', 'GET');
            if ($check['code'] === 200 && is_array($check['data']) && !empty($check['data'][0]['id'])) {
                $existingId = $check['data'][0]['id'];
                $patchData = $payload;
                unset($patchData['id']);
                $res = self::request('user_bookmarks?id=eq.' . urlencode($existingId), 'PATCH', $patchData);
                if ($res['code'] >= 200 && $res['code'] < 300) {
                    return [
                        'success'     => true,
                        'supabase_id' => $existingId,
                        'message'     => 'อัปเดตข้อมูลเดิมบน Supabase เรียบร้อยแล้ว'
                    ];
                }
            }
        }

        // Otherwise insert new record
        $insertData = $payload;
        unset($insertData['id']); // Let Supabase generate UUID
        $res = self::request('user_bookmarks', 'POST', $insertData);
        if ($res['code'] >= 200 && $res['code'] < 300) {
            $inserted = is_array($res['data']) && !empty($res['data'][0]) ? $res['data'][0] : $res['data'];
            return [
                'success'     => true,
                'data'        => $inserted,
                'supabase_id' => is_array($inserted) ? ($inserted['id'] ?? null) : null,
                'message'     => 'เพิ่มข้อมูลใหม่บน Supabase เรียบร้อยแล้ว'
            ];
        }

        $err = $res['error'] ?? ($res['data']['message'] ?? 'HTTP ' . $res['code']);
        return [
            'success' => false,
            'error'   => $err,
            'message' => 'ไม่สามารถบันทึกบน Supabase ได้: ' . $err
        ];
    }

    /**
     * Get information on cached RSS files
     *
     * @return array
     */
    public static function getCacheFilesInfo() {
        $cacheDir = dirname(__DIR__) . '/home/cache';
        $files = glob($cacheDir . '/*.json');
        $info = [];
        $ttl = 900; // 15 mins default

        foreach ($files as $filePath) {
            $filename = basename($filePath);
            $size = filesize($filePath);
            $modified = filemtime($filePath);
            $ageSeconds = time() - $modified;
            $isExpired = $ageSeconds > $ttl;

            $articleCount = 0;
            if (strpos($filename, 'rss_') === 0) {
                $content = @file_get_contents($filePath);
                $decoded = $content ? json_decode($content, true) : null;
                if (is_array($decoded)) {
                    $articleCount = count($decoded);
                }
            }

            $info[] = [
                'filename'      => $filename,
                'path'          => $filePath,
                'size_bytes'    => $size,
                'size_kb'       => round($size / 1024, 2),
                'modified_at'   => date('Y-m-d H:i:s', $modified),
                'age_seconds'   => $ageSeconds,
                'is_expired'    => $isExpired,
                'article_count' => $articleCount
            ];
        }

        return $info;
    }

    /**
     * Purge all RSS cache files
     *
     * @return array
     */
    public static function purgeAllCache() {
        $cacheDir = dirname(__DIR__) . '/home/cache';
        $files = glob($cacheDir . '/rss_*.json');
        $count = 0;
        foreach ($files as $f) {
            if (@unlink($f)) $count++;
        }
        return [
            'success' => true,
            'message' => "ล้างไฟล์แคชข่าวทั้งหมดเรียบร้อยแล้ว ({$count} ไฟล์) ระบบจะเริ่มดึงฟีดสดใหม่ทันที"
        ];
    }

    /**
     * Purge single cache file
     *
     * @param string $filename
     * @return array
     */
    public static function purgeCacheFile($filename) {
        $safeName = basename($filename);
        $filePath = dirname(__DIR__) . '/home/cache/' . $safeName;
        if (file_exists($filePath) && strpos($safeName, 'rss_') === 0) {
            @unlink($filePath);
            return ['success' => true, 'message' => "ล้างไฟล์แคช '{$safeName}' สำเร็จ!"];
        }
        return ['success' => false, 'message' => 'ไม่พบไฟล์แคชที่ระบุ'];
    }

    /**
     * Check health of multiple feed sources
     *
     * @param int $limit
     * @return array
     */
    public static function checkFeedSourcesHealth($limit = 10) {
        $sources = self::getFeedSources(null, true);
        $sample = array_slice($sources, 0, $limit);
        $results = [];

        foreach ($sample as $src) {
            $url = $src['url'] ?? '';
            $check = self::testRssFeed($url);
            $results[] = [
                'id'          => $src['id'] ?? '',
                'label'       => $src['label'] ?? '',
                'url'         => $url,
                'group_key'   => $src['group_key'] ?? '',
                'is_online'   => !empty($check['success']),
                'message'     => $check['message'] ?? '',
                'items_count' => $check['items_count'] ?? 0,
                'feed_title'  => $check['title'] ?? ''
            ];
        }

        return $results;
    }
}

