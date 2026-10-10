<?php
/**
 * News Feed Component
 * Article cards matching the exact layout in the user's screenshot
 */

$totalArticles = count($articles ?? []);
$newCount = 0;
foreach (($articles ?? []) as $a) {
    if (!empty($a['is_new'])) {
        $newCount++;
    }
}
if ($newCount === 0) {
    $newCount = min(13, $totalArticles);
}
?>

<style>
    .feed-wrapper {
        max-width: 680px;
        margin: 0 auto;
        padding: 0 16px 80px 16px;
    }

    /* Breaking alert banner */
    .breaking-banner {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.4);
        border-radius: 999px;
        padding: 9px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        animation: fadeInDown 0.4s ease;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .breaking-banner-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        color: #fca5a5;
        font-weight: 500;
    }

    .breaking-icon {
        color: #ef4444;
        font-size: 1rem;
        animation: pulseIcon 1.5s infinite;
    }

    @keyframes pulseIcon {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.25); }
    }

    .breaking-banner-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-view-new {
        background: #dc2626;
        color: #ffffff;
        border: none;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
    }

    .btn-view-new:hover {
        background: #b91c1c;
        transform: scale(1.03);
    }

    .btn-close-banner {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 14px;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-close-banner:hover {
        color: #ffffff;
    }

    /* Section Subheader */
    .feed-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .feed-title-wrap {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .feed-main-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feed-main-title h2 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .badge-new {
        background: #ef4444;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 2px 9px;
        border-radius: 999px;
        letter-spacing: 0.2px;
    }

    .feed-subtitle {
        font-size: 0.8rem;
        color: var(--app-text-muted);
    }

    .feed-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-header-round {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #151e32;
        border: 1px solid #1f2a44;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-header-round:hover {
        background: #1c2742;
        color: #60a5fa;
    }

    .btn-mark-all-read {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 999px;
        background: #151e32;
        border: 1px solid #1f2a44;
        color: #cbd5e1;
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
    }

    .btn-mark-all-read:hover {
        background: #1c2742;
        color: #34d399;
        border-color: #059669;
    }

    /* Filter Pills */
    .filter-pills-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .feed-filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #94a3b8;
        background: #111827;
        border: 1px solid #1e293b;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .feed-filter-pill:hover {
        color: #ffffff;
        background: #172033;
    }

    .feed-filter-pill.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
    }

    /* Cards Feed */
    .article-cards-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .article-card {
        background: #131b2e;
        border: 1px solid #1e293b;
        border-left: 3.5px solid #ef4444;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    .article-card:hover {
        border-color: #334155;
        border-left-color: #ef4444;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }

    .article-card.is-read {
        opacity: 0.72;
    }

    /* Image Wrapper */
    .article-img-wrap {
        position: relative;
        width: 100%;
        height: 220px;
        background: #0d1322;
        overflow: hidden;
    }

    .article-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .article-card:hover .article-img {
        transform: scale(1.03);
    }

    .article-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        color: #64748b;
        gap: 8px;
    }

    .badge-latest {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
        z-index: 2;
    }

    .badge-reading-time {
        position: absolute;
        bottom: 12px;
        right: 14px;
        background: rgba(0, 0, 0, 0.7);
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 999px;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        z-index: 2;
    }

    /* Content Area */
    .article-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .article-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #f1f5f9;
        line-height: 1.45;
        letter-spacing: -0.2px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        transition: color 0.2s;
    }

    .article-title:hover {
        color: #60a5fa;
    }

    .article-desc {
        font-size: 0.9rem;
        color: #94a3b8;
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Footer */
    .article-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 6px;
        padding-top: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .article-meta-left {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 0.84rem;
    }

    .meta-source {
        color: #60a5fa;
        font-weight: 600;
    }

    .meta-dot {
        color: #475569;
    }

    .meta-author {
        color: #cbd5e1;
        font-weight: 500;
    }

    .meta-time {
        color: #64748b;
    }

    .btn-bookmark {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-bookmark:hover {
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        border-color: rgba(59, 130, 246, 0.4);
    }

    .btn-bookmark.bookmarked {
        background: rgba(37, 99, 235, 0.25);
        color: #60a5fa;
        border-color: #2563eb;
    }

    /* Empty state */
    .feed-empty-box {
        text-align: center;
        padding: 60px 20px;
        background: #111827;
        border: 1px dashed #1e293b;
        border-radius: 16px;
        color: #94a3b8;
        display: none;
    }

    .feed-empty-box.show {
        display: block;
    }
</style>

<div class="feed-wrapper" id="feed-wrapper">

    <!-- Breaking Alert Banner -->
    <div class="breaking-banner" id="breaking-banner">
        <div class="breaking-banner-left">
            <span class="breaking-icon">⚡</span>
            <span>พบข่าวใหม่มาเพิ่ม <strong style="color: #f87171;" id="banner-count"><?php echo $totalArticles; ?></strong> ข่าว!</span>
        </div>
        <div class="breaking-banner-right">
            <button type="button" class="btn-view-new" onclick="scrollToTopFeed()">ดูข่าวใหม่</button>
            <button type="button" class="btn-close-banner" onclick="dismissBanner()" aria-label="ปิดแจ้งเตือน">✕</button>
        </div>
    </div>

    <!-- Feed Header -->
    <div class="feed-section-header">
        <div class="feed-title-wrap">
            <div class="feed-main-title">
                <h2 id="feed-heading-title">ทั้งหมด (All)</h2>
                <span class="badge-new" id="badge-new-count"><?php echo $newCount; ?> ใหม่</span>
            </div>
            <div class="feed-subtitle">อัปเดต เมื่อสักครู่</div>
        </div>

        <div class="feed-header-actions">
            <button type="button" class="btn-header-round" id="view-toggle-btn" title="สลับมุมมอง" onclick="toggleViewCompact()">
                <i class="material-icons" style="font-size: 20px;">view_agenda</i>
            </button>
            <button type="button" class="btn-mark-all-read" onclick="markAllArticlesRead()">
                <i class="material-icons" style="font-size: 16px;">done_all</i>
                <span>อ่านหมด</span>
            </button>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="filter-pills-row">
        <button type="button" class="feed-filter-pill active" data-filter="all" onclick="setFeedFilter('all', this)">
            ทั้งหมด (<span id="count-pill-all"><?php echo $totalArticles; ?></span>)
        </button>
        <button type="button" class="feed-filter-pill" data-filter="new" onclick="setFeedFilter('new', this)">
            🔥 ข่าวใหม่ (<span id="count-pill-new"><?php echo $newCount; ?></span>)
        </button>
        <button type="button" class="feed-filter-pill" data-filter="read" onclick="setFeedFilter('read', this)">
            ✓ อ่านแล้ว (<span id="count-pill-read">0</span>)
        </button>
    </div>

    <!-- Articles List -->
    <div class="article-cards-list" id="articles-list">
        <?php foreach (($articles ?? []) as $index => $art): ?>
            <?php 
                $artId = $art['id'];
                $hasImg = !empty($art['image']);
                $isLatest = ($index < 3 || !empty($art['is_new']));
            ?>
            <article class="article-card" 
                     id="art-<?php echo htmlspecialchars($artId); ?>"
                     data-id="<?php echo htmlspecialchars($artId); ?>"
                     data-source="<?php echo htmlspecialchars($art['source_key']); ?>"
                     data-is-new="<?php echo $art['is_new'] ? '1' : '0'; ?>"
                     data-title="<?php echo htmlspecialchars(strtolower($art['title'])); ?>"
                     data-author="<?php echo htmlspecialchars(strtolower($art['author'])); ?>">

                <div class="article-img-wrap" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                    <?php if ($isLatest): ?>
                        <span class="badge-latest">
                            ⚡ ใหม่ล่าสุด
                        </span>
                    <?php endif; ?>

                    <span class="badge-reading-time">
                        <i class="material-icons" style="font-size: 13px;">schedule</i>
                        <?php echo htmlspecialchars($art['reading_time'] ?? '1 นาที'); ?>
                    </span>

                    <?php if ($hasImg): ?>
                        <img src="<?php echo htmlspecialchars($art['image']); ?>" 
                             alt="<?php echo htmlspecialchars($art['title']); ?>" 
                             class="article-img" 
                             loading="lazy" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="article-img-placeholder" style="display: none;">
                            <i class="material-icons" style="font-size: 38px; color: #3b82f6;">newspaper</i>
                            <span style="font-size: 0.85rem; font-weight: 600;"><?php echo htmlspecialchars($art['source_label']); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="article-img-placeholder">
                            <i class="material-icons" style="font-size: 38px; color: #3b82f6;">newspaper</i>
                            <span style="font-size: 0.85rem; font-weight: 600;"><?php echo htmlspecialchars($art['source_label']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="article-content">
                    <h3 class="article-title" 
                        onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                        <?php echo htmlspecialchars($art['title']); ?>
                    </h3>

                    <p class="article-desc">
                        <?php echo htmlspecialchars($art['description']); ?>
                    </p>

                    <div class="article-footer">
                        <div class="article-meta-left">
                            <span class="meta-source"><?php echo htmlspecialchars($art['source_label']); ?></span>
                            <span class="meta-dot">·</span>
                            <span class="meta-author"><?php echo htmlspecialchars($art['author']); ?></span>
                            <span class="meta-dot">·</span>
                            <span class="meta-time"><?php echo htmlspecialchars($art['time_ago']); ?></span>
                        </div>

                        <button type="button" class="btn-bookmark" 
                                onclick="toggleBookmark('<?php echo htmlspecialchars($artId); ?>', this, event)" 
                                title="บันทึกข่าว">
                            <i class="material-icons" style="font-size: 18px;">bookmark_border</i>
                        </button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Empty Box -->
    <div class="feed-empty-box" id="feed-empty-box">
        <i class="material-icons" style="font-size: 48px; color: #64748b; margin-bottom: 8px;">feed</i>
        <h3 style="color: #ffffff; font-size: 1.1rem; margin-bottom: 6px;">ไม่พบข่าวสารในหมวดหมู่นี้</h3>
        <p style="font-size: 0.88rem; margin-bottom: 14px;">ลองเปลี่ยนแหล่งข่าว หรือกดรีเฟรชเพื่อดึงข้อมูลฟีดใหม่</p>
        <button type="button" class="btn-header-action" onclick="filterBySource('all', document.querySelector('.source-chip[data-source=\'all\']'))">
            แสดงข่าวทั้งหมด
        </button>
    </div>
</div>

<script>
    let activeSourceFilter = 'all';
    let activeFeedMode = 'all';
    let readArticles = JSON.parse(localStorage.getItem('read_articles') || '[]');
    let bookmarkedArticles = JSON.parse(localStorage.getItem('bookmarked_articles') || '[]');

    function dismissBanner() {
        const b = document.getElementById('breaking-banner');
        if (b) b.style.display = 'none';
    }

    function scrollToTopFeed() {
        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    function filterBySource(sourceKey, el) {
        activeSourceFilter = sourceKey;
        document.querySelectorAll('.source-chip').forEach(c => c.classList.remove('active'));
        el?.classList.add('active');

        const titleEl = document.getElementById('feed-heading-title');
        if (titleEl) {
            titleEl.textContent = (sourceKey === 'all') ? 'ทั้งหมด (All)' : (el?.textContent.trim() || sourceKey);
        }

        applyFeedFilters();
    }

    function setFeedFilter(mode, el) {
        activeFeedMode = mode;
        document.querySelectorAll('.feed-filter-pill').forEach(p => p.classList.remove('active'));
        el?.classList.add('active');
        applyFeedFilters();
    }

    function applyFeedFilters() {
        const cards = document.querySelectorAll('.article-card');
        let visibleCount = 0;
        let newCount = 0;

        cards.forEach(card => {
            const cardSource = card.dataset.source;
            const cardId     = card.dataset.id;
            const isNew      = card.dataset.isNew === '1';
            const isRead     = readArticles.includes(cardId);

            const matchSource = (activeSourceFilter === 'all' || cardSource === activeSourceFilter);

            let matchMode = true;
            if (activeFeedMode === 'new') {
                matchMode = isNew;
            } else if (activeFeedMode === 'read') {
                matchMode = isRead;
            }

            if (matchSource && matchMode) {
                card.style.display = 'flex';
                visibleCount++;
                if (isNew) newCount++;
            } else {
                card.style.display = 'none';
            }

            if (isRead) {
                card.classList.add('is-read');
            } else {
                card.classList.remove('is-read');
            }
        });

        const emptyBox = document.getElementById('feed-empty-box');
        if (emptyBox) {
            if (visibleCount === 0) {
                emptyBox.classList.add('show');
            } else {
                emptyBox.classList.remove('show');
            }
        }

        const badgeNew = document.getElementById('badge-new-count');
        if (badgeNew) badgeNew.textContent = newCount + ' ใหม่';

        const readCountEl = document.getElementById('count-pill-read');
        if (readCountEl) readCountEl.textContent = readArticles.length;
    }

    function markAllArticlesRead() {
        const cards = document.querySelectorAll('.article-card');
        cards.forEach(card => {
            const id = card.dataset.id;
            if (id && !readArticles.includes(id)) {
                readArticles.push(id);
            }
        });
        localStorage.setItem('read_articles', JSON.stringify(readArticles));
        applyFeedFilters();
        showAppToast('ทำเครื่องหมายว่าอ่านทั้งหมดแล้ว');
    }

    function toggleBookmark(id, btn, event) {
        event?.stopPropagation();
        const idx = bookmarkedArticles.indexOf(id);
        if (idx >= 0) {
            bookmarkedArticles.splice(idx, 1);
            btn.classList.remove('bookmarked');
            btn.querySelector('i').textContent = 'bookmark_border';
            showAppToast('ลบบุ๊กมาร์กแล้ว');
        } else {
            bookmarkedArticles.push(id);
            btn.classList.add('bookmarked');
            btn.querySelector('i').textContent = 'bookmark';
            showAppToast('บันทึกข่าวลงในบุ๊กมาร์กแล้ว');
        }
        localStorage.setItem('bookmarked_articles', JSON.stringify(bookmarkedArticles));
    }

    function syncBookmarkIcons() {
        document.querySelectorAll('.article-card').forEach(card => {
            const id = card.dataset.id;
            const btn = card.querySelector('.btn-bookmark');
            if (btn && id) {
                if (bookmarkedArticles.includes(id)) {
                    btn.classList.add('bookmarked');
                    btn.querySelector('i').textContent = 'bookmark';
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        syncBookmarkIcons();
        applyFeedFilters();
    });
</script>
