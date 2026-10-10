<?php
/**
 * Category Navigation Component (Level 1 Feed Groups)
 * Category capsule pills exactly matching the reference design
 */

function getCatIconEmoji($key) {
    $map = [
        'tech-business'    => '💻',
        'international'    => '🌐',
        'thai-news'        => '🇹🇭',
        'mobile'           => '📱',
        'computer-games'   => '🎮',
        'ai-innovation'    => '🤖',
        'weather-forecast' => '🌦️',
        'cybersecurity'    => '🛡️',
        'ev-vehicles'      => '🚗',
        'science-space'    => '🚀',
        'fintech-crypto'   => '🪙',
        'developer-coding' => '👨‍💻'
    ];
    return $map[$key] ?? '📁';
}
?>

<style>
    .category-nav-bar {
        background: #090e18;
        border-bottom: 1px solid var(--border-card);
        padding: 14px 0 10px 0;
    }

    .category-scroll-wrap {
        max-width: var(--max-w);
        margin: 0 auto;
        padding: 0 20px;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .category-scroll-wrap::-webkit-scrollbar {
        display: none;
    }

    .category-pills-list {
        display: inline-flex;
        gap: 10px;
    }

    .category-capsule {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 500;
        color: var(--text-muted);
        background: #111827;
        border: 1px solid #1f293d;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }

    .category-capsule:hover {
        background: #182238;
        color: #ffffff;
        border-color: #334155;
    }

    .category-capsule.active {
        background: rgba(37, 99, 235, 0.15);
        border: 1.5px solid #2563eb;
        color: #60a5fa;
        font-weight: 600;
        box-shadow: 0 0 14px rgba(37, 99, 235, 0.25);
    }

    .category-capsule-icon {
        font-size: 1rem;
        line-height: 1;
    }
</style>

<nav class="category-nav-bar">
    <div class="category-scroll-wrap">
        <div class="category-pills-list">
            <?php foreach (($groups ?? []) as $grp): ?>
                <?php 
                    $gKey = $grp['key'];
                    $isActive = ($gKey === $currentGroupKey);
                ?>
                <a href="index.php?group=<?php echo urlencode($gKey); ?>" 
                   class="category-capsule <?php echo $isActive ? 'active' : ''; ?>">
                    <span class="category-capsule-icon"><?php echo getCatIconEmoji($gKey); ?></span>
                    <span><?php echo htmlspecialchars($grp['label']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
