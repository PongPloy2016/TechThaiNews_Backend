<?php
/**
 * TechThaiNews Home & Feeds Module Configuration
 * Supabase Connection & News Feed Constants
 */

if (file_exists(__DIR__ . '/config.local.php')) {
    require_once __DIR__ . '/config.local.php';
}

if (!defined('SUPABASE_URL')) {
    define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://zpigezwdsjelwvdpyahb.supabase.co');
}
if (!defined('SUPABASE_KEY')) {
    define('SUPABASE_KEY', getenv('SUPABASE_KEY') ?: 'YOUR_SUPABASE_SERVICE_ROLE_KEY');
}
if (!defined('SUPABASE_ANON_KEY')) {
    define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: 'YOUR_SUPABASE_ANON_KEY');
}

if (!defined('TABLE_FEED_GROUPS')) {
    define('TABLE_FEED_GROUPS', 'feed_groups');
}
if (!defined('TABLE_FEED_SOURCES')) {
    define('TABLE_FEED_SOURCES', 'feed_sources');
}
if (!defined('NEWS_APP_NAME')) {
    define('NEWS_APP_NAME', 'TechThaiNews & Feed Hub');
}
if (!defined('APP_NAME')) {
    define('APP_NAME', 'TechThaiNews & Feed Hub');
}
if (!defined('APP_TAGLINE')) {
    define('APP_TAGLINE', 'ศูนย์รวมแหล่งข้อมูลและระบบจัดการฟีดข่าวสาร (RSS/Atom Feeds)');
}
if (!defined('CONTACT_EMAIL')) {
    define('CONTACT_EMAIL', 'pongku71@gmail.com');
}
if (!defined('CONTACT_PHONE')) {
    define('CONTACT_PHONE', '0839040153');
}
if (!defined('CONTACT_PHONE_DISPLAY')) {
    define('CONTACT_PHONE_DISPLAY', '083-904-0153');
}
