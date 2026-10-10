<?php
/**
 * Featured Hero Component
 * Magazine / Portal top highlighted stories grid
 */

$featured = !empty($articles) ? $articles[0] : null;
$sideStories = (!empty($articles) && count($articles) > 1) ? array_slice($articles, 1, 3) : [];
?>

<style>
    .featured-hero-section {
        max-width: var(--web-max-w);
        margin: 24px auto 32px auto;
        padding: 0 20px;
    }

    /* Breaking Ticker */
    .breaking-ticker {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 10px 18px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.88rem;
    }

    .ticker-badge {
        background: #dc2626;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .ticker-text {
        color: #fca5a5;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ticker-text a {
        color: #fef2f2;
        font-weight: 600;
        text-decoration: none;
    }

    .ticker-text a:hover {
        text-decoration: underline;
    }

    /* Hero Grid Layout (1 Big Story + 3 Stacked Stories) */
    .hero-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 24px;
    }

    /* Main Big Hero Card */
    .hero-main-card {
        background: var(--web-card);
        border: 1px solid var(--web-border);
        border-radius: var(--web-radius);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.25s, border-color 0.25s, box-shadow 0.25s;
        cursor: pointer;
        position: relative;
    }

    .hero-main-card:hover {
        border-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
    }

    .hero-main-img-wrap {
        width: 100%;
        height: 330px;
        position: relative;
        overflow: hidden;
        background: #0d1322;
    }

    .hero-main-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .hero-main-card:hover .hero-main-img {
        transform: scale(1.04);
    }

    .hero-badge-float {
        position: absolute;
        top: 16px;
        left: 16px;
        background: #ef4444;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .hero-main-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }

    .hero-main-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.4;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hero-main-desc {
        color: var(--web-text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hero-meta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: var(--web-text-muted);
        padding-top: 14px;
        border-top: 1px solid var(--web-border-light);
    }

    /* Side Stories Stack */
    .hero-side-stack {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .hero-side-card {
        background: var(--web-card);
        border: 1px solid var(--web-border);
        border-radius: var(--web-radius);
        padding: 16px;
        display: flex;
        gap: 16px;
        transition: transform 0.2s, border-color 0.2s;
        cursor: pointer;
    }

    .hero-side-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    .hero-side-img-wrap {
        width: 110px;
        height: 90px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: #0d1322;
        position: relative;
    }

    .hero-side-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-side-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
    }

    .hero-side-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hero-side-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: var(--web-text-muted);
        margin-top: 6px;
    }

    @media (max-width: 900px) {
        .hero-grid {
            grid-template-columns: 1fr;
        }
        .hero-main-img-wrap {
            height: 240px;
        }
    }
</style>

<section class="featured-hero-section">
    <!-- Breaking News Ticker -->
    <?php if ($featured): ?>
        <div class="breaking-ticker">
            <span class="ticker-badge">⚡ ข่าวด่วน</span>
            <div class="ticker-text">
                <span>อัปเดตล่าสุด: </span>
                <a href="javascript:void(0)" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($featured['title'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['link'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['author'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['image'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['description'])); ?>', '<?php echo htmlspecialchars($featured['id']); ?>')">
                    <?php echo htmlspecialchars($featured['title']); ?>
                </a>
                <span style="color: var(--web-text-muted); margin-left: 8px;">(<?php echo htmlspecialchars($featured['source_label']); ?> · <?php echo htmlspecialchars($featured['time_ago']); ?>)</span>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($featured): ?>
        <div class="hero-grid">
            <!-- 1. Big Hero Card -->
            <div class="hero-main-card" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($featured['title'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['link'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['author'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['image'])); ?>', '<?php echo htmlspecialchars(addslashes($featured['description'])); ?>', '<?php echo htmlspecialchars($featured['id']); ?>')">
                <div class="hero-main-img-wrap">
                    <span class="hero-badge-float">
                        <i class="material-icons" style="font-size: 14px;">bolt</i> เรื่องเด่นล่าสุด
                    </span>
                    <?php if (!empty($featured['image'])): ?>
                        <img src="<?php echo htmlspecialchars($featured['image']); ?>" 
                             alt="<?php echo htmlspecialchars($featured['title']); ?>" 
                             class="hero-main-img" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="article-img-placeholder" style="display: none; height: 100%;">
                            <i class="material-icons" style="font-size: 48px; color: #3b82f6;">feed</i>
                            <span><?php echo htmlspecialchars($featured['source_label']); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="article-img-placeholder" style="height: 100%;">
                            <i class="material-icons" style="font-size: 48px; color: #3b82f6;">feed</i>
                            <span><?php echo htmlspecialchars($featured['source_label']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="hero-main-body">
                    <div>
                        <h2 class="hero-main-title"><?php echo htmlspecialchars($featured['title']); ?></h2>
                        <p class="hero-main-desc"><?php echo htmlspecialchars($featured['description']); ?></p>
                    </div>

                    <div class="hero-meta-row">
                        <span style="color: #60a5fa; font-weight: 700;"><?php echo htmlspecialchars($featured['source_label']); ?></span>
                        <span>·</span>
                        <span style="color: #cbd5e1;"><?php echo htmlspecialchars($featured['author']); ?></span>
                        <span>·</span>
                        <span><?php echo htmlspecialchars($featured['time_ago']); ?></span>
                        <span style="margin-left: auto; color: #38bdf8; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="material-icons" style="font-size: 15px;">schedule</i> <?php echo htmlspecialchars($featured['reading_time']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Side Stories Stack -->
            <div class="hero-side-stack">
                <div style="font-size: 0.95rem; font-weight: 700; color: #ffffff; display: flex; align-items: center; gap: 8px;">
                    <i class="material-icons" style="color: #3b82f6; font-size: 18px;">trending_up</i>
                    <span>ข่าวมาแรงในหมวดนี้</span>
                </div>

                <?php foreach ($sideStories as $side): ?>
                    <div class="hero-side-card" onclick="openArticleModal('<?php echo htmlspecialchars(addslashes($side['title'])); ?>', '<?php echo htmlspecialchars(addslashes($side['link'])); ?>', '<?php echo htmlspecialchars(addslashes($side['source_label'])); ?>', '<?php echo htmlspecialchars(addslashes($side['author'])); ?>', '<?php echo htmlspecialchars(addslashes($side['time_ago'])); ?>', '<?php echo htmlspecialchars(addslashes($side['image'])); ?>', '<?php echo htmlspecialchars(addslashes($side['description'])); ?>', '<?php echo htmlspecialchars($side['id']); ?>')">
                        <div class="hero-side-img-wrap">
                            <?php if (!empty($side['image'])): ?>
                                <img src="<?php echo htmlspecialchars($side['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($side['title']); ?>" 
                                     class="hero-side-img" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="article-img-placeholder" style="display: none; height: 100%;">
                                    <i class="material-icons" style="font-size: 24px; color: #3b82f6;">feed</i>
                                </div>
                            <?php else: ?>
                                <div class="article-img-placeholder" style="height: 100%;">
                                    <i class="material-icons" style="font-size: 24px; color: #3b82f6;">feed</i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="hero-side-body">
                            <h3 class="hero-side-title"><?php echo htmlspecialchars($side['title']); ?></h3>
                            <div class="hero-side-meta">
                                <span style="color: #60a5fa; font-weight: 600;"><?php echo htmlspecialchars($side['source_label']); ?></span>
                                <span>·</span>
                                <span><?php echo htmlspecialchars($side['time_ago']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
