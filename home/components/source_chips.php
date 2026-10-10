<?php
/**
 * Source Filter Chips Component
 * Level 2 horizontal scrolling chips matching the screenshot
 */
?>

<style>
    .source-chips-container {
        padding: 8px 16px 14px 16px;
        background: var(--app-bg);
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .source-chips-container::-webkit-scrollbar {
        display: none;
    }

    .source-chips-list {
        display: inline-flex;
        gap: 8px;
    }

    .source-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 0.84rem;
        font-weight: 500;
        color: #cbd5e1;
        background: #151e32;
        border: 1px solid #202d47;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .source-chip:hover {
        background: #1c2742;
        color: #ffffff;
    }

    .source-chip.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        font-weight: 600;
        box-shadow: 0 2px 10px rgba(37, 99, 235, 0.4);
    }
</style>

<div class="source-chips-container">
    <div class="source-chips-list">
        <!-- All Chip -->
        <span class="source-chip active" data-source="all" onclick="filterBySource('all', this)">
            ⚡ ทั้งหมด (All)
        </span>

        <?php foreach (($sourcesInGroup ?? []) as $s): ?>
            <span class="source-chip" 
                  data-source="<?php echo htmlspecialchars($s['key']); ?>" 
                  onclick="filterBySource('<?php echo htmlspecialchars($s['key']); ?>', this)">
                <?php echo htmlspecialchars($s['label']); ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>
