<?php
/**
 * RSS & Atom Feed Parser & Cache Service
 * Fetches, normalizes, and caches live news articles from feed_sources
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/SupabaseFeedService.php';

class RssFeedService {

    private static $cacheDir = __DIR__ . '/cache';
    private static $cacheTtl = 300; // 5 minutes (in seconds)

    /**
     * Get articles for a given group key
     *
     * @param string $groupKey
     * @param bool $forceRefresh
     * @param int $maxSources
     * @return array
     */
    public static function getGroupArticles($groupKey = 'tech-business', $forceRefresh = false, $maxSources = 4) {
        if (!file_exists(self::$cacheDir)) {
            @mkdir(self::$cacheDir, 0777, true);
        }

        $safeGroupKey = preg_replace('/[^a-zA-Z0-9_\-]/', '', $groupKey);
        if (empty($safeGroupKey)) {
            $safeGroupKey = 'tech-business';
        }

        $cacheFile = self::$cacheDir . '/rss_' . $safeGroupKey . '.json';

        // Return from cache if fresh (within cacheTtl) and not forced refresh
        $isFresh = file_exists($cacheFile) && (time() - filemtime($cacheFile) < self::$cacheTtl);
        if (!$forceRefresh && $isFresh) {
            $content = @file_get_contents($cacheFile);
            $cached = $content ? json_decode($content, true) : null;
            if (is_array($cached) && !empty($cached)) {
                return $cached;
            }
        }

        // Fetch active feed sources for this group from Supabase
        $allSources = SupabaseFeedService::getFeedSources($safeGroupKey, true);
        if (empty($allSources) && $safeGroupKey !== 'tech-business') {
            // Fallback to active sources across all groups if none for this group
            $allSources = SupabaseFeedService::getFeedSources(null, true);
        }

        $sourcesToFetch = array_slice($allSources, 0, $maxSources);
        $articles = [];

        // Parallel fetch using curl_multi for lightning-fast aggregation
        if (function_exists('curl_multi_init') && count($sourcesToFetch) > 1) {
            $articles = self::fetchParallelFeeds($sourcesToFetch, $safeGroupKey);
        } else {
            foreach ($sourcesToFetch as $src) {
                if (empty($src['url']) || (isset($src['is_active']) && !$src['is_active'])) continue;
                $items = self::fetchAndParseFeed($src, $safeGroupKey);
                if (!empty($items)) {
                    $articles = array_merge($articles, $items);
                }
            }
        }

        // Deduplicate articles by unique link or id
        $uniqueArticles = [];
        $seen = [];
        foreach ($articles as $art) {
            $dedupKey = !empty($art['link']) ? $art['link'] : ($art['id'] ?? '');
            if (!isset($seen[$dedupKey])) {
                $seen[$dedupKey] = true;
                $uniqueArticles[] = $art;
            }
        }
        $articles = $uniqueArticles;

        // If no articles fetched live (e.g. offline/network blocked), try returning stale cache
        if (empty($articles) && file_exists($cacheFile)) {
            $content = @file_get_contents($cacheFile);
            $cached = $content ? json_decode($content, true) : null;
            if (is_array($cached) && !empty($cached)) {
                return $cached;
            }
        }

        // Enrich articles with real og:image from the publisher's webpage if image is missing
        if (!empty($articles)) {
            self::enrichArticlesWithOgImages($articles, 20);
        }

        // Sort strictly by publication timestamp descending (latest news first)
        usort($articles, function($a, $b) {
            return ($b['pub_date'] ?? 0) - ($a['pub_date'] ?? 0);
        });

        // Save to cache
        if (!empty($articles)) {
            @file_put_contents($cacheFile, json_encode($articles, JSON_UNESCAPED_UNICODE));
        }

        return $articles;
    }

    /**
     * Get aggregated articles from ALL active groups, sorted by pub_date descending
     *
     * @param bool $forceRefresh
     * @param int $maxTotal
     * @return array
     */
    public static function getAllArticles($forceRefresh = false, $maxTotal = 80) {
        if (!file_exists(self::$cacheDir)) {
            @mkdir(self::$cacheDir, 0777, true);
        }

        $cacheFile = self::$cacheDir . '/rss_all_aggregated.json';

        // Return from cache if fresh (within cacheTtl) and not forced refresh
        $isFresh = file_exists($cacheFile) && (time() - filemtime($cacheFile) < self::$cacheTtl);
        if (!$forceRefresh && $isFresh) {
            $content = @file_get_contents($cacheFile);
            $cached = $content ? json_decode($content, true) : null;
            if (is_array($cached) && !empty($cached)) {
                return $cached;
            }
        }

        $groups = SupabaseFeedService::getFeedGroups(true);
        if (empty($groups)) {
            $groups = SupabaseFeedService::getDefaultFeedGroups();
        }

        $allArticles = [];
        $seen = [];

        foreach ($groups as $g) {
            $gKey = $g['key'];
            $gLabel = $g['label'];
            // Read group articles (reads group cache if fresh, or re-fetches if forceRefresh or expired)
            $groupArts = self::getGroupArticles($gKey, $forceRefresh, 2);
            if (!empty($groupArts)) {
                $slice = array_slice($groupArts, 0, 8);
                foreach ($slice as $art) {
                    $dedupKey = !empty($art['link']) ? $art['link'] : ($art['id'] ?? '');
                    if (!isset($seen[$dedupKey])) {
                        $seen[$dedupKey] = true;
                        if (empty($art['group_key'])) {
                            $art['group_key'] = $gKey;
                        }
                        if (empty($art['group_label'])) {
                            $art['group_label'] = $gLabel;
                        }
                        $allArticles[] = $art;
                    }
                }
            }
        }

        // Sort strictly by publication timestamp descending (latest news first)
        usort($allArticles, function($a, $b) {
            return ($b['pub_date'] ?? 0) - ($a['pub_date'] ?? 0);
        });

        if (count($allArticles) > $maxTotal) {
            $allArticles = array_slice($allArticles, 0, $maxTotal);
        }

        if (!empty($allArticles)) {
            @file_put_contents($cacheFile, json_encode($allArticles, JSON_UNESCAPED_UNICODE));
        }

        return $allArticles;
    }

    /**
     * Find a single article by ID or link, searching preferred group cache or all cached feeds
     *
     * @param string $id
     * @param string|null $groupKey
     * @return array|null
     */
    public static function findArticleById($id, $groupKey = null) {
        if (empty($id)) return null;
        $id = trim($id);

        // 1. If groupKey is provided, check that group cache first
        if (!empty($groupKey)) {
            $articles = self::getGroupArticles($groupKey, false);
            foreach ($articles as $art) {
                if (($art['id'] ?? '') === $id || ($art['link'] ?? '') === $id) {
                    self::enrichArticleDetail($art);
                    return $art;
                }
            }
        }

        // 2. Search all existing cache files in self::$cacheDir
        if (file_exists(self::$cacheDir)) {
            $files = glob(self::$cacheDir . '/rss_*.json');
            if ($files) {
                foreach ($files as $file) {
                    $content = @file_get_contents($file);
                    if (!$content) continue;
                    $cached = json_decode($content, true);
                    if (is_array($cached)) {
                        foreach ($cached as $art) {
                            if (($art['id'] ?? '') === $id || ($art['link'] ?? '') === $id) {
                                self::enrichArticleDetail($art);
                                return $art;
                            }
                        }
                    }
                }
            }
        }

        // 3. Fallback: Search in default group
        $fallbackArticles = self::getGroupArticles('tech-business', false);
        foreach ($fallbackArticles as $art) {
            if (($art['id'] ?? '') === $id || ($art['link'] ?? '') === $id) {
                self::enrichArticleDetail($art);
                return $art;
            }
        }

        return null;
    }

    /**
     * Fetch rich article details (lead paragraphs, meta description, full text) from source link if needed
     *
     * @param array $article
     * @return void
     */
    public static function enrichArticleDetail(array &$article) {
        $link = $article['link'] ?? '';
        if (empty($link) || !filter_var($link, FILTER_VALIDATE_URL)) {
            return;
        }

        // Skip Google News redirects
        if (strpos($link, 'news.google.com') !== false) {
            return;
        }

        // If article already has paragraphs, skip re-fetching
        if (!empty($article['paragraphs']) && is_array($article['paragraphs']) && count($article['paragraphs']) >= 2) {
            return;
        }

        if (!function_exists('curl_init')) return;

        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$html || $httpCode >= 400) return;

        // 1. og:image if empty
        if (empty($article['image']) || !filter_var($article['image'], FILTER_VALIDATE_URL)) {
            if (preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $img = trim($m[1]);
                if (filter_var($img, FILTER_VALIDATE_URL)) {
                    $article['image'] = $img;
                }
            }
        }

        // 2. og:description or meta description
        $ogDesc = '';
        if (preg_match('/<meta\s+(?:property=["\']og:description["\']|name=["\']description["\'])\s+content=["\']([^"\']+)["\']/i', $html, $m)) {
            $ogDesc = html_entity_decode(trim($m[1]), ENT_QUOTES, 'UTF-8');
            $ogDesc = trim(preg_replace('/\s+/', ' ', strip_tags($ogDesc)));
        }

        // 3. Extract readable paragraphs from <article> or body
        $paragraphs = [];
        $searchHtml = $html;
        if (preg_match('/<article[^>]*>(.*?)<\/article>/is', $html, $artMatch)) {
            $searchHtml = $artMatch[1];
        }
        // Remove noise tags
        $searchHtml = preg_replace('/<(script|style|nav|header|footer|aside|noscript|figure|form)[^>]*>.*?<\/\1>/is', '', $searchHtml);

        if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $searchHtml, $pMatches)) {
            foreach ($pMatches[1] as $p) {
                $cleanP = trim(preg_replace('/\s+/', ' ', strip_tags($p)));
                // Filter out short cookie/advertisement blurbs
                if (mb_strlen($cleanP, 'UTF-8') >= 35 && !preg_match('/(คุ้กกี้|cookie|นโยบายความเป็นส่วนตัว|สงวนลิขสิทธิ์|copyright|สมัครสมาชิก|แชร์ข่าวนี้|ติดตามเรา)/i', $cleanP)) {
                    $paragraphs[] = $cleanP;
                }
                if (count($paragraphs) >= 8) break;
            }
        }

        if (!empty($paragraphs)) {
            $article['paragraphs'] = $paragraphs;
            if (empty($article['full_text']) || mb_strlen($article['full_text'], 'UTF-8') < 200) {
                $article['full_text'] = implode("\n\n", $paragraphs);
            }
        }

        if (!empty($ogDesc)) {
            $article['lead_summary'] = $ogDesc;
            if (empty($article['description']) || mb_strlen($article['description'], 'UTF-8') < 50) {
                $article['description'] = $ogDesc;
            }
        }
    }

    /**
     * Enrich articles with real og:image from source webpage if image is empty
     */
    private static function enrichArticlesWithOgImages(array &$articles, int $maxToEnrich = 20): void {
        $needed = [];
        foreach ($articles as $idx => $art) {
            if (!empty($art['image']) && filter_var($art['image'], FILTER_VALIDATE_URL)) {
                continue;
            }
            $link = $art['link'] ?? '';
            if (empty($link) || !filter_var($link, FILTER_VALIDATE_URL)) continue;
            // Skip Google News redirected links (they use JS redirects)
            if (strpos($link, 'news.google.com') !== false) continue;

            $needed[$idx] = $link;
            if (count($needed) >= $maxToEnrich) break;
        }

        if (empty($needed) || !function_exists('curl_multi_init')) {
            return;
        }

        $mh = curl_multi_init();
        $handles = [];
        foreach ($needed as $idx => $url) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ITNewsReader/2.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RANGE, '0-25600'); // Download first 25KB to find og:image fast
            curl_multi_add_handle($mh, $ch);
            $handles[$idx] = $ch;
        }

        $running = null;
        do {
            $mrc = curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 0.05);
            }
        } while ($running > 0 && $mrc == CURLM_OK);

        foreach ($handles as $idx => $ch) {
            $html = curl_multi_getcontent($ch);
            $imgUrl = '';
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $imgUrl = $m[1];
            } elseif (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $m)) {
                $imgUrl = $m[1];
            }

            if (!empty($imgUrl) && self::isValidImg($imgUrl)) {
                $articles[$idx]['image'] = $imgUrl;
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        curl_multi_close($mh);
    }

    /**
     * Parallel fetch multiple feeds using curl_multi
     */
    private static function fetchParallelFeeds(array $sources, string $groupKey): array {
        $mh = curl_multi_init();
        $handles = [];
        $articles = [];

        foreach ($sources as $idx => $src) {
            if (empty($src['url']) || (isset($src['is_active']) && !$src['is_active'])) continue;
            $url = self::normalizeFeedUrl($src['url']);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ITNewsReader/2.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_multi_add_handle($mh, $ch);
            $handles[$idx] = [
                'ch' => $ch,
                'source' => $src
            ];
        }

        if (empty($handles)) {
            curl_multi_close($mh);
            return [];
        }

        $running = null;
        do {
            $mrc = curl_multi_exec($mh, $running);
            if ($running > 0) {
                curl_multi_select($mh, 0.05);
            }
        } while ($running > 0 && $mrc == CURLM_OK);

        foreach ($handles as $item) {
            $ch = $item['ch'];
            $src = $item['source'];
            $content = curl_multi_getcontent($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode >= 200 && $httpCode < 400 && !empty($content)) {
                $feedItems = self::parseXmlString($content, $src, $groupKey);
                if (!empty($feedItems)) {
                    $articles = array_merge($articles, $feedItems);
                }
            }

            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
        return $articles;
    }

    /**
     * Normalize URL, especially encoding raw non-ASCII Thai query strings (fixes Google News 400 error)
     */
    private static function normalizeFeedUrl(string $url): string {
        $url = trim($url);
        if (preg_match('/[^\x00-\x7F]/', $url)) {
            $parts = parse_url($url);
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $q);
                $query = http_build_query($q);
                $url = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . ($parts['path'] ?? '') . '?' . $query;
            }
        }
        return $url;
    }

    /**
     * Fetch single RSS/Atom feed sequentially (fallback)
     */
    private static function fetchAndParseFeed($source, $groupKey) {
        $url = self::normalizeFeedUrl($source['url']);
        $xmlStr = null;

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ITNewsReader/2.0');
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $xmlStr = curl_exec($ch);
            curl_close($ch);
        } else {
            $ctx = stream_context_create([
                'http' => [
                    'header' => "User-Agent: ITNewsReader/2.0\r\n",
                    'timeout' => 5
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            $xmlStr = @file_get_contents($url, false, $ctx);
        }

        if (!$xmlStr) {
            return [];
        }

        return self::parseXmlString($xmlStr, $source, $groupKey);
    }

    /**
     * Parse XML string into normalized article objects
     */
    private static function parseXmlString($xmlStr, $source, $groupKey) {
        if (empty($xmlStr)) return [];

        libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xml) {
            libxml_clear_errors();
            return [];
        }

        $items = [];
        $isAtom = ($xml->getName() === 'feed') || isset($xml->entry);

        if ($isAtom) {
            $entries = isset($xml->entry) ? $xml->entry : [];
            $limit = 12;
            $count = 0;
            foreach ($entries as $entry) {
                if ($count++ >= $limit) break;
                $item = self::parseAtomEntry($entry, $source, $groupKey);
                if ($item) $items[] = $item;
            }
        } else {
            $rssItems = isset($xml->channel->item) ? $xml->channel->item : [];
            $limit = 12;
            $count = 0;
            foreach ($rssItems as $rssItem) {
                if ($count++ >= $limit) break;
                $item = self::parseRssItem($rssItem, $source, $groupKey);
                if ($item) $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Parse standard RSS 2.0 item
     */
    private static function parseRssItem($item, $source, $groupKey) {
        $title = trim((string)$item->title);
        if (empty($title)) return null;

        $link = trim((string)$item->link);
        if (empty($link) && isset($item->guid)) {
            $link = trim((string)$item->guid);
        }

        $rawDesc = (string)$item->description;
        $content = '';
        $contentEncoded = $item->children('content', true);
        if ($contentEncoded && isset($contentEncoded->encoded)) {
            $content = (string)$contentEncoded->encoded;
        }

        // Author
        $author = '';
        $dc = $item->children('dc', true);
        if ($dc && isset($dc->creator)) {
            $author = trim((string)$dc->creator);
        }
        if (empty($author) && isset($item->author)) {
            $author = trim((string)$item->author);
        }
        if (empty($author)) {
            $author = $source['label'];
        }

        // Date
        $pubDateStr = (string)$item->pubDate;
        $timestamp = !empty($pubDateStr) ? strtotime($pubDateStr) : time();
        if ($timestamp <= 0) $timestamp = time();

        // Image extraction
        $image = self::extractImage($item, $rawDesc . ' ' . $content);

        // Clean snippet
        $rawClean = trim(preg_replace('/\s+/', ' ', strip_tags($rawDesc)));
        if (empty($rawClean) && !empty($content)) {
            $rawClean = trim(preg_replace('/\s+/', ' ', strip_tags($content)));
        }
        $cleanDesc = $rawClean;
        if (mb_strlen($cleanDesc, 'UTF-8') > 160) {
            $cleanDesc = mb_substr($cleanDesc, 0, 157, 'UTF-8') . '...';
        }

        return [
            'id'           => md5($link ?: ($title . $source['key'])),
            'title'        => $title,
            'link'         => $link,
            'description'  => $cleanDesc,
            'full_text'    => $rawClean,
            'raw_content'  => !empty($content) ? $content : $rawDesc,
            'image'        => $image,
            'author'       => $author,
            'source_key'   => $source['key'],
            'source_label' => $source['label'],
            'group_key'    => $groupKey,
            'pub_date'     => $timestamp,
            'time_ago'     => self::timeAgo($timestamp),
            'reading_time' => self::calcReadingTime($rawClean ?: $cleanDesc),
            'is_new'       => (time() - $timestamp) < 86400
        ];
    }

    /**
     * Parse Atom feed entry
     */
    private static function parseAtomEntry($entry, $source, $groupKey) {
        $title = trim((string)$entry->title);
        if (empty($title)) return null;

        $link = '';
        if (isset($entry->link)) {
            foreach ($entry->link as $l) {
                $rel = (string)$l['rel'];
                if ($rel === 'alternate' || empty($rel)) {
                    $link = (string)$l['href'];
                    break;
                }
            }
            if (empty($link) && isset($entry->link[0]['href'])) {
                $link = (string)$entry->link[0]['href'];
            }
        }

        $rawDesc = (string)($entry->summary ?? $entry->content ?? '');

        // Author
        $author = '';
        if (isset($entry->author->name)) {
            $author = trim((string)$entry->author->name);
        }
        if (empty($author)) {
            $author = $source['label'];
        }

        // Date
        $dateStr = (string)($entry->published ?? $entry->updated ?? '');
        $timestamp = !empty($dateStr) ? strtotime($dateStr) : time();
        if ($timestamp <= 0) $timestamp = time();

        $image = self::extractImage($entry, $rawDesc);

        $rawClean = trim(preg_replace('/\s+/', ' ', strip_tags($rawDesc)));
        $cleanDesc = $rawClean;
        if (mb_strlen($cleanDesc, 'UTF-8') > 160) {
            $cleanDesc = mb_substr($cleanDesc, 0, 157, 'UTF-8') . '...';
        }

        return [
            'id'           => md5($link ?: ($title . $source['key'])),
            'title'        => $title,
            'link'         => $link,
            'description'  => $cleanDesc,
            'full_text'    => $rawClean,
            'raw_content'  => $rawDesc,
            'image'        => $image,
            'author'       => $author,
            'source_key'   => $source['key'],
            'source_label' => $source['label'],
            'group_key'    => $groupKey,
            'pub_date'     => $timestamp,
            'time_ago'     => self::timeAgo($timestamp),
            'reading_time' => self::calcReadingTime($rawClean ?: $cleanDesc),
            'is_new'       => (time() - $timestamp) < 86400
        ];
    }

    /**
     * Extract thumbnail image from various RSS tags or HTML content
     */
    private static function extractImage($xmlNode, $htmlContent) {
        // 1. media:content
        $media = $xmlNode->children('media', true);
        if ($media && isset($media->content)) {
            foreach ($media->content as $mc) {
                $url = (string)$mc->attributes()['url'];
                if (!empty($url) && self::isValidImg($url)) return $url;
            }
        }

        // 2. media:thumbnail
        if ($media && isset($media->thumbnail)) {
            $url = (string)$media->thumbnail->attributes()['url'];
            if (!empty($url) && self::isValidImg($url)) return $url;
        }

        // 3. enclosure
        if (isset($xmlNode->enclosure)) {
            $encType = (string)$xmlNode->enclosure['type'];
            if (empty($encType) || strpos($encType, 'image') !== false) {
                $url = (string)$xmlNode->enclosure['url'];
                if (!empty($url) && self::isValidImg($url)) return $url;
            }
        }

        // 4. img tag in HTML content or description
        if (!empty($htmlContent)) {
            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $htmlContent, $matches)) {
                $url = $matches[1];
                if (self::isValidImg($url)) return $url;
            }
        }

        return '';
    }

    private static function isValidImg($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
        // Ignore tracking pixels and emoticons
        if (preg_match('/(wp-includes\/images\/smilies|pixel\.wp\.com|feeds\.feedburner\.com|1x1|spacer\.gif)/i', $url)) {
            return false;
        }
        return true;
    }

    /**
     * Relative time in Thai language
     */
    public static function timeAgo($timestamp) {
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'เมื่อสักครู่';
        } elseif ($diff < 3600) {
            $mins = max(1, floor($diff / 60));
            return $mins . ' นาทีที่แล้ว';
        } elseif ($diff < 86400) {
            $hrs = floor($diff / 3600);
            return $hrs . ' ชั่วโมงที่แล้ว';
        } elseif ($diff < 172800) {
            return 'เมื่อวานนี้';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' วันที่แล้ว';
        } else {
            return date('d/m/Y', $timestamp);
        }
    }

    /**
     * Estimated reading time
     */
    private static function calcReadingTime($text) {
        $wordCount = mb_strlen($text, 'UTF-8');
        $minutes = max(1, round($wordCount / 300));
        return $minutes . ' นาที';
    }

    /**
     * Get best thumbnail for an article with intelligent contextual matching
     */
    public static function getThumbnail($article, $fallbackIndex = 0) {
        if (!empty($article['image']) && filter_var($article['image'], FILTER_VALIDATE_URL)) {
            return $article['image'];
        }

        $text = mb_strtolower(($article['title'] ?? '') . ' ' . ($article['description'] ?? ''), 'UTF-8');

        // 1. Apple / iPhone / iOS / iPad / Mac
        if (preg_match('/(iphone|apple|ipad|macbook|ios|airpods|watchos|tim cook)/i', $text)) {
            return 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=1000&auto=format&fit=crop&q=80';
        }

        // 2. Smartphone / Android / OnePlus / Samsung / Xiaomi / Vivo / Oppo
        if (preg_match('/(oneplus|android|samsung|galaxy|xiaomi|oppo|vivo|มือถือ|สมาร์ทโฟน|coloros|hyperos|oxygenos|hmd|helio|snapdragon|ชาร์จไว|หน้าจอพับ)/i', $text)) {
            return 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=1000&auto=format&fit=crop&q=80';
        }

        // 3. Gaming
        if (preg_match('/(เกม|game|gaming|genshin|resident evil|capcom|playstation|nintendo|xbox|steam|โค้ด|game-ded)/i', $text)) {
            return 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=1000&auto=format&fit=crop&q=80';
        }

        // 4. Camera
        if (preg_match('/(canon|nikon|fujifilm|leica|dslr|mirrorless|eos|กล้องถ่ายรูป|กล้องฟูลเฟรม|ฟูลเฟรม|เลนส์กล้อง)/i', $text)) {
            return 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1000&auto=format&fit=crop&q=80';
        }

        // 5. Audio / Headphones
        if (preg_match('/(ลำโพง|หูฟัง|speaker|headphone|audio|bluetooth|บลูทูธ|earbuds|cmf)/i', $text)) {
            return 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=1000&auto=format&fit=crop&q=80';
        }

        // 6. SpaceX / Satellite
        if (preg_match('/(spacex|ดาวเทียม|starlink|อวกาศ|จรวด|nasa|คลื่นความถี่)/i', $text)) {
            return 'https://images.unsplash.com/photo-1517976487504-59a1a0841246?w=1000&auto=format&fit=crop&q=80';
        }

        // 7. EV / Car
        if (preg_match('/(รถยนต์|ยานยนต์|ev|tesla|byd|มอเตอร์|gwm|tank|hybrid|รถไฟฟ้า)/i', $text)) {
            return 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=1000&auto=format&fit=crop&q=80';
        }

        // 8. AI
        if (preg_match('/(ai|ปัญญาประดิษฐ์|gemini|chatgpt|openai|โมเดล|deepseek|llm|copilot)/i', $text)) {
            return 'https://images.unsplash.com/photo-1677442136019-21780efad99a?w=1000&auto=format&fit=crop&q=80';
        }

        // 9. Crypto
        if (preg_match('/(bitcoin|crypto|เหรียญ|คริปโต|blockchain|บล็อกเชน|btc|eth)/i', $text)) {
            return 'https://images.unsplash.com/photo-1621416894569-0f39ed31d247?w=1000&auto=format&fit=crop&q=80';
        }

        // 10. Cybersecurity
        if (preg_match('/(ไซเบอร์|cyber|แฮก|hack|มิจฉาชีพ|security|ความปลอดภัย|malware)/i', $text)) {
            return 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=1000&auto=format&fit=crop&q=80';
        }

        // 11. Weather
        if (preg_match('/(ฝน|พายุ|น้ำท่วม|สภาพอากาศ|พยากรณ์|pm2\.5|อากาศ)/i', $text)) {
            return 'https://images.unsplash.com/photo-1534088568595-a066f410bcda?w=1000&auto=format&fit=crop&q=80';
        }

        // 12. Computer
        if (preg_match('/(windows|microsoft|pc|โน้ตบุ๊ก|laptop|คอมพิวเตอร์|ชิป|intel|amd)/i', $text)) {
            return 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=1000&auto=format&fit=crop&q=80';
        }

        $curatedPhotos = [
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1000&auto=format&fit=crop&q=80'
        ];
        return $curatedPhotos[$fallbackIndex % count($curatedPhotos)];
    }
}
