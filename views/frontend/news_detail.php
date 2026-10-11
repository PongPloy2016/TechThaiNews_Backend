<?php
/**
 * TechThaiNews - News Detail View
 * Editorial Layout for Individual News Articles with Reading Tools, Sharing, and Related Stories
 */

include __DIR__ . '/includes/head.php';
include __DIR__ . '/includes/topbar.php';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';

$articleThumb = $article ? RssFeedService::getThumbnail($article, 0) : '';
$authorName = $article ? ($article['author'] ?: $article['source_label']) : 'กองบรรณาธิการ';
$authorInitial = mb_substr($authorName, 0, 1, 'UTF-8');

// Format full Thai date
$thaiMonths = [
    1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.', 5 => 'พ.ค.', 6 => 'มิ.ย.',
    7 => 'ก.ค.', 8 => 'ส.ค.', 9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.'
];
$pubTime = $article ? ($article['pub_date'] ?? time()) : time();
$dayNum = (int)date('j', $pubTime);
$monthNum = (int)date('n', $pubTime);
$yearShort = ((int)date('Y', $pubTime) + 543) % 100;
$yearShortStr = sprintf('%02d', $yearShort);
$timeStr = date('H:i', $pubTime);
$fullThaiDate = 'วันที่ ' . $dayNum . ' ' . ($thaiMonths[$monthNum] ?? '') . ' ' . $yearShortStr . ' เวลา ' . $timeStr;

// Editorial color palette
$badgeColors = [
    'tech-business' => 'var(--accent-brand)',
    'ai-innovation' => 'var(--accent-coral)',
    'gadgets'       => 'var(--accent-teal)',
    'telecom'       => '#8b5cf6',
    'cybersecurity' => '#f59e0b',
];
$categoryColor = $badgeColors[$currentGroupKey] ?? 'var(--accent-brand)';

// Tags generation
$tags = ['เทคโนโลยี', 'ดิจิทัล'];
if ($article) {
    $titleLower = mb_strtolower($article['title'] . ' ' . ($article['description'] ?? ''), 'UTF-8');
    if (preg_match('/(ai|chatgpt|gemini|openai|ปัญญาประดิษฐ์)/i', $titleLower)) $tags[] = 'AI';
    if (preg_match('/(iphone|apple|mac|ipad)/i', $titleLower)) $tags[] = 'Apple';
    if (preg_match('/(android|samsung|google|xiaomi)/i', $titleLower)) $tags[] = 'สมาร์ทโฟน';
    if (preg_match('/(ev|tesla|byd|รถยนต์)/i', $titleLower)) $tags[] = 'ยานยนต์ไฟฟ้า';
    if (preg_match('/(เกม|game|gaming|steam)/i', $titleLower)) $tags[] = 'เกม';
    if (preg_match('/(ความปลอดภัย|cyber|hack)/i', $titleLower)) $tags[] = 'ความปลอดภัยไซเบอร์';
}
$tags = array_unique($tags);

// Absolute URL for sharing
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<style>
/* Editorial News Detail Styling */
.news-detail-wrapper {
    background-color: var(--bg-page);
    min-height: 80vh;
}

.article-breadcrumb {
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 1.25rem;
}
.article-breadcrumb a {
    color: var(--text-muted);
    text-decoration: none;
    transition: var(--transition-smooth);
}
.article-breadcrumb a:hover {
    color: var(--accent-brand);
}
.article-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    color: var(--text-faint);
}

.article-hero-box {
    background-color: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg);
    padding: 2.25rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-card);
}

.article-main-title {
    font-family: var(--font-editorial-heading);
    font-size: clamp(1.75rem, 3.5vw, 2.5rem);
    font-weight: 800;
    line-height: 1.3;
    letter-spacing: -0.025em;
    color: var(--text-heading);
    margin-top: 1rem;
    margin-bottom: 1.25rem;
}

.article-meta-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding-bottom: 1.25rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-subtle);
}

.author-info-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.author-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-brand), #2563eb);
    color: #ffffff;
    font-weight: 700;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(14, 165, 233, 0.25);
}

