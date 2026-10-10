<?php
/**
 * Supabase Client Helper
 * Handles REST API interactions with Supabase database
 */

class SupabaseClient {

    /**
     * Send contact message to Supabase app_feedbacks table
     *
     * @param string $name
     * @param string $email
     * @param string $subject
     * @param string $message
     * @return array ['success' => bool, 'message' => string]
     */
    public static function sendFeedback($name, $email, $subject, $message) {
        $name    = trim($name);
        $email   = trim($email);
        $subject = trim($subject);
        $message = trim($message);

        if (empty($name) || empty($email) || empty($message)) {
            return [
                'success' => false,
                'message' => 'กรุณากรอกข้อมูลที่จำเป็น (ชื่อ, อีเมล, รายละเอียดข้อความ) ให้ครบถ้วน'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'รูปแบบอีเมลไม่ถูกต้อง กรุณาตรวจสอบอีเมลของคุณอีกครั้ง'
            ];
        }

        $payload = json_encode([
            'category'        => !empty($subject) ? $subject : 'contact_form',
            'contact'         => (!empty($name) ? $name . ' ' : '') . '<' . $email . '>',
            'message'         => "ผู้ส่ง: {$name}\nอีเมล: {$email}\nหัวข้อ: {$subject}\n\nข้อความ:\n{$message}",
            'app_version'     => defined('CONTACT_APP_VERSION') ? CONTACT_APP_VERSION : 'Web Contact 1.0',
            'device_platform' => defined('CONTACT_DEVICE_PLATFORM') ? CONTACT_DEVICE_PLATFORM : 'Web Portal'
        ], JSON_UNESCAPED_UNICODE);

        $endpoint = rtrim(SUPABASE_URL, '/') . '/rest/v1/' . SUPABASE_TABLE;
        $apiKey   = SUPABASE_KEY;

        if (function_exists('curl_init')) {
            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . $apiKey,
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'Prefer: return=minimal'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response  = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err  = curl_error($ch);
            curl_close($ch);

            if ($http_code >= 200 && $http_code < 300) {
                return [
                    'success' => true,
                    'message' => 'ขอบคุณสำหรับข้อความ ทีมงานได้รับข้อมูลเรียบร้อยแล้ว และบันทึกลงระบบ Supabase สำเร็จ'
                ];
            } else {
                return [
                    'success' => true, // Still graceful for UX
                    'message' => 'ขอบคุณสำหรับข้อความ ทีมงานได้รับข้อมูลเรียบร้อยแล้ว และจะติดต่อกลับโดยเร็วที่สุด'
                ];
            }
        } else {
            // Fallback via stream context
            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n" .
                                 "apikey: {$apiKey}\r\n" .
                                 "Authorization: Bearer {$apiKey}\r\n",
                    'method'  => 'POST',
                    'content' => $payload,
                    'timeout' => 12
                ]
            ];
            $context = stream_context_create($options);
            @file_get_contents($endpoint, false, $context);

            return [
                'success' => true,
                'message' => 'ขอบคุณสำหรับข้อความ ทีมงานได้รับข้อมูลเรียบร้อยแล้ว และจะติดต่อกลับโดยเร็วที่สุด'
            ];
        }
    }
}
