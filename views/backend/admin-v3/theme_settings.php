<?php
/**
 * View: Theme & Visual Studio (Theme Settings)
 * Path: views/backend/admin-v3/theme_settings.php
 */

$theme   = $theme ?? [];
$presets = $presets ?? [];

$themeMode  = $theme['theme_mode'] ?? 'dark';
$accentColor = $theme['accent_color'] ?? '#0ea5e9';
$bgStyle    = $theme['bg_style'] ?? 'mesh_glow';
$fontFamily = $theme['font_family'] ?? 'Prompt';
$cardRadius = $theme['card_radius'] ?? '14px';
$cardLayout = $theme['card_layout'] ?? 'magazine';
$adminBg    = $theme['admin_bg_style'] ?? 'mesh_canvas';
$adminSb    = $theme['admin_sidebar_style'] ?? 'midnight';
$adminColor = $theme['admin_primary_color'] ?? '#2563eb';
?>

<style>
/* Modern Theme Studio Styles */
:root {
    --ts-primary: <?= htmlspecialchars($accentColor) ?>;
    --ts-dark: #0f172a;
    --ts-slate: #1e293b;
    --ts-border: #e2e8f0;
}

.ts-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4338ca 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.ts-hero-banner::before {
    content: '🎨';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.12;
    pointer-events: none;
}
.ts-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.ts-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #c7d2fe;
    line-height: 1.5;
}

.ts-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.ts-stat-chip {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    padding: 7px 15px;
    border-radius: 30px;
    font-size: 12.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #ffffff;
}

.ts-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.ts-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.ts-card-title {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Preset Cards */
.preset-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 25px;
}
.preset-card {
    background: #ffffff;
    border-radius: 14px;
    border: 2px solid #e2e8f0;
    padding: 16px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    cursor: pointer;
    text-align: left;
}
.preset-card:hover {
    border-color: #6366f1;
    transform: translateY(-3px);
    box-shadow: 0 10px 24px -4px rgba(99, 102, 241, 0.15);
}
.preset-card.active {
    border-color: #4338ca;
    background: #f5f3ff;
    box-shadow: 0 8px 20px rgba(67, 56, 202, 0.15);
}
.preset-swatches {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}
.preset-dot {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Visual Option Radio Cards */
.opt-cards-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.opt-card {
    flex: 1;
    min-width: 130px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: all 0.2s;
    background: #ffffff;
    text-align: center;
}
.opt-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.opt-card.active {
    border-color: #4338ca;
    background: #eef2ff;
}
.opt-card input[type="radio"] {
    display: none;
}

/* Color Swatches Grid */
.color-swatches-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 18px;
}
.color-swatch-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 14px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.color-swatch-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 14px rgba(0,0,0,0.2);
}
.color-swatch-btn.active {
    outline: 3px solid #0f172a;
    outline-offset: 2px;
}

/* Native Color Picker wrap */
.color-picker-wrap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    padding: 4px 10px;
    border-radius: 10px;
    height: 38px;
}
.color-picker-wrap input[type="color"] {
    width: 26px;
    height: 26px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: none;
    padding: 0;
}

/* 📱 Live Interactive Preview Screen */
.preview-sticky-wrap {
    position: sticky;
    top: 20px;
}
.preview-frame {
    background: #0f172a;
    border-radius: 24px;
    padding: 16px;
    box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.preview-inner {
    border-radius: 16px;
    padding: 20px;
    min-height: 480px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: background 0.3s ease, color 0.3s ease;
    position: relative;
    overflow: hidden;
}

.preview-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    display: inline-block;
    color: #ffffff;
    transition: background 0.2s;
}

