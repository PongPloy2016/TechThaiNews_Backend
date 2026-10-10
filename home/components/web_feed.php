<?php
/**
 * Web Feed Component
 * Main content 2-column layout (Articles Grid + Sidebar) for standard web portal
 */

$remainingArticles = (!empty($articles) && count($articles) > 4) ? array_slice($articles, 4) : $articles;
$totalCount = count($remainingArticles);
?>

<style>
    .web-main-container {
        max-width: var(--web-max-w);
        margin: 0 auto 60px auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 32px;
        align-items: start;
    }

    /* Left Column (Main Feed) */
    .feed-column-main {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Source Filter Chips */
    .source-filter-toolbar {
        background: var(--web-card);
        border: 1px solid var(--web-border);
        border-radius: var(--web-radius);
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .source-chips-scroll {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--web-border) transparent;
        padding-bottom: 4px;
    }

    .web-source-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--web-text-sub);
        background: #0f172a;
        border: 1px solid var(--web-border);
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        user-select: none;
    }

    .web-source-chip:hover {
        background: #1e293b;
        color: #ffffff;
    }

    .web-source-chip.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.4);
    }

    .feed-section-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 4px;
    }

    .feed-heading {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .feed-mode-pills {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .mode-pill {
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--web-text-muted);
        background: #0f172a;
        border: 1px solid var(--web-border);
        cursor: pointer;
        transition: all 0.2s;
    }

    .mode-pill:hover {
        color: #ffffff;
    }

    .mode-pill.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
    }

    /* 2-Column Grid of Article Cards */
    .web-articles-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .web-article-card {
        background: var(--web-card);
        border: 1px solid var(--web-border);
        border-radius: var(--web-radius);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .web-article-card:hover {
        border-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.4);
    }

    .web-article-card.is-read {
        opacity: 0.7;
    }

    .web-card-img-wrap {
        position: relative;
        width: 100%;
        height: 190px;
        background: #0d1322;
        overflow: hidden;
        cursor: pointer;
    }

    .web-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .web-article-card:hover .web-card-img {
        transform: scale(1.04);
    }

    .card-badge-latest {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        z-index: 2;
    }

    .card-badge-readtime {
        position: absolute;
        bottom: 10px;
        right: 12px;
        background: rgba(0, 0, 0, 0.7);
        color: #ffffff;
        font-size: 0.74rem;
        padding: 3px 8px;
        border-radius: 999px;
        backdrop-filter: blur(4px);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        z-index: 2;
    }

    .web-card-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }

    .web-card-title {
        font-size: 1.08rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        cursor: pointer;
        transition: color 0.2s;
    }

    .web-card-title:hover {
        color: #60a5fa;
    }

    .web-card-desc {
        color: var(--web-text-muted);
        font-size: 0.88rem;
        line-height: 1.55;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .web-card-footer {
        padding: 12px 18px 16px 18px;
        border-top: 1px solid var(--web-border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
    }

    .web-card-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .card-source-name {
        color: #60a5fa;
        font-weight: 700;
    }

    .card-author-name {
        color: var(--web-text-sub);
    }

    .card-time-ago {
        color: var(--web-text-muted);
    }

    .btn-card-bookmark {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--web-border);
        color: var(--web-text-muted);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-card-bookmark:hover, .btn-card-bookmark.bookmarked {
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        border-color: #3b82f6;
    }

    /* Right Column (Sidebar) */
    .sidebar-column {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .sidebar-card {
        background: var(--web-card);
        border: 1px solid var(--web-border);
        border-radius: var(--web-radius);
        padding: 22px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    }

    .sidebar-heading {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid var(--web-border-light);
        padding-bottom: 10px;
    }

    .btn-sidebar-add {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        border: 1px solid transparent;
        margin-bottom: 8px;
    }

    .btn-sidebar-add.primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
    }
    .btn-sidebar-add.primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
    }

    .btn-sidebar-add.secondary {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--web-border);
    }
    .btn-sidebar-add.secondary:hover {
        background: #1a233a;
        color: #ffffff;
        border-color: #3b82f6;
    }

    .sidebar-sources-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar-source-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border-radius: 8px;
        background: #0f172a;
        border: 1px solid var(--web-border);
        font-size: 0.85rem;
    }

    @media (max-width: 992px) {
        .web-main-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .web-articles-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="web-main-container">

    <!-- ============================================================== -->
    <!-- LEFT: MAIN FEED                                                -->
    <!-- ============================================================== -->
    <div class="feed-column-main">

        <!-- Source Filter Toolbar -->
        <div class="source-filter-toolbar">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 0.82rem; color: var(--web-text-muted); font-weight: 600;">
                    เลือกสำนักข่าวในหมวดนี้:
                </span>
                <span style="font-size: 0.8rem; color: #3b82f6;">
                    <?php echo count($sourcesInGroup); ?> แหล่งข้อมูล RSS
                </span>
            </div>

            <div class="source-chips-scroll">
                <span class="web-source-chip active" data-source="all" onclick="filterBySource('all', this)">
                    ⚡ ทั้งหมด (All)
                </span>
                <?php foreach (($sourcesInGroup ?? []) as $s): ?>
                    <span class="web-source-chip" 
                          data-source="<?php echo htmlspecialchars($s['key']); ?>" 
                          onclick="filterBySource('<?php echo htmlspecialchars($s['key']); ?>', this)">
                        <?php echo htmlspecialchars($s['label']); ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Bar with heading and filters -->
            <div class="feed-section-bar">
                <div class="feed-heading">
                    <span id="web-feed-title">ทั้งหมด (All)</span>
                    <span style="background: #2563eb; color: #fff; font-size: 0.75rem; padding: 2px 8px; border-radius: 999px;" id="web-feed-count">
                        <?php echo $totalCount; ?> ข่าว
                    </span>
                </div>

                <div class="feed-mode-pills">
                    <button type="button" class="mode-pill active" data-filter="all" onclick="setFeedFilter('all', this)">
                        ทั้งหมด
                    </button>
                    <button type="button" class="mode-pill" data-filter="new" onclick="setFeedFilter('new', this)">
                        🔥 ข่าวใหม่
                    </button>
                    <button type="button" class="mode-pill" data-filter="read" onclick="setFeedFilter('read', this)">
                        ✓ อ่านแล้ว
                    </button>
                    <button type="button" class="mode-pill" onclick="filterBookmarkedOnly()">
                        🔖 บุ๊กมาร์ก
                    </button>
                </div>
            </div>
        </div>

        <!-- 2-Column Articles Grid -->
        <div class="web-articles-grid" id="web-articles-grid">
            <?php foreach ($remainingArticles as $index => $art): ?>
                <?php 
                    $artId = $art['id'];
                    $hasImg = !empty($art['image']);
                    $isLatest = ($index < 2 || !empty($art['is_new']));
                ?>
                <article class="web-article-card" 
                         id="art-<?php echo htmlspecialchars($artId); ?>"
                         data-id="<?php echo htmlspecialchars($artId); ?>"
                         data-source="<?php echo htmlspecialchars($art['source_key']); ?>"
                         data-is-new="<?php echo $art['is_new'] ? '1' : '0'; ?>"
                         data-title="<?php echo htmlspecialchars(strtolower($art['title'])); ?>"
                         data-author="<?php echo htmlspecialchars(strtolower($art['author'])); ?>">

                    <div class="web-card-img-wrap" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                        <?php if ($isLatest): ?>
                            <span class="card-badge-latest">
                                ⚡ ใหม่ล่าสุด
                            </span>
                        <?php endif; ?>

                        <span class="card-badge-readtime">
                            <i class="material-icons" style="font-size: 13px;">schedule</i>
                            <?php echo htmlspecialchars($art['reading_time'] ?? '1 นาที'); ?>
                        </span>

                        <?php if ($hasImg): ?>
                            <img src="<?php echo htmlspecialchars($art['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($art['title']); ?>" 
                                 class="web-card-img" 
                                 loading="lazy" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="article-img-placeholder" style="display: none; height: 100%;">
                                <i class="material-icons" style="font-size: 38px; color: #3b82f6;">feed</i>
                                <span style="font-size: 0.82rem; font-weight: 600;"><?php echo htmlspecialchars($art['source_label']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="article-img-placeholder" style="height: 100%;">
                                <i class="material-icons" style="font-size: 38px; color: #3b82f6;">feed</i>
                                <span style="font-size: 0.82rem; font-weight: 600;"><?php echo htmlspecialchars($art['source_label']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="web-card-body">
                        <h3 class="web-card-title" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($art['title'])); ?>', '<?php echo htmlspecialchars(addslashes($art['link'])); ?>', '<?php echo htmlspecialchars(addslashes($art['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($art['author'])); ?>', '<?php echo htmlspecialchars(addslashes($art['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($art['image'])); ?>', '<?php echo htmlspecialchars(addslashes($art['description'])); ?>', '<?php echo htmlspecialchars($artId); ?>')">
                            <?php echo htmlspecialchars($art['title']); ?>
                        </h3>

                        <p class="web-card-desc">
                            <?php echo htmlspecialchars($art['description']); ?>
                        </p>
                    </div>

                    <div class="web-card-footer">
                        <div class="web-card-meta">
                            <span class="card-source-name"><?php echo htmlspecialchars($art['source_label']); ?></span>
                            <span style="color: #475569;">·</span>
                            <span class="card-author-name"><?php echo htmlspecialchars($art['author']); ?></span>
                            <span style="color: #475569;">·</span>
                            <span class="card-time-ago"><?php echo htmlspecialchars($art['time_ago']); ?></span>
                        </div>

                        <button type="button" class="btn-card-bookmark" 
                                onclick="toggleBookmark('<?php echo htmlspecialchars($artId); ?>', this, event)" 
                                title="บันทึกข่าว">
                            <i class="material-icons" style="font-size: 17px;">bookmark_border</i>
                        </button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="feed-empty-box" id="web-feed-empty" style="display: none; padding: 60px; text-align: center; background: var(--web-card); border-radius: var(--web-radius); border: 1px dashed var(--web-border);">
            <i class="material-icons" style="font-size: 48px; color: #64748b; margin-bottom: 8px;">search_off</i>
            <h3 style="color: #ffffff; font-size: 1.15rem; margin-bottom: 6px;">ไม่พบข่าวสารในหมวดหมู่นี้</h3>
            <p style="color: var(--web-text-muted); font-size: 0.9rem; margin-bottom: 16px;">ลองเปลี่ยนตัวกรอง หรือค้นหาด้วยคำอื่น</p>
            <button type="button" class="btn-web-action primary" onclick="filterBySource('all', document.querySelector('.web-source-chip[data-source=\'all\']'))">
                แสดงข่าวทั้งหมด
            </button>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- RIGHT: SIDEBAR                                                 -->
    <!-- ============================================================== -->
    <aside class="sidebar-column">

        <!-- Sidebar Card 1: Add Feeds (Supabase schema.sql) -->
        <div class="sidebar-card">
            <h4 class="sidebar-heading">
                <i class="material-icons" style="color: #3b82f6;">cloud_upload</i>
                <span>จัดการฟีดข่าว (Supabase)</span>
            </h4>
            <p style="font-size: 0.85rem; color: var(--web-text-muted); line-height: 1.6; margin-bottom: 16px;">
                ระบบเชื่อมต่อตาราง <code>public.feed_sources</code> และ <code>public.feed_groups</code> ใน Supabase สามารถเพิ่มฟีดได้ทันที:
            </p>

            <button type="button" class="btn-sidebar-add primary" onclick="openAddFeedModal(); switchModalAddTab('source');">
                <i class="material-icons" style="font-size: 18px;">add_circle</i>
                <span>เพิ่มแหล่งข่าว RSS (Feed Source)</span>
            </button>

            <button type="button" class="btn-sidebar-add secondary" onclick="openAddFeedModal(); switchModalAddTab('group');">
                <i class="material-icons" style="font-size: 18px;">create_new_folder</i>
                <span>เพิ่มกลุ่มฟีดใหม่ (Feed Group)</span>
            </button>

            <div style="margin-top: 12px; font-size: 0.78rem; color: #34d399; display: flex; align-items: center; gap: 6px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                <span>Supabase Cloud Connected (81 แหล่งข่าว)</span>
            </div>
        </div>

        <!-- Sidebar Card 2: Active Sources in this Category -->
        <div class="sidebar-card">
            <h4 class="sidebar-heading">
                <i class="material-icons" style="color: #60a5fa;">rss_feed</i>
                <span>สำนักข่าวในหมวดนี้</span>
            </h4>

            <div class="sidebar-sources-list">
                <?php foreach (($sourcesInGroup ?? []) as $src): ?>
                    <div class="sidebar-source-row">
                        <span style="color: #ffffff; font-weight: 500;">
                            <?php echo htmlspecialchars($src['label']); ?>
                        </span>
                        <div style="display: flex; gap: 4px;">
                            <?php if (!empty($src['homepage'])): ?>
                                <a href="<?php echo htmlspecialchars($src['homepage']); ?>" target="_blank" rel="noopener noreferrer" style="color: #60a5fa; padding: 2px 6px;" title="ไปที่เว็บไซต์">
                                    <i class="material-icons" style="font-size: 16px;">language</i>
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo htmlspecialchars($src['url']); ?>" target="_blank" rel="noopener noreferrer" style="color: #94a3b8; padding: 2px 6px;" title="ดูไฟล์ XML">
                                <i class="material-icons" style="font-size: 16px;">open_in_new</i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sidebar Card 3: Google News & Publisher Info -->
        <div class="sidebar-card">
            <h4 class="sidebar-heading">
                <i class="material-icons" style="color: #a855f7;">verified</i>
                <span>ข้อมูลผู้เผยแพร่ (Publisher)</span>
            </h4>
            <div style="font-size: 0.85rem; color: var(--web-text-sub); line-height: 1.7;">
                <div><strong>กองบรรณาธิการ:</strong> ทีมงานข่าวและเทคโนโลยี</div>
                <div><strong>อีเมล:</strong> <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: #60a5fa;"><?php echo CONTACT_EMAIL; ?></a></div>
                <div><strong>โทรศัพท์:</strong> <a href="tel:<?php echo CONTACT_PHONE; ?>" style="color: #60a5fa;"><?php echo CONTACT_PHONE_DISPLAY; ?></a></div>
            </div>
            <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--web-border-light); font-size: 0.8rem;">
                <a href="contact.php" style="color: #60a5fa; text-decoration: none;">ดูหน้าติดต่อเราฉบับเต็ม &rarr;</a>
            </div>
        </div>

    </aside>

</div>

<script>
    function handleLiveSearch(query) {
        query = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.web-article-card');
        let count = 0;

        cards.forEach(card => {
            const title = card.dataset.title || '';
            const author = card.dataset.author || '';
            if (!query || title.includes(query) || author.includes(query)) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyBox = document.getElementById('web-feed-empty');
        if (emptyBox) {
            emptyBox.style.display = (count === 0) ? 'block' : 'none';
        }

        const countBadge = document.getElementById('web-feed-count');
        if (countBadge) countBadge.textContent = count + ' ข่าว';
    }
</script>
