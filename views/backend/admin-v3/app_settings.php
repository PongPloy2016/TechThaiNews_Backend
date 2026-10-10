<?php
/**
 * View: App Settings (Supabase Connection & Remote Configuration)
 * Path: views/backend/admin-v3/app_settings.php
 */

$s = $settings ?? [];
$groups = $news_groups ?? [];

// Helper safe accessor
$val = function($key, $default = '') use ($s) {
    return isset($s[$key]) ? $s[$key] : $default;
};

$bool = function($key, $default = false) use ($s) {
    if (!isset($s[$key])) return $default;
    return $s[$key] === true || $s[$key] === 1 || $s[$key] === '1' || $s[$key] === 'true';
};
?>

<style>
/* Modern App Settings Control Hub Styles */
:root {
    --cfg-primary: #1e40af;
    --cfg-primary-dark: #0f172a;
    --cfg-accent: #3b82f6;
    --cfg-emerald: #10b981;
    --cfg-amber: #f59e0b;
    --cfg-rose: #ef4444;
    --cfg-purple: #8b5cf6;
    --cfg-slate: #1e293b;
    --cfg-border: #e2e8f0;
    --cfg-bg-light: #f8fafc;
}

.settings-header-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%);
    border-radius: 20px;
    padding: 30px 34px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.settings-header-banner::before {
    content: '⚡';
    font-size: 150px;
    position: absolute;
    right: 25px;
    bottom: -45px;
    opacity: 0.08;
    pointer-events: none;
}
.settings-header-banner h2 {
    margin: 0 0 8px 0;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.5px;
    color: #ffffff;
}
.settings-header-banner p {
    margin: 0;
    font-size: 14px;
    color: #94a3b8;
    line-height: 1.5;
}

.supabase-badge-live {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(16, 185, 129, 0.18);
    border: 1px solid rgba(16, 185, 129, 0.35);
    color: #6ee7b7;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.pulse-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulse-green 1.8s infinite;
}
@keyframes pulse-green {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* Tab Navigation */
.settings-nav-tabs {
    display: flex;
    gap: 8px;
    background: #ffffff;
    padding: 10px;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    margin-bottom: 25px;
    overflow-x: auto;
    white-space: nowrap;
    scrollbar-width: thin;
}
.settings-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 10px 18px;
    border-radius: 12px;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.settings-tab-btn i {
    font-size: 17px;
}
.settings-tab-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.settings-tab-btn.active {
    background: linear-gradient(135deg, #1e40af, #2563eb);
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
}
.settings-tab-btn.active i {
    color: #ffffff;
}

/* Card Sections */
.settings-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 26px 30px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
    transition: all 0.25s ease;
}
.settings-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 24px rgba(0,0,0,0.05);
}
.settings-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 22px;
    border-bottom: 1px solid #f1f5f9;
}
.settings-card-title {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}
.settings-card-title i {
    font-size: 20px;
    color: #2563eb;
}
.settings-card-desc {
    margin: 3px 0 0 0;
    font-size: 12.5px;
    color: #64748b;
    font-weight: 500;
}

/* Custom Switch Toggle (Pure CSS) */
.switch-label-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 16px 20px;
    border-radius: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
    margin-bottom: 16px;
}
.switch-label-wrap:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}
.switch-info h4 {
    margin: 0 0 3px 0;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.switch-info p {
    margin: 0;
    font-size: 12px;
    color: #64748b;
    line-height: 1.4;
}
.custom-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}
.custom-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.custom-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 34px;
}
.custom-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
input:checked + .custom-slider {
    background: linear-gradient(135deg, #10b981, #059669);
}
input:checked + .custom-slider:before {
    transform: translateX(22px);
}

/* Form Controls */
.form-group-custom {
    margin-bottom: 20px;
}
.form-group-custom label {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 7px;
    display: block;
}
.form-group-custom .help-text {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 5px;
    display: block;
    line-height: 1.4;
}
.form-control-custom {
    width: 100%;
    padding: 10px 14px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background-color: #ffffff;
    font-size: 13.5px;
    font-weight: 500;
    color: #0f172a;
    transition: all 0.2s ease;
    box-shadow: none;
}
.form-control-custom:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
.input-with-button {
    display: flex;
    gap: 8px;
}

/* Save Bottom Action Bar */
.settings-sticky-footer {
    position: sticky;
    bottom: 20px;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 18px;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    z-index: 99;
    margin-top: 30px;
}
.btn-save-primary {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    font-weight: 800;
    font-size: 14px;
    padding: 11px 26px;
    border-radius: 12px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    transition: all 0.2s ease;
}
.btn-save-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
    color: #ffffff;
}

/* SQL Code Preview */
.code-preview-box {
    background: #0f172a;
    color: #e2e8f0;
    border-radius: 14px;
    padding: 18px 20px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 12px;
    line-height: 1.6;
    overflow-x: auto;
    border: 1px solid #1e293b;
    position: relative;
}
.code-copy-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255,255,255,0.1);
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.2);
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11px;
    cursor: pointer;
    font-weight: 700;
    transition: all 0.2s ease;
}
.code-copy-btn:hover {
    background: rgba(255,255,255,0.25);
}

