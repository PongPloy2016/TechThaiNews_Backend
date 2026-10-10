<?php
/**
 * View: Broadcast & Breaking News Studio (Supabase public.app_settings)
 * Path: views/backend/admin-v3/broadcast.php
 */

$settings = $settings ?? [];

$breakingActive = !empty($settings['breaking_news_active']);
$breakingText   = $settings['breaking_news_text'] ?? '';
$breakingUrl    = $settings['breaking_news_url'] ?? '';

$announcementActive  = !empty($settings['announcement_active']);
$announcementMessage = $settings['announcement_message'] ?? '';

$maintenanceActive  = !empty($settings['is_maintenance']);
$maintenanceMessage = $settings['maintenance_message'] ?? 'ระบบกำลังอยู่ระหว่างการปรับปรุงประสิทธิภาพชั่วคราว ขออภัยในความไม่สะดวก';

$updateTitle   = $settings['force_update_title'] ?? 'มีเวอร์ชันใหม่พร้อมใช้งาน';
$updateMessage = $settings['force_update_message'] ?? 'กรุณาอัปเดตแอปพลิเคชันเป็นเวอร์ชันล่าสุดเพื่อการใช้งานที่ราบรื่นยิ่งขึ้น';
?>

<style>
/* Modern Broadcast Studio Styles */
:root {
    --bc-primary: #ef4444;
    --bc-dark: #0f172a;
    --bc-emerald: #10b981;
    --bc-amber: #f59e0b;
    --bc-indigo: #6366f1;
    --bc-slate: #1e293b;
    --bc-border: #e2e8f0;
}

.bc-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #450a0a 50%, #991b1b 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.bc-hero-banner::before {
    content: '📢';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.12;
    pointer-events: none;
}
.bc-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.bc-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #fca5a5;
    line-height: 1.5;
}

.bc-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.bc-stat-chip {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.16);
    padding: 7px 15px;
    border-radius: 30px;
    font-size: 12.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #ffffff;
}

