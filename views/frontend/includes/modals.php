<?php
/**
 * Editorial Modals Component
 * 1. Article Reader Modal
 * 2. Add Feed Source Modal
 * 3. Add Feed Group Modal
 */

$nextGroupOrder = count($groups ?? []) + 1;
$nextSourceOrder = count($sourcesInGroup ?? []) + 1;
?>

<!-- 1. Article Reader Modal -->
<div class="modal fade" id="modalArticleReader" tabindex="-1" aria-labelledby="modalReaderTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="background-color: var(--bg-surface); color: var(--text-heading); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); box-shadow: var(--shadow-hover);">
            <div class="modal-header border-bottom py-3" style="border-color: var(--border-subtle) !important;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-1" id="readerBadge" style="background-color: var(--accent-brand); font-size: 0.72rem; font-weight: 600; text-transform: uppercase;">ข่าว</span>
                    <span class="text-muted small" id="readerTime" style="font-size: 0.8rem;">เวลา</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="readerImageContainer" class="mb-4 rounded overflow-hidden position-relative" style="max-height: 400px; border-radius: var(--radius-md);">
                    <img id="readerImage" src="" alt="Article cover" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1000&auto=format&fit=crop&q=80';">
                </div>

                <h2 class="fw-bold mb-3" id="readerTitle" style="font-size: 1.5rem; line-height: 1.35; letter-spacing: -0.025em;"></h2>

                <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom" style="border-color: var(--border-subtle) !important;">
                    <span>ผู้เขียน: <strong id="readerAuthor" class="text-body fw-semibold"></strong></span>
                    <span>&bull;</span>
                    <span><i class="bi bi-shield-check text-success"></i> แหล่งข่าวที่ได้รับการตรวจสอบแล้ว</span>
                </div>

                <div class="article-reader-body fs-6" id="readerDescription" style="line-height: 1.8; color: var(--text-body);">
                </div>
            </div>
            <div class="modal-footer border-top d-flex justify-content-between align-items-center py-3" style="border-color: var(--border-subtle) !important;">
                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius: var(--radius-sm);">ปิดหน้าต่าง</button>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="readerDetailLink" class="btn btn-sm btn-outline-primary px-3 fw-medium" style="border-radius: var(--radius-sm);">
                        เปิดอ่านหน้าเต็ม <i class="bi bi-file-earmark-richtext ms-1"></i>
                    </a>
                    <a href="#" id="readerOriginalLink" target="_blank" rel="noopener noreferrer" class="btn btn-sm text-white fw-medium px-3" style="background-color: var(--accent-brand); border-radius: var(--radius-sm);">
                        ต้นฉบับ <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Add Feed Source Modal -->
