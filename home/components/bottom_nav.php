<?php
/**
 * Bottom Navigation Component
 * App bar at bottom matching the screenshot
 */
?>

<style>
    .bottom-nav-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(9, 13, 22, 0.96);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        z-index: 90;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bottom-nav-inner {
        width: 100%;
        max-width: 680px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0 12px;
    }

    .nav-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 0.75rem;
        font-family: inherit;
        font-weight: 500;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 12px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .nav-tab-btn i {
        font-size: 22px;
        transition: transform 0.2s;
    }

    .nav-tab-btn:hover {
        color: #60a5fa;
    }

    .nav-tab-btn:hover i {
        transform: translateY(-2px);
    }

    .nav-tab-btn.active {
        color: #3b82f6;
        font-weight: 700;
    }

    .nav-tab-btn.add-btn {
        background: #2563eb;
        color: #ffffff;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.5);
        margin-top: -12px;
        border: 2px solid #090d16;
    }

    .nav-tab-btn.add-btn:hover {
        background: #1d4ed8;
        transform: scale(1.08);
    }

    .nav-tab-btn.add-btn i {
        font-size: 24px;
    }
</style>

<nav class="bottom-nav-bar">
    <div class="bottom-nav-inner">
        <button type="button" class="nav-tab-btn active" onclick="scrollToTopFeed(); filterFeedMode('all', document.querySelector('.feed-filter-pill[data-filter=\'all\']'));">
            <i class="material-icons">feed</i>
            <span>ข่าวล่าสุด</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="openSearchModal()">
            <i class="material-icons">search</i>
            <span>ค้นหา</span>
        </button>

        <button type="button" class="nav-tab-btn add-btn" onclick="openAddFeedModal()" title="เพิ่มแหล่งข่าว / กลุ่มฟีด">
            <i class="material-icons">add</i>
        </button>

        <button type="button" class="nav-tab-btn" onclick="filterBookmarkedOnly()">
            <i class="material-icons">bookmark</i>
            <span>บันทึก</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="openDrawer()">
            <i class="material-icons">settings</i>
            <span>ตั้งค่า</span>
        </button>
    </div>
</nav>

<script>
    function filterBookmarkedOnly() {
        const cards = document.querySelectorAll('.article-card');
        const bMarks = JSON.parse(localStorage.getItem('bookmarked_articles') || '[]');
        let count = 0;

        cards.forEach(card => {
            const id = card.dataset.id;
            if (bMarks.includes(id)) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        const titleEl = document.getElementById('feed-heading-title');
        if (titleEl) titleEl.textContent = 'ข่าวที่บันทึกไว้ (Bookmarks)';

        const emptyBox = document.getElementById('feed-empty-box');
        if (emptyBox) {
            if (count === 0) {
                emptyBox.classList.add('show');
                emptyBox.querySelector('h3').textContent = 'ยังไม่มีข่าวที่บันทึกไว้';
            } else {
                emptyBox.classList.remove('show');
            }
        }
    }
</script>
