<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-09-09 16:41:27 --> Severity: Warning --> Undefined array key "SERVER_PROTOCOL" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 58
ERROR - 2026-09-09 16:41:27 --> Severity: Warning --> Undefined array key "SERVER_PORT" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 60
ERROR - 2026-09-09 16:41:27 --> Severity: Warning --> Undefined array key "SERVER_NAME" /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_drivers.php 63
ERROR - 2026-09-09 16:41:27 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Applications/MAMP/htdocs/PHP-source-nearby/system/core/Exceptions.php:272) /Applications/MAMP/htdocs/PHP-source-nearby/system/helpers/url_helper.php 565
ERROR - 2026-09-09 16:51:14 --> 404 Page Not Found: ../modules/store/controllers/Api/index
ERROR - 2026-09-09 17:08:30 --> Query error: Table 'nearby_db.config' doesn't exist - Invalid query: SELECT *
FROM `config`
ERROR - 2026-09-09 17:15:43 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-09 17:15:43 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-09 17:16:11 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-09 17:16:11 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-09 17:18:42 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-09 17:18:42 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-09 17:23:31 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/helpers/settings_helper.php 76
ERROR - 2026-09-09 17:23:31 --> Severity: Warning --> foreach() argument must be of type array|object, null given /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/setting/views/backend/html/api_config.php 73
ERROR - 2026-09-09 17:39:17 --> Query error: Unknown column 'sender_id' in 'where clause' - Invalid query: SELECT *
FROM `guest`
WHERE `sender_id` = '5246ba69d3b0df4768cb8b00e891c14310923d95@1'
 LIMIT 1
ERROR - 2026-09-09 17:39:18 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 17:39:19 --> Query error: Unknown column 'user.lat' in 'field list' - Invalid query: SELECT user.*, IF( user.lat = 0, 99999, (1000 * ( 6371 * acos (
                              cos ( radians(13.7798635) )
                              * cos( radians( user.lat ) )
                              * cos( radians( user.lng ) - radians(100.566244) )
                              + sin ( radians(13.7798635) )
                              * sin( radians( user.lat ) )
                            )
                          ) ) ) as 'distance'
FROM `user`
WHERE `user`.`hidden` = 0
AND `grp_access_id` = 0
AND `id_user` != 0
ORDER BY `distance` ASC
 LIMIT 30
ERROR - 2026-09-09 17:39:32 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 17:39:33 --> Query error: Unknown column 'user.lat' in 'field list' - Invalid query: SELECT user.*, IF( user.lat = 0, 99999, (1000 * ( 6371 * acos (
                              cos ( radians(13.7798635) )
                              * cos( radians( user.lat ) )
                              * cos( radians( user.lng ) - radians(100.566244) )
                              + sin ( radians(13.7798635) )
                              * sin( radians( user.lat ) )
                            )
                          ) ) ) as 'distance'
FROM `user`
WHERE `user`.`hidden` = 0
AND `grp_access_id` = 0
AND `id_user` != 0
ORDER BY `distance` ASC
 LIMIT 30
ERROR - 2026-09-09 17:39:48 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 17:39:48 --> Query error: Unknown column 'user.lat' in 'field list' - Invalid query: SELECT user.*, IF( user.lat = 0, 99999, (1000 * ( 6371 * acos (
                              cos ( radians(13.7798635) )
                              * cos( radians( user.lat ) )
                              * cos( radians( user.lng ) - radians(100.566244) )
                              + sin ( radians(13.7798635) )
                              * sin( radians( user.lat ) )
                            )
                          ) ) ) as 'distance'
FROM `user`
WHERE `user`.`hidden` = 0
AND `grp_access_id` = 0
AND `id_user` != 0
ORDER BY `distance` ASC
 LIMIT 30
ERROR - 2026-09-09 17:40:20 --> Query error: Unknown column 'sender_id' in 'where clause' - Invalid query: SELECT *
FROM `guest`
WHERE `sender_id` = '5246ba69d3b0df4768cb8b00e891c14310923d95@1'
 LIMIT 1
