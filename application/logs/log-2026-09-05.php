<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-09-05 04:13:15 --> Severity: User Error --> Composer detected issues in your platform: Your Composer dependencies require a PHP version ">= 8.0.2". You are running 7.4.33. /Applications/MAMP/htdocs/PHP-source-nearby/vendor/composer/platform_check.php 24
ERROR - 2026-09-05 04:13:26 --> Severity: error --> Exception: Undefined constant "__ADMIN" /Applications/MAMP/htdocs/PHP-source-nearby/application/config/routes.php 45
ERROR - 2026-09-05 04:13:54 --> Severity: Warning --> Undefined array key "SERVER_PROTOCOL" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-05 04:13:54 --> Severity: 8192 --> strtolower(): Passing null to parameter #1 ($string) of type string is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-05 04:13:54 --> Severity: Warning --> Undefined array key "SERVER_PORT" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 60
ERROR - 2026-09-05 04:13:54 --> Severity: Warning --> Undefined array key "SERVER_NAME" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 63
ERROR - 2026-09-05 04:13:55 --> Query error: Table 'nearby_db.group_access' doesn't exist - Invalid query: SELECT *
FROM `group_access`
WHERE `id` IS NULL
 LIMIT 1
ERROR - 2026-09-05 04:14:38 --> Query error: Table 'nearby_db.group_access' doesn't exist - Invalid query: SELECT *
FROM `group_access`
WHERE `id` IS NULL
 LIMIT 1
ERROR - 2026-09-05 04:29:28 --> Query error: Table 'nearby_db.token' doesn't exist - Invalid query: ALTER TABLE `token` CHANGE `created_at` `created_at` DATETIME NULL DEFAULT NULL;
ERROR - 2026-09-05 04:29:42 --> Query error: Table 'nearby_db.currencies' doesn't exist - Invalid query: SHOW COLUMNS FROM `currencies`
ERROR - 2026-09-05 04:30:40 --> Query error: Table 'nearby_db.user' doesn't exist - Invalid query: SHOW COLUMNS FROM `user`
ERROR - 2026-09-05 04:30:54 --> Query error: Table 'nearby_db.guest' doesn't exist - Invalid query: SHOW COLUMNS FROM `guest`
ERROR - 2026-09-05 04:31:38 --> Query error: Table 'nearby_db.user' doesn't exist - Invalid query: SHOW COLUMNS FROM `user`
ERROR - 2026-09-05 04:36:30 --> Query error: Table 'nearby_db.user_subscribe_setting' doesn't exist - Invalid query: SHOW COLUMNS FROM `user_subscribe_setting`
ERROR - 2026-09-05 04:37:59 --> Query error: Column 'grp_access_id' cannot be null - Invalid query: INSERT INTO `user` (`name`, `username`, `manager`, `email`, `password`, `status`, `confirmed`, `grp_access_id`) VALUES ('Administrator', 'admin', 1, 'admin@example.com', '41aa6a34921cf7d8450cdd4808362f69bf41fb92', 1, 1, NULL)
ERROR - 2026-09-05 04:38:28 --> Severity: Warning --> Undefined array key "SERVER_PROTOCOL" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-05 04:38:28 --> Severity: 8192 --> strtolower(): Passing null to parameter #1 ($string) of type string is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-05 04:38:28 --> Severity: Warning --> Undefined array key "SERVER_PORT" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 60
ERROR - 2026-09-05 04:38:28 --> Severity: Warning --> Undefined array key "SERVER_NAME" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 63
ERROR - 2026-09-05 04:38:29 --> Severity: error --> Exception: Undefined constant "RADUIS_TRAGET" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/campaign/html.php 37
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property CI_DB_mysqli_driver::$autoinit is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_driver.php 372
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Pre_controller::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Appcore::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Utils::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Exim_tool::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Demo::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Uploader::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Offer::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Nshistoric::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Booking_payment::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Digital_wallet::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Bookmark::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Cf_manager::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property Category::$load is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Controller.php 59
ERROR - 2026-09-05 04:42:22 --> Severity: 8192 --> Creation of dynamic property MY_Loader::$controller is deprecated /Applications/MAMP/htdocs/PHP-source-nearby/application/third_party/MX/Loader.php 51
ERROR - 2026-09-05 04:42:53 --> Query error: Table 'nearby_db.offer' doesn't exist - Invalid query: SHOW COLUMNS FROM `offer`
ERROR - 2026-09-05 04:42:58 --> Query error: Table 'nearby_db.offer' doesn't exist - Invalid query: SHOW COLUMNS FROM `offer`
ERROR - 2026-09-05 04:45:19 --> Query error: Unknown column 'detail' in 'store' - Invalid query: ALTER TABLE `store`
	ADD `video_url` VARCHAR(150) NULL DEFAULT NULL AFTER `detail`