.preview-btn {
    border-radius: 10px;
    padding: 8px 16px;
    font-weight: 700;
    font-size: 12.5px;
    color: #ffffff;
    border: none;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content" style="padding: 22px 28px;">

        <!-- Flash Session Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" style="border-radius: 12px; font-weight: 600; box-shadow: 0 4px 12px rgba(239,68,68,0.15);">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- 🚀 Hero Banner -->
        <div class="ts-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <h2>🎨 สตูดิโอออกแบบ & ปรับแต่งธีม (Theme Studio)</h2>
                    <p>
                        ปรับแต่งสีพื้นหลัง (Background Canvas & Glow), สีไฮไลต์หลัก (Accent Colors), โหมดสว่าง/มืด (Dark/Light Mode), และฟอนต์สำหรับพอร์ทัลข่าวและแอดมิน พร้อมตัวอย่างสดแบบเรียลไทม์
                    </p>
                    <div class="ts-stats-row">
                        <div class="ts-stat-chip">
                            <i class="fa fa-adjust"></i> โหมด: <strong><?= ucfirst($themeMode) ?></strong>
                        </div>
                        <div class="ts-stat-chip" style="background: rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.4); color: #bae6fd;">
                            <i class="fa fa-paint-brush"></i> สีหลัก: <strong><span id="chipColorHex"><?= htmlspecialchars($accentColor) ?></span></strong>
                        </div>
                        <div class="ts-stat-chip" style="background: rgba(16, 185, 129, 0.25); border-color: rgba(16, 185, 129, 0.4); color: #a7f3d0;">
                            <i class="fa fa-magic"></i> พื้นหลัง: <strong><?= htmlspecialchars($bgStyle) ?></strong>
                        </div>
                        <div class="ts-stat-chip" style="background: rgba(99, 102, 241, 0.25); border-color: rgba(99, 102, 241, 0.4); color: #c7d2fe;">
                            <i class="fa fa-font"></i> แบบอักษร: <strong><?= htmlspecialchars($fontFamily) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <a href="<?= base_url("home") ?>" target="_blank" class="btn btn-default" style="border-radius: 12px; font-weight: 700; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); padding: 10px 18px;">
                        <i class="fa fa-external-link"></i> เปิดดูหน้าเว็บจริง &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- 🌈 Section 1: Curated Theme Presets (1-Click Apply) -->
        <div class="ts-card">
            <div class="ts-card-header">
                <div class="ts-card-title">
                    <i class="fa fa-th-large" style="color: #6366f1;"></i>
                    ชุดธีมสำเร็จรูป (Curated Theme Presets — คลิกเดียวเปลี่ยนทั้งระบบ)
                </div>
            </div>

            <div class="preset-grid">
                <?php foreach ($presets as $pKey => $p): 
                    $isActive = ($theme['accent_color'] === $p['accent_color'] && $theme['theme_mode'] === $p['theme_mode']);
                ?>
                <form method="POST" action="<?= admin_url("theme_settings") ?>" style="display: block;">
                    <input type="hidden" name="apply_preset" value="1">
                    <input type="hidden" name="preset_key" value="<?= $pKey ?>">
                    <button type="submit" class="preset-card <?= $isActive ? 'active' : '' ?>" style="width: 100%; border: <?= $isActive ? '2px solid #4338ca' : '2px solid #e2e8f0' ?>;">
                        <div class="preset-swatches">
                            <span class="preset-dot" style="background: <?= $p['accent_color'] ?>;"></span>
                            <span class="preset-dot" style="background: <?= $p['theme_mode'] === 'dark' ? '#0f172a' : '#f8fafc' ?>; border-color: #cbd5e1;"></span>
                            <span class="preset-dot" style="background: <?= $p['admin_primary_color'] ?>;"></span>
                        </div>
                        <div style="font-weight: 800; font-size: 13.5px; color: #0f172a; margin-bottom: 4px;">
                            <?= htmlspecialchars($p['name']) ?>
                        </div>
                        <div style="font-size: 11.5px; color: #64748b; line-height: 1.4; margin-bottom: 12px; height: 32px; overflow: hidden;">
                            <?= htmlspecialchars($p['description']) ?>
                        </div>
                        <div style="font-size: 11px; font-weight: 800; color: <?= $isActive ? '#4338ca' : '#6366f1' ?>;">
                            <?= $isActive ? '✓ กำลังใช้งานอยู่' : 'คลิกเพื่อใช้งาน &rarr;' ?>
                        </div>
                    </button>
                </form>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 🎛️ Section 2: Custom Theme Customization Studio -->
        <form method="POST" action="<?= admin_url("theme_settings") ?>" id="formThemeCustom">
            <input type="hidden" name="save_theme" value="1">

            <div class="row">
                <!-- Left Column: Controls Deck -->
                <div class="col-lg-7 col-md-12">
                    
                    <!-- 1. Theme Mode -->
                    <div class="ts-card">
                        <div class="ts-card-header">
                            <div class="ts-card-title">
                                <i class="fa fa-adjust" style="color: #0ea5e9;"></i>
                                โหมดการแสดงผลเริ่มต้น (Theme Mode)
                            </div>
                        </div>
                        <div class="opt-cards-row">
                            <label class="opt-card <?= $themeMode === 'dark' ? 'active' : '' ?>" onclick="selectMode('dark', this)">
                                <input type="radio" name="theme_mode" value="dark" <?= $themeMode === 'dark' ? 'checked' : '' ?>>
                                <div style="font-size: 24px; margin-bottom: 6px;">🌙</div>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a;">Dark Mode</div>
                                <div style="font-size: 11px; color: #64748b;">มืดสบายตา คอนทราสต์สูง</div>
                            </label>

                            <label class="opt-card <?= $themeMode === 'light' ? 'active' : '' ?>" onclick="selectMode('light', this)">
                                <input type="radio" name="theme_mode" value="light" <?= $themeMode === 'light' ? 'checked' : '' ?>>
                                <div style="font-size: 24px; margin-bottom: 6px;">☀️</div>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a;">Light Mode</div>
                                <div style="font-size: 11px; color: #64748b;">สว่าง คมชัด เรียบหรู</div>
                            </label>

                            <label class="opt-card <?= $themeMode === 'system' ? 'active' : '' ?>" onclick="selectMode('system', this)">
                                <input type="radio" name="theme_mode" value="system" <?= $themeMode === 'system' ? 'checked' : '' ?>>
                                <div style="font-size: 24px; margin-bottom: 6px;">💻</div>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a;">Auto System</div>
                                <div style="font-size: 11px; color: #64748b;">เปลี่ยนตามโหมดมือถือ</div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Primary Accent Color -->
                    <div class="ts-card">
                        <div class="ts-card-header">
                            <div class="ts-card-title">
                                <i class="fa fa-paint-brush" style="color: #6366f1;"></i>
                                สีไฮไลต์หลัก (Primary Accent Color)
                            </div>
                        </div>
                        <p style="font-size: 12.5px; color: #64748b; margin-top: -10px; margin-bottom: 14px;">
                            สีนี้จะนำไปใช้กับปุ่มสำคัญ, แท็กหมวดหมู่ข่าว, เส้นขอบ Active, และลิงก์สำคัญทั่วทั้งระบบ
                        </p>
                        
                        <div class="color-swatches-grid">
                            <?php 
                            $swatches = [
                                '#0ea5e9' => 'Sky Blue',
                                '#6366f1' => 'Indigo',
                                '#10b981' => 'Emerald',
                                '#f43f5e' => 'Crimson Rose',
                                '#f59e0b' => 'Warm Amber',
                                '#8b5cf6' => 'Purple',
                                '#2563eb' => 'Royal Blue',
                                '#0f172a' => 'Obsidian',
                            ];
                            foreach ($swatches as $cHex => $cName):
                                $isColActive = (strtolower($accentColor) === strtolower($cHex));
                            ?>
                            <div class="color-swatch-btn <?= $isColActive ? 'active' : '' ?>" style="background: <?= $cHex ?>;" title="<?= $cName ?> (<?= $cHex ?>)" onclick="selectColor('<?= $cHex ?>', this)">
                                <?= $isColActive ? '✓' : '' ?>
                            </div>
                            <?php endforeach; ?>

                            <!-- Custom Color Picker -->
                            <div class="color-picker-wrap">
                                <input type="color" id="inputCustomColor" value="<?= htmlspecialchars($accentColor) ?>" onchange="onCustomColorChange(this.value)">
                                <span style="font-size: 12px; font-weight: 700; color: #334155;" id="customColorLabel">
                                    <?= htmlspecialchars($accentColor) ?>
                                </span>
                            </div>
                            <input type="hidden" name="accent_color" id="inputAccentColor" value="<?= htmlspecialchars($accentColor) ?>">
                        </div>
                    </div>

                    <!-- 3. Background Style & Atmosphere -->
                    <div class="ts-card">
                        <div class="ts-card-header">
                            <div class="ts-card-title">
                                <i class="fa fa-magic" style="color: #10b981;"></i>
                                สไตล์พื้นหลัง & แสงบรรยากาศ (Background Atmosphere)
                            </div>
                        </div>

                        <div class="opt-cards-row">
                            <label class="opt-card <?= $bgStyle === 'mesh_glow' ? 'active' : '' ?>" onclick="selectBgStyle('mesh_glow', this)">
                                <input type="radio" name="bg_style" value="mesh_glow" <?= $bgStyle === 'mesh_glow' ? 'checked' : '' ?>>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a; margin-bottom: 4px;">✨ Ambient Mesh Glow</div>
                                <div style="font-size: 11px; color: #64748b;">แสงออโรร่าละมุน มีมิติโมเดิร์น (แนะนำ)</div>
                            </label>

                            <label class="opt-card <?= $bgStyle === 'minimal_solid' ? 'active' : '' ?>" onclick="selectBgStyle('minimal_solid', this)">
                                <input type="radio" name="bg_style" value="minimal_solid" <?= $bgStyle === 'minimal_solid' ? 'checked' : '' ?>>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a; margin-bottom: 4px;">◽ Minimal Solid</div>
                                <div style="font-size: 11px; color: #64748b;">สีพื้นทึบเรียบเนียน สบายตา ไร้แสงกวน</div>
                            </label>
                        </div>

                        <div class="opt-cards-row">
                            <label class="opt-card <?= $bgStyle === 'midnight_aurora' ? 'active' : '' ?>" onclick="selectBgStyle('midnight_aurora', this)">
                                <input type="radio" name="bg_style" value="midnight_aurora" <?= $bgStyle === 'midnight_aurora' ? 'checked' : '' ?>>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a; margin-bottom: 4px;">🌌 Deep Midnight Space</div>
                                <div style="font-size: 11px; color: #64748b;">มืดสนิท คอนทราสต์จัดจ้าน สไตล์เกมมิ่ง</div>
                            </label>

                            <label class="opt-card <?= $bgStyle === 'warm_paper' ? 'active' : '' ?>" onclick="selectBgStyle('warm_paper', this)">
                                <input type="radio" name="bg_style" value="warm_paper" <?= $bgStyle === 'warm_paper' ? 'checked' : '' ?>>
                                <div style="font-weight: 800; font-size: 13px; color: #0f172a; margin-bottom: 4px;">📜 Warm Paper Silk</div>
                                <div style="font-size: 11px; color: #64748b;">โทนกระดาษนวลตา นุ่มนวล ถนอมสายตา</div>
                            </label>
                        </div>
                    </div>

                    <!-- 4. Typography & Card Geometry -->
                    <div class="ts-card">
                        <div class="ts-card-header">
                            <div class="ts-card-title">
                                <i class="fa fa-font" style="color: #f59e0b;"></i>
                                รูปแบบตัวอักษร & ความโค้งมน (Typography & Geometry)
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">แบบอักษรหลัก (Font Family):</label>
                                <select class="form-control" name="font_family" id="selectFont" style="border-radius: 10px; height: 40px; font-weight: 600;" onchange="updateLivePreview()">
                                    <option value="Prompt" <?= $fontFamily === 'Prompt' ? 'selected' : '' ?>>Prompt (โมเดิร์น อ่านง่าย นิยมสุด)</option>
                                    <option value="Plus Jakarta Sans" <?= $fontFamily === 'Plus Jakarta Sans' ? 'selected' : '' ?>>Plus Jakarta Sans (สากลระดับพรีเมียม)</option>
                                    <option value="Inter" <?= $fontFamily === 'Inter' ? 'selected' : '' ?>>Inter (คลีน มินิมอล เทคโนโลยี)</option>
                                    <option value="Kanit" <?= $fontFamily === 'Kanit' ? 'selected' : '' ?>>Kanit (คมเข้ม ตัวหนา พาดหัวสะดุดตา)</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">ความโค้งมนของการ์ด (Border Radius):</label>
                                <select class="form-control" name="card_radius" id="selectRadius" style="border-radius: 10px; height: 40px; font-weight: 600;" onchange="updateLivePreview()">
                                    <option value="16px" <?= $cardRadius === '16px' ? 'selected' : '' ?>>16px (โค้งมนนุ่มนวล ทันสมัย)</option>
                                    <option value="14px" <?= $cardRadius === '14px' ? 'selected' : '' ?>>14px (มาตรฐาน Varient / Verge)</option>
                                    <option value="10px" <?= $cardRadius === '10px' ? 'selected' : '' ?>>10px (มาตรฐานทั่วไป)</option>
                                    <option value="6px" <?= $cardRadius === '6px' ? 'selected' : '' ?>>6px (คมกริบ มินิมอลเท่ๆ)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Admin Panel Specific Styles -->
                    <div class="ts-card">
                        <div class="ts-card-header">
                            <div class="ts-card-title">
                                <i class="fa fa-dashboard" style="color: #64748b;"></i>
                                ปรับแต่งเฉพาะหน้าแอดมิน (Admin Canvas & Sidebar)
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">พื้นหลังหน้าแอดมิน (Admin Background):</label>
                                <select class="form-control" name="admin_bg_style" id="selectAdminBg" style="border-radius: 10px; height: 40px;" onchange="updateLivePreview()">
                                    <option value="mesh_canvas" <?= $adminBg === 'mesh_canvas' ? 'selected' : '' ?>>✨ Ambient Mesh Canvas (แนะนำ)</option>
                                    <option value="pure_white" <?= $adminBg === 'pure_white' ? 'selected' : '' ?>>◽ Pure White คลีนบริสุทธิ์</option>
                                    <option value="warm_paper" <?= $adminBg === 'warm_paper' ? 'selected' : '' ?>>📜 Warm Paper สีนวลตา</option>
                                    <option value="dark_slate" <?= $adminBg === 'dark_slate' ? 'selected' : '' ?>>🖤 Dark Slate เข้มดุดัน</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">แถบเมนูด้านข้าง (Admin Sidebar Style):</label>
                                <select class="form-control" name="admin_sidebar_style" id="selectAdminSb" style="border-radius: 10px; height: 40px;" onchange="updateLivePreview()">
                                    <option value="midnight" <?= $adminSb === 'midnight' ? 'selected' : '' ?>>🌑 Deep Midnight Gradient</option>
                                    <option value="deep_slate" <?= $adminSb === 'deep_slate' ? 'selected' : '' ?>>🏢 Slate Dark</option>
                                    <option value="clean_light" <?= $adminSb === 'clean_light' ? 'selected' : '' ?>>☀️ Clean White Minimal</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit action button -->
                    <div style="margin-bottom: 30px;">
                        <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 14px; font-weight: 800; padding: 14px; box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35); background: #2563eb; border: none;">
                            <i class="fa fa-save"></i> บันทึกการตั้งค่าธีมทั้งหมด (Save Theme Settings)
                        </button>
                    </div>

                </div>

                <!-- Right Column: Real-time Live Interactive Preview -->
                <div class="col-lg-5 col-md-12">
                    <div class="preview-sticky-wrap">
                        <div style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-eye" style="color: #6366f1;"></i> ตัวอย่างสด (Interactive Real-time Preview)
                        </div>

                        <div class="preview-frame">
                            <div class="preview-inner" id="previewInner">
                                
                                <!-- Preview Header -->
                                <div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                        <span class="preview-badge" id="previewBadge" style="background: <?= htmlspecialchars($accentColor) ?>;">
                                            AI & TECH
                                        </span>
                                        <span style="font-size: 11.5px; opacity: 0.7;" id="previewDateText">
                                            10 นาทีที่แล้ว
                                        </span>
                                    </div>

                                    <!-- Headline -->
                                    <h3 id="previewTitle" style="font-size: 18px; font-weight: 800; line-height: 1.4; margin: 0 0 10px 0;">
                                        ก้าวล้ำสู่อนาคต! เปิดตัวโมเดล AI ใหม่ล่าสุด ตอบคำถามไวกว่าเดิม 10 เท่า
                                    </h3>
                                    <p id="previewDesc" style="font-size: 12.5px; opacity: 0.8; line-height: 1.5; margin-bottom: 16px;">
                                        สำรวจนวัตกรรมและเทคโนโลยีที่น่าจับตามองในยุคดิจิทัล พร้อมอัปเดตบทวิเคราะห์จากผู้เชี่ยวชาญระดับแนวหน้าของเมืองไทย
                                    </p>
                                </div>

                                <!-- Dummy Image Box -->
                                <div style="height: 120px; border-radius: 12px; background: rgba(255, 255, 255, 0.1); border: 1px dashed rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; font-size: 13px; opacity: 0.7; margin-bottom: 18px;">
                                    <i class="fa fa-picture-o" style="margin-right: 6px;"></i> ภาพหน้าปกบทความข่าว
                                </div>

                                <!-- Preview Footer & Action -->
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 14px;">
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; opacity: 0.8;">
                                        <i class="fa fa-clock-o"></i> ⏱️ 3 นาที
                                    </div>
                                    <button type="button" class="preview-btn" id="previewBtn" style="background: <?= htmlspecialchars($accentColor) ?>;">
                                        อ่านต่อ <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>

                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 12px; font-size: 12px; color: #94a3b8;">
                            <i class="fa fa-info-circle"></i> การเปลี่ยนสีและสไตล์จะส่งผลทั้งในหน้าแรกของเว็บและห้องควบคุมหลังบ้าน
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
</div>

