<?php
/**
 * Editorial Main Content Component
 * Mode 1: All Categories (Multi-Category Magazine Sections with 4-Col Grid per Category)
 * Mode 2: Single Category (8-Col Feed Stream + 4-Col Magazine Sidebar)
 */

if (!function_exists('getVarientThumbnail')) {
    function getVarientThumbnail($article, $fallbackIndex = 0) {
        return RssFeedService::getThumbnail($article, $fallbackIndex);
    }
}

$editorialColors = ['#0ea5e9', '#10b981', '#f43f5e', '#8b5cf6', '#f59e0b', '#06b6d4', '#ec4899', '#84cc16'];

// Category visual metadata mapping (matching NEWS TIMES aesthetic)
$categoryMetaMap = [
    'ai-innovation' => [
        'badge' => 'AI & INNOVATION',
        'color' => '#8b5cf6',
        'icon'  => 'bi-cpu',
    ],
    'mobile' => [
        'badge' => 'MOBILE & GADGETS',
        'color' => '#f43f5e',
        'icon'  => 'bi-phone',
    ],
    'tech-business' => [
        'badge' => 'TECH & BUSINESS',
        'color' => '#0ea5e9',
        'icon'  => 'bi-briefcase',
    ],
    'computer-games' => [
        'badge' => 'PC & GAMING',
        'color' => '#10b981',
        'icon'  => 'bi-controller',
    ],
    'cybersecurity' => [
        'badge' => 'CYBERSECURITY',
        'color' => '#f59e0b',
        'icon'  => 'bi-shield-lock',
    ],
    'thai-news' => [
        'badge' => 'THAI NEWS',
        'color' => '#ef4444',
        'icon'  => 'bi-newspaper',
    ],
    'international' => [
        'badge' => 'WORLD TECH',
        'color' => '#06b6d4',
        'icon'  => 'bi-globe2',
    ],
    'weather-forecast' => [
        'badge' => 'WEATHER',
        'color' => '#3b82f6',
        'icon'  => 'bi-cloud-sun',
    ],
    'agriculture' => [
        'badge' => 'AGRICULTURE',
        'color' => '#84cc16',
        'icon'  => 'bi-tree',
    ],
    'travel-tourism' => [
        'badge' => 'TRAVEL & EVENTS',
        'color' => '#ec4899',
        'icon'  => 'bi-compass',
    ],
];

// Extract unique sources for single-category mode filter pills
$availableSources = [];
foreach (($articles ?? []) as $a) {
    $sk = $a['source_key'];
    if (!isset($availableSources[$sk])) {
        $availableSources[$sk] = [
            'key'   => $sk,
            'label' => $a['source_label'],
            'count' => 0
        ];
    }
    $availableSources[$sk]['count']++;
}

// 5 Trending articles (ranked 1-5)
$trendingArticles = array_slice($articles ?? [], 0, 5);

// Check if we are in Multi-Category All mode
$isAllMode = ($currentGroupKey === 'all' && !empty($articlesByGroup));
?>

