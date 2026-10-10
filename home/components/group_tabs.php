<?php
/**
 * Feed Group Category Tabs Component
 * Level 1 horizontal scrolling tabs matching the screenshot
 */

function getGroupIcon($key) {
    $icons = [
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
    return $icons[$key] ?? '📁';
}
?>

<style>
    .group-tabs-container {
        padding: 14px 16px 6px 16px;
        background: var(--app-bg);
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE */
    }

    .group-tabs-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
    }

    .group-tabs-list {
        display: inline-flex;
        gap: 10px;
    }

    .group-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 500;
        color: var(--app-text-sub);
        background: #111827;
        border: 1px solid #1f293d;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        user-select: none;
    }

    .group-tab-pill:hover {
        background: #182238;
        color: #ffffff;
        border-color: #334155;
    }

    .group-tab-pill.active {
        background: rgba(37, 99, 235, 0.15);
        border: 1.5px solid #2563eb;
        color: #60a5fa;
        font-weight: 600;
        box-shadow: 0 0 14px rgba(37, 99, 235, 0.25);
    }

    .group-tab-icon {
        font-size: 1rem;
        line-height: 1;
    }
</style>

<div class="group-tabs-container">
    <div class="group-tabs-list">
        <?php foreach (($groups ?? []) as $grp): ?>
            <?php 
                $gKey = $grp['key'];
                $isActive = ($gKey === $currentGroupKey);
            ?>
            <a href="index.php?group=<?php echo urlencode($gKey); ?>" 
               class="group-tab-pill <?php echo $isActive ? 'active' : ''; ?>">
                <span class="group-tab-icon"><?php echo getGroupIcon($gKey); ?></span>
                <span><?php echo htmlspecialchars($grp['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