ERROR - 2026-09-05 04:45:46 --> Query error: Unknown column 'detail' in 'store' - Invalid query: ALTER TABLE `store`
	ADD `video_url` VARCHAR(150) NULL DEFAULT NULL AFTER `detail`
ERROR - 2026-09-05 04:46:27 --> Severity: error --> Exception: Undefined constant "EMAIL_VERIFICATION" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/views/plug/user_alerts/html.php 23
ERROR - 2026-09-05 04:47:07 --> Severity: error --> Exception: in_array(): Argument #2 ($haystack) must be of type array, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/payment/helpers/payment_helper.php 299
ERROR - 2026-09-05 04:47:33 --> Severity: error --> Exception: Undefined constant "_APP_VERSION" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/controllers/Setting.php 105
ERROR - 2026-09-05 04:48:38 --> Severity: error --> Exception: Undefined constant "APP_LOGO" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/views/frontend/header.php 143
ERROR - 2026-09-05 04:49:38 --> Severity: error --> Exception: Undefined constant "APP_LOGO" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/views/frontend/header.php 143
ERROR - 2026-09-05 04:50:36 --> Query error: Table 'nearby_db.token' doesn't exist - Invalid query: INSERT INTO `token` (`id`, `uid`, `type`, `content`, `created_at`) VALUES ('15e2930616d781e391e8bb846c256580', '1', 'logged', '', '2026-09-05 04:50:36')
ERROR - 2026-09-05 04:50:59 --> Query error: Unknown column 'user_id' in 'field list' - Invalid query: INSERT INTO `cf_list` (`user_id`, `label`, `default_value`, `fields`, `created_at`, `updated_at`, `editable`) VALUES (1, 'Default_Event_Booking_fields', '', '[{\"type\":\"input.text\",\"label\":\"Full name\",\"required\":1,\"order\":1,\"step\":1,\"id\":\"full_name\"},{\"type\":\"input.phone\",\"label\":\"Phone\",\"required\":1,\"order\":2,\"step\":1,\"id\":\"phone\"}]', '2026-09-05 04:50:59', '2026-09-05 04:50:59', 1)
ERROR - 2026-09-05 04:51:02 --> Query error: Unknown column 'user_id' in 'field list' - Invalid query: INSERT INTO `cf_list` (`user_id`, `label`, `default_value`, `fields`, `created_at`, `updated_at`, `editable`) VALUES (1, 'Default_Event_Booking_fields', '', '[{\"type\":\"input.text\",\"label\":\"Full name\",\"required\":1,\"order\":1,\"step\":1,\"id\":\"full_name\"},{\"type\":\"input.phone\",\"label\":\"Phone\",\"required\":1,\"order\":2,\"step\":1,\"id\":\"phone\"}]', '2026-09-05 04:51:02', '2026-09-05 04:51:02', 1)
ERROR - 2026-09-05 04:51:57 --> Severity: error --> Exception: Undefined constant "DASHBOARD_ANALYTICS" /Applications/MAMP/htdocs/PHP-source-nearby/views/backend/admin-v3/include/header.php 72
ERROR - 2026-09-05 04:52:01 --> Severity: error --> Exception: Undefined constant "DASHBOARD_ANALYTICS" /Applications/MAMP/htdocs/PHP-source-nearby/views/backend/admin-v3/include/header.php 72
ERROR - 2026-09-05 04:52:06 --> Severity: error --> Exception: Undefined constant "DASHBOARD_ANALYTICS" /Applications/MAMP/htdocs/PHP-source-nearby/views/backend/admin-v3/include/header.php 72
ERROR - 2026-09-05 04:52:30 --> Query error: Table 'nearby_db.message' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `message`
JOIN `discussion` ON `discussion`.`id_discussion`=`message`.`discussion_id`
WHERE `message`.`receiver_id` = '1'
AND `message`.`status` = -1
AND `discussion`.`status` >= 0
ERROR - 2026-09-05 04:52:59 --> Query error: Table 'nearby_db.message' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `message`
JOIN `discussion` ON `discussion`.`id_discussion`=`message`.`discussion_id`
WHERE `message`.`receiver_id` = '1'
AND `message`.`status` = -1
AND `discussion`.`status` >= 0
ERROR - 2026-09-05 04:53:29 --> Query error: Unknown column 'participants.status' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `participants`
JOIN `event` ON `participants`.`event_id`=`event`.`id_event`
WHERE `event`.`user_id` = '1'
AND `participants`.`status` = 0
ERROR - 2026-09-05 04:53:34 --> Query error: Unknown column 'participants.status' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `participants`
JOIN `event` ON `participants`.`event_id`=`event`.`id_event`
WHERE `event`.`user_id` = '1'
AND `participants`.`status` = 0
ERROR - 2026-09-05 04:54:02 --> Query error: Unknown column 'verified' in 'event' - Invalid query: ALTER TABLE `event`
	ADD `cf_id` INT DEFAULT 0 AFTER `verified`
