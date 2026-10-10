<?php
/**
 * IT News App - Home Controller & RSS Feed Hub
 * Matches mobile app news feed UI with live RSS parsing & Supabase integration
 */

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Load Configurations & Services
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/SupabaseFeedService.php';
require_once __DIR__ . '/RssFeedService.php';

$alert_status  = null;
$alert_message = '';

// 2. Process Form Submissions (Add Source / Add Group to Supabase)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_source' || isset($_POST['btnAddSource'])) {
        $result = SupabaseFeedService::addFeedSource($_POST);
        $alert_status  = $result['success'] ? 'success' : 'error';
        $alert_message = $result['message'];
    } elseif ($action === 'add_group' || isset($_POST['btnAddGroup'])) {
        $result = SupabaseFeedService::addFeedGroup($_POST);
        $alert_status  = $result['success'] ? 'success' : 'error';
        $alert_message = $result['message'];
    }
}

// 3. Fetch Feed Groups & Selected Category (Active Only)
$groups = SupabaseFeedService::getFeedGroups(true);

$rawGroupParam = $_GET['group'] ?? '';
$isAllCategories = empty($rawGroupParam) || $rawGroupParam === 'all';
$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] == 1;

// 4. Fetch Feed Sources & Live RSS Articles
if ($isAllCategories) {
    $currentGroupKey = 'all';
    $currentGroupLabel = 'รวมทุกหมวดหมู่';
    $sourcesInGroup = SupabaseFeedService::getFeedSources(null, true);
    $articles = RssFeedService::getAllArticles($forceRefresh, 80);
    $page_title = 'TechThaiNews | รวมข่าวสารทุกหมวดหมู่แบบเรียลไทม์ - นิตยสารและพอร์ทัลข่าวไอที ดิจิทัล';

    // Separate articles by category for homepage multi-category blocks
    $articlesByGroup = [];
    foreach ($groups as $g) {
        $gKey = $g['key'];
        $gArticles = RssFeedService::getGroupArticles($gKey, false);
        if (!empty($gArticles)) {
            $articlesByGroup[$gKey] = [
                'group'    => $g,
                'articles' => array_slice($gArticles, 0, 4)
            ];
        }
    }
} else {
    $currentGroupKey = $rawGroupParam;
    $currentGroupLabel = 'ข่าวเทคโนโลยีและธุรกิจดิจิทัล';
    foreach ($groups as $g) {
        if ($g['key'] === $currentGroupKey) {
            $currentGroupLabel = $g['label'];
            break;
        }
    }
    $sourcesInGroup = SupabaseFeedService::getFeedSources($currentGroupKey);
    $articles = RssFeedService::getGroupArticles($currentGroupKey, $forceRefresh);
    $page_title = 'TechThaiNews | ' . $currentGroupLabel . ' - นิตยสารและพอร์ทัลข่าวไอที ดิจิทัล';
}

// 5. Render Frontend View (MVC Architecture)
require_once dirname(__DIR__) . '/views/frontend/home.php';


