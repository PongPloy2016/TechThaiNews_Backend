<?php
/**
 * Editorial News Ticker Component
 * Real-time news ticker with minimalist editorial styling
 */

$tickerArticles = array_slice($articles ?? [], 0, 8);
?>

<div class="container-xl">
    <div class="editorial-ticker">
        <!-- Label Pill -->
        <div class="ticker-pill">
            <span class="d-none d-sm-inline">FLASH DISPATCH</span>
            <span class="d-sm-none">FLASH</span>
        </div>

        <!-- Headline Content -->
        <div class="ticker-body flex-grow-1" id="tickerContainer">
            <?php if (!empty($tickerArticles)): ?>
                <?php foreach ($tickerArticles as $idx => $tArt): ?>
                    <div class="ticker-item <?php echo $idx === 0 ? 'd-flex' : 'd-none'; ?> align-items-center gap-2" data-index="<?php echo $idx; ?>">
                        <?php 
                        $tickLabel = !empty($tArt['group_label']) ? $tArt['group_label'] : $tArt['source_label'];
                        $cleanTick = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $tickLabel));
                        ?>
                        <span class="badge rounded-1 font-monospace" style="background: rgba(255, 255, 255, 0.08); color: var(--text-muted); font-size: 0.7rem; font-weight: 500;">
                            <?php echo htmlspecialchars($cleanTick ?: $tArt['source_label']); ?>
                        </span>
                        <a href="news_detail.php?id=<?php echo urlencode($tArt['id']); ?>&group=<?php echo urlencode($tArt['group_key'] ?? ($currentGroupKey ?? 'tech-business')); ?>" class="text-truncate fw-semibold text-decoration-none" title="<?php echo htmlspecialchars($tArt['title']); ?>">
                            <?php echo htmlspecialchars($tArt['title']); ?>
                        </a>
                        <span class="text-muted small ms-2 d-none d-md-inline">&bull; <?php echo htmlspecialchars($tArt['time_ago']); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted">กำลังรวบรวมข่าวสารล่าสุด...</span>
            <?php endif; ?>
        </div>

        <!-- Controls: Prev / Next -->
        <div class="d-flex align-items-center gap-1 pe-3 flex-shrink-0">
            <button type="button" class="btn btn-sm btn-link text-reset p-1 border-0" onclick="prevTickerItem()" aria-label="Previous headline">
                <i class="bi bi-chevron-left small text-muted"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link text-reset p-1 border-0" onclick="nextTickerItem()" aria-label="Next headline">
                <i class="bi bi-chevron-right small text-muted"></i>
            </button>
        </div>
    </div>
</div>

<script>
    let currentTickerIdx = 0;
    const tickerItems = document.querySelectorAll('.ticker-item');
    let tickerInterval = null;

    function showTickerItem(index) {
        if (!tickerItems.length) return;
        tickerItems.forEach(el => {
            el.classList.remove('d-flex');
            el.classList.add('d-none');
        });
        currentTickerIdx = (index + tickerItems.length) % tickerItems.length;
        tickerItems[currentTickerIdx].classList.remove('d-none');
        tickerItems[currentTickerIdx].classList.add('d-flex');
    }

    function nextTickerItem() {
        showTickerItem(currentTickerIdx + 1);
        resetTickerTimer();
    }

    function prevTickerItem() {
        showTickerItem(currentTickerIdx - 1);
        resetTickerTimer();
    }

    function resetTickerTimer() {
        if (tickerInterval) clearInterval(tickerInterval);
        tickerInterval = setInterval(nextTickerItem, 7000);
    }

    if (tickerItems.length > 1) {
        tickerInterval = setInterval(nextTickerItem, 7000);
    }
</script>