ERROR - 2026-09-05 04:54:25 --> Query error: Incorrect table definition; there can be only one auto column and it must be defined as a key - Invalid query: CREATE TABLE IF NOT EXISTS `event` (`id` int(11) NOT NULL AUTO_INCREMENT, `id_event` int(11) NOT NULL AUTO_INCREMENT, `store_id` int(11) DEFAULT 0, `user_id` int(11) DEFAULT 0, `name` text DEFAULT NULL, `description` text DEFAULT NULL, `address` text DEFAULT NULL, `latitude` double DEFAULT 0, `longitude` double DEFAULT 0, `date_start` datetime DEFAULT NULL, `date_end` datetime DEFAULT NULL, `image` text DEFAULT NULL, `status` int(11) DEFAULT 1, `verified` int(11) DEFAULT 0, `tags` text DEFAULT NULL, `created_at` datetime DEFAULT NULL, `updated_at` datetime DEFAULT NULL, `tel` varchar(100) DEFAULT NULL, `web` text DEFAULT NULL, `email` varchar(100) DEFAULT NULL, PRIMARY KEY (`id_event`), KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ERROR - 2026-09-05 04:54:53 --> Severity: Warning --> Undefined variable $new /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 5
ERROR - 2026-09-05 04:54:53 --> Severity: Warning --> Undefined variable $confirmed /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 14
ERROR - 2026-09-05 04:54:53 --> Severity: Warning --> Undefined variable $canceled /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 23
ERROR - 2026-09-05 04:54:53 --> Severity: error --> Exception: Undefined constant "DASHBOARD_COLOR" /Applications/MAMP/htdocs/PHP-source-nearby/views/backend/admin-v3/include/footer.php 354
ERROR - 2026-09-05 04:55:28 --> Severity: Warning --> Undefined variable $new /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 5
ERROR - 2026-09-05 04:55:28 --> Severity: Warning --> Undefined variable $confirmed /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 14
ERROR - 2026-09-05 04:55:28 --> Severity: Warning --> Undefined variable $canceled /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 23
ERROR - 2026-09-05 04:55:28 --> Severity: error --> Exception: Undefined constant "DASHBOARD_COLOR" /Applications/MAMP/htdocs/PHP-source-nearby/views/backend/admin-v3/include/footer.php 354
ERROR - 2026-09-05 04:56:21 --> Severity: Warning --> Undefined variable $new /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 5
ERROR - 2026-09-05 04:56:21 --> Severity: Warning --> Undefined variable $confirmed /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 14
ERROR - 2026-09-05 04:56:21 --> Severity: Warning --> Undefined variable $canceled /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 23
ERROR - 2026-09-05 04:58:41 --> Query error: Unknown column 'dateLogin' in 'field list' - Invalid query: INSERT INTO `user` (`telephone`, `username`, `email`, `name`, `password`, `created_at`, `updated_at`, `confirmed`, `dateLogin`) VALUES ('+66839040153', 'pong', 'pongku71@gmail.com', 'Ponglang Petrung', '2fb1ff7b8f58336a0e22cfca59a10e6e1ebaa978', '2026-09-05 04:58:41', '2026-09-05 04:58:41', 0, '2026-09-05 04:58:00')
ERROR - 2026-09-05 05:00:17 --> Severity: Warning --> Undefined variable $new /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 5
ERROR - 2026-09-05 05:00:17 --> Severity: Warning --> Undefined variable $confirmed /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 14
ERROR - 2026-09-05 05:00:17 --> Severity: Warning --> Undefined variable $canceled /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 23
ERROR - 2026-09-05 05:00:26 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 05:00:44 --> Query error: Unknown column '_order' in 'order clause' - Invalid query: SELECT *
FROM `packmanager`
ORDER BY `_order` ASC
ERROR - 2026-09-05 05:01:26 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 05:01:32 --> Severity: Warning --> Undefined variable $new /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 5
ERROR - 2026-09-05 05:01:32 --> Severity: Warning --> Undefined variable $confirmed /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 14
ERROR - 2026-09-05 05:01:32 --> Severity: Warning --> Undefined variable $canceled /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/charts/client-counters.php 23
ERROR - 2026-09-05 05:05:16 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('Screenshot_20260808_154134-27821', 'image/png', 1)
ERROR - 2026-09-05 05:05:32 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('Screenshot-2569-02-21-at-22-66136', 'image/png', 1)
ERROR - 2026-09-05 05:05:43 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 05:09:51 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 05:09:57 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 05:13:24 --> Query error: Unknown column '_order' in 'order clause' - Invalid query: SELECT *
FROM `packmanager`
ORDER BY `_order` ASC
ERROR - 2026-09-05 05:15:54 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 05:17:29 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:17:35 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:17:41 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:17:46 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:17:51 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:17:52 --> Severity: error --> Exception: syntax error, unexpected token "return", expecting "function" or "const" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/helpers/group_access_helper.php 316
ERROR - 2026-09-05 05:20:22 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 05:52:02 --> Severity: error --> Exception: Undefined constant "NO_OF_STORE_ITEMS_PER_PAGE" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/controllers/Admin.php 275
ERROR - 2026-09-05 05:54:38 --> Query error: Table 'nearby_db.campaign' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `campaign`
WHERE `status` = -1
ERROR - 2026-09-05 05:55:29 --> Severity: error --> Exception: Undefined constant "MAPS_API_KEY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/location_picker/views/plug/gmap/js.php 3
ERROR - 2026-09-05 08:30:27 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `image`
JOIN `user` ON `user`.`id_user`=`image`.`user_id`
WHERE `user_id` = '1'
ERROR - 2026-09-05 09:06:28 --> Unable to connect to the database
ERROR - 2026-09-05 09:08:27 --> Unable to connect to the database
ERROR - 2026-09-05 15:43:35 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('792090222_1717683679344924_8504588493357156132_n-39537', 'image/jpeg', 1)
ERROR - 2026-09-05 15:43:41 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('792090222_1717683679344924_8504588493357156132_n-41347', 'image/jpeg', 1)
ERROR - 2026-09-05 15:43:45 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('792090222_1717683679344924_8504588493357156132_n-4646', 'image/jpeg', 1)
ERROR - 2026-09-05 15:43:59 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 15:48:48 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('792090222_1717683679344924_8504588493357156132_n-49162', 'image/jpeg', 1)
ERROR - 2026-09-05 15:49:00 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('Screenshot-2569-09-05-at-12-82855', 'image/png', 1)
ERROR - 2026-09-05 15:51:57 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('Screenshot-2569-08-31-at-22-47874', 'image/png', 1)
ERROR - 2026-09-05 15:52:12 --> Query error: Table 'nearby_db.image' doesn't exist - Invalid query: INSERT INTO `image` (`image`, `type`, `user_id`) VALUES ('Screenshot-2569-07-22-at-08-67824', 'image/png', 1)
ERROR - 2026-09-05 15:55:36 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 15:55:52 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 16:06:21 --> Query error: Unknown column 'telephone' in 'field list' - Invalid query: INSERT INTO `store` (`images`, `name`, `address`, `telephone`, `latitude`, `longitude`, `category_id`, `detail`, `user_id`, `canChat`, `book`, `date_created`, `created_at`, `status`) VALUES ('{\"0\":\"image1\"}', 'Test Store', '123 Test Street', '0812345678', 13.7563, 100.5018, 1, 'Test Detail', 1, 1, 1, '2026-09-05 16:06:21', '2026-09-05 16:06:21', 0)
ERROR - 2026-09-05 16:08:11 --> Query error: Unknown column 'default_uri' in 'field list' - Invalid query: INSERT INTO `cms_uri` (`slug`, `default_uri`, `created_at`, `updated_at`) VALUES ('3-Test-Store', 'detail-store/3', '2026-09-05 16:08:11', '2026-09-05 16:08:11')
ERROR - 2026-09-05 16:12:28 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 16:13:00 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 16:13:16 --> Query error: Table 'nearby_db.city' doesn't exist - Invalid query: SELECT *
FROM `city`
WHERE `id_city` = ''
 LIMIT 1
ERROR - 2026-09-05 16:20:12 --> Severity: error --> Exception: Class "ConfigManager" not found /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/location_picker/controllers/Location_picker.php 15
ERROR - 2026-09-05 16:20:16 --> Severity: error --> Exception: Class "ConfigManager" not found /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/location_picker/controllers/Location_picker.php 15
ERROR - 2026-09-05 16:20:24 --> Severity: error --> Exception: Class "ConfigManager" not found /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/location_picker/controllers/Location_picker.php 15
ERROR - 2026-09-05 16:22:32 --> Query error: Unknown column 'opening' in 'field list' - Invalid query: INSERT INTO `opening_time` (`store_id`, `day`, `opening`, `closing`, `timezone`, `enabled`) VALUES ('16', 'monday', '00:00', '00:00', 'UTC', 0)
ERROR - 2026-09-05 16:22:44 --> Query error: Unknown column 'opening' in 'field list' - Invalid query: INSERT INTO `opening_time` (`store_id`, `day`, `opening`, `closing`, `timezone`, `enabled`) VALUES ('17', 'monday', '00:00', '00:00', 'UTC', 0)
ERROR - 2026-09-05 16:27:44 --> Query error: Unknown column 'module_id' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `gallery`
WHERE `module_id` = 19
AND `module` = 'store'
ERROR - 2026-09-05 16:30:11 --> Query error: Unknown column 'rate.id_rate' in 'field list' - Invalid query: SELECT ( SELECT sum(rate.rate)/count(rate.id_rate) as sumRating FROM rate WHERE rate.store_id=store.id_store ) as 'sumRating', category.color as category_color, category.name as category_name, store.*
FROM `store`
JOIN `category` ON `category`.`id_category`=`store`.`category_id`
WHERE `store`.`hidden` = 0
AND `store`.`user_id` = 1
ORDER BY `store`.`id_store` DESC
 LIMIT 10
ERROR - 2026-09-05 16:32:10 --> Query error: Unknown column 'id_offer' in 'order clause' - Invalid query: SELECT *
FROM `offer`
WHERE `store_id` = '23'
AND `status` = 1
ORDER BY `id_offer` DESC
 LIMIT 1
ERROR - 2026-09-05 16:33:35 --> Query error: Unknown column 'hidden' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `service`
WHERE `hidden` = 0
AND `store_id` = '25'
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:34:43 --> Severity: Warning --> Undefined array key "featured" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/backend/html/stores.php 103
ERROR - 2026-09-05 16:36:56 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:38:56 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:39:26 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 16:39:34 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 16:41:31 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:41:41 --> Query error: Table 'nearby_db.campaign' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `campaign`
WHERE `status` >= -1
ERROR - 2026-09-05 16:41:47 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:43:05 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `service` (`store_id`, `label`, `option_type`, `created_at`, `updated_at`) VALUES (28, 'A', 'one_option', '2026-09-05 16:43:05', '2026-09-05 16:43:05')
ERROR - 2026-09-05 16:43:07 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `service` (`store_id`, `label`, `option_type`, `created_at`, `updated_at`) VALUES (28, 'A', 'one_option', '2026-09-05 16:43:07', '2026-09-05 16:43:07')
ERROR - 2026-09-05 16:43:08 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `service` (`store_id`, `label`, `option_type`, `created_at`, `updated_at`) VALUES (28, 'A', 'one_option', '2026-09-05 16:43:08', '2026-09-05 16:43:08')
ERROR - 2026-09-05 16:44:38 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `service` (`store_id`, `label`, `option_type`, `created_at`, `updated_at`) VALUES (28, 'Website for pongploydev.infinityfree.me', 'one_option', '2026-09-05 16:44:38', '2026-09-05 16:44:38')
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:46:39 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:47:31 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:47:34 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 16:47:40 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:47:45 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:47:54 --> Query error: Table 'nearby_db.wallet' doesn't exist - Invalid query: SELECT *
FROM `wallet`
WHERE `user_id` = '1'
 LIMIT 1
ERROR - 2026-09-05 16:49:30 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:49:30 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:49:30 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:09 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:09 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:09 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:28 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:28 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:28 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:42 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:42 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:50:42 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/currency_helper.php 20
ERROR - 2026-09-05 16:52:00 --> Query error: Unknown column 'cfg_key' in 'where clause' - Invalid query: SELECT *
FROM `app_config`
WHERE `cfg_key` = 'DEFAULT_CURRENCY'
ERROR - 2026-09-05 16:53:07 --> Query error: Unknown column 'cfg_key' in 'where clause' - Invalid query: SELECT *
FROM `app_config`
WHERE `cfg_key` = 'DEFAULT_CURRENCY'
ERROR - 2026-09-05 16:55:49 --> Query error: Table 'nearby_db.block' doesn't exist - Invalid query: SELECT `user_id`
FROM `block`
WHERE `user_id` != '1'
AND `blocked_id` = '1'
ERROR - 2026-09-05 16:55:57 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '1'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 16:57:17 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-05 16:59:14 --> Query error: Unknown column 'dateLogin' in 'field list' - Invalid query: INSERT INTO `user` (`telephone`, `username`, `email`, `name`, `password`, `created_at`, `updated_at`, `confirmed`, `dateLogin`) VALUES ('+66839040153', 'user01', 'pongku71@gmail.com', 'Ponglang Petrung', '2fb1ff7b8f58336a0e22cfca59a10e6e1ebaa978', '2026-09-05 16:59:14', '2026-09-05 16:59:14', 0, '2026-09-05 16:59:00')
ERROR - 2026-09-05 16:59:23 --> Query error: Unknown column 'dateLogin' in 'field list' - Invalid query: INSERT INTO `user` (`telephone`, `username`, `email`, `name`, `password`, `created_at`, `updated_at`, `confirmed`, `dateLogin`) VALUES ('+66839040153', 'user01', 'pongku71@gmail.com', 'Ponglang Petrung', '0387434c17fcf333702afddcb79d1fa76368c6df', '2026-09-05 16:59:23', '2026-09-05 16:59:23', 0, '2026-09-05 16:59:00')
ERROR - 2026-09-05 16:59:39 --> Query error: Unknown column 'dateLogin' in 'field list' - Invalid query: INSERT INTO `user` (`telephone`, `username`, `email`, `name`, `password`, `created_at`, `updated_at`, `confirmed`, `dateLogin`) VALUES ('+66839040153', 'user01', 'pongku71@gmail.com', 'Ponglang Petrung', '0387434c17fcf333702afddcb79d1fa76368c6df', '2026-09-05 16:59:39', '2026-09-05 16:59:39', 0, '2026-09-05 16:59:00')
ERROR - 2026-09-05 17:03:31 --> Query error: Unknown column 'will_expired' in 'field list' - Invalid query: INSERT INTO `user_subscribe_setting` (`nbr_stores`, `nbr_offers_monthly`, `nbr_events_monthly`, `push_campaign_auto`, `nbr_campaigns_monthly`, `user_settings_package`, `will_expired`, `created_at`, `user_id`) VALUES (-1, -1, -1, 1, -1, '{\"nbr_stores\":-1,\"nbr_offers_monthly\":-1,\"nbr_events_monthly\":-1,\"push_campaign_auto\":1,\"nbr_campaigns_monthly\":-1}', '2026-09-05 17:03:31', '2026-09-05 17:03:31', 3)
ERROR - 2026-09-05 17:07:17 --> Severity: error --> Exception: Undefined constant "MESSAGE_WELCOME" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/controllers/Ajax.php 222
ERROR - 2026-09-05 17:08:44 --> Query error: Unknown column '_order' in 'order clause' - Invalid query: SELECT *
FROM `packmanager`
ORDER BY `_order` ASC
ERROR - 2026-09-05 17:12:27 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 64
ERROR - 2026-09-05 17:12:27 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 66
ERROR - 2026-09-05 17:12:27 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 133
ERROR - 2026-09-05 17:12:27 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 135
ERROR - 2026-09-05 17:12:27 --> Severity: error --> Exception: Undefined constant "PAYMENT_CURRENCY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 28
ERROR - 2026-09-05 17:12:34 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 64
ERROR - 2026-09-05 17:12:34 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 66
ERROR - 2026-09-05 17:12:34 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 133
ERROR - 2026-09-05 17:12:34 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 135
ERROR - 2026-09-05 17:12:34 --> Severity: error --> Exception: Undefined constant "PAYMENT_CURRENCY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 28
ERROR - 2026-09-05 17:12:54 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 64
ERROR - 2026-09-05 17:12:54 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 66
ERROR - 2026-09-05 17:12:54 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 133
ERROR - 2026-09-05 17:12:54 --> Severity: Warning --> Undefined property: stdClass::$nbr_offers_monthly /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 135
ERROR - 2026-09-05 17:12:54 --> Severity: error --> Exception: Undefined constant "PAYMENT_CURRENCY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/views/client_view/html/pick-pack.php 28
ERROR - 2026-09-05 17:13:29 --> Query error: Unknown column 'module' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `invoice`
WHERE `status` = 0
AND `module` = 'pack'
AND `user_id` = 6
ERROR - 2026-09-05 17:13:31 --> Query error: Unknown column 'module' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `invoice`
WHERE `status` = 0
AND `module` = 'pack'
AND `user_id` = 6
ERROR - 2026-09-05 17:13:39 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '6'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 17:13:52 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '6'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 17:14:11 --> Query error: Unknown column 'images' in 'field list' - Invalid query: UPDATE `user` SET `images` = 'Screenshot-2569-09-05-at-12-44086', `username` = 'pong', `telephone` = '+66839040150', `email` = 'pongku71@gmail.com', `name` = 'Ponglang Petrung'
WHERE `id_user` = 6
ERROR - 2026-09-05 17:14:18 --> Query error: Unknown column 'event.images' in 'field list' - Invalid query: SELECT user.name as 'user_name', user.email as 'user_email', event.name as 'event_name', event.images as 'images', user.guest_id as 'user_guest_id', event.date_b as 'event_date_b', event.date_e as 'event_date_e', participants.*
FROM `participants`
INNER JOIN `event` ON `event`.`id_event`=`participants`.`event_id`
INNER JOIN `user` ON `user`.`id_user`=`participants`.`user_id`
WHERE `participants`.`user_id` = '6'
ORDER BY `id` DESC
 LIMIT 100
ERROR - 2026-09-05 17:19:40 --> Query error: Table 'nearby_db.block' doesn't exist - Invalid query: SELECT `user_id`
FROM `block`
WHERE `user_id` != '1'
AND `blocked_id` = '1'
ERROR - 2026-09-05 17:24:46 --> Severity: error --> Exception: preg_match(): Argument #2 ($subject) must be of type string, stdClass given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/images_helper.php 37
ERROR - 2026-09-05 17:26:26 --> Severity: error --> Exception: preg_match(): Argument #2 ($subject) must be of type string, stdClass given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/images_helper.php 37
ERROR - 2026-09-05 17:26:28 --> Severity: error --> Exception: preg_match(): Argument #2 ($subject) must be of type string, stdClass given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/images_helper.php 37
ERROR - 2026-09-05 17:28:09 --> Severity: error --> Exception: preg_match(): Argument #2 ($subject) must be of type string, stdClass given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/images_helper.php 37
ERROR - 2026-09-05 17:28:24 --> Severity: error --> Exception: preg_match(): Argument #2 ($subject) must be of type string, stdClass given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/images_helper.php 37
ERROR - 2026-09-05 17:30:01 --> Severity: Warning --> Undefined array key "SERVER_PROTOCOL" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-05 17:30:01 --> Severity: Warning --> Undefined array key "SERVER_PORT" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 60
ERROR - 2026-09-05 17:30:53 --> Severity: error --> Exception: Cannot use object of type stdClass as array /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/uploader_helper.php 250
ERROR - 2026-09-05 17:31:32 --> Severity: error --> Exception: Cannot use object of type stdClass as array /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/uploader/helpers/uploader_helper.php 250
ERROR - 2026-09-05 17:45:06 --> Severity: error --> Exception: Undefined constant "PAYMENT_CURRENCY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/models/Pack_model.php 469
ERROR - 2026-09-05 17:45:11 --> Severity: error --> Exception: Undefined constant "PAYMENT_CURRENCY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/models/Pack_model.php 469
ERROR - 2026-09-05 17:45:12 --> Query error: Unknown column 'module' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `invoice`
WHERE `status` = 0
AND `module` = 'pack'
AND `user_id` = 6
ERROR - 2026-09-05 17:46:18 --> 404 Page Not Found: ../modules/booking/controllers/Admin/client_bookings
ERROR - 2026-09-05 17:48:01 --> Query error: Unknown column 'amount' in 'field list' - Invalid query: INSERT INTO `booking` (`store_id`, `user_id`, `amount`, `status`, `payment_status`, `booking_type`, `cart`, `cf_data`, `created_at`, `updated_at`) VALUES (39, 1, 3500, 0, 'cod', 'service', '{\"0\":{\"module\":\"service\",\"module_id\":\"51\",\"qty\":1,\"amount\":3500}}', '{\"note\":\"\\u0e08\\u0e2d\\u0e07\\u0e1c\\u0e48\\u0e32\\u0e19\\u0e1b\\u0e38\\u0e48\\u0e21 Create Booking \\u0e1a\\u0e19\\u0e2b\\u0e19\\u0e49\\u0e32\\u0e40\\u0e27\\u0e47\\u0e1a\",\"booking_date\":\"2026-09-05 17:48:01\",\"service_name\":\"\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e1e\\u0e31\\u0e01\\u0e14\\u0e35\\u0e25\\u0e31\\u0e01\\u0e0b\\u0e4c\\u0e27\\u0e34\\u0e27\\u0e17\\u0e30\\u0e40\\u0e25\\u0e1e\\u0e23\\u0e49\\u0e2d\\u0e21\\u0e2d\\u0e32\\u0e2b\\u0e32\\u0e23\\u0e40\\u0e0a\\u0e49\\u0e32 (Deluxe Ocean View Suite 1 \\u0e04\\u0e37\\u0e19)\"}', '2026-09-05 17:48:01', '2026-09-05 17:48:01')
ERROR - 2026-09-05 17:50:56 --> Query error: Unknown column 'module' in 'where clause' - Invalid query: SELECT *
FROM `invoice`
WHERE `module` = 'booking_payment'
AND `module_id` = '2'
 LIMIT 1
ERROR - 2026-09-05 17:57:48 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 119
ERROR - 2026-09-05 17:57:48 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 121
ERROR - 2026-09-05 17:58:06 --> Severity: Warning --> Attempt to read property "method" on null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 79
ERROR - 2026-09-05 17:58:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 119
ERROR - 2026-09-05 17:58:06 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 121
ERROR - 2026-09-05 17:58:06 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 119
ERROR - 2026-09-05 17:58:06 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 121
ERROR - 2026-09-05 17:58:41 --> Severity: Warning --> Attempt to read property "method" on null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 79
ERROR - 2026-09-05 17:58:41 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 119
ERROR - 2026-09-05 17:58:41 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 121
ERROR - 2026-09-05 17:58:41 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 119
ERROR - 2026-09-05 17:58:41 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 121
ERROR - 2026-09-05 18:00:24 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:00:24 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:00:24 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:00:24 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:01:03 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:01:03 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:01:03 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:01:03 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:01:18 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:01:18 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:01:18 --> Severity: Warning --> Trying to access array offset on value of type null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 120
ERROR - 2026-09-05 18:01:18 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/views/backend/booking_popup_detail.php 122
ERROR - 2026-09-05 18:09:04 --> Severity: Warning --> Undefined property: stdClass::$images /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/user/views/backend/html/edit.php 58
ERROR - 2026-09-05 18:09:18 --> Query error: Table 'nearby_db.block' doesn't exist - Invalid query: SELECT `user_id`
FROM `block`
WHERE `user_id` != '1'
AND `blocked_id` = '1'
ERROR - 2026-09-05 18:09:51 --> 404 Page Not Found: ../modules/user/controllers/Ajax/login
ERROR - 2026-09-05 18:11:18 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `nsh_notifications` (`label`, `label_description`, `module`, `module_id`, `auth_type`, `auth_id`, `image`, `status`, `updated_at`, `created_at`) VALUES ('Booking #3 is  Confirmed', 'ออร่า เวลเนส สปา แอนด์ นวดแผนไทย (Aura Wellness Spa & Massage)', 'booking', 3, 'user', 6, '[\"\"]', 0, '2026-09-05 18:11;18', '2026-09-05 18:11;18')
ERROR - 2026-09-05 18:11:33 --> Query error: Unknown column 'label' in 'field list' - Invalid query: INSERT INTO `nsh_notifications` (`label`, `label_description`, `module`, `module_id`, `auth_type`, `auth_id`, `image`, `status`, `updated_at`, `created_at`) VALUES ('Booking #3 is  Confirmed', 'ออร่า เวลเนส สปา แอนด์ นวดแผนไทย (Aura Wellness Spa & Massage)', 'booking', 3, 'user', 6, '[\"\"]', 0, '2026-09-05 18:11;33', '2026-09-05 18:11;33')
ERROR - 2026-09-05 18:17:05 --> Severity: Warning --> Attempt to read property "status" on null /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking_payment/models/Booking_payment_model.php 52
ERROR - 2026-09-05 18:40:32 --> Query error: Table 'nearby_db.campaign' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `campaign`
WHERE `status` >= -1
ERROR - 2026-09-05 18:40:40 --> Query error: Table 'nearby_db.block' doesn't exist - Invalid query: SELECT `user_id`
FROM `block`
WHERE `user_id` != '1'
AND `blocked_id` = '1'
ERROR - 2026-09-05 18:45:31 --> Query error: Table 'nearby_db.campaign' doesn't exist - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `campaign`
WHERE `status` >= -1
ERROR - 2026-09-05 18:45:39 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-05 18:45:39 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-05 18:46:07 --> Severity: error --> Exception: Undefined constant "NO_OF_ITEMS_PER_PAGE" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/pack/models/Pack_model.php 348
ERROR - 2026-09-05 18:46:24 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-05 18:46:24 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-05 18:49:06 --> Query error: Table 'nearby_db.ns_config' doesn't exist - Invalid query: SELECT *
FROM `ns_config`
ERROR - 2026-09-05 18:51:17 --> Severity: error --> Exception: count(): Argument #1 ($value) must be of type Countable|array, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 34
ERROR - 2026-09-05 18:52:04 --> Severity: Warning --> Undefined array key "ENABLE_FRONT_END" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 90
ERROR - 2026-09-05 18:52:04 --> Severity: Warning --> Undefined array key "IMAGES_LIMITATION" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 111
ERROR - 2026-09-05 18:52:04 --> Severity: Warning --> Undefined array key "NO_OF_ITEMS_PER_PAGE" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 143
ERROR - 2026-09-05 18:52:04 --> Severity: Warning --> Undefined array key "MAPS_API_KEY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/setting_viewer/html.php 25
ERROR - 2026-09-05 18:52:14 --> Severity: Warning --> Undefined array key "ENABLE_FRONT_END" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 90
ERROR - 2026-09-05 18:52:14 --> Severity: Warning --> Undefined array key "IMAGES_LIMITATION" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 111
ERROR - 2026-09-05 18:52:14 --> Severity: Warning --> Undefined array key "NO_OF_ITEMS_PER_PAGE" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 143
ERROR - 2026-09-05 18:52:14 --> Severity: Warning --> Undefined array key "MAPS_API_KEY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/setting_viewer/html.php 25
ERROR - 2026-09-05 18:53:09 --> Severity: Warning --> Undefined array key "ENABLE_FRONT_END" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 90
ERROR - 2026-09-05 18:53:09 --> Severity: Warning --> Undefined array key "IMAGES_LIMITATION" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 111
ERROR - 2026-09-05 18:53:09 --> Severity: Warning --> Undefined array key "NO_OF_ITEMS_PER_PAGE" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/setting_viewer/dashboard.php 143
ERROR - 2026-09-05 18:53:09 --> Severity: Warning --> Undefined array key "MAPS_API_KEY" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/store/views/setting_viewer/html.php 25
