<?php
/**
 * Modals Component
 * In-App Reader Modal, Instant Search Modal, and Add Feeds Modal (Supabase)
 */
$nextGroupOrder = count($groups ?? []) + 1;
$nextSourceOrder = count($sources ?? []) + 1;
?>

<style>
    /* Generic Modal Styling */
    .app-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        animation: fadeIn 0.25s ease;
    }

    .app-modal-overlay.open {
        display: flex;
    }

    .app-modal-card {
        background: #111827;
        border: 1px solid #1f293d;
        border-radius: 24px;
        width: 100%;
        max-width: 580px;
        max-height: 90vh;
        overflow-y: auto;
        color: #f1f5f9;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid #1f293d;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h3 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
    }

    .modal-close-btn {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        color: #cbd5e1;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #f87171;
    }

    /* Article Modal */
    .reader-img {
        width: 100%;
        max-height: 280px;
        object-fit: cover;
        border-radius: 14px;
        margin-bottom: 16px;
    }

    .reader-title {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.4;
        color: #ffffff;
        margin-bottom: 12px;
    }

    .reader-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #1f293d;
    }

    .reader-desc {
        font-size: 0.96rem;
        line-height: 1.7;
        color: #cbd5e1;
        margin-bottom: 24px;
    }

    .btn-read-source {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px;
        background: #2563eb;
        color: #ffffff;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-read-source:hover {
        background: #1d4ed8;
    }

    /* Toast */
    .app-toast {
        position: fixed;
        bottom: 76px;
        left: 50%;
        transform: translateX(-50%) translateY(20px);
        background: #1f293d;
        color: #ffffff;
        border: 1px solid #334155;
        padding: 10px 20px;
        border-radius: 999px;
        font-size: 0.88rem;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .app-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
</style>

<!-- ============================================================== -->
<!-- 1. ARTICLE READER MODAL                                        -->
<!-- ============================================================== -->
<div class="app-modal-overlay" id="reader-modal-overlay" onclick="closeArticleModal(event)">
    <div class="app-modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <span style="font-size: 0.85rem; color: #60a5fa; font-weight: 600;" id="reader-source-badge">
                Source Label
            </span>
            <button type="button" class="modal-close-btn" onclick="closeArticleModal()">✕</button>
        </div>
        <div class="modal-body">
            <img src="" alt="" class="reader-img" id="reader-img" style="display: none;">
            <h2 class="reader-title" id="reader-title">Title</h2>
            <div class="reader-meta">
                <span id="reader-author" style="color: #cbd5e1; font-weight: 500;">Author</span>
                <span>·</span>
                <span id="reader-time">Time</span>
            </div>
            <div class="reader-desc" id="reader-desc">Description</div>
            <a href="#" target="_blank" rel="noopener noreferrer" class="btn-read-source" id="reader-link-btn">
                <span>อ่านบทความต้นฉบับฉบับเต็ม</span>
                <i class="material-icons" style="font-size: 18px;">open_in_new</i>
            </a>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 2. SEARCH MODAL                                                -->
<!-- ============================================================== -->
<div class="app-modal-overlay" id="search-modal-overlay" onclick="closeSearchModal(event)">
    <div class="app-modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3><i class="material-icons" style="vertical-align: middle; font-size: 20px; color: #3b82f6;">search</i> ค้นหาข่าวสาร</h3>
            <button type="button" class="modal-close-btn" onclick="closeSearchModal()">✕</button>
        </div>
        <div class="modal-body">
            <div style="position: relative; margin-bottom: 20px;">
                <input type="text" id="modal-search-input" 
                       placeholder="พิมพ์คำค้นหาหัวข้อข่าว หรือชื่อนักเขียน..." 
                       style="width: 100%; padding: 12px 16px 12px 42px; background: #1a233a; border: 1.5px solid #2d3b5e; border-radius: 12px; color: #fff; font-family: inherit; font-size: 0.95rem; outline: none;"
                       oninput="liveModalSearch(this.value)">
                <i class="material-icons" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 20px;">search</i>
            </div>
            <div id="modal-search-results" style="display: flex; flex-direction: column; gap: 12px; max-height: 400px; overflow-y: auto;">
                <div style="text-align: center; color: #64748b; font-size: 0.9rem; padding: 30px 0;">
                    เริ่มพิมพ์คำเพื่อค้นหาข่าวสารในกลุ่มนี้...
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- 3. ADD FEED MODAL (public.feed_sources & public.feed_groups)   -->
<!-- ============================================================== -->
<div class="app-modal-overlay" id="add-modal-overlay" onclick="closeAddFeedModal(event)">
    <div class="app-modal-card" style="max-width: 620px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3><i class="material-icons" style="vertical-align: middle; color: #3b82f6;">add_circle</i> เพิ่มข้อมูลจาก schema.sql (Supabase)</h3>
            <button type="button" class="modal-close-btn" onclick="closeAddFeedModal()">✕</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid #1f293d; padding-bottom: 10px;">
                <button type="button" class="source-chip active" id="modal-tab-src" onclick="switchModalAddTab('source')">
                    ➕ เพิ่มแหล่งข่าว (feed_sources)
                </button>
                <button type="button" class="source-chip" id="modal-tab-grp" onclick="switchModalAddTab('group')">
                    📁 เพิ่มกลุ่มฟีด (feed_groups)
                </button>
            </div>

            <!-- Form 1: Source -->
            <form method="POST" action="index.php?group=<?php echo urlencode($currentGroupKey); ?>" id="modal-form-src">
                <input type="hidden" name="action" value="add_source">
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            ชื่อแหล่งข่าว (Label) *
                        </label>
                        <input type="text" name="label" required placeholder="เช่น Blognone, TechCrunch" 
                               style="width:100%; padding:10px 12px; background:#1a233a; border:1px solid #2d3b5e; border-radius:8px; color:#fff;">
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            RSS/Atom URL *
                        </label>
                        <input type="url" name="url" required placeholder="https://www.blognone.com/atom.xml" 
                               style="width:100%; padding:10px 12px; background:#1a233a; border:1px solid #2d3b5e; border-radius:8px; color:#fff;">
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            กลุ่มข่าวสาร (Feed Group)
                        </label>
                        <select name="group_key" style="width:100%; padding:10px 12px; background:#1a233a; border:1px solid #2d3b5e; border-radius:8px; color:#fff;">
                            <?php foreach (($groups ?? []) as $g): ?>
                                <option value="<?php echo htmlspecialchars($g['key']); ?>" <?php echo $g['key'] === $currentGroupKey ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($g['label']); ?> (<?php echo htmlspecialchars($g['key']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                        <button type="submit" name="btnAddSource" class="btn-read-source" style="width: auto; padding: 10px 24px;">
                            <i class="material-icons" style="font-size: 18px;">cloud_upload</i> บันทึกลง Supabase
                        </button>
                    </div>
                </div>
            </form>

            <!-- Form 2: Group -->
            <form method="POST" action="index.php?group=<?php echo urlencode($currentGroupKey); ?>" id="modal-form-grp" style="display: none;">
                <input type="hidden" name="action" value="add_group">
                <div style="display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            ชื่อกลุ่มฟีด (Label) *
                        </label>
                        <input type="text" name="label" required placeholder="เช่น ปัญญาประดิษฐ์และ AI" 
                               style="width:100%; padding:10px 12px; background:#1a233a; border:1px solid #2d3b5e; border-radius:8px; color:#fff;">
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            รหัสกลุ่ม (Key) *
                        </label>
                        <input type="text" name="key" required placeholder="เช่น ai-innovation (ภาษาอังกฤษ)" 
                               style="width:100%; padding:10px 12px; background:#1a233a; border:1px solid #2d3b5e; border-radius:8px; color:#fff;">
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 5px;">
                            สีกลุ่ม (Hex Color)
                        </label>
                        <input type="color" name="color" value="#2F6FED" 
                               style="width:60px; height:40px; background:none; border:none; cursor:pointer;">
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                        <button type="submit" name="btnAddGroup" class="btn-read-source" style="width: auto; padding: 10px 24px; background: #059669;">
                            <i class="material-icons" style="font-size: 18px;">save</i> บันทึกลง Supabase
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="app-toast" id="app-toast">
    <i class="material-icons" style="color: #34d399; font-size: 18px;">check_circle</i>
    <span id="app-toast-text">ข้อความ</span>
</div>

<script>
    function openArticleModal(title, link, source, author, time, img, desc, id) {
        document.getElementById('reader-title').textContent = title;
        document.getElementById('reader-source-badge').textContent = source;
        document.getElementById('reader-author').textContent = author;
        document.getElementById('reader-time').textContent = time;
        document.getElementById('reader-desc').textContent = desc;
        document.getElementById('reader-link-btn').href = link;

        const imgEl = document.getElementById('reader-img');
        if (img) {
            imgEl.src = img;
            imgEl.style.display = 'block';
        } else {
            imgEl.style.display = 'none';
        }

        // Mark as read
        if (id && !readArticles.includes(id)) {
            readArticles.push(id);
            localStorage.setItem('read_articles', JSON.stringify(readArticles));
            applyFeedFilters();
        }

        document.getElementById('reader-modal-overlay')?.classList.add('open');
    }

    function closeArticleModal() {
        document.getElementById('reader-modal-overlay')?.classList.remove('open');
    }

    function openSearchModal() {
        document.getElementById('search-modal-overlay')?.classList.add('open');
        document.getElementById('modal-search-input')?.focus();
    }

    function closeSearchModal() {
        document.getElementById('search-modal-overlay')?.classList.remove('open');
    }

    function openAddFeedModal() {
        document.getElementById('add-modal-overlay')?.classList.add('open');
    }

    function closeAddFeedModal() {
        document.getElementById('add-modal-overlay')?.classList.remove('open');
    }

    function switchModalAddTab(tab) {
        const formSrc = document.getElementById('modal-form-src');
        const formGrp = document.getElementById('modal-form-grp');
        const tabSrc  = document.getElementById('modal-tab-src');
        const tabGrp  = document.getElementById('modal-tab-grp');

        if (tab === 'group') {
            formSrc.style.display = 'none';
            formGrp.style.display = 'block';
            tabGrp.classList.add('active');
            tabSrc.classList.remove('active');
        } else {
            formSrc.style.display = 'block';
            formGrp.style.display = 'none';
            tabSrc.classList.add('active');
            tabGrp.classList.remove('active');
        }
    }

    function liveModalSearch(query) {
        const resBox = document.getElementById('modal-search-results');
        query = query.toLowerCase().trim();
        if (!query) {
            resBox.innerHTML = '<div style="text-align: center; color: #64748b; font-size: 0.9rem; padding: 30px 0;">เริ่มพิมพ์คำเพื่อค้นหาข่าวสารในกลุ่มนี้...</div>';
            return;
        }

        const cards = document.querySelectorAll('.article-card');
        let html = '';
        let found = 0;

        cards.forEach(card => {
            const title = card.dataset.title || '';
            const author = card.dataset.author || '';
            if (title.includes(query) || author.includes(query)) {
                found++;
                const cardTitle = card.querySelector('.article-title')?.textContent.trim();
                const source = card.querySelector('.meta-source')?.textContent.trim();
                const time = card.querySelector('.meta-time')?.textContent.trim();
                const id = card.dataset.id;
                html += `
                    <div style="background:#151e32; border:1px solid #1f2a44; border-radius:12px; padding:12px; cursor:pointer;" onclick="cardClickById('${id}')">
                        <div style="font-size:0.78rem; color:#60a5fa; font-weight:600; margin-bottom:4px;">${source} · ${time}</div>
                        <div style="font-size:0.92rem; font-weight:600; color:#fff; line-height:1.4;">${cardTitle}</div>
                    </div>
                `;
            }
        });

        if (found === 0) {
            resBox.innerHTML = '<div style="text-align: center; color: #64748b; font-size: 0.9rem; padding: 30px 0;">ไม่พบข่าวสารที่ตรงกับคำค้นหา</div>';
        } else {
            resBox.innerHTML = html;
        }
    }

    function cardClickById(id) {
        closeSearchModal();
        const card = document.getElementById('art-' + id);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth' });
            card.querySelector('.article-title')?.click();
        }
    }

    function showAppToast(msg) {
        const toast = document.getElementById('app-toast');
        const text = document.getElementById('app-toast-text');
        if (toast && text) {
            text.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2500);
        }
    }
</script>
