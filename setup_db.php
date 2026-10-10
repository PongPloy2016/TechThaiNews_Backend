<?php
define('ENVIRONMENT', 'development');

define('FCPATH', __DIR__ . '/');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('VIEWPATH', __DIR__ . '/views/');
define('EXT', '.php');
define('MB_ENABLED', extension_loaded('mbstring'));
define('ICONV_ENABLED', extension_loaded('iconv'));

$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '8888';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost:8888';

require_once 'vendor/autoload.php';
require_once 'config/config.php';
require_once 'init.php';
require_once APPPATH . 'config/constants.php';
require_once BASEPATH . 'core/Common.php';

require_once(BASEPATH.'core/compat/mbstring.php');
require_once(BASEPATH.'core/compat/hash.php');
require_once(BASEPATH.'core/compat/password.php');
require_once(BASEPATH.'core/compat/standard.php');

$GLOBALS['CFG'] =& load_class('Config', 'core');
$GLOBALS['UNI'] =& load_class('Utf8', 'core');
$GLOBALS['URI'] =& load_class('URI', 'core');
$GLOBALS['RTR'] =& load_class('Router', 'core');
$GLOBALS['OUT'] =& load_class('Output', 'core');
$GLOBALS['SEC'] =& load_class('Security', 'core');
$GLOBALS['IN']  =& load_class('Input', 'core');
$GLOBALS['LANG'] =& load_class('Lang', 'core');

require_once BASEPATH . 'core/Controller.php';

function &get_instance() {
    return CI_Controller::get_instance();
}

spl_autoload_register('Modules::autoload');

require_once APPPATH . 'core/MAIN_Controller.php';

$CI = new MAIN_Controller();

echo "Initializing database tables...\n";

// Load helpers
$CI->load->helper('appcore/modulemanager');
$CI->load->helper('appcore/fmoduleloader');
$CI->load->helper('utils/functions');
$CI->load->helper('utils/security');
$CI->load->helper('setting/settings');
$CI->load->helper('setting/currency');
$CI->load->helper('user/group_access');
$CI->load->helper('user/user');

// Load models
$CI->load->model('setting/config_model', 'mConfigModel');
$CI->load->model('setting/setting_model', 'mSettingModel');
$CI->load->model('setting/currency_model', 'mCurrencyModel');
$CI->load->model('user/group_access_model', 'mGroupAccessModel');
$CI->load->model('user/user_model', 'mUserModel');
$CI->load->model('appcore/appcoremodel', 'mAppcoreModel');

// 1. Create Core Config & User tables
echo "1. Creating Core tables...\n";
$CI->mConfigModel->createConfigTable();
$CI->mSettingModel->updateSetting();
$CI->mSettingModel->updateFields();
$CI->mCurrencyModel->updateFields();
$CI->mCurrencyModel->initCurrencies();
$CI->mAppcoreModel->createTable();

$CI->mUserModel->createTable();
$CI->mUserModel->addFields();
$CI->mUserModel->updateFields();

$CI->mGroupAccessModel->createTableGroupAccess();
$CI->mGroupAccessModel->createTableModuleActions();
$CI->mGroupAccessModel->updateFields();