ERROR - 2026-09-09 17:40:21 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 17:40:22 --> Query error: Unknown column 'user.lat' in 'field list' - Invalid query: SELECT user.*, IF( user.lat = 0, 99999, (1000 * ( 6371 * acos (
                              cos ( radians(13.7798635) )
                              * cos( radians( user.lat ) )
                              * cos( radians( user.lng ) - radians(100.566244) )
                              + sin ( radians(13.7798635) )
                              * sin( radians( user.lat ) )
                            )
                          ) ) ) as 'distance'
FROM `user`
WHERE `user`.`hidden` = 0
AND `grp_access_id` = 0
AND `id_user` != 0
ORDER BY `distance` ASC
 LIMIT 30
ERROR - 2026-09-09 17:41:29 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = 7
ERROR - 2026-09-09 17:42:10 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = 8
ERROR - 2026-09-09 17:44:40 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = 9
ERROR - 2026-09-09 17:45:37 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = '9'
ERROR - 2026-09-09 17:45:40 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = '9'
ERROR - 2026-09-09 17:49:15 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '13.7798635', `lng` = '100.566244'
WHERE `id_user` = '9'
ERROR - 2026-09-09 18:00:32 --> Query error: Unknown column 'lat' in 'field list' - Invalid query: UPDATE `user` SET `lat` = '', `lng` = ''
WHERE `id_user` = '1'
ERROR - 2026-09-09 18:01:13 --> Query error: Data truncated for column 'lat' at row 1 - Invalid query: UPDATE `user` SET `lat` = '', `lng` = ''
WHERE `id_user` = '1'
ERROR - 2026-09-09 18:12:37 --> Query error: Unknown column 'sender_id' in 'where clause' - Invalid query: SELECT *
FROM `guest`
WHERE `sender_id` = '5246ba69d3b0df4768cb8b00e891c14310923d95@1'
 LIMIT 1
ERROR - 2026-09-09 18:12:38 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 18:13:22 --> Query error: Unknown column 'sender_id' in 'where clause' - Invalid query: SELECT *
FROM `guest`
WHERE `sender_id` = '5246ba69d3b0df4768cb8b00e891c14310923d95@1'
 LIMIT 1
ERROR - 2026-09-09 18:13:23 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 18:14:17 --> Query error: Unknown column 'updated_at' in 'where clause' - Invalid query: SELECT COUNT(*) AS `numrows`
FROM `bookmarks`
WHERE `module_id` = 36
AND `module` = 'store'
AND `user_id` = 9
AND `updated_at` = '2026-09-09 18:14;17'
AND `created_at` = '2026-09-09 18:14;17'
ERROR - 2026-09-09 18:14:35 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 18:38:22 --> Query error: Unknown column 'sender_id' in 'where clause' - Invalid query: SELECT *
FROM `guest`
WHERE `sender_id` = '5246ba69d3b0df4768cb8b00e891c14310923d95@1'
 LIMIT 1
ERROR - 2026-09-09 18:38:24 --> Query error: Unknown column 'created_at' in 'order clause' - Invalid query: SELECT *
FROM `ns_banners`
WHERE `status` = 1
ORDER BY `created_at` DESC
 LIMIT 30
ERROR - 2026-09-09 18:40:13 --> Severity: Warning --> Undefined array key "cart" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/models/Booking_model.php 1154
ERROR - 2026-09-09 18:40:13 --> Severity: Warning --> Undefined array key "cart" /Applications/MAMP/htdocs/PHP-source-nearby/application/modules/booking/models/Booking_model.php 1160
ERROR - 2026-09-09 18:40:13 --> Severity: Warning --> Array to string conversion /Applications/MAMP/htdocs/PHP-source-nearby/system/database/DB_driver.php 1485
ERROR - 2026-09-09 18:40:13 --> Query error: Unknown column 'Array' in 'field list' - Invalid query: INSERT INTO `booking` (`store_id`, `user_id`, `booking_type`, `cf_data`, `status`, `created_at`, `updated_at`) VALUES (1, 1, 'service', Array, 0, '2026-09-09 18:40:13', '2026-09-09 18:40:13')
