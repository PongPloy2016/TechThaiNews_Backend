<?php
/**
 * TechThaiNews - News Detail Controller
 * Direct Editorial Article Reader & Deep Linking System
 */

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Load Configurations & Services
require_once __DIR__ . '/home/config.php';
require_once __DIR__ . '/home/SupabaseFeedService.php';
require_once __DIR__ . '/home/RssFeedService.php';

// 2. Query Parameters
$articleId = isset($_GET['id']) ? trim($_GET['id']) : '';
$currentGroupKey = isset($_GET['group']) ? trim($_GET['group']) : '';

// 3. Fetch Feed Groups for Navigation
$groups = SupabaseFeedService::getFeedGroups(true);

if (empty($currentGroupKey) && !empty($groups[0]['key'])) {
    $currentGroupKey = $groups[0]['key'];
}

// 4. Retrieve Target Article
$article = null;
if (!empty($articleId)) {
    $article = RssFeedService::findArticleById($articleId, $currentGroupKey);
}

// If article specifies a different group, update currentGroupKey
if (!empty($article['group_key'])) {
    $currentGroupKey = $article['group_key'];
}

// 5. Current Group Label
$currentGroupLabel = 'ข่าวเทคโนโลยีและธุรกิจดิจิทัล';
foreach ($groups as $g) {
    if ($g['key'] === $currentGroupKey) {
        $currentGroupLabel = $g['label'];
        break;
    }
}

// Clean label without emojis if any
$cleanCurrentGroupLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $currentGroupLabel));

// 6. Fetch Related Articles & Trending
$allGroupArticles = RssFeedService::getGroupArticles($currentGroupKey, false);
$relatedArticles = [];
if ($article) {
    foreach ($allGroupArticles as $item) {
        if (($item['id'] ?? '') !== $article['id']) {
            $relatedArticles[] = $item;
        }
        if (count($relatedArticles) >= 4) break;
    }
} else {
    $relatedArticles = array_slice($allGroupArticles, 0, 4);
}

$trendingArticles = array_slice($allGroupArticles, 0, 5);
$sourcesInGroup = SupabaseFeedService::getFeedSources($currentGroupKey);

// 7. Page Title & Meta Tags
if ($article) {
    $page_title = $article['title'] . ' | TechThaiNews';
    $page_description = mb_substr($article['lead_summary'] ?? $article['description'] ?? '', 0, 160);
    $page_image = RssFeedService::getThumbnail($article, 0);
} else {
    $page_title = 'ไม่พบบทความ | TechThaiNews';
    $page_description = 'ไม่พบเนื้อหาบทความที่คุณต้องการ หรือบทความอาจถูกอัปเดต';
    $page_image = '';
}

// 8. Render Frontend News Detail View
require_once __DIR__ . '/views/frontend/news_detail.php';