<script>
var currentThemeMode = '<?= $themeMode ?>';
var currentAccentColor = '<?= $accentColor ?>';
var currentBgStyle = '<?= $bgStyle ?>';

function selectMode(mode, el) {
    currentThemeMode = mode;
    document.querySelectorAll('input[name="theme_mode"]').forEach(function(r) {
        if (r.value === mode) r.checked = true;
    });
    document.querySelectorAll('.opt-cards-row .opt-card').forEach(function(c) {
        if (c.querySelector('input[name="theme_mode"]')) {
            c.classList.remove('active');
        }
    });
    el.classList.add('active');
    updateLivePreview();
}

function selectColor(hex, el) {
    currentAccentColor = hex;
    document.getElementById('inputAccentColor').value = hex;
    document.getElementById('inputCustomColor').value = hex;
    document.getElementById('customColorLabel').textContent = hex;
    document.getElementById('chipColorHex').textContent = hex;

    document.querySelectorAll('.color-swatch-btn').forEach(function(btn) {
        btn.classList.remove('active');
        btn.textContent = '';
    });
    el.classList.add('active');
    el.textContent = '✓';

    updateLivePreview();
}

function onCustomColorChange(hex) {
    currentAccentColor = hex;
    document.getElementById('inputAccentColor').value = hex;
    document.getElementById('customColorLabel').textContent = hex;
    document.getElementById('chipColorHex').textContent = hex;

    document.querySelectorAll('.color-swatch-btn').forEach(function(btn) {
        btn.classList.remove('active');
        btn.textContent = '';
    });
    updateLivePreview();
}