<div class="container-xl my-4">
<?php if ($isAllMode): ?>
    <!-- ========================================================================= -->
    <!-- MODE 1: MULTI-CATEGORY HOMEPAGE (NEWS TIMES MAGAZINE SEPARATED BLOCKS) -->
    <!-- ========================================================================= -->

    <!-- Quick Category Jump Bar -->
    <div class="category-quick-nav-wrapper mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-1 text-white" style="background: var(--accent-coral); font-size: 0.78rem; font-weight: 700; letter-spacing: 0.05em;">
                    <i class="bi bi-grid-fill me-1"></i> หมวดหมู่ข่าวสาร
                </span>
                <span class="small fw-bold" style="color: var(--text-heading);">
                    แยกตามหมวดหมู่ครอบคลุมทุกประเด็น
                </span>
            </div>
            <div class="small text-muted font-monospace">
                <?php echo count($articlesByGroup); ?> หมวดหมู่ &bull; <?php echo count($articles ?? []); ?> ข่าวล่าสุด
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 overflow-x-auto pb-1" style="scrollbar-width: none;">
            <?php foreach ($articlesByGroup as $gKey => $gData): 
                $meta = $categoryMetaMap[$gKey] ?? ['color' => '#0ea5e9', 'icon' => 'bi-tag', 'badge' => strtoupper($gKey)];
                $cleanLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $gData['group']['label']));
            ?>
                <a href="#cat-<?php echo htmlspecialchars($gKey); ?>" class="category-pill-link">
                    <i class="bi <?php echo $meta['icon']; ?>" style="color: <?php echo $meta['color']; ?>;"></i>
                    <span><?php echo htmlspecialchars($cleanLabel ?: $gData['group']['label']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Render Each Category Block (4 Cards Per Row) -->
    <?php foreach ($articlesByGroup as $gKey => $gData): 
        $meta = $categoryMetaMap[$gKey] ?? [
            'color' => '#ef4444', 
            'icon'  => 'bi-collection-play', 
            'badge' => strtoupper(str_replace('-', ' ', $gKey))
        ];
        $cleanLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $gData['group']['label']));
        $catArticles = $gData['articles'] ?? [];
        if (empty($catArticles)) continue;
    ?>
        <section id="cat-<?php echo htmlspecialchars($gKey); ?>" class="category-block-section mb-5" style="scroll-margin-top: 100px;">
            <!-- Category Header Bar (Matching NEWS TIMES reference layout) -->
            <div class="category-header-bar">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="category-header-badge" style="background-color: <?php echo $meta['color']; ?>;">
                        <i class="bi <?php echo $meta['icon']; ?> me-1"></i> <?php echo htmlspecialchars($meta['badge']); ?>
                    </span>
                    <h3 class="category-header-title mb-0">
                        <?php echo htmlspecialchars($cleanLabel ?: $gData['group']['label']); ?>
                    </h3>
                    <span class="category-header-count">
                        <?php echo count($catArticles); ?> ข่าวล่าสุด
                    </span>
                </div>
                <a href="index.php?group=<?php echo urlencode($gKey); ?>" class="category-view-all-link">
                    <span>ดูทั้งหมดในหมวดนี้</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- 4-Column Card Grid (Matching NEWS TIMES reference layout) -->
            <div class="row g-3 g-md-4">
                <?php foreach ($catArticles as $artIdx => $art): 
                    $thumb = getVarientThumbnail($art, $artIdx);
                    $authorName = $art['author'] ?: $art['source_label'];
                ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <article class="category-news-card">
                            <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($gKey); ?>" class="cat-card-media d-block position-relative text-decoration-none">
                                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>" class="cat-card-img" loading="lazy">
                                <!-- Floating Vibrant Category Pill on Image (matching reference photo) -->
                                <span class="cat-card-pill" style="background-color: <?php echo $meta['color']; ?>;">
                                    <?php echo htmlspecialchars($meta['badge']); ?>
                                </span>
                                <!-- Floating Publisher Badge -->
                                <span class="cat-card-source">
                                    <?php echo htmlspecialchars($art['source_label']); ?>
                                </span>
                            </a>
                            <div class="cat-card-body">
                                <h4 class="cat-card-title">
                                    <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($gKey); ?>">
                                        <?php echo htmlspecialchars($art['title']); ?>
                                    </a>
                                </h4>
                                <p class="cat-card-snippet">
                                    <?php echo htmlspecialchars($art['description'] ?: 'อ่านรายละเอียดบทความฉบับเต็มและสาระสำคัญจากสำนักข่าว...'); ?>
                                </p>
                                <div class="cat-card-footer">
                                    <span class="cat-card-time">
                                        <i class="bi bi-clock me-1 opacity-75"></i> <?php echo htmlspecialchars($art['time_ago']); ?>
                                    </span>
                                    <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($gKey); ?>" class="cat-card-read-more" style="color: <?php echo $meta['color']; ?>;">
                                        อ่านต่อ &rarr;
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- Magazine Lower Widgets Row (Trending, Sources, Newsletter) -->
    <div class="row g-4 mt-2 pt-3 border-top" style="border-color: var(--border-subtle) !important;">
        <!-- Widget 1: Trending 01 - 05 -->
        <div class="col-12 col-lg-5">
            <div class="editorial-widget h-100 mb-0">
                <h4 class="widget-headline"><i class="bi bi-fire text-danger"></i> ข่าวยอดนิยมประจำวัน</h4>
                <div class="d-flex flex-column">
                    <?php foreach ($trendingArticles as $rank => $tr): 
                        $rankStr = sprintf('%02d', $rank + 1);
                        $trThumb = getVarientThumbnail($tr, $rank);
                    ?>
                        <div class="trending-row">
                            <span class="trending-rank-num"><?php echo $rankStr; ?></span>
                            <a href="news_detail.php?id=<?php echo urlencode($tr['id']); ?>&group=<?php echo urlencode($tr['group_key'] ?? $currentGroupKey); ?>" class="d-block flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($trThumb); ?>" alt="<?php echo htmlspecialchars($tr['title']); ?>" class="trending-row-thumb" loading="lazy">
                            </a>
                            <div class="flex-grow-1 min-w-0">
                                <a href="news_detail.php?id=<?php echo urlencode($tr['id']); ?>&group=<?php echo urlencode($tr['group_key'] ?? $currentGroupKey); ?>" class="trending-row-title">
                                    <?php echo htmlspecialchars($tr['title']); ?>
                                </a>
                                <div class="text-muted small mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                    <span><?php echo htmlspecialchars($tr['source_label']); ?></span>
                                    <span>&bull;</span>
                                    <span><?php echo htmlspecialchars($tr['time_ago']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Widget 2: Curated Sources -->
        <div class="col-12 col-lg-4">
            <div class="editorial-widget h-100 mb-0">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <h4 class="widget-headline mb-0 border-0 pb-0">
                        <i class="bi bi-patch-check-fill text-primary"></i> แหล่งข่าวที่คัดสรร
                    </h4>
                    <span class="badge rounded-1 font-monospace" style="background: rgba(255, 255, 255, 0.08); color: var(--text-muted); font-size: 0.7rem;">
                        <?php echo count($sourcesInGroup ?? []); ?> สำนัก
                    </span>
                </div>
                <div class="d-flex flex-column gap-2 mb-3" style="max-height: 280px; overflow-y: auto;">
                    <?php if (!empty($sourcesInGroup)): ?>
                        <?php foreach (array_slice($sourcesInGroup, 0, 8) as $s): ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle);">
                                <div class="d-flex align-items-center gap-2 text-truncate">
                                    <i class="bi bi-rss-fill text-warning small"></i>
                                    <span class="fw-medium small text-truncate"><?php echo htmlspecialchars($s['label']); ?></span>
                                </div>
                                <span class="badge rounded-1 small font-monospace" style="background: rgba(255, 255, 255, 0.06); color: var(--text-muted); font-size: 0.68rem;">
                                    <?php echo htmlspecialchars($s['key']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-medium d-flex align-items-center justify-content-center gap-1 py-2" style="border-radius: var(--radius-sm);" data-bs-toggle="modal" data-bs-target="#modalAddSource">
                    <i class="bi bi-plus-lg"></i> แนะนำฟีดข่าวใหม่
                </button>
            </div>
        </div>

        <!-- Widget 3: Newsletter & Contact -->
        <div class="col-12 col-lg-3 d-flex flex-column gap-4">
            <div class="editorial-widget flex-grow-1 mb-0" style="background: linear-gradient(145deg, rgba(14, 165, 233, 0.08), rgba(37, 99, 235, 0.04)); border-color: rgba(14, 165, 233, 0.2);">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small text-uppercase mb-1" style="letter-spacing: 0.08em;">
                    <i class="bi bi-envelope-paper"></i> The Morning Dispatch
                </div>
                <h5 class="fw-bold mb-2">สรุปข่าวไอทีและ AI ทุกเช้า</h5>
                <p class="small text-muted mb-3">คัดกรองประเด็นสำคัญ เทรนด์เทคโนโลยี และความเคลื่อนไหวล่าสุด</p>
                <div class="input-group mb-2">
                    <input type="email" class="form-control" placeholder="อีเมลของคุณ..." style="background-color: var(--bg-surface); border-color: var(--border-subtle); color: var(--text-heading); font-size: 0.85rem; border-radius: var(--radius-sm) 0 0 var(--radius-sm);">
                    <button class="btn btn-primary fw-semibold" style="background-color: var(--accent-brand); border: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: 0.85rem;" type="button">
                        ติดตาม
                    </button>
                </div>
                <div class="text-muted small opacity-75" style="font-size: 0.72rem;">ไม่มีสแปม &bull; ยกเลิกได้ทุกเมื่อ</div>
            </div>

            <div class="editorial-widget mb-0">
                <h4 class="widget-headline"><i class="bi bi-pen text-muted"></i> กองบรรณาธิการ</h4>
                <div class="small d-flex flex-column gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-primary"></i>
                        <a href="mailto:pongku71@gmail.com" class="text-decoration-none fw-semibold">pongku71@gmail.com</a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-success"></i>
                        <span class="fw-semibold">083-904-0153</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ========================================================================= -->
    <!-- MODE 2: SINGLE CATEGORY (8-COLUMN STREAM + 4-COLUMN SIDEBAR) -->
    <!-- ========================================================================= -->
    <?php
    $gridArticles = array_slice($articles ?? [], 4);
    if (empty($gridArticles)) {
        $gridArticles = $articles ?? [];
    }
    ?>
    <div class="row g-4">
        <!-- ==================== LEFT 8 COLS: EDITORIAL STREAM ==================== -->
        <div class="col-12 col-lg-8">
            <!-- Section Header & Filter Tabs -->
            <div class="section-header-row flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <h3 class="section-headline">
                        <span>ข่าวสารล่าสุด &bull; <?php echo htmlspecialchars($currentGroupLabel); ?></span>
                    </h3>
                    <span class="badge rounded-pill" style="background-color: rgba(14, 165, 233, 0.12); color: var(--accent-brand); font-size: 0.75rem; font-weight: 600;">
                        <?php echo count($articles ?? []); ?> เรื่อง
                    </span>
                </div>

                <!-- Source Filter Pills -->
                <div class="d-flex align-items-center gap-2 overflow-x-auto w-100 w-sm-auto pb-1" style="scrollbar-width: none;">
                    <button type="button" class="filter-tab-pill active" data-source="all" onclick="filterBySource('all', this)">
                        ทั้งหมด
                    </button>
                    <?php foreach ($availableSources as $s): ?>
                        <button type="button" class="filter-tab-pill" data-source="<?php echo htmlspecialchars($s['key']); ?>" onclick="filterBySource('<?php echo htmlspecialchars($s['key']); ?>', this)">
                            <?php echo htmlspecialchars($s['label']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- News Cards Grid -->
            <div class="row g-4" id="mainArticlesGrid">
                <?php if (!empty($gridArticles)): ?>
                    <?php foreach ($gridArticles as $i => $art): 
                        $thumb = getVarientThumbnail($art, $i + 4);
                        $badgeColor = $editorialColors[($i % count($editorialColors))];
                        $authorName = $art['author'] ?: $art['source_label'];
                        $artGroupKey = $art['group_key'] ?? $currentGroupKey;
                    ?>
                        <div class="col-12 col-md-6 article-grid-item" data-source="<?php echo htmlspecialchars($art['source_key']); ?>" data-group="<?php echo htmlspecialchars($artGroupKey); ?>" data-title="<?php echo htmlspecialchars(mb_strtolower($art['title'])); ?>">
                            <article class="article-feed-card">
                                <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($artGroupKey); ?>" class="feed-card-media d-block text-decoration-none position-relative">
                                    <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($art['title']); ?>" class="feed-card-img" loading="lazy">
                                    <span class="feed-card-badge" style="background-color: <?php echo $badgeColor; ?>;">
                                        <?php echo htmlspecialchars($art['source_label']); ?>
                                    </span>
                                </a>
                                <div class="feed-card-body">
                                    <h4 class="feed-card-title">
                                        <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($artGroupKey); ?>">
                                            <?php echo htmlspecialchars($art['title']); ?>
                                        </a>
                                    </h4>
                                    <p class="feed-card-snippet">
                                        <?php echo htmlspecialchars($art['description'] ?: 'อ่านรายละเอียดบทความฉบับเต็มและสาระสำคัญจากสำนักข่าว...'); ?>
                                    </p>
                                    <div class="feed-card-footer">
                                        <span class="d-flex align-items-center gap-1">
                                            <span><?php echo htmlspecialchars($authorName); ?></span>
                                            <span class="opacity-50">&bull;</span>
                                            <span><?php echo htmlspecialchars($art['time_ago']); ?></span>
                                        </span>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="news_detail.php?id=<?php echo urlencode($art['id']); ?>&group=<?php echo urlencode($artGroupKey); ?>" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="color: var(--accent-brand); font-size: 0.8125rem;">
                                                อ่านต่อ &rarr;
                                            </a>
                                            <a href="<?php echo htmlspecialchars($art['link']); ?>" target="_blank" rel="noopener noreferrer" class="text-muted" title="เปิดหน้าเว็บต้นฉบับ">
                                                <i class="bi bi-box-arrow-up-right small"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">ไม่พบบทความในหมวดหมู่นี้ในขณะนี้</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Client Filter Empty State -->
            <div id="noResultsState" class="d-none text-center py-5">
                <i class="bi bi-search fs-2 text-muted"></i>
                <p class="mt-3 text-muted">ไม่พบข้อมูลที่ตรงกับคำค้นหาหรือตัวกรองที่เลือก</p>
                <button class="btn btn-sm btn-outline-secondary" onclick="resetFilters()">ล้างตัวกรอง</button>
            </div>
        </div>

        <!-- ==================== RIGHT 4 COLS: MAGAZINE SIDEBAR ==================== -->
        <div class="col-12 col-lg-4">
            <!-- Widget 1: Search Widget -->
            <div class="editorial-widget">
                <h4 class="widget-headline"><i class="bi bi-search text-muted"></i> ค้นหาบทความ</h4>
                <div class="input-group">
                    <input type="text" id="sidebarSearchInput" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); font-size: 0.88rem; border-radius: var(--radius-sm) 0 0 var(--radius-sm);" placeholder="พิมพ์หัวข้อหรือคำค้น..." oninput="filterArticlesLive(this.value)">
                    <button class="btn btn-outline-secondary" style="border-color: var(--border-subtle); border-radius: 0 var(--radius-sm) var(--radius-sm) 0;" type="button" onclick="document.getElementById('sidebarSearchInput').value=''; filterArticlesLive('')">
                        <i class="bi bi-x-lg small"></i>
                    </button>
                </div>
            </div>

            <!-- Widget 2: Most Read / Trending (01 - 05) -->
            <div class="editorial-widget">
                <h4 class="widget-headline"><i class="bi bi-fire text-danger"></i> ข่าวยอดนิยม</h4>
                <div class="d-flex flex-column">
                    <?php foreach ($trendingArticles as $rank => $tr): 
                        $rankStr = sprintf('%02d', $rank + 1);
                        $trThumb = getVarientThumbnail($tr, $rank);
                    ?>
                        <div class="trending-row">
                            <span class="trending-rank-num"><?php echo $rankStr; ?></span>
                            <a href="news_detail.php?id=<?php echo urlencode($tr['id']); ?>&group=<?php echo urlencode($tr['group_key'] ?? $currentGroupKey); ?>" class="d-block flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($trThumb); ?>" alt="<?php echo htmlspecialchars($tr['title']); ?>" class="trending-row-thumb" loading="lazy">
                            </a>
                            <div class="flex-grow-1 min-w-0">
                                <a href="news_detail.php?id=<?php echo urlencode($tr['id']); ?>&group=<?php echo urlencode($tr['group_key'] ?? $currentGroupKey); ?>" class="trending-row-title">
                                    <?php echo htmlspecialchars($tr['title']); ?>
                                </a>
                                <div class="text-muted small mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                    <span><?php echo htmlspecialchars($tr['source_label']); ?></span>
                                    <span>&bull;</span>
                                    <span><?php echo htmlspecialchars($tr['time_ago']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Widget 3: Curated Sources -->
            <div class="editorial-widget">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <h4 class="widget-headline mb-0 border-0 pb-0">
                        <i class="bi bi-patch-check-fill text-primary"></i> แหล่งข่าวที่คัดสรร
                    </h4>
                    <span class="badge rounded-1 font-monospace" style="background: rgba(255, 255, 255, 0.08); color: var(--text-muted); font-size: 0.7rem;">
                        <?php echo count($sourcesInGroup ?? []); ?> สำนัก
                    </span>
                </div>

                <div class="d-flex flex-column gap-2 mb-3">
                    <?php if (!empty($sourcesInGroup)): ?>
                        <?php foreach ($sourcesInGroup as $s): ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle);">
                                <div class="d-flex align-items-center gap-2 text-truncate">
                                    <i class="bi bi-rss-fill text-warning small"></i>
                                    <span class="fw-medium small text-truncate"><?php echo htmlspecialchars($s['label']); ?></span>
                                </div>
                                <span class="badge rounded-1 small font-monospace" style="background: rgba(255, 255, 255, 0.06); color: var(--text-muted); font-size: 0.68rem;">
                                    <?php echo htmlspecialchars($s['key']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted small">ฟีดในหมวดหมู่นี้พร้อมใช้งาน</div>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-medium d-flex align-items-center justify-content-center gap-1 py-2" style="border-radius: var(--radius-sm);" data-bs-toggle="modal" data-bs-target="#modalAddSource">
                    <i class="bi bi-plus-lg"></i> แนะนำฟีดข่าวใหม่
                </button>
            </div>

            <!-- Widget 4: Newsletter Box -->
            <div class="editorial-widget" style="background: linear-gradient(145deg, rgba(14, 165, 233, 0.08), rgba(37, 99, 235, 0.04)); border-color: rgba(14, 165, 233, 0.2);">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small text-uppercase mb-1" style="letter-spacing: 0.08em;">
                    <i class="bi bi-envelope-paper"></i> The Morning Dispatch
                </div>
                <h5 class="fw-bold mb-2">รับสรุปข่าวไอทีและ AI ประจำวัน</h5>
                <p class="small text-muted mb-3">คัดกรองประเด็นสำคัญ เทรนด์เทคโนโลยี และข่าวสารน่าสนใจ ส่งตรงทุกเช้า</p>
                <div class="input-group mb-2">
                    <input type="email" class="form-control" placeholder="อีเมลของคุณ..." style="background-color: var(--bg-surface); border-color: var(--border-subtle); color: var(--text-heading); font-size: 0.85rem; border-radius: var(--radius-sm) 0 0 var(--radius-sm);">
                    <button class="btn btn-primary fw-semibold" style="background-color: var(--accent-brand); border: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: 0.85rem;" type="button">
                        ติดตาม
                    </button>
                </div>
                <div class="text-muted small opacity-75" style="font-size: 0.72rem;">ไม่มีสแปม &bull; สามารถยกเลิกได้ทุกเมื่อ</div>
            </div>

            <!-- Widget 5: Masthead Contact Desk -->
            <div class="editorial-widget">
                <h4 class="widget-headline"><i class="bi bi-pen text-muted"></i> กองบรรณาธิการ</h4>
                <p class="small text-muted mb-3">สำหรับส่งข่าวประชาสัมพันธ์ แจ้งแก้ไขบทความ หรือติดต่อทีมงาน:</p>
                <div class="small d-flex flex-column gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-primary"></i>
                        <a href="mailto:pongku71@gmail.com" class="text-decoration-none fw-semibold">pongku71@gmail.com</a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-success"></i>
                        <span class="fw-semibold">083-904-0153</span>
                    </div>
                </div>
                <a href="contact.php" class="btn btn-sm btn-outline-secondary w-100 py-2 fw-medium" style="border-radius: var(--radius-sm);">
                    ไปยังหน้าติดต่อเรา &rarr;
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<script>
    let activeSourceFilter = 'all';
    let activeGroupFilter = 'all';
    let activeSearchQuery = '';

    function filterBySource(sourceKey, btnElement) {
        activeSourceFilter = sourceKey;
        document.querySelectorAll('.filter-tab-pill').forEach(b => b.classList.remove('active'));
        if (btnElement) btnElement.classList.add('active');
        applyCombinedFilter();
    }

    function filterByGroup(groupKey, btnElement) {
        activeGroupFilter = groupKey;
        document.querySelectorAll('.filter-tab-pill').forEach(b => b.classList.remove('active'));
        if (btnElement) btnElement.classList.add('active');
        applyCombinedFilter();
    }

    function filterArticlesLive(query) {
        activeSearchQuery = query.toLowerCase().trim();
        applyCombinedFilter();
    }

    function handleClientSearch(e) {
        e.preventDefault();
        const q = document.getElementById('mainHeaderSearchInput').value;
        filterArticlesLive(q);
        const grid = document.getElementById('mainArticlesGrid');
        if (grid) grid.scrollIntoView({ behavior: 'smooth' });
        return false;
    }

    function resetFilters() {
        activeSourceFilter = 'all';
        activeGroupFilter = 'all';
        activeSearchQuery = '';
        const searchInput = document.getElementById('sidebarSearchInput');
        if (searchInput) searchInput.value = '';
        const headerSearch = document.getElementById('mainHeaderSearchInput');
        if (headerSearch) headerSearch.value = '';
        document.querySelectorAll('.filter-tab-pill').forEach((b, idx) => {
            if (idx === 0) b.classList.add('active');
            else b.classList.remove('active');
        });
        applyCombinedFilter();
    }

    function applyCombinedFilter() {
        const items = document.querySelectorAll('.article-grid-item');
        let visibleCount = 0;

        items.forEach(item => {
            const itemSource = item.getAttribute('data-source');
            const itemGroup = item.getAttribute('data-group');
            const itemTitle = item.getAttribute('data-title') || '';

            const matchesSource = (activeSourceFilter === 'all' || itemSource === activeSourceFilter);
            const matchesGroup = (activeGroupFilter === 'all' || itemGroup === activeGroupFilter);
            const matchesQuery = (activeSearchQuery === '' || itemTitle.includes(activeSearchQuery));

            if (matchesSource && matchesGroup && matchesQuery) {
                item.classList.remove('d-none');
                visibleCount++;
            } else {
                item.classList.add('d-none');
            }
        });

        const noRes = document.getElementById('noResultsState');
        if (noRes) {
            if (visibleCount === 0 && items.length > 0) {
                noRes.classList.remove('d-none');
            } else {
                noRes.classList.add('d-none');
            }
        }
    }
</script>
