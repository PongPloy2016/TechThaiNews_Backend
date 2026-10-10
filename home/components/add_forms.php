<?php
/**
 * Add Forms Component
 * Provides forms to add new records into public.feed_sources and public.feed_groups
 */
$nextGroupOrder = count($groups ?? []) + 1;
$nextSourceOrder = count($sources ?? []) + 1;
?>

<style>
    .add-section-wrapper {
        max-width: 1200px;
        margin: -32px auto 48px auto;
        padding: 0 24px;
        position: relative;
        z-index: 10;
    }

    .form-container-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    /* Tab navigation */
    .form-tab-header {
        display: flex;
        border-bottom: 1px solid var(--border);
        background: #f8fafc;
        padding: 6px 12px 0 12px;
        gap: 8px;
    }

    .form-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 22px;
        background: transparent;
        border: 1px solid transparent;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
        font-family: inherit;
        font-size: 0.98rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .form-tab-btn:hover {
        color: var(--primary);
        background: rgba(255, 255, 255, 0.6);
    }

    .form-tab-btn.active {
        background: #ffffff;
        color: var(--primary);
        border-color: var(--border);
        box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.02);
    }

    .form-tab-content {
        padding: 32px;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-heading-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .form-heading-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-heading-badge {
        font-size: 0.78rem;
        background: var(--primary-light);
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        border: 1px solid var(--primary-border);
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--secondary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label .req {
        color: #ef4444;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        font-family: inherit;
        font-size: 0.93rem;
        color: var(--text-main);
        background: #ffffff;
        transition: var(--transition);
        outline: none;
    }

    .form-input:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-hint {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .color-picker-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .color-input {
        width: 48px;
        height: 44px;
        padding: 2px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        cursor: pointer;
        background: #ffffff;
    }

    .palette-chips {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .color-chip {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 1px var(--border);
        transition: var(--transition);
    }

    .color-chip:hover {
        transform: scale(1.2);
    }

    /* Live Preview Box */
    .preview-box {
        background: #f8fafc;
        border: 1px dashed var(--border);
        border-radius: var(--radius);
        padding: 16px 20px;
        margin: 16px 0 24px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .preview-label {
        font-size: 0.82rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 28px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        font-family: inherit;
        font-size: 0.98rem;
        font-weight: 600;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
    }

    /* Toggle Switch */
    .switch-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #10b981;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }

    /* Alerts */
    .alert {
        padding: 16px 20px;
        border-radius: var(--radius);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
    }

    .alert-success {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .form-tab-content {
            padding: 20px;
        }
    }
</style>

<div class="add-section-wrapper" id="add-section">

    <!-- Flash message from submission -->
    <?php if (!empty($alert_status)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($alert_status); ?>" role="alert">
            <i class="material-icons" style="font-size: 24px;">
                <?php echo $alert_status === 'success' ? 'check_circle' : 'error'; ?>
            </i>
            <div>
                <strong><?php echo $alert_status === 'success' ? 'สำเร็จ!' : 'เกิดข้อผิดพลาด!'; ?></strong>
                <div><?php echo htmlspecialchars($alert_message ?? ''); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-container-card">
        <!-- Tabs Header -->
        <div class="form-tab-header">
            <button type="button" class="form-tab-btn active" id="tab-btn-source" onclick="switchAddTab('source')">
                <i class="material-icons">rss_feed</i> เพิ่มแหล่งข่าว RSS/Atom (Feed Source)
            </button>
            <button type="button" class="form-tab-btn" id="tab-btn-group" onclick="switchAddTab('group')">
                <i class="material-icons">create_new_folder</i> เพิ่มกลุ่มฟีดข่าว (Feed Group)
            </button>
        </div>

        <div class="form-tab-content">

            <!-- ============================================================== -->
            <!-- TAB 1: ADD FEED SOURCE (public.feed_sources)                   -->
            <!-- ============================================================== -->
            <div class="tab-panel active" id="tab-panel-source">
                <div class="form-heading-row">
                    <div>
                        <h2 class="form-heading-title">
                            <i class="material-icons" style="color: var(--primary);">playlist_add</i>
                            เพิ่มแหล่งข้อมูลข่าวสารใหม่ (New Feed Source)
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px;">
                            บันทึกลงตาราง <code>public.feed_sources</code> ใน Supabase เพื่อนำไปแสดงในแอปพลิเคชันข่าว
                        </p>
                    </div>
                    <span class="form-heading-badge">Table: public.feed_sources</span>
                </div>

                <form method="POST" action="index.php#add-section" id="form-add-source">
                    <input type="hidden" name="action" value="add_source">

                    <div class="form-grid-2">
                        <!-- Label -->
                        <div class="form-group">
                            <label class="form-label" for="src_label">
                                ชื่อแหล่งข่าว (Label) <span class="req">*</span>
                            </label>
                            <input type="text" id="src_label" name="label" class="form-input" 
                                   placeholder="เช่น Blognone, TechCrunch, THE STANDARD" 
                                   required oninput="updateSourcePreview()">
                            <span class="form-hint">ชื่อแสดงผลของสำนักข่าวหรือเว็บไซต์</span>
                        </div>

                        <!-- Key -->
                        <div class="form-group">
                            <label class="form-label" for="src_key">
                                รหัสอ้างอิงแหล่งข่าว (Key) <span class="req">*</span>
                            </label>
                            <input type="text" id="src_key" name="key" class="form-input" 
                                   placeholder="เช่น blognone, techcrunch (ภาษาอังกฤษตัวเล็ก)" 
                                   pattern="[a-zA-Z0-9_\-]+" oninput="updateSourcePreview()">
                            <span class="form-hint">ต้องไม่ซ้ำกัน (ระบบจะสร้างให้อัตโนมัติหากเว้นว่าง)</span>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <!-- Feed URL -->
                        <div class="form-group">
                            <label class="form-label" for="src_url">
                                RSS / Atom Feed URL <span class="req">*</span>
                            </label>
                            <input type="url" id="src_url" name="url" class="form-input" 
                                   placeholder="https://www.blognone.com/atom.xml" 
                                   required oninput="handleUrlInput(this.value)">
                            <span class="form-hint">ที่อยู่ URL ของฟีด XML/RSS/Atom ที่ดึงข่าวได้</span>
                        </div>

                        <!-- Homepage URL -->
                        <div class="form-group">
                            <label class="form-label" for="src_homepage">
                                เว็บไซต์หน้าแรก (Homepage URL)
                            </label>
                            <input type="url" id="src_homepage" name="homepage" class="form-input" 
                                   placeholder="https://www.blognone.com" oninput="updateSourcePreview()">
                            <span class="form-hint">ลิงก์หน้าหลักสำนักข่าวเพื่อให้ผู้อ่านเข้าชม</span>
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <!-- Group Selection -->
                        <div class="form-group">
                            <label class="form-label" for="src_group">
                                กลุ่มข่าวสาร (Feed Group)
                            </label>
                            <select id="src_group" name="group_key" class="form-select" onchange="updateSourcePreview()">
                                <option value="">-- ไม่ระบุกลุ่ม (Unassigned) --</option>
                                <?php foreach (($groups ?? []) as $grp): ?>
                                    <option value="<?php echo htmlspecialchars($grp['key']); ?>" 
                                            data-color="<?php echo htmlspecialchars($grp['color'] ?? '#2F6FED'); ?>"
                                            data-label="<?php echo htmlspecialchars($grp['label']); ?>">
                                        <?php echo htmlspecialchars($grp['label']); ?> (<?php echo htmlspecialchars($grp['key']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="form-hint">จัดหมวดหมู่อ้างอิงตาราง feed_groups</span>
                        </div>

                        <!-- Feed Type -->
                        <div class="form-group">
                            <label class="form-label" for="src_type">
                                ประเภทฟีด (Feed Type)
                            </label>
                            <select id="src_type" name="type" class="form-select" onchange="updateSourcePreview()">
                                <option value="rss" selected>RSS Feed (มาตรฐาน)</option>
                                <option value="atom">Atom Feed (XML)</option>
                                <option value="json">JSON Feed</option>
                                <option value="api">Custom API</option>
                            </select>
                            <span class="form-hint">รูปแบบข้อมูลฟีดของแหล่งข่าว</span>
                        </div>

                        <!-- Order Index -->
                        <div class="form-group">
                            <label class="form-label" for="src_order">
                                ลำดับการแสดงผล (Order Index)
                            </label>
                            <input type="number" id="src_order" name="order_index" class="form-input" 
                                   value="<?php echo $nextSourceOrder; ?>" min="0">
                            <span class="form-hint">ค่าน้อยจะแสดงก่อน (เรียงจาก 0, 1, 2...)</span>
                        </div>
                    </div>

                    <!-- Active Toggle -->
                    <div class="form-group" style="margin-top: 6px;">
                        <label class="switch-wrap">
                            <span class="switch">
                                <input type="checkbox" name="is_active" id="src_is_active" value="1" checked>
                                <span class="slider"></span>
                            </span>
                            <span style="font-weight: 600; font-size: 0.92rem; color: var(--secondary);">
                                เปิดใช้งานแหล่งข่าวนี้ทันที (Active Status)
                            </span>
                        </label>
                    </div>

                    <!-- Live Preview -->
                    <div class="preview-box" id="source-preview-box">
                        <div>
                            <span class="preview-label">ตัวอย่างการแสดงผล (Live Preview):</span>
                            <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px; flex-wrap: wrap;">
                                <span id="preview-src-name" style="font-weight: 700; font-size: 1.05rem; color: var(--secondary);">
                                    ชื่อแหล่งข่าว
                                </span>
                                <span id="preview-src-key" style="background: #e2e8f0; color: #475569; font-size: 0.76rem; padding: 2px 8px; border-radius: 4px; font-family: monospace;">
                                    key
                                </span>
                                <span id="preview-src-group" style="background: #2F6FED; color: #ffffff; font-size: 0.78rem; padding: 2px 10px; border-radius: 999px; font-weight: 500;">
                                    กลุ่มข่าว
                                </span>
                                <span id="preview-src-type" style="background: #e0f2fe; color: #0369a1; font-size: 0.76rem; padding: 2px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase;">
                                    RSS
                                </span>
                            </div>
                        </div>
                        <div id="preview-src-url" style="font-size: 0.8rem; color: #64748b; font-family: monospace;">
                            https://example.com/feed
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <button type="submit" name="btnAddSource" class="btn-submit" id="btn-submit-source">
                            <i class="material-icons">cloud_upload</i> บันทึกแหล่งข่าวลง Supabase
                        </button>
                    </div>
                </form>
            </div>


            <!-- ============================================================== -->
            <!-- TAB 2: ADD FEED GROUP (public.feed_groups)                     -->
            <!-- ============================================================== -->
            <div class="tab-panel" id="tab-panel-group">
                <div class="form-heading-row">
                    <div>
                        <h2 class="form-heading-title">
                            <i class="material-icons" style="color: #10b981;">create_new_folder</i>
                            เพิ่มกลุ่มฟีดข่าวสารใหม่ (New Feed Group)
                        </h2>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px;">
                            บันทึกลงตาราง <code>public.feed_groups</code> ใน Supabase เพื่อใช้จัดหมวดหมู่แหล่งข่าว
                        </p>
                    </div>
                    <span class="form-heading-badge" style="background: #ecfdf5; color: #059669; border-color: #a7f3d0;">
                        Table: public.feed_groups
                    </span>
                </div>

                <form method="POST" action="index.php#add-section" id="form-add-group">
                    <input type="hidden" name="action" value="add_group">

                    <div class="form-grid-2">
                        <!-- Group Label -->
                        <div class="form-group">
                            <label class="form-label" for="grp_label">
                                ชื่อกลุ่มข่าว / หมวดหมู่ (Label) <span class="req">*</span>
                            </label>
                            <input type="text" id="grp_label" name="label" class="form-input" 
                                   placeholder="เช่น ปัญญาประดิษฐ์และ AI, ข่าวยานยนต์ EV" 
                                   required oninput="updateGroupPreview()">
                            <span class="form-hint">ชื่อภาษาไทยสำหรับแสดงผลบนแถบหมวดหมู่ในแอป</span>
                        </div>

                        <!-- Group Key -->
                        <div class="form-group">
                            <label class="form-label" for="grp_key">
                                รหัสกลุ่ม (Key) <span class="req">*</span>
                            </label>
                            <input type="text" id="grp_key" name="key" class="form-input" 
                                   placeholder="เช่น ai-innovation, ev-vehicles" 
                                   pattern="[a-zA-Z0-9_\-]+" oninput="updateGroupPreview()">
                            <span class="form-hint">รหัสภาษาอังกฤษตัวเล็กใช้เชื่อมกับ feed_sources.group_key</span>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <!-- Group Color -->
                        <div class="form-group">
                            <label class="form-label" for="grp_color">
                                ธีมสีประจำกลุ่ม (Badge Color) <span class="req">*</span>
                            </label>
                            <div class="color-picker-wrap">
                                <input type="color" id="grp_color" name="color" class="color-input" 
                                       value="#2F6FED" oninput="syncColorCode(this.value)">
                                <input type="text" id="grp_color_text" class="form-input" 
                                       value="#2F6FED" style="max-width: 140px; font-family: monospace;" 
                                       oninput="syncColorPicker(this.value)">
                            </div>
                            <div class="palette-chips">
                                <span class="color-chip" style="background:#2F6FED;" onclick="selectColor('#2F6FED')" title="Tech Blue"></span>
                                <span class="color-chip" style="background:#0284C7;" onclick="selectColor('#0284C7')" title="Sky Blue"></span>
                                <span class="color-chip" style="background:#D946EF;" onclick="selectColor('#D946EF')" title="Magenta"></span>
                                <span class="color-chip" style="background:#17A673;" onclick="selectColor('#17A673')" title="Green"></span>
                                <span class="color-chip" style="background:#F59E0B;" onclick="selectColor('#F59E0B')" title="Amber"></span>
                                <span class="color-chip" style="background:#8B5CF6;" onclick="selectColor('#8B5CF6')" title="Purple"></span>
                                <span class="color-chip" style="background:#EF4444;" onclick="selectColor('#EF4444')" title="Red"></span>
                                <span class="color-chip" style="background:#10B981;" onclick="selectColor('#10B981')" title="Emerald"></span>
                                <span class="color-chip" style="background:#6366F1;" onclick="selectColor('#6366F1')" title="Indigo"></span>
                                <span class="color-chip" style="background:#06B6D4;" onclick="selectColor('#06B6D4')" title="Cyan"></span>
                            </div>
                            <span class="form-hint">สีแท็กของกลุ่มฟีดตามโครงสร้าง color ใน schema.sql</span>
                        </div>

                        <!-- Group Order Index -->
                        <div class="form-group">
                            <label class="form-label" for="grp_order">
                                ลำดับการจัดเรียง (Order Index)
                            </label>
                            <input type="number" id="grp_order" name="order_index" class="form-input" 
                                   value="<?php echo $nextGroupOrder; ?>" min="0">
                            <span class="form-hint">ลำดับที่แสดงบนแท็บหมวดหมู่ (เช่น 1, 2, 3...)</span>
                        </div>
                    </div>

                    <!-- Live Preview Group -->
                    <div class="preview-box" id="group-preview-box">
                        <div>
                            <span class="preview-label">ตัวอย่างป้ายกลุ่มฟีด (Badge Preview):</span>
                            <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px;">
                                <span id="preview-grp-pill" style="background: #2F6FED; color: #ffffff; padding: 6px 16px; border-radius: 999px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                    ชื่อกลุ่มข่าว
                                </span>
                                <span id="preview-grp-key" style="background: #e2e8f0; color: #475569; font-size: 0.8rem; padding: 3px 8px; border-radius: 4px; font-family: monospace;">
                                    group-key
                                </span>
                            </div>
                        </div>
                        <div style="font-size: 0.82rem; color: #64748b;">
                            บันทึกลงตาราง <strong>public.feed_groups</strong>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                        <button type="submit" name="btnAddGroup" class="btn-submit" id="btn-submit-group" style="background: linear-gradient(135deg, #059669, #047857);">
                            <i class="material-icons">save</i> บันทึกกลุ่มฟีดลง Supabase
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Tab switching logic
    function switchAddTab(tab) {
        const btnSource = document.getElementById('tab-btn-source');
        const btnGroup  = document.getElementById('tab-btn-group');
        const panelSource = document.getElementById('tab-panel-source');
        const panelGroup  = document.getElementById('tab-panel-group');

        if (tab === 'group') {
            btnGroup?.classList.add('active');
            btnSource?.classList.remove('active');
            panelGroup?.classList.add('active');
            panelSource?.classList.remove('active');
        } else {
            btnSource?.classList.add('active');
            btnGroup?.classList.remove('active');
            panelSource?.classList.add('active');
            panelGroup?.classList.remove('active');
        }
    }

    // Auto extract homepage when URL is typed
    function handleUrlInput(url) {
        const homepageInput = document.getElementById('src_homepage');
        if (homepageInput && (!homepageInput.value || homepageInput.dataset.autofilled === 'true')) {
            try {
                const parsed = new URL(url);
                homepageInput.value = parsed.origin;
                homepageInput.dataset.autofilled = 'true';
            } catch(e) {}
        }
        updateSourcePreview();
    }

    // Live update source preview
    function updateSourcePreview() {
        const label = document.getElementById('src_label')?.value || 'ชื่อแหล่งข่าว';
        const key   = document.getElementById('src_key')?.value || 'key';
        const url   = document.getElementById('src_url')?.value || 'https://example.com/feed';
        const type  = document.getElementById('src_type')?.value || 'RSS';
        const groupSelect = document.getElementById('src_group');
        const selectedOption = groupSelect?.options[groupSelect.selectedIndex];

        const groupLabel = selectedOption?.dataset.label || 'กลุ่มข่าว';
        const groupColor = selectedOption?.dataset.color || '#2F6FED';

        const pName  = document.getElementById('preview-src-name');
        const pKey   = document.getElementById('preview-src-key');
        const pUrl   = document.getElementById('preview-src-url');
        const pType  = document.getElementById('preview-src-type');
        const pGroup = document.getElementById('preview-src-group');

        if (pName)  pName.textContent = label;
        if (pKey)   pKey.textContent = key;
        if (pUrl)   pUrl.textContent = url;
        if (pType)  pType.textContent = type.toUpperCase();
        if (pGroup) {
            pGroup.textContent = groupLabel;
            pGroup.style.backgroundColor = groupColor;
        }
    }

    // Color pickers sync
    function syncColorCode(color) {
        const textInput = document.getElementById('grp_color_text');
        if (textInput) textInput.value = color;
        updateGroupPreview();
    }

    function syncColorPicker(color) {
        if (/^#[0-9a-fA-F]{6}$/.test(color)) {
            const picker = document.getElementById('grp_color');
            if (picker) picker.value = color;
            updateGroupPreview();
        }
    }

    function selectColor(color) {
        const picker = document.getElementById('grp_color');
        const textInput = document.getElementById('grp_color_text');
        if (picker) picker.value = color;
        if (textInput) textInput.value = color;
        updateGroupPreview();
    }

    function updateGroupPreview() {
        const label = document.getElementById('grp_label')?.value || 'ชื่อกลุ่มข่าว';
        const key   = document.getElementById('grp_key')?.value || 'group-key';
        const color = document.getElementById('grp_color')?.value || '#2F6FED';

        const pPill = document.getElementById('preview-grp-pill');
        const pKey  = document.getElementById('preview-grp-key');

        if (pPill) {
            pPill.textContent = label;
            pPill.style.backgroundColor = color;
        }
        if (pKey) pKey.textContent = key;
    }

    // Initialize previews and tab states on load
    document.addEventListener('DOMContentLoaded', function() {
        updateSourcePreview();
        updateGroupPreview();

        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        const hash = window.location.hash;

        if (tabParam === 'group' || hash === '#group' || hash === '#add-group') {
            switchAddTab('group');
        } else if (tabParam === 'add' || tabParam === 'source' || hash === '#add-source') {
            switchAddTab('source');
        }

        if (tabParam === 'add' || hash.includes('add')) {
            const addSec = document.getElementById('add-section');
            if (addSec) {
                addSec.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