function selectBgStyle(bg, el) {
    currentBgStyle = bg;
    document.querySelectorAll('input[name="bg_style"]').forEach(function(r) {
        if (r.value === bg) r.checked = true;
    });
    document.querySelectorAll('.opt-cards-row .opt-card').forEach(function(c) {
        if (c.querySelector('input[name="bg_style"]')) {
            c.classList.remove('active');
        }
    });
    el.classList.add('active');
    updateLivePreview();
}

function updateLivePreview() {
    var preview = document.getElementById('previewInner');
    var badge = document.getElementById('previewBadge');
    var btn = document.getElementById('previewBtn');
    var radius = document.getElementById('selectRadius').value || '14px';
    var font = document.getElementById('selectFont').value || 'Prompt';

    // Apply color
    badge.style.background = currentAccentColor;
    btn.style.background = currentAccentColor;
    preview.style.borderRadius = radius;
    preview.style.fontFamily = font + ', sans-serif';

    // Apply Mode & Background
    var isDark = (currentThemeMode === 'dark' || currentThemeMode === 'system');

    if (isDark) {
        preview.style.color = '#f8fafc';
        if (currentBgStyle === 'mesh_glow') {
            preview.style.background = 'radial-gradient(ellipse 90% 60% at 50% -20%, ' + currentAccentColor + '33, transparent 75%), #0a0e17';
        } else if (currentBgStyle === 'midnight_aurora') {
            preview.style.background = 'radial-gradient(ellipse 90% 60% at 50% -20%, rgba(244, 63, 94, 0.25), transparent 75%), #05070c';
        } else {
            preview.style.background = '#0a0e17';
        }
    } else {
        preview.style.color = '#0f172a';
        if (currentBgStyle === 'mesh_glow') {
            preview.style.background = 'radial-gradient(ellipse 90% 60% at 50% -20%, ' + currentAccentColor + '18, transparent 75%), #f8fafc';
        } else if (currentBgStyle === 'warm_paper') {
            preview.style.background = 'radial-gradient(circle at 50% 0%, rgba(245, 158, 11, 0.1), transparent 60%), #faf8f5';
        } else {
            preview.style.background = '#ffffff';
        }
    }
}

// Initial trigger on load
document.addEventListener('DOMContentLoaded', function() {
    updateLivePreview();
});
</script>