/* Tab Panels */
.tab-content-panel {
    display: none;
    animation: fadeInTab 0.25s ease;
}
.tab-content-panel.active {
    display: block;
}
@keyframes fadeInTab {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

.range-slider-wrap {
    display: flex;
    align-items: center;
    gap: 15px;
}
.range-val-badge {
    background: #1e293b;
    color: #38bdf8;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 800;
    font-size: 13px;
    min-width: 55px;
    text-align: center;
}
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content" style="padding: 22px 28px;">

        <!-- Flash Session Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(16,185,129,0.15); margin-bottom: 22px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(239,68,68,0.15); margin-bottom: 22px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- 🚀 Hero Banner -->
        <div class="settings-header-banner">
            <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span class="supabase-badge-live">
                            <span class="pulse-indicator"></span>
                            Supabase Connected (PostgreSQL REST API)
                        </span>
                        <span class="badge" style="background: rgba(255,255,255,0.15); font-weight: 600; font-size: 11px; padding: 4px 10px; border-radius: 20px;">
                            Table: public.app_settings | ID: default
                        </span>
                    </div>
                    <h2>⚙️ ศูนย์ตั้งค่าแอปพลิเคชัน & ฐานข้อมูล Supabase</h2>
                    <p>
                        ควบคุมฟังก์ชันโมบายล์แอปพลิเคชันแบบ Remote Config เรียลไทม์: ระบบ AI สรุปข่าว, เสียงอ่าน (TTS), ข่าวด่วน Breaking News, โฆษณา AdMob, บังคับอัปเดต และโหมดปิดปรับปรุงระบบ
                    </p>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <div style="font-size: 11.5px; color: #94a3b8; margin-bottom: 8px;">
                        <i class="fa fa-clock-o"></i> อัปเดตล่าสุด:
                        <strong style="color: #ffffff;"><?= !empty($s['updated_at']) ? date("d/m/Y H:i:s", strtotime($s['updated_at'])) : 'ยังไม่มีประวัติ' ?></strong>
                    </div>
                    <button type="button" onclick="document.getElementById('appSettingsForm').submit();" class="btn-save-primary">
                        <i class="fa fa-save"></i> บันทึกการตั้งค่า
                    </button>
                </div>
            </div>
        </div>

        <!-- 📑 Tab Navigation Menu -->
        <div class="settings-nav-tabs">
            <button type="button" class="settings-tab-btn active" data-tab="tab-ai">
                <i class="mdi mdi-robot"></i> 🤖 AI & เสียงอ่าน (TTS)
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-breaking">
                <i class="mdi mdi-bullhorn"></i> 🚨 ข่าวด่วน & ประกาศ
                <?php if ($bool('breaking_news_active')): ?>
                    <span class="badge" style="background: #ef4444; font-size: 9px; padding: 2px 6px;">LIVE</span>
                <?php endif; ?>
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-ads">
                <i class="mdi mdi-google-ads"></i> 💰 โฆษณา AdMob
                <?php if ($bool('ads_enabled')): ?>
                    <span class="badge" style="background: #10b981; font-size: 9px; padding: 2px 6px;">ON</span>
                <?php endif; ?>
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-version">
                <i class="mdi mdi-cellphone-arrow-down"></i> 📲 อัปเดต & ปิดปรับปรุง
                <?php if ($bool('is_maintenance')): ?>
                    <span class="badge" style="background: #f59e0b; font-size: 9px; padding: 2px 6px;">MAINT</span>
                <?php endif; ?>
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-display">
                <i class="mdi mdi-palette-outline"></i> 🎨 การแสดงผล & ฟีด
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-feed-groups">
                <i class="mdi mdi-view-grid-plus-outline"></i> 📁 หมวดหมู่ข่าว (Feed Groups)
                <span class="badge" style="background: #2563eb; font-size: 9px; padding: 2px 6px;"><?= count($groups ?? []) ?></span>
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-legal">
                <i class="mdi mdi-shield-check-outline"></i> ⚖️ นโยบาย & ติดต่อ
            </button>
            <button type="button" class="settings-tab-btn" data-tab="tab-developer">
                <i class="mdi mdi-code-braces"></i> 🛠️ Supabase SQL & API
            </button>
        </div>

        <!-- 📝 Main Form -->
        <form action="<?= admin_url("app_settings") ?>" method="POST" id="appSettingsForm">
            <input type="hidden" name="save_app_settings" value="1">
            <input type="hidden" name="id" value="<?= htmlspecialchars($val('id', 'default')) ?>">

            <!-- ==========================================
                 TAB 1: AI & TTS
                 ========================================== -->
            <div id="tab-ai" class="tab-content-panel active">
                <div class="row">
                    <div class="col-md-7">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-robot"></i> ระบบประมวลผล AI อัตโนมัติ (AI News Summary)</h3>
                                    <p class="settings-card-desc">ควบคุมการเปิดใช้งาน Google Gemini เพื่อสรุปใจความสำคัญและวิเคราะห์ข่าวสาร</p>
                                </div>
                            </div>

                            <!-- is_ai_enabled -->
                            <label class="switch-label-wrap">
                                <div class="switch-info">
                                    <h4>เปิดใช้งานฟังก์ชัน AI สรุปข่าวในแอป</h4>
                                    <p>อนุญาตให้ผู้ใช้งานกดปุ่ม "AI สรุปข่าว" เพื่ออ่านประเด็นสำคัญแบบกระชับ</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="is_ai_enabled" value="1" <?= $bool('is_ai_enabled', true) ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- ai_model_name -->
                            <div class="form-group-custom">
                                <label for="ai_model_name">โมเดล AI ที่ใช้งาน (Model Name)</label>
                                <select name="ai_model_name" id="ai_model_name" class="form-control-custom">
                                    <option value="gemini-1.5-flash" <?= $val('ai_model_name') == 'gemini-1.5-flash' ? 'selected' : '' ?>>Gemini 1.5 Flash (เร็วที่สุด & คุ้มค่าที่สุด - แนะนำ)</option>
                                    <option value="gemini-1.5-pro" <?= $val('ai_model_name') == 'gemini-1.5-pro' ? 'selected' : '' ?>>Gemini 1.5 Pro (วิเคราะห์ระดับสูง)</option>
                                    <option value="gemini-2.0-flash-exp" <?= $val('ai_model_name') == 'gemini-2.0-flash-exp' ? 'selected' : '' ?>>Gemini 2.0 Flash (Experimental)</option>
                                    <option value="gpt-4o-mini" <?= $val('ai_model_name') == 'gpt-4o-mini' ? 'selected' : '' ?>>OpenAI GPT-4o Mini</option>
                                </select>
                                <span class="help-text">แอปพลิเคชันจะใช้ Model Identifier นี้ในการส่ง Request เข้าสู่ระบบ AI API</span>
                            </div>

                            <!-- ai_max_tokens -->
                            <div class="form-group-custom">
                                <label for="ai_max_tokens">จำนวน Token สูงสุดต่อการสรุป (Max Output Tokens)</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" name="ai_max_tokens" id="ai_max_tokens" class="form-control-custom" min="100" max="4000" step="50" value="<?= (int)$val('ai_max_tokens', 400) ?>">
                                    <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-weight: 700; border-radius: 10px;">Tokens</span>
                                </div>
                                <span class="help-text">แนะนำ: 300 - 600 Tokens เพื่อการสรุปข่าว 3-4 บรรทัดที่ไม่ยาวจนเกินไป</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-volume-high"></i> เสียงอ่านข่าวอัตโนมัติ (Text-to-Speech)</h3>
                                    <p class="settings-card-desc">ความเร็วและระดับเสียงเริ่มต้นในการอ่านเนื้อหาข่าวให้ผู้ใช้ฟัง</p>
                                </div>
                            </div>

                            <!-- tts_default_rate -->
                            <div class="form-group-custom">
                                <label for="tts_default_rate">ความเร็วเสียงอ่านเริ่มต้น (Speech Rate)</label>
                                <div class="range-slider-wrap">
                                    <input type="range" name="tts_default_rate" id="tts_default_rate" min="0.5" max="1.5" step="0.05" value="<?= (float)$val('tts_default_rate', 0.95) ?>" oninput="document.getElementById('ttsRateBadge').innerText = this.value + 'x';" style="flex: 1; cursor: pointer;">
                                    <span class="range-val-badge" id="ttsRateBadge"><?= number_format((float)$val('tts_default_rate', 0.95), 2) ?>x</span>
                                </div>
                                <span class="help-text">ค่ามาตรฐาน 0.95x - 1.00x เหมาะสำหรับภาษาไทย ฟังง่าย ชัดเจน ไม่เร็วเกินไป</span>
                            </div>

                            <div style="background: #f1f5f9; border-radius: 12px; padding: 16px; margin-top: 15px; border-left: 4px solid #2563eb;">
                                <strong style="font-size: 13px; color: #1e293b;"><i class="fa fa-info-circle"></i> ข้อแนะนำระบบเสียง (TTS):</strong>
                                <p style="font-size: 12px; color: #475569; margin: 4px 0 0 0;">
                                    หากผู้ใช้งานเปิดฟังข่าวขณะขับรถหรือเดินทาง แอปจะดึงค่าอัตรานี้เป็นค่าตั้งต้น โดยผู้ใช้สามารถเร่งหรือลดความเร็วเองได้ในหน้าอ่านข่าว
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 2: BREAKING NEWS & ANNOUNCEMENTS
                 ========================================== -->
            <div id="tab-breaking" class="tab-content-panel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-flash-alert"></i> แถบข่าวด่วนฉุกเฉิน (Breaking News Ticker)</h3>
                                    <p class="settings-card-desc">แสดงแถบสีแดงกระพริบที่ด้านบนสุดของแอปสำหรับเหตุการณ์สำคัญเร่งด่วน</p>
                                </div>
                            </div>

                            <!-- breaking_news_active -->
                            <label class="switch-label-wrap" style="<?= $bool('breaking_news_active') ? 'border-color: #ef4444; background: #fef2f2;' : '' ?>">
                                <div class="switch-info">
                                    <h4 style="<?= $bool('breaking_news_active') ? 'color: #b91c1c;' : '' ?>">เปิดแถบข่าวด่วน (Live Breaking Bar)</h4>
                                    <p>เมื่อเปิดใช้งาน แถบข่าวด่วนจะแสดงผลทันทีบนหน้าแรกของแอป</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="breaking_news_active" value="1" <?= $bool('breaking_news_active') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- breaking_news_text -->
                            <div class="form-group-custom">
                                <label for="breaking_news_text">ข้อความพาดหัวข่าวด่วน</label>
                                <textarea name="breaking_news_text" id="breaking_news_text" rows="3" class="form-control-custom" placeholder="เช่น ด่วน! อัปเดตนโยบายเทคโนโลยีใหม่ หรือเหตุการณ์สำคัญเร่งด่วน..."><?= htmlspecialchars($val('breaking_news_text')) ?></textarea>
                                <span class="help-text">ข้อความสั้นกระชับที่ต้องการให้เลื่อนผ่านหน้าจอ</span>
                            </div>

                            <!-- breaking_news_url -->
                            <div class="form-group-custom">
                                <label for="breaking_news_url">ลิงก์เนื้อหาข่าวด่วน (เมื่อคลิกที่แถบ)</label>
                                <div class="input-with-button">
                                    <input type="url" name="breaking_news_url" id="breaking_news_url" class="form-control-custom" placeholder="https://..." value="<?= htmlspecialchars($val('breaking_news_url')) ?>">
                                    <?php if (!empty($val('breaking_news_url'))): ?>
                                        <a href="<?= htmlspecialchars($val('breaking_news_url')) ?>" target="_blank" class="btn btn-default" style="border-radius: 12px;" title="เปิดดูลิงก์"><i class="fa fa-external-link"></i></a>
                                    <?php endif; ?>
                                </div>
                                <span class="help-text">ลิงก์ข่าวฉบับเต็ม หรือปล่อยว่างไว้หากไม่มีลิงก์</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-bullhorn"></i> ป๊อปอัปประกาศสำคัญจากแอดมิน (Announcement Modal)</h3>
                                    <p class="settings-card-desc">แสดงกล่องแจ้งข่าวสารหรือกิจกรรมให้ผู้ใช้ทราบเมื่อเปิดแอป</p>
                                </div>
                            </div>

                            <!-- announcement_active -->
                            <label class="switch-label-wrap">
                                <div class="switch-info">
                                    <h4>เปิดใช้งานการประกาศ (Active Modal)</h4>
                                    <p>จะแสดงป๊อปอัปแจ้งเตือน 1 ครั้งเมื่อเปิดแอปพลิเคชัน</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="announcement_active" value="1" <?= $bool('announcement_active') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- announcement_message -->
                            <div class="form-group-custom">
                                <label for="announcement_message">เนื้อหาข้อความประกาศ</label>
                                <textarea name="announcement_message" id="announcement_message" rows="6" class="form-control-custom" placeholder="ระบุข้อความประกาศแจ้งให้สมาชิกและผู้ใช้งานทราบ..."><?= htmlspecialchars($val('announcement_message')) ?></textarea>
                                <span class="help-text">สามารถแจ้งเตือนการอัปเดตฟีเจอร์ กิจกรรม หรือข้อมูลจากทีมงาน</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 3: ADMOB ADS MONETIZATION
                 ========================================== -->
            <div id="tab-ads" class="tab-content-panel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-google-ads"></i> การควบคุมโฆษณา Google AdMob</h3>
                                    <p class="settings-card-desc">สวิตช์เปิด-ปิดโฆษณาประเภทต่างๆ ในแอปพลิเคชัน</p>
                                </div>
                            </div>

                            <!-- ads_enabled -->
                            <label class="switch-label-wrap" style="<?= $bool('ads_enabled') ? 'border-color: #10b981; background: #ecfdf5;' : '' ?>">
                                <div class="switch-info">
                                    <h4 style="<?= $bool('ads_enabled') ? 'color: #047857;' : '' ?>">เปิดระบบโฆษณาโดยรวม (Master Ads Switch)</h4>
                                    <p>หากปิดสวิตช์นี้ โฆษณาทุกตำแหน่งในแอปพลิเคชันจะถูกปิดทันที</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="ads_enabled" value="1" <?= $bool('ads_enabled') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- banner_ads_enabled -->
                            <label class="switch-label-wrap">
                                <div class="switch-info">
                                    <h4>โฆษณาแบนเนอร์ด้านล่าง (Banner Ads)</h4>
                                    <p>แสดงแถบโฆษณาขนาดเล็กบริเวณขอบล่างของหน้าจอ</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="banner_ads_enabled" value="1" <?= $bool('banner_ads_enabled') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- interstitial_ads_enabled -->
                            <label class="switch-label-wrap">
                                <div class="switch-info">
                                    <h4>โฆษณาคั่นเต็มหน้าจอ (Interstitial Ads)</h4>
                                    <p>แสดงโฆษณาคั่นระหว่างการเปลี่ยนหน้าอ่านข่าว</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="interstitial_ads_enabled" value="1" <?= $bool('interstitial_ads_enabled') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- interstitial_interval_clicks -->
                                    <div class="form-group-custom">
                                        <label for="interstitial_interval_clicks">ความถี่แสดงผล (ทุกๆ กี่คลิก)</label>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <input type="number" name="interstitial_interval_clicks" id="interstitial_interval_clicks" class="form-control-custom" min="1" max="20" value="<?= (int)$val('interstitial_interval_clicks', 4) ?>">
                                            <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-weight: 700; border-radius: 10px;">คลิก</span>
                                        </div>
                                        <span class="help-text">แนะนำ: 4-6 คลิกเพื่อไม่ให้รบกวนผู้ใช้งานมากเกินไป</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- interstitial_min_delay_sec -->
                                    <div class="form-group-custom">
                                        <label for="interstitial_min_delay_sec">ระยะเวลาหน่วงขั้นต่ำ (วินาที)</label>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <input type="number" name="interstitial_min_delay_sec" id="interstitial_min_delay_sec" class="form-control-custom" min="10" max="300" step="5" value="<?= (int)$val('interstitial_min_delay_sec', 60) ?>">
                                            <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-weight: 700; border-radius: 10px;">วินาที</span>
                                        </div>
                                        <span class="help-text">ป้องกันโฆษณาเด้งติดๆ กัน (แนะนำ 60 วินาที)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-identifier"></i> Ad Unit IDs (Android)</h3>
                                    <p class="settings-card-desc">รหัสหน่วยโฆษณาจาก Google AdMob Console สำหรับ Android</p>
                                </div>
                            </div>

                            <!-- admob_banner_id_android -->
                            <div class="form-group-custom">
                                <label for="admob_banner_id_android">AdMob Banner Unit ID (Android)</label>
                                <input type="text" name="admob_banner_id_android" id="admob_banner_id_android" class="form-control-custom" placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy" value="<?= htmlspecialchars($val('admob_banner_id_android')) ?>">
                                <span class="help-text">ตัวอย่าง Test ID: <code>ca-app-pub-3940256099942544/6300978111</code></span>
                            </div>

                            <!-- admob_interstitial_id_android -->
                            <div class="form-group-custom">
                                <label for="admob_interstitial_id_android">AdMob Interstitial Unit ID (Android)</label>
                                <input type="text" name="admob_interstitial_id_android" id="admob_interstitial_id_android" class="form-control-custom" placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy" value="<?= htmlspecialchars($val('admob_interstitial_id_android')) ?>">
                                <span class="help-text">ตัวอย่าง Test ID: <code>ca-app-pub-3940256099942544/1033173712</code></span>
                            </div>

                            <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 16px; margin-top: 15px;">
                                <strong style="color: #854d0e; font-size: 13px;"><i class="fa fa-lightbulb-o"></i> เคล็ดลับการตั้งค่า AdMob:</strong>
                                <p style="color: #a16207; font-size: 12px; margin: 4px 0 0 0; line-height: 1.5;">
                                    สามารถสลับเปิด-ปิดระบบโฆษณาได้ทันทีโดยไม่ต้องส่งอัปเดตแอปขึ้น Google Play Store ใหม่
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 4: VERSION CONTROL & MAINTENANCE
                 ========================================== -->
            <div id="tab-version" class="tab-content-panel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-alert-octagon"></i> โหมดปิดปรับปรุงระบบ (Maintenance Mode)</h3>
                                    <p class="settings-card-desc">บล็อกการเข้าใช้งานแอปชั่วคราวเมื่อต้องการอัปเกรดเซิร์ฟเวอร์</p>
                                </div>
                            </div>

                            <!-- is_maintenance -->
                            <label class="switch-label-wrap" style="<?= $bool('is_maintenance') ? 'border-color: #f59e0b; background: #fffbeb;' : '' ?>">
                                <div class="switch-info">
                                    <h4 style="<?= $bool('is_maintenance') ? 'color: #b45309;' : '' ?>">เปิดโหมดปิดปรับปรุงระบบ (Maintenance Mode)</h4>
                                    <p>เมื่อเปิด สมาชิกจะไม่สามารถเข้าดูข่าวได้ และจะเห็นหน้าแจ้งปรับปรุง</p>
                                </div>
                                <div class="custom-switch">
                                    <input type="checkbox" name="is_maintenance" value="1" <?= $bool('is_maintenance') ? 'checked' : '' ?>>
                                    <span class="custom-slider"></span>
                                </div>
                            </label>

                            <!-- maintenance_message -->
                            <div class="form-group-custom">
                                <label for="maintenance_message">ข้อความแจ้งเตือนขณะปิดปรับปรุง</label>
                                <textarea name="maintenance_message" id="maintenance_message" rows="4" class="form-control-custom"><?= htmlspecialchars($val('maintenance_message', 'ระบบกำลังปิดปรับปรุงชั่วคราว ทีมงานกำลังเร่งแก้ไขครับ')) ?></textarea>
                                <span class="help-text">ข้อความนี้จะแสดงในหน้าจอ Maintenance ของแอปพลิเคชัน</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-cellphone-arrow-down"></i> บังคับอัปเดตแอป (Force App Update)</h3>
                                    <p class="settings-card-desc">ตรวจสอบเวอร์ชันของเครื่องผู้ใช้ และแจ้งให้อัปเดตจาก Google Play Store</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- min_supported_version -->
                                    <div class="form-group-custom">
                                        <label for="min_supported_version">เวอร์ชันต่ำสุดที่รองรับ (Min Supported)</label>
                                        <input type="text" name="min_supported_version" id="min_supported_version" class="form-control-custom" placeholder="1.0.0" value="<?= htmlspecialchars($val('min_supported_version', '1.0.0')) ?>">
                                        <span class="help-text">เวอร์ชันที่ต่ำกว่านี้จะถูกบังคับให้อัปเดต</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- latest_version -->
                                    <div class="form-group-custom">
                                        <label for="latest_version">เวอร์ชันล่าสุดบน Store (Latest Version)</label>
                                        <input type="text" name="latest_version" id="latest_version" class="form-control-custom" placeholder="1.0.0" value="<?= htmlspecialchars($val('latest_version', '1.0.0')) ?>">
                                        <span class="help-text">เวอร์ชันปัจจุบันที่เผยแพร่อยู่</span>
                                    </div>
                                </div>
                            </div>

                            <!-- force_update_title -->
                            <div class="form-group-custom">
                                <label for="force_update_title">หัวข้อแจ้งเตือนให้อัปเดต</label>
                                <input type="text" name="force_update_title" id="force_update_title" class="form-control-custom" value="<?= htmlspecialchars($val('force_update_title', 'มีเวอร์ชันใหม่พร้อมใช้งาน')) ?>">
                            </div>

                            <!-- force_update_message -->
                            <div class="form-group-custom">
                                <label for="force_update_message">รายละเอียดการอัปเดต</label>
                                <textarea name="force_update_message" id="force_update_message" rows="3" class="form-control-custom"><?= htmlspecialchars($val('force_update_message', 'กรุณาอัปเดตเป็นเวอร์ชันล่าสุดเพื่อการใช้งานที่ราบรื่นและปลอดภัย')) ?></textarea>
                            </div>

                            <!-- play_store_url -->
                            <div class="form-group-custom">
                                <label for="play_store_url">URL ลิงก์ Google Play Store</label>
                                <div class="input-with-button">
                                    <input type="url" name="play_store_url" id="play_store_url" class="form-control-custom" value="<?= htmlspecialchars($val('play_store_url')) ?>">
                                    <?php if (!empty($val('play_store_url'))): ?>
                                        <a href="<?= htmlspecialchars($val('play_store_url')) ?>" target="_blank" class="btn btn-default" style="border-radius: 12px;"><i class="fa fa-external-link"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 5: DISPLAY, LAYOUT & FEEDS
                 ========================================== -->
            <div id="tab-display" class="tab-content-panel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-view-dashboard-outline"></i> รูปแบบการแสดงผลเริ่มต้น (App UI Defaults)</h3>
                                    <p class="settings-card-desc">กำหนดดีไซน์และการแสดงผลเมื่อผู้ใช้งานเปิดแอปครั้งแรก</p>
                                </div>
                            </div>

                            <!-- default_feed_key -->
                            <div class="form-group-custom">
                                <label for="default_feed_key">หมวดหมู่ข่าวเริ่มต้น (Default Active Category)</label>
                                <select name="default_feed_key" id="default_feed_key" class="form-control-custom">
                                    <?php if (!empty($groups)): ?>
                                        <?php foreach ($groups as $gk => $gv): ?>
                                            <option value="<?= htmlspecialchars($gk) ?>" <?= $val('default_feed_key', 'tech-business') === $gk ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($gv['name_th'] ?? $gk) ?> (<?= htmlspecialchars($gk) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="tech-business" <?= $val('default_feed_key') === 'tech-business' ? 'selected' : '' ?>>เทคโนโลยี & ธุรกิจ (tech-business)</option>
                                        <option value="thai-news" <?= $val('default_feed_key') === 'thai-news' ? 'selected' : '' ?>>ข่าวเด่นประเทศไทย (thai-news)</option>
                                    <?php endif; ?>
                                </select>
                                <span class="help-text">หมวดข่าวที่จะถูกเปิดขึ้นมาเป็นแท็บแรกเมื่อเข้าแอป</span>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- default_card_layout -->
                                    <div class="form-group-custom">
                                        <label for="default_card_layout">รูปแบบการ์ดข่าว (Card Layout)</label>
                                        <select name="default_card_layout" id="default_card_layout" class="form-control-custom">
                                            <option value="magazine" <?= $val('default_card_layout', 'magazine') === 'magazine' ? 'selected' : '' ?>>Magazine (การ์ดใหญ่ รูปเด่น)</option>
                                            <option value="compact" <?= $val('default_card_layout') === 'compact' ? 'selected' : '' ?>>Compact (การ์ดเล็กกระทัดรัด)</option>
                                            <option value="grid" <?= $val('default_card_layout') === 'grid' ? 'selected' : '' ?>>Grid (ตาราง 2 คอลัมน์)</option>
                                            <option value="list" <?= $val('default_card_layout') === 'list' ? 'selected' : '' ?>>List (รายการแบบข้อความ)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- default_theme_mode -->
                                    <div class="form-group-custom">
                                        <label for="default_theme_mode">ธีมเริ่มต้น (Theme Mode)</label>
                                        <select name="default_theme_mode" id="default_theme_mode" class="form-control-custom">
                                            <option value="system" <?= $val('default_theme_mode', 'system') === 'system' ? 'selected' : '' ?>>ตามระบบเครื่อง (System)</option>
                                            <option value="dark" <?= $val('default_theme_mode') === 'dark' ? 'selected' : '' ?>>ธีมมืด (Dark Mode)</option>
                                            <option value="light" <?= $val('default_theme_mode') === 'light' ? 'selected' : '' ?>>ธีมสว่าง (Light Mode)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- default_link_open_mode -->
                                    <div class="form-group-custom">
                                        <label for="default_link_open_mode">การเปิดอ่านข่าวต้นฉบับ</label>
                                        <select name="default_link_open_mode" id="default_link_open_mode" class="form-control-custom">
                                            <option value="in_app" <?= $val('default_link_open_mode', 'in_app') === 'in_app' ? 'selected' : '' ?>>In-App Webview (เปิดในแอป)</option>
                                            <option value="external_browser" <?= $val('default_link_open_mode') === 'external_browser' ? 'selected' : '' ?>>เบราว์เซอร์ภายนอก (Chrome/Safari)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- default_font_size -->
                                    <div class="form-group-custom">
                                        <label for="default_font_size">ขนาดตัวอักษรเริ่มต้น</label>
                                        <select name="default_font_size" id="default_font_size" class="form-control-custom">
                                            <option value="small" <?= $val('default_font_size') === 'small' ? 'selected' : '' ?>>เล็ก (Small)</option>
                                            <option value="medium" <?= $val('default_font_size', 'medium') === 'medium' ? 'selected' : '' ?>>มาตรฐาน (Medium)</option>
                                            <option value="large" <?= $val('default_font_size') === 'large' ? 'selected' : '' ?>>ใหญ่ (Large)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-speedometer"></i> ประสิทธิภาพ & แหล่งฟีด (Feed & Cache)</h3>
                                    <p class="settings-card-desc">จัดการแคช ความเร็ว และแหล่ง RSS Feed หลัก</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- cache_ttl_minutes -->
                                    <div class="form-group-custom">
                                        <label for="cache_ttl_minutes">อายุแคชฟีดข่าว (Cache TTL)</label>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <input type="number" name="cache_ttl_minutes" id="cache_ttl_minutes" class="form-control-custom" min="1" max="1440" value="<?= (int)$val('cache_ttl_minutes', 15) ?>">
                                            <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-weight: 700; border-radius: 10px;">นาที</span>
                                        </div>
                                        <span class="help-text">แนะนำ 15-30 นาทีเพื่อความรวดเร็วและประหยัดเน็ตเวิร์ก</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- related_news_limit -->
                                    <div class="form-group-custom">
                                        <label for="related_news_limit">จำนวนข่าวที่เกี่ยวข้อง (Related Limit)</label>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <input type="number" name="related_news_limit" id="related_news_limit" class="form-control-custom" min="3" max="30" value="<?= (int)$val('related_news_limit', 10) ?>">
                                            <span class="badge" style="background: #e2e8f0; color: #334155; padding: 8px 12px; font-weight: 700; border-radius: 10px;">รายการ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- data_saver_default -->
                                    <label class="switch-label-wrap" style="padding: 12px 16px;">
                                        <div class="switch-info">
                                            <h4 style="font-size: 13px;">โหมดประหยัดดาต้าอินเทอร์เน็ต</h4>
                                            <p style="font-size: 11px;">บีบอัดรูปภาพและปิดการโหลดอัตโนมัติ</p>
                                        </div>
                                        <div class="custom-switch">
                                            <input type="checkbox" name="data_saver_default" value="1" <?= $bool('data_saver_default') ? 'checked' : '' ?>>
                                            <span class="custom-slider"></span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <!-- cloud_sync_enabled -->
                                    <label class="switch-label-wrap" style="padding: 12px 16px;">
                                        <div class="switch-info">
                                            <h4 style="font-size: 13px;">ซิงค์คลาวด์ (Cloud Sync)</h4>
                                            <p style="font-size: 11px;">ซิงค์บุ๊กมาร์กและประวัติกับเซิร์ฟเวอร์</p>
                                        </div>
                                        <div class="custom-switch">
                                            <input type="checkbox" name="cloud_sync_enabled" value="1" <?= $bool('cloud_sync_enabled', true) ? 'checked' : '' ?>>
                                            <span class="custom-slider"></span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- rss_source_name & url -->
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group-custom">
                                        <label for="rss_source_name">ชื่อสำนักข่าวตั้งต้น</label>
                                        <input type="text" name="rss_source_name" id="rss_source_name" class="form-control-custom" value="<?= htmlspecialchars($val('rss_source_name', 'Blognone')) ?>">
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group-custom">
                                        <label for="rss_source_url">URL เว็บไซต์สำนักข่าว</label>
                                        <input type="url" name="rss_source_url" id="rss_source_url" class="form-control-custom" value="<?= htmlspecialchars($val('rss_source_url', 'https://www.blognone.com')) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 6: LEGAL & CONTACT
                 ========================================== -->
            <div id="tab-legal" class="tab-content-panel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-shield-check"></i> นโยบายและความเป็นส่วนตัว (Legal & Compliance)</h3>
                                    <p class="settings-card-desc">ลิงก์นโยบายสำคัญสำหรับ Google Play Store และผู้ใช้งาน</p>
                                </div>
                            </div>

                            <!-- privacy_policy_url -->
                            <div class="form-group-custom">
                                <label for="privacy_policy_url">URL นโยบายความเป็นส่วนตัว (Privacy Policy)</label>
                                <div class="input-with-button">
                                    <input type="url" name="privacy_policy_url" id="privacy_policy_url" class="form-control-custom" value="<?= htmlspecialchars($val('privacy_policy_url', 'https://pongploydev.github.io/privacy-policy')) ?>">
                                    <?php if (!empty($val('privacy_policy_url'))): ?>
                                        <a href="<?= htmlspecialchars($val('privacy_policy_url')) ?>" target="_blank" class="btn btn-default" style="border-radius: 12px;"><i class="fa fa-external-link"></i></a>
                                    <?php endif; ?>
                                </div>
                                <span class="help-text">จำเป็นสำหรับการเผยแพร่บน Google Play และ App Store</span>
                            </div>

                            <!-- terms_url -->
                            <div class="form-group-custom">
                                <label for="terms_url">URL ข้อตกลงการใช้งาน (Terms of Service)</label>
                                <div class="input-with-button">
                                    <input type="url" name="terms_url" id="terms_url" class="form-control-custom" value="<?= htmlspecialchars($val('terms_url', 'https://pongploydev.github.io/terms')) ?>">
                                    <?php if (!empty($val('terms_url'))): ?>
                                        <a href="<?= htmlspecialchars($val('terms_url')) ?>" target="_blank" class="btn btn-default" style="border-radius: 12px;"><i class="fa fa-external-link"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h3 class="settings-card-title"><i class="mdi mdi-email-outline"></i> ช่องทางติดต่อ & การสนับสนุน (Support & Contact)</h3>
                                    <p class="settings-card-desc">ข้อมูลให้ผู้ใช้งานหรือสโตร์ติดต่อทีมงานพัฒนา</p>
                                </div>
                            </div>

                            <!-- support_email -->
                            <div class="form-group-custom">
                                <label for="support_email">อีเมลสนับสนุนผู้ใช้งาน (Support Email)</label>
                                <input type="email" name="support_email" id="support_email" class="form-control-custom" value="<?= htmlspecialchars($val('support_email', 'pongku71@gmail.com')) ?>">
                                <span class="help-text">อีเมลที่แสดงในหน้า About / Contact Us ในแอปพลิเคชัน</span>
                            </div>

                            <!-- contact_custom_url -->
                            <div class="form-group-custom">
                                <label for="contact_custom_url">ลิงก์ติดต่อทีมงานเพิ่มเติม (Custom Contact URL / Line / Social)</label>
                                <div class="input-with-button">
                                    <input type="url" name="contact_custom_url" id="contact_custom_url" class="form-control-custom" placeholder="https://line.me/... หรือ https://facebook.com/..." value="<?= htmlspecialchars($val('contact_custom_url')) ?>">
                                    <?php if (!empty($val('contact_custom_url'))): ?>
                                        <a href="<?= htmlspecialchars($val('contact_custom_url')) ?>" target="_blank" class="btn btn-default" style="border-radius: 12px;"><i class="fa fa-external-link"></i></a>
                                    <?php endif; ?>
                                </div>
                                <span class="help-text">ลิงก์หน้าแบบฟอร์มติดต่อ หรือ Line Official Account</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB: FEED GROUPS MANAGEMENT (public.feed_groups)
                 ========================================== -->
            <div id="tab-feed-groups" class="tab-content-panel">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="mdi mdi-view-grid-plus-outline" style="color: #2563eb;"></i> จัดการเปิด / ปิด หมวดหมู่ข่าวสาร (Feed Groups)</h3>
                            <p class="settings-card-desc">ควบคุมการแสดงผลหมวดหมู่ข่าวในแอปและหน้าเว็บ (ตาราง <code>public.feed_groups</code> ใน Supabase)</p>
                        </div>
                        <div>
                            <a href="<?= admin_url("feed_groups") ?>" class="btn btn-primary" style="border-radius: 12px; font-weight: 700; font-size: 13px;">
                                <i class="fa fa-external-link"></i> เปิดหน้าจัดการหมวดหมู่แบบเต็ม (Full Manager)
                            </a>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <strong style="color: #1e293b; font-size: 13px;"><i class="fa fa-info-circle" style="color: #2563eb;"></i> สถานะการเปิด/ปิดหมวดหมู่:</strong>
                                <span style="font-size: 12.5px; color: #64748b; margin-left: 5px;">
                                    คลิกที่สวิตช์เพื่อเปิดหรือปิดหมวดหมู่ได้ทันที (หมวดที่ปิดจะไม่แสดงในหน้าแรกของแอป)
                                </span>
                            </div>
                            <div>
                                <span class="badge" style="background: #10b981; padding: 6px 12px; border-radius: 20px; font-size: 12px;">
                                    <?= count($groups ?? []) ?> หมวดหมู่ทั้งหมด
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f1f5f9; color: #475569; font-size: 12px;">
                                    <th style="width: 70px; text-align: center;">ลำดับ</th>
                                    <th>ชื่อหมวดหมู่ (Label)</th>
                                    <th>รหัส Key (Slug)</th>
                                    <th>สีประจำหมวด</th>
                                    <th style="width: 140px; text-align: center;">สถานะ</th>
                                    <th style="width: 110px; text-align: center;">เปิด / ปิด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($groups)): ?>
                                    <?php foreach ($groups as $grp): ?>
                                        <?php
                                        $gid = $grp['id'] ?? '';
                                        $gkey = $grp['key'] ?? '';
                                        $glabel = $grp['label'] ?? '';
                                        $gcolor = $grp['color'] ?? '#2F6FED';
                                        $gorder = (int)($grp['order_index'] ?? 0);
                                        $gactive = !empty($grp['is_active']);
                                        ?>
                                        <tr>
                                            <td style="text-align: center; font-weight: 800; color: #64748b;">#<?= $gorder ?></td>
                                            <td><strong style="color: #0f172a; font-size: 13.5px;"><?= htmlspecialchars($glabel) ?></strong></td>
                                            <td><code style="background: #f1f5f9; color: #2563eb; padding: 2px 7px; border-radius: 6px; font-size: 12px;"><?= htmlspecialchars($gkey) ?></code></td>
                                            <td>
                                                <span style="display: inline-block; width: 14px; height: 14px; border-radius: 50%; background: <?= htmlspecialchars($gcolor) ?>; vertical-align: middle; margin-right: 5px;"></span>
                                                <span style="font-size: 12px; font-family: monospace; color: #475569;"><?= htmlspecialchars($gcolor) ?></span>
                                            </td>
                                            <td style="text-align: center;" id="tab-status-<?= htmlspecialchars($gid) ?>">
                                                <?php if ($gactive): ?>
                                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #059669; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                                        <i class="fa fa-check-circle"></i> เปิดใช้งาน
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge" style="background: rgba(100, 116, 139, 0.15); color: #64748b; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                                                        <i class="fa fa-ban"></i> ปิดใช้งาน
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <label class="custom-switch" style="margin: 0;" title="เปิด/ปิดหมวดหมู่นี้">
                                                    <input type="checkbox" class="toggle-tab-group-switch" data-id="<?= htmlspecialchars($gid) ?>" <?= $gactive ? 'checked' : '' ?>>
                                                    <span class="custom-slider"></span>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">
                                            ยังไม่มีข้อมูลกลุ่มฟีดในระบบ
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div style="background: #f1f5f9; border-radius: 12px; padding: 16px; margin-top: 20px; border-left: 4px solid #8b5cf6;">
                        <strong style="font-size: 13px; color: #1e293b;"><i class="mdi mdi-database"></i> คำสั่ง SQL สำหรับ Supabase Table (public.feed_groups):</strong>
                        <p style="font-size: 12px; color: #475569; margin: 4px 0 0 0;">
                            คำสั่งเพิ่มคอลัมน์เปิด-ปิด: <code>ALTER TABLE public.feed_groups ADD COLUMN IF NOT EXISTS is_active boolean NOT NULL DEFAULT true;</code>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 TAB 7: DEVELOPER & SUPABASE SQL SCHEMA
                 ========================================== -->
            <div id="tab-developer" class="tab-content-panel">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <div>
                            <h3 class="settings-card-title"><i class="mdi mdi-database"></i> Supabase PostgreSQL Table Schema (`public.app_settings`)</h3>
                            <p class="settings-card-desc">สคริปต์ SQL สำหรับสร้างตารางบน Supabase SQL Editor และรายละเอียดการเชื่อมต่อ REST API</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <strong style="font-size: 13px; color: #1e293b;">📄 คำสั่ง SQL DDL:</strong>
                                <button type="button" class="btn btn-xs btn-primary" onclick="copySqlSchema();" style="border-radius: 8px;">
                                    <i class="fa fa-copy"></i> คัดลอกโค้ด SQL
                                </button>
                            </div>

                            <div class="code-preview-box" id="sqlSchemaText">
create table public.app_settings (
  id text not null default 'default'::text,
  is_ai_enabled boolean not null default true,
  ai_model_name text not null default 'gemini-1.5-flash'::text,
  ai_max_tokens integer not null default 400,
  tts_default_rate numeric(3, 2) not null default 0.95,
  ads_enabled boolean not null default false,
  banner_ads_enabled boolean not null default false,
  interstitial_ads_enabled boolean not null default false,
  interstitial_interval_clicks integer not null default 4,
  interstitial_min_delay_sec integer not null default 60,
  admob_banner_id_android text null,
  admob_interstitial_id_android text null,
  min_supported_version text not null default '1.0.0'::text,
  latest_version text not null default '1.0.0'::text,
  force_update_title text null default 'มีเวอร์ชันใหม่พร้อมใช้งาน'::text,
  force_update_message text null default 'กรุณาอัปเดตเป็นเวอร์ชันล่าสุดเพื่อการใช้งานที่ราบรื่นและปลอดภัย'::text,
  is_maintenance boolean not null default false,
  maintenance_message text null default 'ระบบกำลังปิดปรับปรุงชั่วคราว ทีมงานกำลังเร่งแก้ไขครับ'::text,
  privacy_policy_url text null default 'https://pongploydev.github.io/privacy-policy'::text,
  terms_url text null default 'https://pongploydev.github.io/terms'::text,
  support_email text null default 'pongku71@gmail.com'::text,
  play_store_url text null default 'https://play.google.com/store/apps/details?id=com.pongploydev.technewsth.app'::text,
  announcement_active boolean not null default false,
  announcement_message text null,
  breaking_news_active boolean not null default false,
  breaking_news_text text null,
  breaking_news_url text null,
  default_feed_key text not null default 'tech-business'::text,
  cache_ttl_minutes integer not null default 15,
  updated_at timestamp with time zone not null default timezone ('utc'::text, now()),
  default_card_layout text not null default 'magazine'::text,
  default_theme_mode text not null default 'system'::text,
  default_link_open_mode text not null default 'in_app'::text,
  default_font_size text not null default 'medium'::text,
  data_saver_default boolean not null default false,
  cloud_sync_enabled boolean not null default true,
  related_news_limit integer not null default 10,
  rss_source_name text not null default 'Blognone'::text,
  rss_source_url text not null default 'https://www.blognone.com'::text,
  contact_custom_url text null,
  constraint app_settings_pkey primary key (id)
) TABLESPACE pg_default;</div>
                        </div>

                        <div class="col-md-5">
                            <strong style="font-size: 13px; color: #1e293b; display: block; margin-bottom: 12px;">🔌 Supabase REST API Endpoint:</strong>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                                <p style="font-size: 12px; margin: 0 0 8px 0; color: #475569;">
                                    <strong>Method:</strong> <span class="badge" style="background: #2563eb;">GET</span> &nbsp;
                                    <strong>Query:</strong> <code>/rest/v1/app_settings?id=eq.default</code>
                                </p>
                                <p style="font-size: 12px; margin: 0 0 8px 0; color: #475569;">
                                    <strong>Method:</strong> <span class="badge" style="background: #10b981;">PATCH</span> &nbsp;
                                    <strong>Update:</strong> <code>/rest/v1/app_settings?id=eq.default</code>
                                </p>
                                <hr style="margin: 10px 0;">
                                <strong style="font-size: 11.5px; color: #64748b; text-transform: uppercase;">Raw Live Data (JSON):</strong>
                                <pre style="font-size: 11px; background: #0f172a; color: #38bdf8; padding: 10px; border-radius: 8px; max-height: 220px; overflow-y: auto; margin-top: 6px;"><?= htmlspecialchars(json_encode($s, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 💾 Sticky Save Action Bar -->
            <div class="settings-sticky-footer">
                <div style="color: #cbd5e1; font-size: 13px;">
                    <i class="fa fa-info-circle" style="color: #38bdf8;"></i> ข้อมูลจะถูกบันทึกส่งตรงไปยังฐานข้อมูล Supabase ทันทีเมื่อกดยืนยัน
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="reset" class="btn btn-default" style="border-radius: 12px; font-weight: 700; padding: 11px 20px;">
                        รีเซ็ตค่าเดิม
                    </button>
                    <button type="submit" class="btn-save-primary">
                        <i class="fa fa-save"></i> บันทึกการตั้งค่าทั้งหมด (Save Changes)
                    </button>
                </div>
            </div>

        </form>

    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tab switching logic
    var tabBtns = document.querySelectorAll('.settings-tab-btn');
    var tabPanels = document.querySelectorAll('.tab-content-panel');

    function switchTab(targetId) {
        tabBtns.forEach(function(b) {
            if (b.getAttribute('data-tab') === targetId) {
                b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
        });
        tabPanels.forEach(function(p) {
            if (p.id === targetId) {
                p.classList.add('active');
            } else {
                p.classList.remove('active');
            }
        });
        if (history.replaceState) {
            history.replaceState(null, null, '#' + targetId);
        }
    }

    tabBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var target = this.getAttribute('data-tab');
            switchTab(target);
        });
    });

    // Check URL hash on page load
    var currentHash = window.location.hash ? window.location.hash.substring(1) : '';
    if (currentHash && document.getElementById(currentHash)) {
        switchTab(currentHash);
    }

    // Toggle Feed Groups switch AJAX
    var tabGroupSwitches = document.querySelectorAll('.toggle-tab-group-switch');
    tabGroupSwitches.forEach(function(sw) {
        sw.addEventListener('change', function() {
            var groupId = this.getAttribute('data-id');
            var isChecked = this.checked ? '1' : '0';
            var statusCell = document.getElementById('tab-status-' + groupId);

            if (statusCell) {
                if (isChecked === '1') {
                    statusCell.innerHTML = '<span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #059669; font-weight: 700; padding: 4px 10px; border-radius: 20px;"><i class="fa fa-check-circle"></i> เปิดใช้งาน</span>';
                } else {
                    statusCell.innerHTML = '<span class="badge" style="background: rgba(100, 116, 139, 0.15); color: #64748b; font-weight: 700; padding: 4px 10px; border-radius: 20px;"><i class="fa fa-ban"></i> ปิดใช้งาน</span>';
                }
            }

            var formData = new FormData();
            formData.append('toggle_feed_group', '1');
            formData.append('group_id', groupId);
            formData.append('is_active', isChecked);

            fetch('<?= admin_url("app_settings") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .catch(function(err) { console.error(err); });
        });
    });
});

function copySqlSchema() {
    var text = document.getElementById('sqlSchemaText').innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert('คัดลอกสคริปต์ SQL เรียบร้อยแล้ว! สามารถนำไปรันใน Supabase SQL Editor ได้ทันที');
    }).catch(function(err) {
        alert('ไม่สามารถคัดลอกได้ กรุณาคัดลอกด้วยตนเอง');
    });
}
</script>
