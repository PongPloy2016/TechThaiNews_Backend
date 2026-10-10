<?php
/**
 * TechThaiNews & Nearby System Router
 * - Routes /admin, /admin/*, /user/login, /cms/* -> CodeIgniter Admin Panel
 * - Routes / or /index.php -> TechThaiNews Editorial Portal
 */

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$queryString = $_SERVER['QUERY_STRING'] ?? '';

// Check if request is targeting CodeIgniter Backend Admin or system routes
$isCiRoute = preg_match('#/(admin|user|cms|api|clientRequest|ajax)(/.*)?$#i', $requestUri) 
          || preg_match('#^(admin|user|cms|api|clientRequest|ajax)#i', $queryString);

if ($isCiRoute) {
    require_once __DIR__ . '/index_ci_backup.php';
    exit;
}

// Default: TechThaiNews Editorial Magazine & Feed Portal
require_once __DIR__ . '/home/index.php';
