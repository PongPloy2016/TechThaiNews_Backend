<?php
/**
 * Feed Explorer Component
 * Searchable, filterable directory of feed groups and sources from Supabase
 */

// Build group lookup map
$groupMap = [];
$groupCounts = [];
foreach (($groups ?? []) as $g) {
    $groupMap[$g['key']] = $g;
    $groupCounts[$g['key']] = 0;
}

foreach (($sources ?? []) as $s) {
    $gk = $s['group_key'] ?? '';
    if (!empty($gk) && isset($groupCounts[$gk])) {
        $groupCounts[$gk]++;
    }
}
?>

<style>
    .explorer-section {
        max-width: 1200px;
        margin: 0 auto 60px auto;
        padding: 0 24px;
    }

    .explorer-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .explorer-title-area h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .explorer-title-area p {
        color: var(--text-muted);
        font-size: 0.92rem;
        margin-top: 4px;
    }

    /* Search & Filter Toolbar */
    .filter-toolbar {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .search-row {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 260px;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 20px;
    }

    .search-input {
        width: 100%;
        padding: 11px 16px 11px 42px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 0.95rem;
        outline: none;
        transition: var(--transition);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .filter-chips-row {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: thin;
    }

    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        background: #f1f5f9;
        color: var(--text-main);
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        border: 1px solid transparent;
        transition: var(--transition);
        user-select: none;
    }

    .filter-chip:hover {
        background: #e2e8f0;
    }

    .filter-chip.active {
        background: var(--secondary);
        color: #ffffff;
        border-color: var(--secondary);
    }

    .chip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .chip-count {
        background: rgba(0, 0, 0, 0.1);
        padding: 1px 6px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-chip.active .chip-count {
        background: rgba(255, 255, 255, 0.25);
    }

    /* Cards Grid */
    .sources-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
    }

    .source-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }

    .source-card:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }

    .source-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 12px;
    }

    .source-group-badge {
        font-size: 0.78rem;
        padding: 3px 10px;
        border-radius: 999px;
        color: #ffffff;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .source-type-badge {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 3px 7px;
        border-radius: 4px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .source-active-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
    }

    .source-inactive-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #94a3b8;
    }

    .source-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .source-card-key {
        font-size: 0.78rem;
        font-family: monospace;
        color: var(--text-muted);
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 14px;
    }

    .source-feed-url {
        background: #f8fafc;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 8px 10px;
        font-family: monospace;
        font-size: 0.78rem;
        color: #475569;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-bottom: 14px;
    }

    .source-card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid var(--border-light);
        font-size: 0.85rem;
    }

    .btn-action-icon {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-muted);
        border: 1px solid var(--border);
        background: #ffffff;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-action-icon:hover {
        color: var(--primary);
        border-color: var(--primary);
        background: var(--primary-light);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #ffffff;
        border-radius: var(--radius);
        border: 1px dashed var(--border);
        display: none;
    }

    .empty-state.show {
        display: block;
    }

    /* Toast Notification */
    .toast-msg {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 20px;
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        z-index: 9999;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .toast-msg.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<section class="explorer-section" id="feeds-directory">
    <div class="explorer-header">
        <div class="explorer-title-area">
            <h2>
                <i class="material-icons" style="color: var(--primary);">feed</i>
                สำรวจและตรวจสอบฟีดข่าวทั้งหมด (Feed Directory)
            </h2>
            <p>
                แสดงแหล่งข้อมูลข่าวสารทั้งหมด <strong id="visible-count"><?php echo count($sources ?? []); ?></strong> รายการ 
                จาก <strong><?php echo count($groups ?? []); ?></strong> กลุ่มฟีด เชื่อมต่อแบบเรียลไทม์กับ Supabase
            </p>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="filter-toolbar">
        <div class="search-row">
            <div class="search-box">
                <i class="material-icons">search</i>
                <input type="text" id="feed-search-input" class="search-input" 
                       placeholder="ค้นหาชื่อสำนักข่าว, รหัส Key หรือ Feed URL..." 
                       oninput="filterSources()">
            </div>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
                คลิกเลือกหมวดหมู่ด้านล่างเพื่อกรองข่าว:
            </span>
        </div>

        <div class="filter-chips-row">
            <span class="filter-chip active" data-group="all" onclick="selectGroupFilter('all', this)">
                ทั้งหมด (All) <span class="chip-count"><?php echo count($sources ?? []); ?></span>
            </span>

            <?php foreach (($groups ?? []) as $grp): ?>
                <?php 
                    $gKey = $grp['key'];
                    $gColor = $grp['color'] ?? '#2F6FED';
                    $count = $groupCounts[$gKey] ?? 0;
                ?>
                <span class="filter-chip" data-group="<?php echo htmlspecialchars($gKey); ?>" 
                      onclick="selectGroupFilter('<?php echo htmlspecialchars($gKey); ?>', this)">
                    <span class="chip-dot" style="background-color: <?php echo htmlspecialchars($gColor); ?>;"></span>
                    <?php echo htmlspecialchars($grp['label']); ?>
                    <span class="chip-count"><?php echo $count; ?></span>
                </span>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sources Grid -->
    <div class="sources-grid" id="sources-grid">
        <?php foreach (($sources ?? []) as $src): ?>
            <?php 
                $gKey = $src['group_key'] ?? '';
                $groupInfo = $groupMap[$gKey] ?? null;
                $groupLabel = $groupInfo ? $groupInfo['label'] : 'ไม่ระบุกลุ่ม';
                $groupColor = $groupInfo['color'] ?? '#64748b';
                $isActive = !empty($src['is_active']);
            ?>
            <div class="source-card" 
                 data-group="<?php echo htmlspecialchars($gKey); ?>"
                 data-key="<?php echo htmlspecialchars($src['key'] ?? ''); ?>"
                 data-label="<?php echo htmlspecialchars(strtolower($src['label'] ?? '')); ?>"
                 data-url="<?php echo htmlspecialchars(strtolower($src['url'] ?? '')); ?>">
                
                <div>
                    <div class="source-card-top">
                        <span class="source-group-badge" style="background-color: <?php echo htmlspecialchars($groupColor); ?>;">
                            <?php echo htmlspecialchars($groupLabel); ?>
                        </span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="source-type-badge">
                                <?php echo htmlspecialchars($src['type'] ?? 'RSS'); ?>
                            </span>
                            <span class="<?php echo $isActive ? 'source-active-dot' : 'source-inactive-dot'; ?>" 
                                  title="<?php echo $isActive ? 'เปิดใช้งาน (Active)' : 'ปิดใช้งาน (Inactive)'; ?>"></span>
                        </div>
                    </div>

                    <h3 class="source-card-title"><?php echo htmlspecialchars($src['label'] ?? ''); ?></h3>
                    <span class="source-card-key"><?php echo htmlspecialchars($src['key'] ?? ''); ?></span>

                    <div class="source-feed-url" title="<?php echo htmlspecialchars($src['url'] ?? ''); ?>">
                        <i class="material-icons" style="font-size: 13px; vertical-align: middle; color: #94a3b8;">link</i>
                        <?php echo htmlspecialchars($src['url'] ?? ''); ?>
                    </div>
                </div>

                <div class="source-card-actions">
                    <button type="button" class="btn-action-icon" 
                            onclick="copyToClipboard('<?php echo htmlspecialchars(addslashes($src['url'] ?? '')); ?>')">
                        <i class="material-icons" style="font-size: 16px;">content_copy</i> คัดลอก URL
                    </button>

                    <div style="display: flex; gap: 6px;">
                        <?php if (!empty($src['homepage'])): ?>
                            <a href="<?php echo htmlspecialchars($src['homepage']); ?>" target="_blank" rel="noopener noreferrer" class="btn-action-icon" title="เปิดหน้าเว็บไซต์">
                                <i class="material-icons" style="font-size: 16px;">language</i> เว็บไซต์
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo htmlspecialchars($src['url']); ?>" target="_blank" rel="noopener noreferrer" class="btn-action-icon" title="ทดสอบดูฟีดข่าว">
                            <i class="material-icons" style="font-size: 16px;">open_in_new</i> ทดสอบ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div class="empty-state" id="empty-state">
        <i class="material-icons" style="font-size: 56px; color: #94a3b8; margin-bottom: 12px;">search_off</i>
        <h3 style="font-size: 1.2rem; color: var(--secondary); margin-bottom: 6px;">ไม่พบแหล่งข่าวที่ตรงกับเงื่อนไขการค้นหา</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 18px;">ลองค้นหาด้วยคำอื่น หรือคลิกปุ่มเพื่อล้างตัวกรองทั้งหมด</p>
        <button type="button" class="btn-action-icon" style="padding: 10px 20px; font-weight: 600;" onclick="resetFilter()">
            <i class="material-icons">refresh</i> แสดงแหล่งข่าวทั้งหมด
        </button>
    </div>
</section>

<!-- Toast -->
<div class="toast-msg" id="toast-msg">
    <i class="material-icons" style="color: #34d399; font-size: 20px;">check_circle</i>
    <span id="toast-text">คัดลอก RSS URL สำเร็จเรียบร้อย!</span>
</div>

<script>
    let currentGroupFilter = 'all';

    function selectGroupFilter(groupKey, el) {
        currentGroupFilter = groupKey;
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        el?.classList.add('active');
        filterSources();
    }

    function filterSources() {
        const query = (document.getElementById('feed-search-input')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.source-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardGroup = card.dataset.group || '';
            const cardKey   = (card.dataset.key || '').toLowerCase();
            const cardLabel = (card.dataset.label || '').toLowerCase();
            const cardUrl   = (card.dataset.url || '').toLowerCase();

            const matchesGroup = (currentGroupFilter === 'all' || cardGroup === currentGroupFilter);
            const matchesQuery = (!query || cardKey.includes(query) || cardLabel.includes(query) || cardUrl.includes(query));

            if (matchesGroup && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const countDisplay = document.getElementById('visible-count');
        if (countDisplay) countDisplay.textContent = visibleCount;

        const emptyState = document.getElementById('empty-state');
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.classList.add('show');
            } else {
                emptyState.classList.remove('show');
            }
        }
    }

    function resetFilter() {
        const searchInput = document.getElementById('feed-search-input');
        if (searchInput) searchInput.value = '';
        const allChip = document.querySelector('.filter-chip[data-group="all"]');
        selectGroupFilter('all', allChip);
    }

    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => showToast('คัดลอก RSS URL แล้ว: ' + text));
        } else {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToast('คัดลอก RSS URL แล้ว: ' + text);
        }
    }

    function showToast(msg) {
        const toast = document.getElementById('toast-msg');
        const toastText = document.getElementById('toast-text');
        if (toast && toastText) {
            toastText.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    }
</script>
