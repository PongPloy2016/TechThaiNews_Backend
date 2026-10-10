<?php
/**
 * Asymmetrical Editorial Hero Grid Component
 * 1 Dominant Lead Story + 3 Secondary Magazine Features
 */

if (!function_exists('getVarientThumbnail')) {
    function getVarientThumbnail($article, $fallbackIndex = 0) {
        return RssFeedService::getThumbnail($article, $fallbackIndex);
    }
}

$editorialColors = ['#0ea5e9', '#10b981', '#f43f5e', '#8b5cf6', '#f59e0b', '#06b6d4'];

$featuredList = array_slice($articles ?? [], 0, 4);
$leadPost = $featuredList[0] ?? null;
$subPost1 = $featuredList[1] ?? null;
$subPost2 = $featuredList[2] ?? null;
$subPost3 = $featuredList[3] ?? null;
?>

<div class="container-xl">
    <?php if ($leadPost): 
        $leadAuthor = $leadPost['author'] ?: $leadPost['source_label'];
        $leadInitial = mb_substr($leadAuthor, 0, 1, 'UTF-8');
    ?>
    <section class="hero-magazine-grid">
        <!-- 1. Lead Story (Dominant Left 60%) -->
        <div class="grid-area-lead">
            <div class="magazine-hero-card">
                <a href="news_detail.php?id=<?php echo urlencode($leadPost['id']); ?>&group=<?php echo urlencode($leadPost['group_key'] ?? $currentGroupKey); ?>" class="hero-card-overlay-link" aria-label="<?php echo htmlspecialchars($leadPost['title']); ?>"></a>
                <img src="<?php echo htmlspecialchars(getVarientThumbnail($leadPost, 0)); ?>" alt="<?php echo htmlspecialchars($leadPost['title']); ?>" class="hero-card-img" loading="eager">
                <div class="hero-card-content">
                    <?php 
                    $leadCat = !empty($leadPost['group_label']) ? $leadPost['group_label'] : ($currentGroupKey === 'all' ? 'ข่าวเด่น' : 'LEAD FEATURE');
                    $cleanLeadCat = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $leadCat));
                    ?>
                    <span class="editorial-kicker" style="background-color: var(--accent-coral);">
                        <?php echo htmlspecialchars($cleanLeadCat ?: 'ข่าวเด่น'); ?> &bull; <?php echo htmlspecialchars($leadPost['source_label']); ?>
                    </span>
                    <h2 class="hero-lead-title">
                        <a href="news_detail.php?id=<?php echo urlencode($leadPost['id']); ?>&group=<?php echo urlencode($leadPost['group_key'] ?? $currentGroupKey); ?>" class="text-white text-decoration-none">
                            <?php echo htmlspecialchars($leadPost['title']); ?>
                        </a>
                    </h2>
                    <div class="hero-card-meta">
                        <div class="d-flex align-items-center gap-2">
                            <span class="author-avatar-sm"><?php echo htmlspecialchars($leadInitial); ?></span>
                            <span class="text-white fw-medium"><?php echo htmlspecialchars($leadAuthor); ?></span>
                        </div>
                        <span class="opacity-50">&bull;</span>
                        <span><?php echo htmlspecialchars($leadPost['time_ago']); ?></span>
                        <span class="opacity-50">&bull;</span>
                        <span><?php echo htmlspecialchars($leadPost['reading_time'] ?? 'อ่าน 2 นาที'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Sub Story Top (Right Top) -->
        <?php if ($subPost1): 
            $sub1Author = $subPost1['author'] ?: $subPost1['source_label'];
            $sub1Cat = !empty($subPost1['group_label']) ? $subPost1['group_label'] : '';
            $cleanSub1Cat = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $sub1Cat));
        ?>
        <div class="grid-area-sub1">
            <div class="magazine-hero-card">
                <a href="news_detail.php?id=<?php echo urlencode($subPost1['id']); ?>&group=<?php echo urlencode($subPost1['group_key'] ?? $currentGroupKey); ?>" class="hero-card-overlay-link" aria-label="<?php echo htmlspecialchars($subPost1['title']); ?>"></a>
                <img src="<?php echo htmlspecialchars(getVarientThumbnail($subPost1, 1)); ?>" alt="<?php echo htmlspecialchars($subPost1['title']); ?>" class="hero-card-img" loading="lazy">
                <div class="hero-card-content">
                    <span class="editorial-kicker" style="background-color: var(--accent-brand);">
                        <?php echo htmlspecialchars($cleanSub1Cat ? ($cleanSub1Cat . ' • ' . $subPost1['source_label']) : $subPost1['source_label']); ?>
                    </span>
                    <h3 class="hero-sub-title">
                        <a href="news_detail.php?id=<?php echo urlencode($subPost1['id']); ?>&group=<?php echo urlencode($subPost1['group_key'] ?? $currentGroupKey); ?>" class="text-white text-decoration-none">
                            <?php echo htmlspecialchars($subPost1['title']); ?>
                        </a>
                    </h3>
                    <div class="hero-card-meta">
                        <span><?php echo htmlspecialchars($sub1Author); ?></span>
                        <span class="opacity-50">&bull;</span>
                        <span><?php echo htmlspecialchars($subPost1['time_ago']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 3. Sub Story Bottom (2 Small Columns Side by Side) -->
        <div class="grid-area-sub2">
            <div class="sub-dual-grid">
                <?php if ($subPost2): 
                    $sub2Cat = !empty($subPost2['group_label']) ? $subPost2['group_label'] : '';
                    $cleanSub2Cat = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $sub2Cat));
                ?>
                <div class="magazine-hero-card">
                    <a href="news_detail.php?id=<?php echo urlencode($subPost2['id']); ?>&group=<?php echo urlencode($subPost2['group_key'] ?? $currentGroupKey); ?>" class="hero-card-overlay-link" aria-label="<?php echo htmlspecialchars($subPost2['title']); ?>"></a>
                    <img src="<?php echo htmlspecialchars(getVarientThumbnail($subPost2, 2)); ?>" alt="<?php echo htmlspecialchars($subPost2['title']); ?>" class="hero-card-img" loading="lazy">
                    <div class="hero-card-content p-3">
                        <span class="editorial-kicker" style="background-color: var(--accent-teal);">
                            <?php echo htmlspecialchars($cleanSub2Cat ? ($cleanSub2Cat . ' • ' . $subPost2['source_label']) : $subPost2['source_label']); ?>
                        </span>
                        <h4 class="hero-mini-title">
                            <a href="news_detail.php?id=<?php echo urlencode($subPost2['id']); ?>&group=<?php echo urlencode($subPost2['group_key'] ?? $currentGroupKey); ?>" class="text-white text-decoration-none">
                                <?php echo htmlspecialchars($subPost2['title']); ?>
                            </a>
                        </h4>
                        <div class="hero-card-meta" style="font-size: 0.72rem;">
                            <span><?php echo htmlspecialchars($subPost2['time_ago']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($subPost3): 
                    $sub3Cat = !empty($subPost3['group_label']) ? $subPost3['group_label'] : '';
                    $cleanSub3Cat = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $sub3Cat));
                ?>
                <div class="magazine-hero-card">
                    <a href="news_detail.php?id=<?php echo urlencode($subPost3['id']); ?>&group=<?php echo urlencode($subPost3['group_key'] ?? $currentGroupKey); ?>" class="hero-card-overlay-link" aria-label="<?php echo htmlspecialchars($subPost3['title']); ?>"></a>
                    <img src="<?php echo htmlspecialchars(getVarientThumbnail($subPost3, 3)); ?>" alt="<?php echo htmlspecialchars($subPost3['title']); ?>" class="hero-card-img" loading="lazy">
                    <div class="hero-card-content p-3">
                        <span class="editorial-kicker" style="background-color: #8b5cf6;">
                            <?php echo htmlspecialchars($cleanSub3Cat ? ($cleanSub3Cat . ' • ' . $subPost3['source_label']) : $subPost3['source_label']); ?>
                        </span>
                        <h4 class="hero-mini-title">
                            <a href="news_detail.php?id=<?php echo urlencode($subPost3['id']); ?>&group=<?php echo urlencode($subPost3['group_key'] ?? $currentGroupKey); ?>" class="text-white text-decoration-none">
                                <?php echo htmlspecialchars($subPost3['title']); ?>
                            </a>
                        </h4>
                        <div class="hero-card-meta" style="font-size: 0.72rem;">
                            <span><?php echo htmlspecialchars($subPost3['time_ago']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