// Execute table creation SQL for all application modules
$tables_sql = array(
    "CREATE TABLE IF NOT EXISTS `category` (`id_category` int(11) NOT NULL AUTO_INCREMENT, `parent_id` int(11) DEFAULT 0, `category_name` varchar(100) DEFAULT NULL, `name` varchar(100) DEFAULT NULL, `image` text DEFAULT NULL, `_order` int(11) DEFAULT 0, `icon` varchar(100) DEFAULT NULL, `color` varchar(100) DEFAULT NULL, `cf_id` int(11) DEFAULT 0, PRIMARY KEY (`id_category`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `store` (`id_store` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT 0, `category_id` int(11) DEFAULT 0, `name` text DEFAULT NULL, `address` text DEFAULT NULL, `latitude` double DEFAULT 0, `longitude` double DEFAULT 0, `phone` varchar(100) DEFAULT NULL, `description` text DEFAULT NULL, `detail` text DEFAULT NULL, `tags` text DEFAULT NULL, `image` text DEFAULT NULL, `images` text DEFAULT NULL, `status` int(11) DEFAULT 1, `verified` int(11) DEFAULT 0, `user_id_manager` int(11) DEFAULT 0, `can_chat` int(11) DEFAULT 1, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, `vote` double DEFAULT 0, `nbr_votes` int(11) DEFAULT 0, `opening_time` text DEFAULT NULL, `website` text DEFAULT NULL, PRIMARY KEY (`id_store`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `rate` (`id_rate` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `rate` double DEFAULT 0, `review` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id_rate`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `opening_time` (`id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `day` varchar(20) DEFAULT NULL, `open_time` varchar(20) DEFAULT NULL, `close_time` varchar(20) DEFAULT NULL, `status` int(11) DEFAULT 1, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `offer` (`id_offer` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `name` text DEFAULT NULL, `description` text DEFAULT NULL, `value_type` varchar(10) DEFAULT 'percent', `offer_value` double DEFAULT 0, `currency` varchar(30) DEFAULT 'USD', `image` text DEFAULT NULL, `images` text DEFAULT NULL, `status` int(11) DEFAULT 1, `date_start` datetime DEFAULT NULL, `date_end` datetime DEFAULT NULL, `verified` int(11) DEFAULT 0, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, `is_deal` int(11) DEFAULT 0, `order_enabled` int(11) DEFAULT 0, `cf_id` int(11) DEFAULT 0, `order_button` text DEFAULT NULL, `payment_enabled` int(11) DEFAULT 0, `tags` text DEFAULT NULL, PRIMARY KEY (`id_offer`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `event` (`id_event` int(11) NOT NULL AUTO_INCREMENT, `id` int(11) DEFAULT 0, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `name` text DEFAULT NULL, `description` text DEFAULT NULL, `address` text DEFAULT NULL, `latitude` double DEFAULT 0, `longitude` double DEFAULT 0, `date_start` datetime DEFAULT NULL, `date_end` datetime DEFAULT NULL, `image` text DEFAULT NULL, `status` int(11) DEFAULT 1, `verified` int(11) DEFAULT 0, `tags` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, `tel` varchar(100) DEFAULT NULL, `web` text DEFAULT NULL, `email` varchar(100) DEFAULT NULL, PRIMARY KEY (`id_event`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `participants` (`id` int(11) NOT NULL AUTO_INCREMENT, `event_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `guest_id` varchar(100) DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `service` (`id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `name` text DEFAULT NULL, `description` text DEFAULT NULL, `price` double DEFAULT 0, `currency` varchar(30) DEFAULT NULL, `image` text DEFAULT NULL, `status` int(11) DEFAULT 1, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `translate` (`id` int(11) NOT NULL AUTO_INCREMENT, `lang` varchar(10) DEFAULT NULL, `key_name` text DEFAULT NULL, `val` text DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `ns_banners` (`id` int(11) NOT NULL AUTO_INCREMENT, `title` text DEFAULT NULL, `image` text DEFAULT NULL, `module` varchar(50) DEFAULT NULL, `module_id` int(11) DEFAULT 0, `status` int(11) DEFAULT 1, `_order` int(11) DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `nsh_notifications` (`id` int(11) NOT NULL AUTO_INCREMENT, `label` varchar(100) DEFAULT NULL, `label_description` text DEFAULT NULL, `title` text DEFAULT NULL, `message` text DEFAULT NULL, `image` text DEFAULT NULL, `auth_type` varchar(100) DEFAULT NULL, `auth_id` int(11) DEFAULT NULL, `module` varchar(50) DEFAULT NULL, `module_id` int(11) DEFAULT 0, `detail` text DEFAULT NULL, `status` int(11) DEFAULT 0, `updated_at` datetime DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `bookmarks` (`id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT 0, `guest_id` varchar(100) DEFAULT NULL, `module` varchar(50) DEFAULT NULL, `module_id` int(11) DEFAULT 0, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `booking` (`id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `service_id` int(11) DEFAULT 0, `booking_date` datetime DEFAULT NULL, `status` int(11) DEFAULT 0, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `payouts` (`id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT 0, `amount` double DEFAULT 0, `status` int(11) DEFAULT 0, `note` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `cf_list` (`id` int(11) NOT NULL AUTO_INCREMENT, `label` text DEFAULT NULL, `type` varchar(50) DEFAULT NULL, `module` varchar(50) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `cf_fields` (`id` int(11) NOT NULL AUTO_INCREMENT, `cf_id` int(11) DEFAULT 0, `module_id` int(11) DEFAULT 0, `value` text DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `coupons` (`id` int(11) NOT NULL AUTO_INCREMENT, `offer_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `code` varchar(100) DEFAULT NULL, `status` int(11) DEFAULT 0, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `gallery` (`id` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `image` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `packmanager` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) DEFAULT NULL, `recommended` int(11) DEFAULT NULL, `duration` int(11) DEFAULT NULL, `price` double DEFAULT 0, `price_yearly` double DEFAULT NULL, `display` int(11) NOT NULL DEFAULT '1', `nbr_products_monthly` int(11) DEFAULT NULL, `nbr_events_monthly` int(11) DEFAULT NULL, `nbr_campaigns_monthly` int(11) DEFAULT NULL, `push_campaign_auto` tinyint(1) DEFAULT NULL, `nbr_stores` int(11) DEFAULT NULL, `description` varchar(300) DEFAULT NULL, `grp_access_id` int(11) DEFAULT NULL, `trial_period` int(11) NOT NULL DEFAULT '1', `_order` int(11) DEFAULT 0, `updated_at` datetime DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `invoice` (`id` int(11) NOT NULL AUTO_INCREMENT, `no` int(11) DEFAULT NULL, `method` varchar(30) DEFAULT NULL, `module` varchar(60) DEFAULT NULL, `module_id` int(11) DEFAULT NULL, `user_id` int(11) DEFAULT 0, `amount` double DEFAULT 0, `paid` double DEFAULT 0, `tax_id` int(11) DEFAULT NULL, `taxes` varchar(50) DEFAULT NULL, `currency` varchar(30) DEFAULT 'THB', `items` text DEFAULT NULL, `extras` text DEFAULT NULL, `status` varchar(50) DEFAULT NULL, `transaction_id` varchar(60) DEFAULT NULL, `rp_type` varchar(30) DEFAULT NULL, `rp_frequency` varchar(30) DEFAULT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `payment_transactions` (`id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) DEFAULT 0, `amount` double DEFAULT 0, `status` varchar(50) DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `taxes` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) DEFAULT NULL, `rate` double DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `files` (`id` int(11) NOT NULL AUTO_INCREMENT, `filename` text DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `cms_pages` (`id` int(11) NOT NULL AUTO_INCREMENT, `title` text DEFAULT NULL, `content` text DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `cms_uri` (`id` int(11) NOT NULL AUTO_INCREMENT, `page_id` int(11) DEFAULT 0, `slug` varchar(100) DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `cms_menu` (`id` int(11) NOT NULL AUTO_INCREMENT, `label` varchar(100) DEFAULT NULL, `url` text DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `token` (`id` varchar(100) NOT NULL, `uid` int(11) DEFAULT 0, `type` varchar(50) DEFAULT NULL, `content` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `discussion` (`id_discussion` int(11) NOT NULL AUTO_INCREMENT, `sender_id` int(11) DEFAULT 0, `receiver_id` int(11) DEFAULT 0, `status` int(11) DEFAULT 0, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`id_discussion`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `message` (`id_message` bigint(20) NOT NULL AUTO_INCREMENT, `discussion_id` int(11) DEFAULT 0, `sender_id` int(11) DEFAULT 0, `receiver_id` int(11) DEFAULT 0, `content` text DEFAULT NULL, `status` int(11) DEFAULT -1, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id_message`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `ns_tracker` (`id` int(11) NOT NULL AUTO_INCREMENT, `module` varchar(50) DEFAULT NULL, `module_id` int(11) DEFAULT 0, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `image` (`id_image` int(11) NOT NULL AUTO_INCREMENT, `image` varchar(255) DEFAULT NULL, `type` varchar(100) DEFAULT NULL, `user_id` int(11) DEFAULT 0, `used` int(11) DEFAULT 0, `managed_by` varchar(100) DEFAULT '', `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, PRIMARY KEY (`id_image`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `city` (`id_city` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) DEFAULT NULL, `country` int(11) DEFAULT '0', `longitude` double DEFAULT NULL, `latitude` double DEFAULT 0, `country_name` varchar(60) DEFAULT NULL, `country_code` varchar(60) DEFAULT NULL, `local_gp_ref` varchar(100) DEFAULT NULL, PRIMARY KEY (`id_city`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `wallet` (`id` int(11) NOT NULL AUTO_INCREMENT, `balance` double DEFAULT 0, `currency` varchar(20) DEFAULT 'USD', `user_id` int(11) DEFAULT 0, `updated_at` datetime DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `wallet_transaction` (`id` int(11) NOT NULL AUTO_INCREMENT, `amount` double DEFAULT 0, `currency` varchar(20) DEFAULT 'USD', `no` varchar(100) DEFAULT NULL, `operation` varchar(50) DEFAULT NULL, `note` text DEFAULT NULL, `user_id` int(11) DEFAULT 0, `updated_at` datetime DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `wallet_banks` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) DEFAULT NULL, `account_number` varchar(100) DEFAULT NULL, `holder_name` varchar(100) DEFAULT NULL, `country` varchar(100) DEFAULT NULL, `user_id` int(11) DEFAULT 0, `updated_at` datetime DEFAULT NULL, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "CREATE TABLE IF NOT EXISTS `block` (`id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL DEFAULT 0, `blocked_id` int(11) NOT NULL DEFAULT 0, `created_at` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
);

foreach ($tables_sql as $sql) {
    $CI->db->query($sql);
}

// Guarantee required columns exist in store table
if (!$CI->db->field_exists('detail', 'store')) {
    $CI->db->query("ALTER TABLE `store` ADD `detail` TEXT NULL AFTER `description`");
}
if (!$CI->db->field_exists('tags', 'store')) {
    $CI->db->query("ALTER TABLE `store` ADD `tags` TEXT NULL AFTER `detail`");
}
if (!$CI->db->field_exists('featured', 'store')) {
    $CI->db->query("ALTER TABLE `store` ADD `featured` INT DEFAULT 0");
}
$event_cols = array(
    'tags' => "TEXT NULL",
    'verified' => "INT DEFAULT 0",
    'images' => "TEXT DEFAULT NULL",
    'date_b' => "DATETIME DEFAULT NULL",
    'date_e' => "DATETIME DEFAULT NULL",
    'featured' => "INT DEFAULT 0",
    'lat' => "DOUBLE DEFAULT 0",
    'lng' => "DOUBLE DEFAULT 0",
);
foreach ($event_cols as $col => $type) {
    if ($CI->db->table_exists('event') && !$CI->db->field_exists($col, 'event')) {
        $CI->db->query("ALTER TABLE `event` ADD `$col` $type");
    }
}
if (!$CI->db->field_exists('dateLogin', 'user')) {
    $CI->db->query("ALTER TABLE `user` ADD `dateLogin` DATETIME DEFAULT NULL");
}
if (!$CI->db->field_exists('typeAuth', 'user')) {
    $CI->db->query("ALTER TABLE `user` ADD `typeAuth` VARCHAR(50) DEFAULT NULL");
}

$subscribe_cols = array(
    'will_expired' => "DATETIME DEFAULT NULL",
    'pack_id' => "INT DEFAULT NULL",
    'status' => "INT DEFAULT NULL",
    'reminded' => "INT DEFAULT 0"
);
foreach ($subscribe_cols as $col => $type) {
    if ($CI->db->table_exists('user_subscribe_setting') && !$CI->db->field_exists($col, 'user_subscribe_setting')) {
        $CI->db->query("ALTER TABLE `user_subscribe_setting` ADD `$col` $type");
    }
}

$opening_time_cols = array(
    'opening' => "VARCHAR(20) DEFAULT NULL",
    'closing' => "VARCHAR(20) DEFAULT NULL",
    'timezone' => "VARCHAR(60) DEFAULT 'UTC'",
    'enabled' => "INT(11) DEFAULT 0",
    'updated_at' => "DATETIME DEFAULT NULL",
    'created_at' => "DATETIME DEFAULT NULL",
);
foreach ($opening_time_cols as $col => $type) {
    if (!$CI->db->field_exists($col, 'opening_time')) {
        $CI->db->query("ALTER TABLE `opening_time` ADD `$col` $type");
    }
}

$gallery_cols = array(
    'module_id' => "INT(11) DEFAULT NULL",
    'module' => "VARCHAR(30) DEFAULT NULL",
    'image_id' => "INT(11) DEFAULT NULL",
    '_order' => "INT(11) DEFAULT 0",
    'updated_at' => "DATETIME DEFAULT NULL"
);
foreach ($gallery_cols as $col => $type) {
    if (!$CI->db->field_exists($col, 'gallery')) {
        $CI->db->query("ALTER TABLE `gallery` ADD `$col` $type");
    }
}

if ($CI->db->field_exists('id', 'rate') && !$CI->db->field_exists('id_rate', 'rate')) {
    $CI->db->query("ALTER TABLE `rate` CHANGE `id` `id_rate` INT(11) NOT NULL AUTO_INCREMENT");
}

$booking_cols = array(
    'amount' => "DOUBLE DEFAULT 0",
    'payment_status' => "VARCHAR(50) DEFAULT 'cod'",
    'cart' => "TEXT DEFAULT NULL",
    'cf_data' => "TEXT DEFAULT NULL",
    'cf_id' => "INT(11) DEFAULT NULL",
    'booking_type' => "VARCHAR(30) DEFAULT 'service'",
    'updated_at' => "DATETIME DEFAULT NULL"
);
foreach ($booking_cols as $col => $type) {
    if ($CI->db->table_exists('booking') && !$CI->db->field_exists($col, 'booking')) {
        $CI->db->query("ALTER TABLE `booking` ADD `$col` $type");
    }
}

$service_cols = array(
    'label' => "VARCHAR(120) DEFAULT NULL",
    'value' => "DOUBLE DEFAULT NULL",
    'hidden' => "INT(11) DEFAULT 0",
    'parent_id' => "INT(11) DEFAULT 0",
    '_order' => "INT(11) DEFAULT 0",
    'option_type' => "VARCHAR(100) DEFAULT 'one_option'"
);
foreach ($service_cols as $col => $type) {
    if (!$CI->db->field_exists($col, 'service')) {
        $CI->db->query("ALTER TABLE `service` ADD `$col` $type");
    }
}

$invoice_cols = array(
    'no' => "INT(11) DEFAULT NULL",
    'method' => "VARCHAR(30) DEFAULT NULL",
    'module' => "VARCHAR(60) DEFAULT NULL",
    'module_id' => "INT(11) DEFAULT NULL",
    'paid' => "DOUBLE DEFAULT 0",
    'tax_id' => "INT(11) DEFAULT NULL",
    'taxes' => "VARCHAR(50) DEFAULT NULL",
    'currency' => "VARCHAR(30) DEFAULT 'THB'",
    'items' => "TEXT DEFAULT NULL",
    'extras' => "TEXT DEFAULT NULL",
    'transaction_id' => "VARCHAR(60) DEFAULT NULL",
    'rp_type' => "VARCHAR(30) DEFAULT NULL",
    'rp_frequency' => "VARCHAR(30) DEFAULT NULL",
    'updated_at' => "DATETIME DEFAULT NULL"
);
foreach ($invoice_cols as $col => $type) {
    if ($CI->db->table_exists('invoice') && !$CI->db->field_exists($col, 'invoice')) {
        $CI->db->query("ALTER TABLE `invoice` ADD `$col` $type");
    }
}

$nsh_notifications_cols = array(
    'label' => "VARCHAR(100) DEFAULT NULL",
    'label_description' => "TEXT DEFAULT NULL",
    'auth_type' => "VARCHAR(100) DEFAULT NULL",
    'auth_id' => "INT(11) DEFAULT NULL",
    'detail' => "TEXT DEFAULT NULL",
    'status' => "INT(11) DEFAULT 0",
    'updated_at' => "DATETIME DEFAULT NULL"
);
foreach ($nsh_notifications_cols as $col => $type) {
    if ($CI->db->table_exists('nsh_notifications') && !$CI->db->field_exists($col, 'nsh_notifications')) {
        $CI->db->query("ALTER TABLE `nsh_notifications` ADD `$col` $type");
    }
}

if ($CI->db->table_exists('service')) {
    $stores_with_services = $CI->db->select("DISTINCT(store_id)")->get("service")->result_array();
    foreach ($stores_with_services as $sRow) {
        $st_id = $sRow["store_id"];
        $grp = $CI->db->get_where("service", ["store_id" => $st_id, "parent_id" => 0, "value" => 0])->row_array();
        if (!$grp) {
            $CI->db->insert("service", [
                "store_id" => $st_id,
                "user_id" => 1,
                "name" => "รายการบริการและสินค้า (Services & Products)",
                "label" => "รายการบริการและสินค้า (Services & Products)",
                "parent_id" => 0,
                "option_type" => "one_option",
                "value" => 0,
                "status" => 1,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
            $grp_id = $CI->db->insert_id();
        } else {
            $grp_id = $grp["id"];
        }
        $CI->db->where("store_id", $st_id)
            ->where("id !=", $grp_id)
            ->where("parent_id", 0)
            ->update("service", ["parent_id" => $grp_id]);
    }
}

// Set essential default configurations in app_config
echo "Setting default app configuration values...\n";
ConfigManager::setValue("APP_NAME", "NearbyStores", TRUE);
ConfigManager::setValue("DEFAULT_EMAIL", "admin@example.com", TRUE);
ConfigManager::setValue("RADUIS_TRAGET", 100, TRUE);
ConfigManager::setValue("DEFAULT_CURRENCY", "USD", TRUE);
ConfigManager::setValue("DEFAULT_LANG", "en", TRUE);
ConfigManager::setValue("TIME_ZONE", "UTC", TRUE);
ConfigManager::setValue("ITEMS_PER_PAGE", 10, TRUE);
ConfigManager::setValue("STORES_PER_PAGE", 10, TRUE);
ConfigManager::setValue("OPENING_TIME_ENABLED", TRUE, TRUE);
ConfigManager::setValue("SETTING_AUTO_LOC_DETECT", TRUE, TRUE);
ConfigManager::setValue("ANYTHINGS_APPROVAL", TRUE, TRUE);
ConfigManager::setValue("STORE_RADIUS_LIMIT", 1000, TRUE);
ConfigManager::setValue("_NOTIFICATION_AGREEMENT_USE", FALSE, TRUE);
ConfigManager::setValue("PUSH_CAMPAIGNS_WITH_CRON", TRUE, TRUE);
ConfigManager::setValue("LIMIT_PUSHED_GUESTS_PER_CAMPAIGN", 100, TRUE);
ConfigManager::setValue("NBR_PUSHS_FOR_EVERY_TIME", 10, TRUE);
ConfigManager::setValue("EMAIL_VERIFICATION", FALSE, TRUE);
ConfigManager::setValue("USER_REGISTRATION", TRUE, TRUE);
ConfigManager::setValue("_APP_VERSION", APP_VERSION, TRUE);
ConfigManager::setValue("APP_LOGO", "", TRUE);
ConfigManager::setValue("DASHBOARD_ANALYTICS", "", TRUE);
ConfigManager::setValue("DASHBOARD_COLOR", "#3c8dbc", TRUE);

// 2. Install all modules
echo "2. Installing module tables...\n";
$modules = array(
    'setting', 'user', 'cms', 'category', 'store', 'event', 'offer',
    'campaign', 'service', 'notification', 'messenger', 'nstranslator',
    'nsbanner', 'nshistoric', 'bookmark', 'booking', 'booking_payment',
    'cf_manager', 'digital_wallet', 'payout', 'qrcoupon', 'gallery',
    'location_picker', 'pack', 'payment', 'simple_mailer', 'uploader',
    'business_manager', 'exim_tool'
);

foreach ($modules as $mod) {
    echo "Module '{$mod}'... ";
    try {
        $CI->db->where('module_name', $mod);
        $c = $CI->db->count_all_results('modules');
        if ($c == 0) {
            $CI->db->insert('modules', array(
                'module_name' => $mod,
                'version_code' => 100,
                'version_name' => '1.0.0',
                '_order' => 0,
                '_enabled' => 1,
                '_installed' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ));
        } else {
            $CI->db->where('module_name', $mod);
            $CI->db->update('modules', array('_enabled' => 1, '_installed' => 1));
        }
        echo "Loaded OK\n";
    } catch (Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Call updateFields on key models
if ($CI->load->model('store/store_model', 'mStoreModel')) {
    $CI->mStoreModel->addOpeningTimeTable();
    $CI->mStoreModel->updateFields();
}
if ($CI->load->model('offer/offer_model', 'mOfferModel')) {
    $CI->mOfferModel->updateFields();
}
if ($CI->load->model('category/category_model', 'mCategoryModel')) {
    $CI->mCategoryModel->updateFields();
}
if ($CI->load->model('cf_manager/cf_manager_model', 'mCFManager')) {
    $CI->mCFManager->updateFields();
}
if ($CI->load->model('event/event_model', 'mEventModel')) {
    $CI->mEventModel->updateFields();
}
if ($CI->load->model('booking/booking_model', 'mBookingModel')) {
    $CI->mBookingModel->create_table();
    $CI->mBookingModel->update_fields();
}

// 3. Create SuperAdmin & Admin User
echo "3. Creating Default Admin User...\n";
try {
    $actions = GroupAccess::getModuleActions();
    $permissions = array();
    foreach ($actions as $key => $action) {
        $permissions[$key] = array();
        foreach ($action as $value) {
            $permissions[$key][$value] = 1;
        }
    }
    $permJson = json_encode($permissions, JSON_FORCE_OBJECT);

    $CI->db->where('name', 'SuperAdmin');
    $grpCount = $CI->db->count_all_results('group_access');
    if ($grpCount == 0) {
        $CI->db->insert('group_access', array(
            'name' => 'SuperAdmin',
            'permissions' => $permJson,
            'editable' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ));
        $grpId = $CI->db->insert_id();
    } else {
        $CI->db->where('name', 'SuperAdmin');
        $grpRes = $CI->db->get('group_access', 1)->result();
        $grpId = $grpRes[0]->id;
        $CI->db->where('id', $grpId);
        $CI->db->update('group_access', array('permissions' => $permJson));
    }

    $CI->db->where('username', 'admin');
    $userCount = $CI->db->count_all_results('user');
    if ($userCount == 0) {
        $CI->db->insert('user', array(
            'name' => 'Administrator',
            'username' => 'admin',
            'manager' => 1,
            'email' => 'admin@example.com',
            'password' => Security::cryptPassword('admin123'),
            'status' => 1,
            'confirmed' => 1,
            'grp_access_id' => $grpId,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ));
        $adminId = $CI->db->insert_id();
        $CI->db->insert('user_subscribe_setting', array(
            'user_id' => $adminId,
            'nbr_stores' => -1,
            'nbr_events_monthly' => -1,
            'nbr_offers_monthly' => -1,
            'nbr_campaigns_monthly' => -1,
            'nbr_gallery_per_store' => 30,
            'created_at' => date('Y-m-d H:i:s')
        ));
        echo "Default Admin created successfully! (Username: admin, Password: admin123)\n";
    } else {
        echo "Admin user already exists.\n";
    }
} catch (Throwable $e) {
    echo "Error creating admin: " . $e->getMessage() . "\n";
}

// Seed default packages if packmanager is empty
if ($CI->db->table_exists('packmanager') && $CI->db->count_all_results('packmanager') == 0) {
    $CI->db->insert('packmanager', array(
        'name' => 'Free Pack',
        'recommended' => 0,
        'duration' => 30,
        'price' => 0.00,
        'price_yearly' => 0.00,
        'display' => 1,
        'nbr_products_monthly' => -1,
        'nbr_events_monthly' => -1,
        'nbr_campaigns_monthly' => -1,
        'push_campaign_auto' => 1,
        'nbr_stores' => -1,
        'description' => 'Free basic plan for new members',
        'grp_access_id' => 0,
        'trial_period' => 0,
        '_order' => 1,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s")
    ));
}

echo "\nDatabase Setup Completed Successfully!\n";
