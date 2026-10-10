<?php
/**
 * Contact Module Configuration
 * Supabase & Official Contact Settings
 */

// Supabase Configuration
if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

if (!defined('SUPABASE_URL')) {
    define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://zpigezwdsjelwvdpyahb.supabase.co');
}
if (!defined('SUPABASE_KEY')) {
    define('SUPABASE_KEY', getenv('SUPABASE_KEY') ?: 'YOUR_SUPABASE_SERVICE_ROLE_KEY');
}
if (!defined('SUPABASE_TABLE')) {
    define('SUPABASE_TABLE', 'app_feedbacks');
}

// Official Contact Information (Google Play News Policy Compliant)
define('CONTACT_PUBLISHER_NAME', 'กองบรรณาธิการข่าวสารและทีมพัฒนาระบบ');
define('CONTACT_EMAIL', 'pongku71@gmail.com');
define('CONTACT_PHONE', '0839040153');
define('CONTACT_PHONE_DISPLAY', '083-904-0153 (+66 83 904 0153)');
define('CONTACT_ADDRESS', 'กรุงเทพมหานคร ประเทศไทย (Bangkok, Thailand)');
define('CONTACT_OPERATING_HOURS', 'วันจันทร์ - วันศุกร์: 09:00 - 18:00 น.');
define('CONTACT_APP_VERSION', 'Web Contact 1.0');
define('CONTACT_DEVICE_PLATFORM', 'Web Portal (contact/)');