.reading-tools-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tool-btn {
    background-color: var(--bg-surface-elevated);
    border: 1px solid var(--border-subtle);
    color: var(--text-body);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.4rem 0.75rem;
    border-radius: var(--radius-sm);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    cursor: pointer;
    transition: var(--transition-smooth);
    text-decoration: none;
}
.tool-btn:hover {
    background-color: var(--bg-surface-hover);
    color: var(--accent-brand);
    border-color: var(--accent-brand);
}
.tool-btn.active {
    background-color: var(--accent-brand);
    color: #ffffff;
    border-color: var(--accent-brand);
}

.share-action-pill {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 0.95rem;
    text-decoration: none;
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.share-action-pill:hover {
    transform: translateY(-2px);
    opacity: 0.9;
    color: #ffffff;
}
.share-fb { background-color: #1877f2; }
.share-x  { background-color: #0f1419; border: 1px solid rgba(255,255,255,0.2); }
.share-line { background-color: #06c755; }
.share-copy { background-color: #475569; }

.article-feature-image-container {
    position: relative;
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-subtle);
    background-color: var(--bg-surface-elevated);
}
.article-feature-img {
    width: 100%;
    max-height: 520px;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.article-feature-image-container:hover .article-feature-img {
    transform: scale(1.015);
}
.article-img-caption {
    padding: 0.65rem 1rem;
    font-size: 0.78rem;
    color: var(--text-muted);
    background-color: var(--bg-surface);
    border-top: 1px solid var(--border-subtle);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.article-lead-pullquote {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.08), rgba(99, 102, 241, 0.04));
    border-left: 4px solid var(--accent-brand);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    padding: 1.25rem 1.5rem;
    font-size: 1.15rem;
    font-weight: 500;
    line-height: 1.7;
    color: var(--text-heading);
    margin-bottom: 2rem;
}

.article-reader-content {
    font-family: var(--font-editorial-body);
    font-size: 1.0625rem;
    line-height: 1.85;
    color: var(--text-body);
    transition: font-size 0.2s ease;
}
.article-reader-content p {
    margin-bottom: 1.4rem;
}

.article-highlights-card {
    background-color: var(--bg-surface-elevated);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin: 2rem 0;
}

.source-attribution-banner {
    background: linear-gradient(135deg, var(--bg-surface-elevated), var(--bg-surface));
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    margin-top: 2.5rem;
    margin-bottom: 2.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    box-shadow: var(--shadow-card);
}
@media (min-width: 768px) {
    .source-attribution-banner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.related-story-card {
    background-color: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: var(--transition-smooth);
}
.related-story-card:hover {
    transform: translateY(-4px);
    border-color: var(--border-strong);
    box-shadow: var(--shadow-hover);
}
.related-story-thumb {
    width: 100%;
    height: 160px;
    object-fit: cover;
    display: block;
}
.related-story-body {
    padding: 1.15rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.related-story-title {
    font-size: 0.95rem;
    font-weight: 700;
    line-height: 1.4;
    color: var(--text-heading);
    text-decoration: none;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.related-story-title:hover {
    color: var(--accent-brand);
}

/* Toast alert for copied link */
#copyLinkToast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    background-color: #10b981;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 12px 20px;
    border-radius: 999px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    display: none;
    align-items: center;
    gap: 8px;
    animation: fadeInSlide 0.3s ease;
}
@keyframes fadeInSlide {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="news-detail-wrapper py-4">
    <div class="container-xl">
        <!-- 1. Breadcrumbs -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb article-breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php"><i class="bi bi-house-door-fill me-1"></i>หน้าแรก</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>">
                        <?php echo htmlspecialchars($cleanCurrentGroupLabel); ?>
                    </a>
                </li>
                <?php if ($article): ?>
                    <li class="breadcrumb-item active text-truncate d-none d-md-inline-block" style="max-width: 450px;" aria-current="page">
                        <?php echo htmlspecialchars($article['title']); ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">ไม่พบเนื้อหา</li>
                <?php endif; ?>
            </ol>
        </nav>

        <?php if ($article): ?>
            <div class="row g-4">
                <!-- ==================== LEFT 8 COLS: EDITORIAL ARTICLE ==================== -->
                <div class="col-12 col-lg-8">
                    <article class="article-hero-box">
                        <!-- Category Kicker -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="editorial-kicker" style="background-color: <?php echo $categoryColor; ?>;">
                                <?php echo htmlspecialchars($cleanCurrentGroupLabel); ?> &bull; <?php echo htmlspecialchars($article['source_label']); ?>
                            </span>

                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-pill font-monospace" style="background: rgba(16, 185, 129, 0.12); color: var(--accent-teal); font-size: 0.72rem; font-weight: 600;">
                                    <i class="bi bi-patch-check-fill me-1"></i> ตรวจสอบแล้ว
                                </span>
                                <?php if (!empty($article['is_new'])): ?>
                                    <span class="badge rounded-pill" style="background: rgba(244, 63, 94, 0.12); color: var(--accent-coral); font-size: 0.72rem; font-weight: 600;">
                                        ข่าวล่าสุด
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Main Title -->
                        <h1 class="article-main-title">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </h1>

                        <!-- Meta Bar -->
                        <div class="article-meta-bar">
                            <!-- Left: Author Info -->
                            <div class="author-info-group">
                                <div class="author-circle">
                                    <?php echo htmlspecialchars($authorInitial); ?>
                                </div>
                                <div>
                                    <div class="fw-semibold text-heading" style="font-size: 0.95rem;">
                                        <?php echo htmlspecialchars($authorName); ?>
                                    </div>
                                    <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                                        <span><?php echo htmlspecialchars($fullThaiDate); ?></span>
                                        <span>&bull;</span>
                                        <span><?php echo htmlspecialchars($article['reading_time'] ?? 'อ่าน 2 นาที'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Reading Tools -->
                            <div class="reading-tools-group">
                                <button type="button" class="tool-btn" onclick="adjustFontSize(-1)" title="ลดขนาดตัวอักษร">
                                    <i class="bi bi-type"></i> A-
                                </button>
                                <button type="button" class="tool-btn" onclick="adjustFontSize(1)" title="เพิ่มขนาดตัวอักษร">
                                    <i class="bi bi-type"></i> A+
                                </button>
                                <button type="button" class="tool-btn" onclick="window.print()" title="พิมพ์บทความ">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <button type="button" class="tool-btn" id="btnBookmarkToggle" onclick="toggleBookmarkArticle()" title="บันทึกบุ๊กมาร์ก">
                                    <i class="bi bi-bookmark" id="bookmarkIcon"></i>
                                    <span id="bookmarkText">บันทึก</span>
                                </button>
                            </div>
                        </div>

                        <!-- Social Share Bar Top -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded mb-4" style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle);">
                            <span class="fw-semibold small text-muted d-flex align-items-center gap-2">
                                <i class="bi bi-share-fill text-primary"></i> แชร์บทความนี้:
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($currentUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-action-pill share-fb" title="แชร์บน Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title'] . ' ' . $currentUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-action-pill share-x" title="แชร์บน X (Twitter)">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                                <a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode($currentUrl); ?>" target="_blank" rel="noopener noreferrer" class="share-action-pill share-line" title="แชร์ผ่าน LINE">
                                    <i class="bi bi-line"></i>
                                </a>
                                <button type="button" class="share-action-pill share-copy border-0" onclick="copyArticleLink()" title="คัดลอกลิงก์">
                                    <i class="bi bi-link-45deg fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Feature Cover Image -->
                        <div class="article-feature-image-container">
                            <img src="<?php echo htmlspecialchars($articleThumb); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-feature-img" loading="eager">
                            <div class="article-img-caption">
                                <span><i class="bi bi-image me-1"></i> ภาพประกอบจากสำนักข่าว: <?php echo htmlspecialchars($article['source_label']); ?></span>
                                <span class="font-monospace">TechThaiNews Live Dispatch</span>
                            </div>
                        </div>

                        <!-- Lead Summary Quote -->
                        <?php 
                        $leadSummary = !empty($article['lead_summary']) ? $article['lead_summary'] : $article['description'];
                        if (!empty($leadSummary)): 
                        ?>
                            <div class="article-lead-pullquote">
                                <i class="bi bi-quote fs-3 text-primary me-2 align-middle"></i>
                                <?php echo htmlspecialchars($leadSummary); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Reader Content -->
                        <div class="article-reader-content" id="articleBodyText">
                            <?php if (!empty($article['paragraphs']) && is_array($article['paragraphs'])): ?>
                                <?php foreach ($article['paragraphs'] as $pIdx => $para): ?>
                                    <p><?php echo nl2br(htmlspecialchars($para)); ?></p>
                                    <?php if ($pIdx === 1): ?>
                                        <!-- Key Highlights Box in between paragraphs -->
                                        <div class="article-highlights-card">
                                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1rem; color: var(--text-heading);">
                                                <i class="bi bi-lightning-charge-fill text-warning"></i> สาระสำคัญที่ควรรู้
                                            </h5>
                                            <ul class="mb-0 ps-3 small text-muted d-flex flex-column gap-2" style="font-size: 0.88rem; line-height: 1.6;">
                                                <li>รายงานโดย <strong><?php echo htmlspecialchars($article['source_label']); ?></strong> ผ่านระบบถ่ายทอดสดแบบ Real-Time RSS Feed</li>
                                                <li>หมวดหมู่ข่าว: <strong><?php echo htmlspecialchars($cleanCurrentGroupLabel); ?></strong></li>
                                                <li>ตรวจสอบและประมวลผลข้อมูลบทความเมื่อ: <strong><?php echo htmlspecialchars($article['time_ago']); ?></strong></li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php elseif (!empty($article['full_text'])): ?>
                                <p><?php echo nl2br(htmlspecialchars($article['full_text'])); ?></p>
                            <?php else: ?>
                                <p><?php echo htmlspecialchars($article['description'] ?: 'อ่านรายละเอียดบทความฉบับสมบูรณ์ได้จากเว็บไซต์สำนักข่าวต้นทาง...'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Source Publisher Attribution Card -->
                        <div class="source-attribution-banner">
                            <div class="d-flex align-items-center gap-3">
                                <div class="brand-mark" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-6 text-heading">
                                        ต้นฉบับข่าวจาก: <?php echo htmlspecialchars($article['source_label']); ?>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        คลิกปุ่มด้านล่างเพื่อเข้าอ่านเนื้อหาฉบับเต็ม พร้อมรูปภาพและคลิปประกอบจากสำนักข่าวต้นฉบับ
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <a href="<?php echo htmlspecialchars($article['link']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary fw-semibold px-4 py-2 d-inline-flex align-items-center gap-2 text-white" style="background-color: var(--accent-brand); border: none; border-radius: var(--radius-sm); font-size: 0.9rem;">
                                    <span>อ่านบทความต้นฉบับ</span>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>

                                <button class="btn btn-outline-secondary py-2 px-3 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWebPreview" aria-expanded="false" aria-controls="collapseWebPreview" style="font-size: 0.88rem; border-radius: var(--radius-sm);">
                                    <i class="bi bi-window me-1"></i> พรีวิวหน้าเว็บ
                                </button>
                            </div>
                        </div>

                        <!-- Web Preview Accordion (Collapsible) -->
                        <div class="collapse mb-4" id="collapseWebPreview">
                            <div class="card card-body p-2" style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-md);">
                                <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-2">
                                    <span class="small text-muted font-monospace"><i class="bi bi-globe me-1"></i> <?php echo htmlspecialchars($article['link']); ?></span>
                                    <a href="<?php echo htmlspecialchars($article['link']); ?>" target="_blank" class="small text-primary text-decoration-none">เปิดในแท็บใหม่ ↗</a>
                                </div>
                                <div style="height: 520px; width: 100%; border-radius: var(--radius-sm); overflow: hidden; background: #fff;">
                                    <iframe src="<?php echo htmlspecialchars($article['link']); ?>" style="width: 100%; height: 100%; border: none;" title="Web Preview" sandbox="allow-same-origin allow-scripts allow-popups"></iframe>
                                </div>
                                <div class="small text-muted mt-2 px-2 text-center" style="font-size: 0.75rem;">
                                    * หากบางสำนักข่าวบล็อกการแสดงผลในเฟรม กรุณาคลิกปุ่ม "อ่านบทความต้นฉบับ" ด้านบนเพื่อเปิดหน้าเว็บโดยตรง
                                </div>
                            </div>
                        </div>

                        <!-- Tag Pills -->
                        <div class="d-flex align-items-center flex-wrap gap-2 pt-3 border-top" style="border-color: var(--border-subtle) !important;">
                            <span class="small text-muted me-2"><i class="bi bi-tags-fill me-1"></i> แท็ก:</span>
                            <?php foreach ($tags as $tag): ?>
                                <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>" class="badge rounded-pill text-decoration-none py-2 px-3 fw-normal" style="background-color: var(--bg-surface-elevated); border: 1px solid var(--border-subtle); color: var(--text-body); font-size: 0.78rem;">
                                    #<?php echo htmlspecialchars($tag); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Navigation Back to Stream -->
                        <div class="mt-4 pt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>" class="btn btn-outline-secondary btn-sm py-2 px-3 d-inline-flex align-items-center gap-2" style="border-radius: var(--radius-sm);">
                                &larr; กลับไปหน้ารวมข่าว <?php echo htmlspecialchars($cleanCurrentGroupLabel); ?>
                            </a>

                            <div class="d-flex align-items-center gap-2">
                                <a href="index.php" class="btn btn-sm btn-link text-decoration-none text-muted">
                                    <i class="bi bi-house me-1"></i> หน้าแรก
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                                    <i class="bi bi-arrow-up"></i> กลับขึ้นด้านบน
                                </button>
                            </div>
                        </div>
                    </article>

                    <!-- ==================== RELATED STORIES ==================== -->
                    <div class="my-5">
                        <div class="section-header-row mb-4">
                            <h3 class="section-headline">
                                <span>ข่าวที่เกี่ยวข้องในหมวดนี้</span>
                            </h3>
                            <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>" class="text-decoration-none small fw-semibold" style="color: var(--accent-brand);">
                                ดูทั้งหมดในหมวดนี้ &rarr;
                            </a>
                        </div>

                        <div class="row g-3">
                            <?php if (!empty($relatedArticles)): ?>
                                <?php foreach ($relatedArticles as $relIdx => $relArt): 
                                    $relThumb = RssFeedService::getThumbnail($relArt, $relIdx + 1);
                                    $relAuthor = $relArt['author'] ?: $relArt['source_label'];
                                ?>
                                    <div class="col-12 col-sm-6 col-md-3">
                                        <div class="related-story-card">
                                            <a href="news_detail.php?id=<?php echo urlencode($relArt['id']); ?>&group=<?php echo urlencode($currentGroupKey); ?>">
                                                <img src="<?php echo htmlspecialchars($relThumb); ?>" alt="<?php echo htmlspecialchars($relArt['title']); ?>" class="related-story-thumb" loading="lazy">
                                            </a>
                                            <div class="related-story-body">
                                                <div class="mb-2">
                                                    <span class="badge rounded-1 font-monospace" style="background: rgba(14, 165, 233, 0.1); color: var(--accent-brand); font-size: 0.68rem;">
                                                        <?php echo htmlspecialchars($relArt['source_label']); ?>
                                                    </span>
                                                </div>
                                                <a href="news_detail.php?id=<?php echo urlencode($relArt['id']); ?>&group=<?php echo urlencode($currentGroupKey); ?>" class="related-story-title">
                                                    <?php echo htmlspecialchars($relArt['title']); ?>
                                                </a>
                                                <div class="mt-auto pt-2 text-muted small d-flex align-items-center justify-content-between" style="font-size: 0.72rem;">
                                                    <span class="text-truncate" style="max-width: 100px;"><?php echo htmlspecialchars($relAuthor); ?></span>
                                                    <span><?php echo htmlspecialchars($relArt['time_ago']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center py-4 text-muted">
                                    ไม่มีข่าวที่เกี่ยวข้องเพิ่มเติมในขณะนี้
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ==================== RIGHT 4 COLS: MAGAZINE SIDEBAR ==================== -->
                <div class="col-12 col-lg-4">
                    <!-- Widget 1: Search -->
                    <div class="editorial-widget">
                        <h4 class="widget-headline"><i class="bi bi-search text-muted"></i> ค้นหาบทความ</h4>
                        <form action="index.php" method="get">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); font-size: 0.88rem; border-radius: var(--radius-sm) 0 0 var(--radius-sm);" placeholder="ค้นหาข่าวสาร...">
                                <button class="btn btn-primary" style="background-color: var(--accent-brand); border: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Widget 2: Trending / Most Read (01 - 05) -->
                    <div class="editorial-widget">
                        <h4 class="widget-headline"><i class="bi bi-fire text-danger"></i> ข่าวยอดนิยมในหมวด</h4>
                        <div class="d-flex flex-column">
                            <?php foreach ($trendingArticles as $rank => $tr): 
                                $rankStr = sprintf('%02d', $rank + 1);
                                $trThumb = RssFeedService::getThumbnail($tr, $rank);
                            ?>
                                <div class="trending-row">
                                    <span class="trending-rank-num"><?php echo $rankStr; ?></span>
                                    <img src="<?php echo htmlspecialchars($trThumb); ?>" alt="<?php echo htmlspecialchars($tr['title']); ?>" class="trending-row-thumb" loading="lazy">
                                    <div class="flex-grow-1 min-w-0">
                                        <a href="news_detail.php?id=<?php echo urlencode($tr['id']); ?>&group=<?php echo urlencode($currentGroupKey); ?>" class="trending-row-title">
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

                    <!-- Widget 3: Quick Category Navigator -->
                    <div class="editorial-widget">
                        <h4 class="widget-headline"><i class="bi bi-grid text-muted"></i> หมวดหมู่อื่นๆ</h4>
                        <div class="list-group list-group-flush">
                            <?php foreach (($groups ?? []) as $grp): 
                                $gKey = $grp['key'];
                                $gLabel = $grp['label'];
                                $cleanGrpLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $gLabel));
                                $isCurrent = ($currentGroupKey === $gKey);
                            ?>
                                <a href="index.php?group=<?php echo urlencode($gKey); ?>" class="list-group-item list-group-item-action bg-transparent border-0 px-0 py-2 d-flex align-items-center justify-content-between <?php echo $isCurrent ? 'fw-bold text-primary' : 'text-body'; ?>" style="font-size: 0.88rem;">
                                    <span><?php echo htmlspecialchars($cleanGrpLabel ?: $gLabel); ?></span>
                                    <i class="bi bi-chevron-right small text-muted"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Widget 4: Newsletter Box -->
                    <div class="editorial-widget" style="background: linear-gradient(145deg, rgba(14, 165, 233, 0.08), rgba(37, 99, 235, 0.04)); border-color: rgba(14, 165, 233, 0.2);">
                        <div class="d-flex align-items-center gap-2 text-primary fw-bold small text-uppercase mb-1" style="letter-spacing: 0.08em;">
                            <i class="bi bi-envelope-paper"></i> The Morning Dispatch
                        </div>
                        <h5 class="fw-bold mb-2">รับสรุปข่าวไอทีประจำวัน</h5>
                        <p class="small text-muted mb-3">สรุปข่าวสารและเทคโนโลยีสำคัญส่งตรงถึงคุณทุกเช้า</p>
                        <div class="input-group mb-2">
                            <input type="email" class="form-control" placeholder="อีเมลของคุณ..." style="background-color: var(--bg-surface); border-color: var(--border-subtle); color: var(--text-heading); font-size: 0.85rem; border-radius: var(--radius-sm) 0 0 var(--radius-sm);">
                            <button class="btn btn-primary fw-semibold" style="background-color: var(--accent-brand); border: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-size: 0.85rem;" type="button">
                                ติดตาม
                            </button>
                        </div>
                        <div class="text-muted small opacity-75" style="font-size: 0.72rem;">ไม่มีสแปม &bull; สามารถยกเลิกได้ทุกเมื่อ</div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- 404 / Article Not Found State -->
            <div class="row justify-content-center my-5">
                <div class="col-12 col-md-8 col-lg-6 text-center">
                    <div class="p-5 rounded-4" style="background-color: var(--bg-surface); border: 1px solid var(--border-subtle); box-shadow: var(--shadow-card);">
                        <div class="display-1 text-muted mb-3"><i class="bi bi-journal-x"></i></div>
                        <h2 class="fw-bold mb-2">ไม่พบเนื้อหาบทความที่คุณต้องการ</h2>
                        <p class="text-muted mb-4">
                            บทความนี้อาจถูกปรับปรุง หรือข้อมูลฟีดของสำนักข่าวมีการอัปเดตชุดใหม่ล่าสุด คุณสามารถกลับไปเลือกดูข่าวสารจากหน้าหลัก หรือเลือกหมวดหมู่อื่นๆ ได้ครับ
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="index.php" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: var(--accent-brand); border: none; border-radius: var(--radius-sm);">
                                <i class="bi bi-house-door me-1"></i> กลับหน้าแรก
                            </a>
                            <a href="index.php?refresh=1" class="btn btn-outline-secondary px-4 py-2" style="border-radius: var(--radius-sm);">
                                <i class="bi bi-arrow-clockwise me-1"></i> รีเฟรชฟีดล่าสุด
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Toast notification for copy link -->
<div id="copyLinkToast">
    <i class="bi bi-check-circle-fill"></i>
    <span>คัดลอกลิงก์บทความเรียบร้อยแล้ว!</span>
</div>

<script>
// Dynamic Font Size Adjuster
let currentFontSizeMultiplier = 1;
function adjustFontSize(delta) {
    const bodyText = document.getElementById('articleBodyText');
    if (!bodyText) return;
    currentFontSizeMultiplier += (delta * 0.1);
    if (currentFontSizeMultiplier < 0.85) currentFontSizeMultiplier = 0.85;
    if (currentFontSizeMultiplier > 1.35) currentFontSizeMultiplier = 1.35;
    bodyText.style.fontSize = (1.0625 * currentFontSizeMultiplier) + 'rem';
}

// Copy Link with Toast notification
function copyArticleLink() {
    const url = window.location.href;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(showToast);
    } else {
        const tempInput = document.createElement('input');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showToast();
    }
}

function showToast() {
    const toast = document.getElementById('copyLinkToast');
    if (!toast) return;
    toast.style.display = 'flex';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 2800);
}

// Local Storage Bookmark Toggle
const articleData = <?php echo json_encode($article ?? null, JSON_UNESCAPED_UNICODE); ?>;

function checkBookmarkStatus() {
    if (!articleData || !articleData.id) return;
    const bookmarks = JSON.parse(localStorage.getItem('techthai_bookmarks') || '[]');
    const isBookmarked = bookmarks.some(b => b.id === articleData.id);
    updateBookmarkUi(isBookmarked);
}

function updateBookmarkUi(isBookmarked) {
    const icon = document.getElementById('bookmarkIcon');
    const text = document.getElementById('bookmarkText');
    const btn = document.getElementById('btnBookmarkToggle');
    if (!icon || !btn) return;

    if (isBookmarked) {
        icon.className = 'bi bi-bookmark-fill text-warning';
        text.innerText = 'บันทึกแล้ว';
        btn.classList.add('border-warning');
    } else {
        icon.className = 'bi bi-bookmark';
        text.innerText = 'บันทึก';
        btn.classList.remove('border-warning');
    }
}

function toggleBookmarkArticle() {
    if (!articleData || !articleData.id) return;
    let bookmarks = JSON.parse(localStorage.getItem('techthai_bookmarks') || '[]');
    const idx = bookmarks.findIndex(b => b.id === articleData.id);

    if (idx >= 0) {
        bookmarks.splice(idx, 1);
        localStorage.setItem('techthai_bookmarks', JSON.stringify(bookmarks));
        updateBookmarkUi(false);
    } else {
        bookmarks.push({
            id: articleData.id,
            title: articleData.title,
            link: articleData.link,
            image: articleData.image || '',
            source_label: articleData.source_label || '',
            saved_at: new Date().toISOString()
        });
        localStorage.setItem('techthai_bookmarks', JSON.stringify(bookmarks));
        updateBookmarkUi(true);
    }
}

document.addEventListener('DOMContentLoaded', checkBookmarkStatus);
</script>

<?php
include __DIR__ . '/includes/modals.php';
include __DIR__ . '/includes/footer.php';
?>