.bc-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.bc-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 16px;
    margin-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
}
.bc-card-title {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Custom switch toggle */
.bc-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
}
.bc-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.bc-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 30px;
}
.bc-slider:before {
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
input:checked + .bc-slider {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}
input:checked + .bc-slider.slider-amber {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}
input:checked + .bc-slider.slider-blue {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
}
input:checked + .bc-slider:before {
    transform: translateX(22px);
}

/* Mobile Phone Frame Mockup */
.mockup-phone-wrapper {
    position: sticky;
    top: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.mockup-phone {
    width: 330px;
    height: 640px;
    background: #0f172a;
    border-radius: 46px;
    padding: 12px;
    box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
    position: relative;
}
.mockup-screen {
    width: 100%;
    height: 100%;
    background: #f8fafc;
    border-radius: 36px;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.mockup-notch {
    width: 110px;
    height: 22px;
    background: #0f172a;
    border-radius: 0 0 16px 16px;
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mockup-camera {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #1e293b;
    border: 1px solid #334155;
}

/* Inside Phone Screen */
.phone-header {
    background: #ffffff;
    padding: 28px 16px 10px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.phone-header-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Live Breaking News Bar in Phone */
.phone-breaking-bar {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color: #ffffff;
    padding: 8px 12px;
    font-size: 11.5px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.35);
    transition: all 0.3s;
}
.phone-breaking-bar .badge-live {
    background: #ffffff;
    color: #b91c1c;
    font-size: 9.5px;
    font-weight: 900;
    padding: 2px 6px;
    border-radius: 4px;
    animation: pulse 1.5s infinite;
}

.phone-content {
    padding: 14px;
    flex: 1;
    overflow-y: auto;
}
.phone-dummy-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    display: flex;
    gap: 10px;
}
.phone-dummy-thumb {
    width: 65px;
    height: 65px;
    border-radius: 8px;
    background: #e2e8f0;
    flex-shrink: 0;
}
.phone-dummy-line {
    height: 10px;
    background: #e2e8f0;
    border-radius: 4px;
    margin-bottom: 6px;
}

/* Live Announcement Popup in Phone */
.phone-announcement-modal {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    z-index: 60;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    transition: all 0.3s;
}
.phone-announcement-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 20px;
    width: 100%;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Live Maintenance Screen in Phone */
.phone-maintenance-screen {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: #0f172a;
    color: #ffffff;
    z-index: 70;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px;
    text-align: center;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
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
        <div class="bc-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <h2>📢 ศูนย์ควบคุมการถ่ายทอด & ข่าวด่วน (Broadcast Studio)</h2>
                    <p>
                        ส่งแถบข่าวด่วนสีแดง (Breaking News Ticker), ป๊อปอัปประกาศสำคัญทั่วทั้งแอป (In-App Announcement), และเปิด/ปิดโหมดบำรุงรักษา พร้อมหน้าจอจำลองสมาร์ทโฟนแบบเรียลไทม์
                    </p>
                    <div class="bc-stats-row">
                        <div class="bc-stat-chip" style="<?= $breakingActive ? 'background: rgba(239, 68, 68, 0.3); border-color: rgba(239, 68, 68, 0.5);' : '' ?>">
                            <i class="fa fa-bolt"></i> แถบข่าวด่วน: <strong><?= $breakingActive ? '🔴 กำลังออกอากาศ' : '⚪ ปิดใช้งาน' ?></strong>
                        </div>
                        <div class="bc-stat-chip" style="<?= $announcementActive ? 'background: rgba(59, 130, 246, 0.3); border-color: rgba(59, 130, 246, 0.5);' : '' ?>">
                            <i class="fa fa-bullhorn"></i> ป๊อปอัปประกาศ: <strong><?= $announcementActive ? '🔔 เปิดใช้งาน' : '⚪ ปิดใช้งาน' ?></strong>
                        </div>
                        <div class="bc-stat-chip" style="<?= $maintenanceActive ? 'background: rgba(245, 158, 11, 0.3); border-color: rgba(245, 158, 11, 0.5);' : '' ?>">
                            <i class="fa fa-wrench"></i> สถานะแอป: <strong><?= $maintenanceActive ? '⚠️ โหมดปรับปรุง' : '🟢 ออนไลน์ปกติ' ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <button type="submit" form="formBroadcast" class="btn btn-danger btn-lg" style="border-radius: 14px; font-weight: 800; padding: 12px 28px; box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);">
                        <i class="fa fa-save"></i> บันทึก & เผยแพร่ทันที
                    </button>
                    <div style="margin-top: 10px;">
                        <a href="<?= admin_url("app_settings") ?>" style="color: #fca5a5; font-size: 12px; text-decoration: none;">
                            <i class="mdi mdi-tune-vertical"></i> ไปยังการตั้งค่าหลัก (App Settings) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🎛️ Broadcast Deck (Split Layout) -->
        <form method="POST" action="<?= admin_url("broadcast") ?>" id="formBroadcast">
            <input type="hidden" name="save_broadcast" value="1">

            <div class="row">
                <!-- Left Column: Controls Deck -->
                <div class="col-lg-7 col-md-12">
                    
                    <!-- 🚨 Card 1: Breaking News Ticker -->
                    <div class="bc-card">
                        <div class="bc-card-header">
                            <div class="bc-card-title">
                                <i class="fa fa-bolt" style="color: #ef4444; font-size: 20px;"></i>
                                แถบข่าวด่วนฉุกเฉิน (Breaking News Ticker)
                            </div>
                            <div>
                                <label class="bc-switch">
                                    <input type="checkbox" name="breaking_news_active" id="inputBreakingActive" value="1" <?= $breakingActive ? 'checked' : '' ?> onchange="updateLivePreview()">
                                    <span class="bc-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700; color: #334155; font-size: 13px;">
                                ข้อความพาดหัวข่าวด่วน (Headline Text):
                            </label>
                            <input type="text" class="form-control" name="breaking_news_text" id="inputBreakingText" value="<?= htmlspecialchars($breakingText) ?>" placeholder="ตัวอย่าง: ด่วน! ศบค. แถลงการณ์มาตรการใหม่ล่าสุด..." style="border-radius: 10px; height: 42px; font-weight: 600;" onkeyup="updateLivePreview()">
                            <small class="text-muted" style="font-size: 11.5px;">ข้อความนี้จะแสดงเป็นแถบสีแดงกระพริบด้านบนสุดของหน้าแรกในแอปพลิเคชัน</small>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-weight: 700; color: #334155; font-size: 13px;">
                                ลิงก์เมื่อผู้อ่านแตะแถบข่าวด่วน (Article Link / URL):
                            </label>
                            <input type="url" class="form-control" name="breaking_news_url" value="<?= htmlspecialchars($breakingUrl) ?>" placeholder="https://..." style="border-radius: 10px; height: 42px;">
                        </div>
                    </div>

                    <!-- 🔔 Card 2: In-App Announcement -->
                    <div class="bc-card">
                        <div class="bc-card-header">
                            <div class="bc-card-title">
                                <i class="fa fa-bullhorn" style="color: #3b82f6; font-size: 20px;"></i>
                                ป๊อปอัปแจ้งประกาศสำคัญ (In-App Announcement Modal)
                            </div>
                            <div>
                                <label class="bc-switch">
                                    <input type="checkbox" name="announcement_active" id="inputAnnounceActive" value="1" <?= $announcementActive ? 'checked' : '' ?> onchange="updateLivePreview()">
                                    <span class="bc-slider slider-blue"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-weight: 700; color: #334155; font-size: 13px;">
                                ข้อความประกาศ (Announcement Message):
                            </label>
                            <textarea class="form-control" name="announcement_message" id="inputAnnounceMsg" rows="3" placeholder="ระบุข้อความประกาศสำหรับผู้อ่าน..." style="border-radius: 10px; font-weight: 500;" onkeyup="updateLivePreview()"><?= htmlspecialchars($announcementMessage) ?></textarea>
                            <small class="text-muted" style="font-size: 11.5px;">จะแสดงเป็น Dialog ป๊อปอัปเด้งขึ้นมากลางจอเมื่อผู้ใช้เปิดแอปครั้งแรกของวัน</small>
                        </div>
                    </div>

                    <!-- 🚧 Card 3: Maintenance Mode -->
                    <div class="bc-card">
                        <div class="bc-card-header">
                            <div class="bc-card-title">
                                <i class="fa fa-wrench" style="color: #f59e0b; font-size: 20px;"></i>
                                โหมดปิดปรับปรุงระบบ (Maintenance Mode)
                            </div>
                            <div>
                                <label class="bc-switch">
                                    <input type="checkbox" name="is_maintenance" id="inputMaintActive" value="1" <?= $maintenanceActive ? 'checked' : '' ?> onchange="updateLivePreview()">
                                    <span class="bc-slider slider-amber"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-weight: 700; color: #334155; font-size: 13px;">
                                ข้อความแจ้งเตือนขณะปิดระบบ (Maintenance Notice):
                            </label>
                            <textarea class="form-control" name="maintenance_message" id="inputMaintMsg" rows="2" placeholder="ระบบกำลังอยู่ระหว่างการปรับปรุงประสิทธิภาพ..." style="border-radius: 10px;" onkeyup="updateLivePreview()"><?= htmlspecialchars($maintenanceMessage) ?></textarea>
                            <small class="text-muted" style="font-size: 11.5px;">เมื่อเปิดใช้งาน ผู้ใช้ทั่วไปจะไม่สามารถโหลดหน้าข่าวได้ และจะแสดงหน้าจอปิดปรับปรุง</small>
                        </div>
                    </div>

                    <!-- 📲 Card 4: Force Update Message -->
                    <div class="bc-card">
                        <div class="bc-card-header">
                            <div class="bc-card-title">
                                <i class="fa fa-cloud-download" style="color: #6366f1; font-size: 20px;"></i>
                                ข้อความบังคับอัปเดตแอป (Force Update Dialog)
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">หัวข้อแจ้งอัปเดต:</label>
                                <input type="text" class="form-control" name="force_update_title" value="<?= htmlspecialchars($updateTitle) ?>" style="border-radius: 10px; height: 40px;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; color: #334155; font-size: 13px;">ข้อความอธิบาย:</label>
                                <input type="text" class="form-control" name="force_update_message" value="<?= htmlspecialchars($updateMessage) ?>" style="border-radius: 10px; height: 40px;">
                            </div>
                        </div>
                    </div>

                    <!-- Submit action button -->
                    <div style="margin-bottom: 30px;">
                        <button type="submit" class="btn btn-danger btn-lg btn-block" style="border-radius: 14px; font-weight: 800; padding: 14px; box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);">
                            <i class="fa fa-check-circle"></i> บันทึกการเปลี่ยนแปลง (Save Broadcast)
                        </button>
                    </div>

                </div>

                <!-- Right Column: Live Mobile Mockup Preview -->
                <div class="col-lg-5 col-md-12">
                    <div class="mockup-phone-wrapper">
                        <div style="font-size: 13px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                            <i class="fa fa-eye" style="color: #ef4444;"></i> ตัวอย่างหน้าจอผู้ใช้จริง (Live Mobile Preview)
                        </div>

                        <!-- Phone Frame -->
                        <div class="mockup-phone">
                            <div class="mockup-notch">
                                <div class="mockup-camera"></div>
                            </div>

                            <div class="mockup-screen">
                                <!-- Phone Header -->
                                <div class="phone-header">
                                    <div class="phone-header-title">
                                        <i class="fa fa-newspaper-o" style="color: #ef4444;"></i> NewsApp
                                    </div>
                                    <div style="display: flex; gap: 8px; color: #64748b; font-size: 14px;">
                                        <i class="fa fa-search"></i>
                                        <i class="fa fa-bookmark-o"></i>
                                    </div>
                                </div>

                                <!-- 🚨 Live Breaking News Bar -->
                                <div class="phone-breaking-bar" id="phoneBreakingBar" style="<?= $breakingActive ? '' : 'display: none;' ?>">
                                    <span class="badge-live">LIVE</span>
                                    <marquee scrollamount="4" style="flex: 1;" id="phoneBreakingText">
                                        <?= htmlspecialchars(!empty($breakingText) ? $breakingText : 'ข่าวด่วน: ยังไม่มีข้อความ') ?>
                                    </marquee>
                                </div>

                                <!-- Dummy News Feed -->
                                <div class="phone-content">
                                    <div style="font-size: 12px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
                                        ข่าวล่าสุดวันนี้
                                    </div>

                                    <div class="phone-dummy-card">
                                        <div class="phone-dummy-thumb" style="background: #cbd5e1;"></div>
                                        <div style="flex: 1;">
                                            <div class="phone-dummy-line" style="width: 85%; background: #94a3b8;"></div>
                                            <div class="phone-dummy-line" style="width: 60%;"></div>
                                            <div class="phone-dummy-line" style="width: 40%; margin-top: 8px;"></div>
                                        </div>
                                    </div>

                                    <div class="phone-dummy-card">
                                        <div class="phone-dummy-thumb" style="background: #e2e8f0;"></div>
                                        <div style="flex: 1;">
                                            <div class="phone-dummy-line" style="width: 90%;"></div>
                                            <div class="phone-dummy-line" style="width: 70%;"></div>
                                            <div class="phone-dummy-line" style="width: 50%; margin-top: 8px;"></div>
                                        </div>
                                    </div>

                                    <div class="phone-dummy-card">
                                        <div class="phone-dummy-thumb" style="background: #f1f5f9;"></div>
                                        <div style="flex: 1;">
                                            <div class="phone-dummy-line" style="width: 75%;"></div>
                                            <div class="phone-dummy-line" style="width: 45%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 🔔 Live Announcement Modal Overlay -->
                                <div class="phone-announcement-modal" id="phoneAnnounceModal" style="<?= $announcementActive ? '' : 'display: none;' ?>">
                                    <div class="phone-announcement-card">
                                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #dbeafe; color: #2563eb; font-size: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                                            <i class="fa fa-bullhorn"></i>
                                        </div>
                                        <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                                            ประกาศจากทางแอป
                                        </div>
                                        <div id="phoneAnnounceText" style="font-size: 11.5px; color: #475569; line-height: 1.5; margin-bottom: 14px;">
                                            <?= htmlspecialchars(!empty($announcementMessage) ? $announcementMessage : 'ยินดีต้อนรับสู่แอปข่าวสาร') ?>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-block btn-xs" style="border-radius: 8px; font-weight: 700; padding: 6px;" onclick="document.getElementById('phoneAnnounceModal').style.display='none'">
                                            รับทราบ
                                        </button>
                                    </div>
                                </div>

                                <!-- 🚧 Live Maintenance Screen Overlay -->
                                <div class="phone-maintenance-screen" id="phoneMaintScreen" style="<?= $maintenanceActive ? '' : 'display: none;' ?>">
                                    <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(245, 158, 11, 0.2); color: #f59e0b; font-size: 24px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                        <i class="fa fa-wrench"></i>
                                    </div>
                                    <div style="font-size: 15px; font-weight: 800; margin-bottom: 6px;">
                                        ปิดปรับปรุงระบบ
                                    </div>
                                    <div id="phoneMaintText" style="font-size: 11.5px; color: #94a3b8; line-height: 1.5;">
                                        <?= htmlspecialchars(!empty($maintenanceMessage) ? $maintenanceMessage : 'ระบบกำลังอยู่ระหว่างการปรับปรุงประสิทธิภาพชั่วคราว') ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

    </section>
</div>

<script>
function updateLivePreview() {
    // 1. Breaking News
    var isBreaking = document.getElementById('inputBreakingActive').checked;
    var breakingText = document.getElementById('inputBreakingText').value || 'ข่าวด่วน: ยังไม่มีข้อความ';
    var breakingBar = document.getElementById('phoneBreakingBar');
    var breakingEl = document.getElementById('phoneBreakingText');

    breakingBar.style.display = isBreaking ? 'flex' : 'none';
    breakingEl.textContent = breakingText;

    // 2. Announcement
    var isAnnounce = document.getElementById('inputAnnounceActive').checked;
    var announceMsg = document.getElementById('inputAnnounceMsg').value || 'ยินดีต้อนรับสู่แอปข่าวสาร';
    var announceModal = document.getElementById('phoneAnnounceModal');
    var announceEl = document.getElementById('phoneAnnounceText');

    announceModal.style.display = isAnnounce ? 'flex' : 'none';
    announceEl.textContent = announceMsg;

    // 3. Maintenance
    var isMaint = document.getElementById('inputMaintActive').checked;
    var maintMsg = document.getElementById('inputMaintMsg').value || 'ระบบกำลังอยู่ระหว่างการปรับปรุง';
    var maintScreen = document.getElementById('phoneMaintScreen');
    var maintEl = document.getElementById('phoneMaintText');

    maintScreen.style.display = isMaint ? 'flex' : 'none';
    maintEl.textContent = maintMsg;
}
</script>
