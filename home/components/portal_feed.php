<?php
/**
 * Portal Feed Component
 * Level 2 source chips, breaking banner, filter pills, and 3-column responsive card grid
 */

$totalArticles = count($articles ?? []);
$newCount = 0;
foreach (($articles ?? []) as $a) {
    if (!empty($a['is_new'])) $newCount++;
}
if ($newCount === 0) $newCount = min(13, $totalArticles);
?>

<style>
    .portal-feed-wrap {
        padding: 20px 0 60px 0;
    }

    /* Level 2: Sub-Source Chips */
    .source-chips-row {
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
        margin-bottom: 20px;
        padding-bottom: 4px;
    }

    .source-chips-row::-webkit-scrollbar {
        display: none;
    }

    .source-chips-inner {
        display: inline-flex;
        gap: 8px;
    }

    .sub-source-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-sub);
        background: var(--bg-chip);
        border: 1px solid var(--border-card);
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        white-space: nowrap;
    }

    .sub-source-chip:hover {
        background: #1c2742;
        color: #ffffff;
    }

    .sub-source-chip.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.4);
    }

    /* Red Notification Banner */
    .breaking-alert-banner {
        background: var(--accent-red-bg);
        border: 1px solid var(--accent-red-border);
        border-radius: 999px;
        padding: 9px 18px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.15);
    }

    .breaking-alert-left {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #fca5a5;
        font-weight: 500;
    }

    .breaking-flash-icon {
        color: var(--accent-red);
        font-size: 1.1rem;
        animation: pulseIcon 1.5s infinite;
    }

    .breaking-alert-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-see-breaking {
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

    .btn-see-breaking:hover {
        background: #b91c1c;
        transform: scale(1.03);
    }

    .btn-close-alert {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 15px;
        cursor: pointer;
        padding: 4px;
    }

    .btn-close-alert:hover {
        color: #ffffff;
    }

    /* Section Subheader */
    .section-meta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .section-title-box {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .section-main-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-main-heading h2 {
        font-size: 1.45rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .badge-red-count {
        background: #ef4444;
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 999px;
    }

    .section-update-time {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .section-action-btns {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-round-tool {
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

    .btn-round-tool:hover {
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
    .filter-pills-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .portal-filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-muted);
        background: #111827;
        border: 1px solid var(--border-card);
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .portal-filter-pill:hover {
        color: #ffffff;
        background: #172033;
    }

    .portal-filter-pill.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
    }

    /* Responsive Grid of Cards (3 cols on desktop, 2 on tablet, 1 on mobile) */
    .cards-grid-responsive {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .cards-grid-responsive.view-agenda {
        grid-template-columns: repeat(2, 1fr);
    }

    /* Single News Card */
    .portal-card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-left: 3.5px solid var(--accent-red); /* EXACT SIGNATURE RED LEFT BORDER */
        border-radius: var(--radius-card);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s, border-color 0.2s;
        position: relative;
    }

    .portal-card:hover {
        transform: translateY(-3px);
        border-color: #334155;
        border-left-color: var(--accent-red);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.45);
    }

    .portal-card.is-read {
        opacity: 0.72;
    }

    /* Image Wrapper */
    .portal-card-thumb {
        position: relative;
        width: 100%;
        height: 205px;
        background: #0d1322;
        overflow: hidden;
        cursor: pointer;
    }

    .portal-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .portal-card:hover .portal-card-img {
        transform: scale(1.04);
    }

    /* Sleek Tech Gradient Placeholder (Zero Glitches!) */
    .card-placeholder-mesh {
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 75% 25%, #1e3a8a 0%, #0f172a 75%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .card-placeholder-mesh::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 16px 16px;
        opacity: 0.6;
    }

    .placeholder-center-tag {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: #93c5fd;
        font-weight: 600;
        font-size: 0.88rem;
    }

    .placeholder-center-tag i {
        font-size: 34px;
        color: #3b82f6;
    }

    /* Badges on Thumbnail */
    .badge-latest-pill {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5);
        z-index: 2;
    }

    .badge-read-time {
        position: absolute;
        bottom: 12px;
        right: 14px;
        background: rgba(0, 0, 0, 0.7);
        color: #ffffff;
        font-size: 0.75rem;
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

    /* Card Content */
    .portal-card-body {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }

    .portal-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-white);
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

    .portal-card-title:hover {
        color: #60a5fa;
    }

    .portal-card-desc {
        font-size: 0.88rem;
        color: var(--text-muted);
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Card Footer */
    .portal-card-footer {
        padding: 12px 20px 16px 20px;
        border-top: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.83rem;
    }

    .card-footer-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .meta-source-blue {
        color: #60a5fa;
        font-weight: 700;
    }

    .meta-sep {
        color: #475569;
    }

    .meta-author-gray {
        color: var(--text-sub);
        font-weight: 500;
    }

    .meta-time-muted {
        color: var(--text-muted);
    }

    .btn-bookmark-card {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-card);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-bookmark-card:hover {
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        border-color: #3b82f6;
    }

    .btn-bookmark-card.bookmarked {
        background: rgba(37, 99, 235, 0.25);
        color: #60a5fa;
        border-color: #2563eb;
    }

    /* Empty state */
    .portal-empty-box {
        text-align: center;
        padding: 80px 20px;
        background: var(--bg-card);
        border: 1px dashed var(--border-card);
        border-radius: var(--radius-card);
        color: var(--text-muted);
        display: none;
    }

    @media (max-width: 1100px) {
        .cards-grid-responsive {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 680px) {
        .cards-grid-responsive {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="portal-container portal-feed-wrap">

    <!-- 1. Level 2: Sub-Source Filter Chips -->
    <div class="source-chips-row">
        <div class="source-chips-inner">
            <span class="sub-source-chip active" data-source="all" onclick="filterBySource('all', this)">
                ⚡ ทั้งหมด (All)
            </span>
            <?php foreach (($sourcesInGroup ?? []) as $s): ?>
                <span class="sub-source-chip" 
                      data-source="<?php echo htmlspecialchars($s['key']); ?>" 
                      onclick="filterBySource('<?php echo htmlspecialchars($s['key']); ?>', this)">
                    <?php echo htmlspecialchars($s['label']); ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 2. Red Notification Banner -->
    <div class="breaking-alert-banner" id="breaking-banner">
        <div class="breaking-alert-left">
            <span class="breaking-flash-icon">⚡</span>
            <span>พบข่าวใหม่มาเพิ่ม <strong style="color: #f87171;" id="alert-total-count"><?php echo $totalArticles; ?></strong> ข่าว!</span>
        </div>
        <div class="breaking-alert-right">
            <button type="button" class="btn-see-breaking" onclick="scrollToCardsTop()">ดูข่าวใหม่</button>
            <button type="button" class="btn-close-alert" onclick="dismissBannerAlert()" aria-label="ปิดแจ้งเตือน">✕</button>
        </div>
    </div>

    <!-- 3. Section Subheader -->
    <div class="section-meta-header">
        <div class="section-title-box">
            <div class="section-main-heading">
                <h2 id="section-heading-text">ทั้งหมด (All)</h2>
                <span class="badge-red-count" id="badge-red-count"><?php echo $newCount; ?> ใหม่</span>
            </div>
            <div class="section-update-time">อัปเดต เมื่อสักครู่</div>
        </div>

        <div class="section-action-btns">
            <button type="button" class="btn-round-tool" id="btn-toggle-grid" title="สลับมุมมอง" onclick="toggleCardGrid()">
                <i class="material-icons" style="font-size: 20px;">view_module</i>
            </button>
            <button type="button" class="btn-mark-all-read" onclick="markAllAsRead()">
                <i class="material-icons" style="font-size: 16px;">done_all</i>
                <span>อ่านหมด</span>
            </button>
        </div>
    </div>

    <!-- 4. Filter Pills Bar -->
    <div class="filter-pills-bar">
        <button type="button" class="portal-filter-pill active" data-filter="all" onclick="applyModeFilter('all', this)">
            ทั้งหมด (<span id="pill-count-all"><?php echo $totalArticles; ?></span>)
        </button>
        <button type="button" class="portal-filter-pill" data-filter="new" onclick="applyModeFilter('new', this)">
            🔥 ข่าวใหม่ (<span id="pill-count-new"><?php echo $newCount; ?></span>)
        </button>
        <button type="button" class="portal-filter-pill" data-filter="read" onclick="applyModeFilter('read', this)">
            ✓ อ่านแล้ว (<span id="pill-count-read">0</span>)
        </button>
        <button type="button" class="portal-filter-pill" data-filter="bookmark" onclick="applyModeFilter('bookmark', this)">
            🔖 บุ๊กมาร์ก (<span id="pill-count-bmark">0</span>)
        </button>
    </div>

    <!-- 5. 3-Column Responsive News Cards Grid -->
    <div class="cards-grid-responsive" id="cards-grid">
        <?php foreach (($articles ?? []) as $index => $art): ?>
            <?php 
                $artId = $art['id'];
                $hasImg = !empty($art['image']);
                $isLatest = ($index < 3 || !empty($art['is_new']));
            ?>
            <article class="portal-card" 
                     id="card-<?php echo htmlspecialchars($artId); ?>"
                     data-id="<?php echo htmlspecialchars($artId); ?>"
                     data-source="<?php echo htmlspecialchars($art['source_key']); ?>"
                     data-is-new="<?php echo $art['is_new'] ? '1' : '0'; ?>"
                     data-title="<?php echo htmlspecialchars(strtolower($art['title'])); ?>"
                     data-author="<?php echo htmlspecialchars(strtolower($art['author'])); ?>">

                <!-- Thumbnail -->
                <div class="portal-card-thumb" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                    <?php if ($isLatest): ?>
                        <span class="badge-latest-pill">
                            ⚡ ใหม่ล่าสุด
                        </span>
                    <?php endif; ?>

                    <span class="badge-read-time">
                        <i class="material-icons" style="font-size: 13px;">schedule</i>
                        <?php echo htmlspecialchars($art['reading_time'] ?? '1 นาที'); ?>
                    </span>

                    <?php if ($hasImg): ?>
                        <img src="<?php echo htmlspecialchars($art['image']); ?>" 
                             alt="<?php echo htmlspecialchars($art['title']); ?>" 
                             class="portal-card-img" 
                             loading="lazy" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="card-placeholder-mesh" style="display: none;">
                            <div class="placeholder-center-tag">
                                <i class="material-icons">newspaper</i>
                                <span><?php echo htmlspecialchars($art['source_label']); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card-placeholder-mesh">
                            <div class="placeholder-center-tag">
                                <i class="material-icons">feed</i>
                                <span><?php echo htmlspecialchars($art['source_label']); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Body -->
                <div class="portal-card-body">
                    <h3 class="portal-card-title" 
                        onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                        <?php echo htmlspecialchars($art['title']); ?>
                    </h3>

                    <p class="portal-card-desc">
                        <?php echo htmlspecialchars($art['description']); ?>
                    </p>
                </div>

                <!-- Footer (Source · Author · Time | Bookmark) -->
                <div class="portal-card-footer">
                    <div class="card-footer-meta">
                        <span class="meta-source-blue"><?php echo htmlspecialchars($art['source_label']); ?></span>
                        <span class="meta-sep">·</span>
                        <span class="meta-author-gray"><?php echo htmlspecialchars($art['author']); ?></span>
                        <span class="meta-sep">·</span>
                        <span class="meta-time-muted"><?php echo htmlspecialchars($art['time_ago']); ?></span>
                    </div>

                    <button type="button" class="btn-bookmark-card" 
                            onclick="toggleBookmark('<?php echo htmlspecialchars($artId); ?>', this, event)" 
                            title="บันทึกข่าว">
                        <i class="material-icons" style="font-size: 18px;">bookmark_border</i>
                    </button>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div class="portal-empty-box" id="portal-empty-box">
        <i class="material-icons" style="font-size: 48px; color: #64748b; margin-bottom: 8px;">feed</i>
        <h3 style="color: #ffffff; font-size: 1.15rem; margin-bottom: 6px;">ไม่พบข่าวสารในหมวดหมู่นี้</h3>
        <p style="font-size: 0.9rem; margin-bottom: 16px;">ลองเปลี่ยนตัวกรองแหล่งข่าว หรือกดรีเฟรชฟีดใหม่</p>
        <button type="button" class="btn-see-breaking" style="padding: 8px 20px;" onclick="filterBySource('all', document.querySelector('.sub-source-chip[data-source=\'all\']'))">
            แสดงข่าวทั้งหมด
        </button>
    </div>

</section>

<script>
    let currentSource = 'all';
    let currentMode = 'all';
    let readList = JSON.parse(localStorage.getItem('read_articles') || '[]');
    let bookmarkList = JSON.parse(localStorage.getItem('bookmarked_articles') || '[]');

    function dismissBannerAlert() {
        const b = document.getElementById('breaking-banner');
        if (b) b.style.display = 'none';
    }

    function scrollToCardsTop() {
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }

    function filterBySource(srcKey, el) {
        currentSource = srcKey;
        document.querySelectorAll('.sub-source-chip').forEach(c => c.classList.remove('active'));
        el?.classList.add('active');

        const titleText = document.getElementById('section-heading-text');
        if (titleText) {
            titleText.textContent = (srcKey === 'all') ? 'ทั้งหมด (All)' : (el?.textContent.trim() || srcKey);
        }

        applyCombinedFilters();
    }

    function applyModeFilter(mode, el) {
        currentMode = mode;
        document.querySelectorAll('.portal-filter-pill').forEach(p => p.classList.remove('active'));
        el?.classList.add('active');
        applyCombinedFilters();
    }

    function handleFilterSearch(query) {
        query = (query || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.portal-card');
        let count = 0;

        cards.forEach(card => {
            const title = card.dataset.title || '';
            const author = card.dataset.author || '';
            const match = (!query || title.includes(query) || author.includes(query));
            if (match) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        const empty = document.getElementById('portal-empty-box');
        if (empty) empty.style.display = (count === 0) ? 'block' : 'none';
    }

    function applyCombinedFilters() {
        const cards = document.querySelectorAll('.portal-card');
        let visibleCount = 0;
        let newCount = 0;

        cards.forEach(card => {
            const cardSource = card.dataset.source;
            const cardId     = card.dataset.id;
            const isNew      = card.dataset.isNew === '1';
            const isRead     = readList.includes(cardId);
            const isBookmark = bookmarkList.includes(cardId);

            const matchSource = (currentSource === 'all' || cardSource === currentSource);

            let matchMode = true;
            if (currentMode === 'new') {
                matchMode = isNew;
            } else if (currentMode === 'read') {
                matchMode = isRead;
            } else if (currentMode === 'bookmark') {
                matchMode = isBookmark;
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

        const empty = document.getElementById('portal-empty-box');
        if (empty) empty.style.display = (visibleCount === 0) ? 'block' : 'none';

        const redBadge = document.getElementById('badge-red-count');
        if (redBadge) redBadge.textContent = newCount + ' ใหม่';

        const readCountEl = document.getElementById('pill-count-read');
        if (readCountEl) readCountEl.textContent = readList.length;

        const bmarkCountEl = document.getElementById('pill-count-bmark');
        if (bmarkCountEl) bmarkCountEl.textContent = bookmarkList.length;
    }

    function markAllAsRead() {
        const cards = document.querySelectorAll('.portal-card');
        cards.forEach(card => {
            const id = card.dataset.id;
            if (id && !readList.includes(id)) {
                readList.push(id);
            }
        });
        localStorage.setItem('read_articles', JSON.stringify(readList));
        applyCombinedFilters();
        showAppToast('ทำเครื่องหมายว่าอ่านทั้งหมดแล้ว');
    }

    function toggleBookmark(id, btn, event) {
        event?.stopPropagation();
        const idx = bookmarkList.indexOf(id);
        if (idx >= 0) {
            bookmarkList.splice(idx, 1);
            btn.classList.remove('bookmarked');
            btn.querySelector('i').textContent = 'bookmark_border';
            showAppToast('ลบบุ๊กมาร์กแล้ว');
        } else {
            bookmarkList.push(id);
            btn.classList.add('bookmarked');
            btn.querySelector('i').textContent = 'bookmark';
            showAppToast('บันทึกข่าวลงในบุ๊กมาร์กแล้ว');
        }
        localStorage.setItem('bookmarked_articles', JSON.stringify(bookmarkList));
        const bmarkCountEl = document.getElementById('pill-count-bmark');
        if (bmarkCountEl) bmarkCountEl.textContent = bookmarkList.length;
    }

    function toggleCardGrid() {
        const grid = document.getElementById('cards-grid');
        grid?.classList.toggle('view-agenda');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Sync bookmark icons
        document.querySelectorAll('.portal-card').forEach(card => {
            const id = card.dataset.id;
            const btn = card.querySelector('.btn-bookmark-card');
            if (btn && id && bookmarkList.includes(id)) {
                btn.classList.add('bookmarked');
                btn.querySelector('i').textContent = 'bookmark';
            }
        });
        applyCombinedFilters();
    });
</script>