<div class="modal fade" id="modalAddSource" tabindex="-1" aria-labelledby="modalAddSourceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--bg-surface); color: var(--text-heading); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); box-shadow: var(--shadow-hover);">
            <div class="modal-header border-bottom py-3" style="border-color: var(--border-subtle) !important;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalAddSourceLabel" style="font-size: 1.1rem;">
                    <i class="bi bi-rss-fill text-warning"></i> แนะนำและเพิ่มฟีดข่าว RSS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?group=<?php echo urlencode($currentGroupKey); ?>" method="post">
                <input type="hidden" name="action" value="add_source">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">หมวดหมู่ข่าว <span class="text-danger">*</span></label>
                        <select name="group_key" class="form-select" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" required>
                            <?php foreach (($groups ?? []) as $g): 
                                $cleanSelectLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $g['label']));
                            ?>
                                <option value="<?php echo htmlspecialchars($g['key']); ?>" <?php echo $g['key'] === $currentGroupKey ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cleanSelectLabel ?: $g['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ชื่อสำนักข่าว / ผู้ให้บริการฟีด <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" placeholder="เช่น Blognone, The Standard Tech, Wired" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">รหัสอ้างอิงฟีด (Key) <span class="text-danger">*</span></label>
                        <input type="text" name="key" class="form-control font-monospace" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" placeholder="เช่น blognone, standard-tech" pattern="[a-zA-Z0-9_\-]+" required>
                        <div class="form-text small text-muted" style="font-size: 0.75rem;">ภาษาอังกฤษ ตัวพิมพ์เล็ก และขีดกลางเท่านั้น</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">URL ฟีด RSS หรือ Atom <span class="text-danger">*</span></label>
                        <input type="url" name="url" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" placeholder="https://www.blognone.com/atom.xml" required>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">ลำดับแสดงผล</label>
                            <input type="number" name="order_index" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" value="<?php echo $nextSourceOrder; ?>">
                        </div>
                        <div class="col-6 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="swActiveSource" checked>
                                <label class="form-check-label small fw-semibold" for="swActiveSource">เปิดใช้งานทันที</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3" style="border-color: var(--border-subtle) !important;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius: var(--radius-sm);">ยกเลิก</button>
                    <button type="submit" name="btnAddSource" class="btn btn-sm btn-primary px-4 fw-medium" style="background-color: var(--accent-brand); border: none; border-radius: var(--radius-sm);">
                        บันทึกแหล่งข่าว
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Add Feed Group Modal -->
<div class="modal fade" id="modalAddGroup" tabindex="-1" aria-labelledby="modalAddGroupLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--bg-surface); color: var(--text-heading); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); box-shadow: var(--shadow-hover);">
            <div class="modal-header border-bottom py-3" style="border-color: var(--border-subtle) !important;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalAddGroupLabel" style="font-size: 1.1rem;">
                    <i class="bi bi-folder-plus text-success"></i> เพิ่มหมวดหมู่ข่าวสารใหม่
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?group=<?php echo urlencode($currentGroupKey); ?>" method="post">
                <input type="hidden" name="action" value="add_group">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ชื่อหมวดหมู่ที่แสดง <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" placeholder="เช่น ข่าวสตาร์ทอัพ, AI & Robotics" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">รหัสหมวดหมู่ (Key) <span class="text-danger">*</span></label>
                        <input type="text" name="key" class="form-control font-monospace" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" placeholder="เช่น startup, robotics" pattern="[a-zA-Z0-9_\-]+" required>
                        <div class="form-text small text-muted" style="font-size: 0.75rem;">ภาษาอังกฤษ ตัวพิมพ์เล็ก และขีดกลางเท่านั้น</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ลำดับแสดงผล</label>
                        <input type="number" name="order_index" class="form-control" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: var(--radius-sm);" value="<?php echo $nextGroupOrder; ?>">
                    </div>
                </div>
                <div class="modal-footer border-top py-3" style="border-color: var(--border-subtle) !important;">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius: var(--radius-sm);">ยกเลิก</button>
                    <button type="submit" name="btnAddGroup" class="btn btn-sm btn-success px-4 fw-medium" style="border-radius: var(--radius-sm);">
                        บันทึกหมวดหมู่
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<?php if (!empty($alert_message)): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div class="toast show align-items-center text-bg-<?php echo $alert_status === 'success' ? 'success' : 'danger'; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: var(--radius-md);">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-<?php echo $alert_status === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'; ?> fs-5"></i>
                <span><?php echo htmlspecialchars($alert_message); ?></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    function openArticleModal(article) {
        if (!article) return;

        document.getElementById('readerTitle').textContent = article.title || 'บทความ';
        document.getElementById('readerBadge').textContent = article.source_label || 'ข่าว';
        document.getElementById('readerTime').textContent = article.time_ago || '';
        document.getElementById('readerAuthor').textContent = article.author || article.source_label || 'กองบรรณาธิการ';
        document.getElementById('readerDescription').innerHTML = article.description ? `<p>${article.description}</p>` : '<p class="text-muted">คลิกปุ่มเปิดอ่านต้นฉบับเต็มเพื่อดูเนื้อหาทั้งหมดจากผู้ให้บริการข่าวสาร</p>';

        const origLink = document.getElementById('readerOriginalLink');
        if (origLink) {
            origLink.href = article.link || '#';
        }

        const detailLink = document.getElementById('readerDetailLink');
        if (detailLink) {
            if (article.id) {
                detailLink.href = 'news_detail.php?id=' + encodeURIComponent(article.id) + '&group=' + encodeURIComponent(article.group_key || '');
                detailLink.classList.remove('d-none');
            } else {
                detailLink.classList.add('d-none');
            }
        }

        const imgEl = document.getElementById('readerImage');
        const imgWrap = document.getElementById('readerImageContainer');
        if (article.image) {
            imgEl.src = article.image;
            imgWrap.classList.remove('d-none');
        } else {
            // Sync with the exact thumbnail shown on the card
            let cardThumb = '';
            if (window.event && window.event.target) {
                const parentCard = window.event.target.closest('.article-feed-card, .magazine-hero-card, .trending-row');
                const thumbEl = parentCard ? parentCard.querySelector('img') : null;
                if (thumbEl && thumbEl.src) cardThumb = thumbEl.src;
            }
            if (cardThumb) {
                imgEl.src = cardThumb;
                imgWrap.classList.remove('d-none');
            } else {
                imgWrap.classList.add('d-none');
            }
        }

        const modalEl = document.getElementById('modalArticleReader');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
</script>
