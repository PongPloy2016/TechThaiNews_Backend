<?php
/**
 * View: User Feedbacks & Bug Reports (Supabase public.app_feedbacks)
 * Path: views/backend/admin-v3/feedbacks.php
 */

$allFeedbacks = $feedbacks ?? [];
$selectedCat = $selected_category ?? 'all';
$selectedPlat = $selected_platform ?? 'all';

// Compute statistics
$totalCount = count($allFeedbacks);
$bugCount = 0;
$suggestionCount = 0;
$featureCount = 0;
$otherCount = 0;
$androidCount = 0;
$iosCount = 0;
$webCount = 0;

foreach ($allFeedbacks as $f) {
    $cat = strtolower($f['category'] ?? '');
    if (strpos($cat, 'bug') !== false || strpos($cat, 'ปัญหา') !== false) {
        $bugCount++;
    } elseif (strpos($cat, 'suggest') !== false || strpos($cat, 'แนะ') !== false) {
        $suggestionCount++;
    } elseif (strpos($cat, 'feature') !== false || strpos($cat, 'ฟีเจอร์') !== false) {
        $featureCount++;
    } else {
        $otherCount++;
    }

    $plat = strtolower($f['device_platform'] ?? '');
    if (strpos($plat, 'android') !== false) $androidCount++;
    elseif (strpos($plat, 'ios') !== false) $iosCount++;
    else $webCount++;
}
?>

<style>
/* Modern Feedbacks Hub Styles */
:root {
    --fb-primary: #3b82f6;
    --fb-rose: #ef4444;
    --fb-emerald: #10b981;
    --fb-amber: #f59e0b;
    --fb-purple: #8b5cf6;
    --fb-slate: #0f172a;
    --fb-border: #e2e8f0;
}

.fb-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.fb-hero-banner::before {
    content: '💬';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.12;
    pointer-events: none;
}
.fb-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.fb-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #94a3b8;
    line-height: 1.5;
}

.fb-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.fb-stat-chip {
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

.fb-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.fb-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.fb-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Filter controls */
.filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    background: #f8fafc;
    padding: 14px 18px;
    border-radius: 14px;
    margin-bottom: 20px;
    border: 1px solid #edf2f7;
}
.filter-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.filter-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none !important;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.filter-pill:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.filter-pill.active {
    background: #0f172a;
    color: #ffffff;
    border-color: #0f172a;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
}

.search-box-wrap {
    position: relative;
    min-width: 260px;
}
.search-box-wrap input {
    width: 100%;
    padding: 7px 14px 7px 34px;
    border-radius: 20px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    outline: none;
    transition: border 0.2s;
}
.search-box-wrap input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}
.search-box-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
}

/* Feedback Table */
.table-fb {
    width: 100%;
    margin-bottom: 0;
}
.table-fb th {
    background: #f8fafc;
    color: #475569;
    font-weight: 800;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
}
.table-fb td {
    padding: 16px;
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
    font-size: 13.5px;
}
.table-fb tr:hover td {
    background-color: #f8fafc;
}

/* Badges */
.badge-cat {
    padding: 5px 11px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.badge-cat-bug { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
.badge-cat-suggestion { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-cat-feature { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
.badge-cat-other { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

.badge-platform {
    padding: 4px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.plat-android { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.plat-ios { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
.plat-web { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

.fb-msg-preview {
    font-weight: 600;
    color: #1e293b;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.45;
}

.fb-btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    transition: all 0.2s;
    cursor: pointer;
    font-size: 13px;
    margin: 0 2px;
}
.fb-btn-action:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.fb-btn-action.btn-del:hover {
    background: #fee2e2;
    color: #ef4444;
    border-color: #fca5a5;
}
.fb-btn-action.btn-view:hover {
    background: #dbeafe;
    color: #2563eb;
    border-color: #bfdbfe;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}
.empty-state i {
    font-size: 54px;
    margin-bottom: 14px;
    display: block;
    color: #cbd5e1;
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
        <div class="fb-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <h2>💬 กล่องข้อเสนอแนะ & แจ้งปัญหาจากผู้ใช้ (Feedback & Reports)</h2>
                    <p>
                        ศูนย์รวบรวมเสียงตอบรับและรายงานบั๊กจากผู้ใช้งานจริง เชื่อมต่อตาราง <code>public.app_feedbacks</code> ใน Supabase ช่วยให้ทีมงานตรวจสอบปัญหาและพัฒนาแอปได้ตรงจุด
                    </p>
                    <div class="fb-stats-row">
                        <div class="fb-stat-chip">
                            <i class="fa fa-inbox"></i> ทั้งหมด: <strong><?= $totalCount ?> ข้อความ</strong>
                        </div>
                        <div class="fb-stat-chip" style="background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.35); color: #fca5a5;">
                            <i class="fa fa-bug"></i> แจ้งบั๊ก/ปัญหา: <strong><?= $bugCount ?></strong>
                        </div>
                        <div class="fb-stat-chip" style="background: rgba(59, 130, 246, 0.2); border-color: rgba(59, 130, 246, 0.35); color: #93c5fd;">
                            <i class="fa fa-lightbulb-o"></i> ข้อเสนอแนะ: <strong><?= $suggestionCount ?></strong>
                        </div>
                        <div class="fb-stat-chip" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.35); color: #6ee7b7;">
                            <i class="fa fa-star"></i> ขอฟีเจอร์ใหม่: <strong><?= $featureCount ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <a href="<?= admin_url("app_settings") ?>" class="btn btn-default" style="border-radius: 12px; font-weight: 700; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25); padding: 10px 18px;">
                        <i class="mdi mdi-tune-vertical"></i> ตั้งค่าระบบ &rarr;
                    </a>
                    <div style="margin-top: 10px; display: flex; gap: 12px; justify-content: flex-end;">
                        <span style="font-size: 12px; color: #94a3b8;">
                            <i class="fa fa-android" style="color: #6ee7b7;"></i> <?= $androidCount ?> Android &nbsp;|&nbsp;
                            <i class="fa fa-apple" style="color: #ddd6fe;"></i> <?= $iosCount ?> iOS &nbsp;|&nbsp;
                            <i class="fa fa-globe" style="color: #bae6fd;"></i> <?= $webCount ?> Web
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📑 Main Feedbacks Card -->
        <div class="fb-card">
            <div class="fb-card-header">
                <div class="fb-card-title">
                    <i class="mdi mdi-message-text-outline" style="color: #3b82f6; font-size: 22px;"></i>
                    รายการข้อความตอบรับจากผู้ใช้ (Feedback Inbox)
                    <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 12px; font-weight: 700;">
                        <?= count($allFeedbacks) ?> รายการ
                    </span>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-pills">
                    <span style="font-size: 12px; font-weight: 800; color: #64748b; margin-right: 4px; display: inline-flex; align-items: center;">
                        <i class="fa fa-filter"></i> หมวดหมู่:
                    </span>
                    <a href="<?= admin_url("feedbacks?category=all&platform=".$selectedPlat) ?>" class="filter-pill <?= $selectedCat === 'all' ? 'active' : '' ?>">
                        ทั้งหมด (<?= $totalCount ?>)
                    </a>
                    <a href="<?= admin_url("feedbacks?category=bug&platform=".$selectedPlat) ?>" class="filter-pill <?= $selectedCat === 'bug' ? 'active' : '' ?>">
                        🐞 บั๊ก / ปัญหา (<?= $bugCount ?>)
                    </a>
                    <a href="<?= admin_url("feedbacks?category=suggestion&platform=".$selectedPlat) ?>" class="filter-pill <?= $selectedCat === 'suggestion' ? 'active' : '' ?>">
                        💡 แนะนำการใช้งาน (<?= $suggestionCount ?>)
                    </a>
                    <a href="<?= admin_url("feedbacks?category=feature&platform=".$selectedPlat) ?>" class="filter-pill <?= $selectedCat === 'feature' ? 'active' : '' ?>">
                        🚀 ขอฟีเจอร์ (<?= $featureCount ?>)
                    </a>
                </div>

                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <!-- Platform select -->
                    <select class="form-control" style="width: auto; border-radius: 20px; font-size: 12.5px; height: 34px; font-weight: 600;" onchange="location.href='<?= admin_url("feedbacks?category=".$selectedCat."&platform=") ?>' + this.value">
                        <option value="all" <?= $selectedPlat === 'all' ? 'selected' : '' ?>>ทุกแพลตฟอร์ม</option>
                        <option value="android" <?= $selectedPlat === 'android' ? 'selected' : '' ?>>📱 Android</option>
                        <option value="ios" <?= $selectedPlat === 'ios' ? 'selected' : '' ?>>🍎 iOS</option>
                        <option value="web" <?= $selectedPlat === 'web' ? 'selected' : '' ?>>🌐 Web</option>
                    </select>

                    <!-- Search input -->
                    <div class="search-box-wrap">
                        <i class="fa fa-search"></i>
                        <input type="text" id="fbSearchInput" placeholder="ค้นหาข้อความ หรือ อีเมลติดต่อ..." onkeyup="filterFeedbackTable()">
                    </div>
                </div>
            </div>

            <!-- Feedbacks Table -->
            <?php if (empty($allFeedbacks)): ?>
                <div class="empty-state">
                    <i class="mdi mdi-inbox-outline"></i>
                    <h4 style="font-weight: 700; color: #475569;">ไม่พบข้อเสนอแนะตามเงื่อนไขที่เลือก</h4>
                    <p style="font-size: 13px; max-width: 400px; margin: 0 auto;">ยังไม่มีผู้ใช้งานส่งข้อความเข้ามา หรือลองเปลี่ยนตัวกรองหมวดหมู่และแพลตฟอร์มด้านบน</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-fb" id="feedbacksTable">
                        <thead>
                            <tr>
                                <th style="width: 140px;">ประเภท</th>
                                <th>ข้อความจากผู้ใช้</th>
                                <th style="width: 180px;">ผู้ส่ง / ข้อมูลติดต่อ</th>
                                <th style="width: 140px;">แพลตฟอร์ม & เวอร์ชัน</th>
                                <th style="width: 150px;">เวลาที่ส่ง</th>
                                <th style="width: 100px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allFeedbacks as $idx => $fb): 
                                $catRaw = strtolower($fb['category'] ?? 'other');
                                $catClass = 'badge-cat-other';
                                $catLabel = $fb['category'] ?? 'ทั่วไป';
                                $catIcon = 'fa-comment-o';

                                if (strpos($catRaw, 'bug') !== false || strpos($catRaw, 'ปัญหา') !== false) {
                                    $catClass = 'badge-cat-bug';
                                    $catLabel = 'แจ้งบั๊ก / ปัญหา';
                                    $catIcon = 'fa-bug';
                                } elseif (strpos($catRaw, 'suggest') !== false || strpos($catRaw, 'แนะ') !== false) {
                                    $catClass = 'badge-cat-suggestion';
                                    $catLabel = 'ข้อเสนอแนะ';
                                    $catIcon = 'fa-lightbulb-o';
                                } elseif (strpos($catRaw, 'feature') !== false || strpos($catRaw, 'ฟีเจอร์') !== false) {
                                    $catClass = 'badge-cat-feature';
                                    $catLabel = 'ขอฟีเจอร์';
                                    $catIcon = 'fa-star';
                                }

                                $platformRaw = strtolower($fb['device_platform'] ?? 'web');
                                $platClass = 'plat-web';
                                $platIcon = 'fa-globe';
                                $platName = 'Web Browser';
                                if (strpos($platformRaw, 'android') !== false) {
                                    $platClass = 'plat-android';
                                    $platIcon = 'fa-android';
                                    $platName = 'Android';
                                } elseif (strpos($platformRaw, 'ios') !== false) {
                                    $platClass = 'plat-ios';
                                    $platIcon = 'fa-apple';
                                    $platName = 'iOS';
                                }

                                $timeStr = !empty($fb['created_at']) ? date('d/m/Y H:i', strtotime($fb['created_at'])) : '-';
                                $contact = !empty($fb['contact']) ? htmlspecialchars($fb['contact']) : '<span style="color:#94a3b8; font-style:italic;">ไม่ระบุ</span>';
                                $msgShort = htmlspecialchars($fb['message'] ?? '');
                                $version = !empty($fb['app_version']) ? 'v' . htmlspecialchars($fb['app_version']) : '-';
                            ?>
                            <tr class="fb-row" data-search="<?= strtolower(htmlspecialchars(($fb['message'] ?? '') . ' ' . ($fb['contact'] ?? '') . ' ' . $catLabel)) ?>">
                                <td>
                                    <span class="badge-cat <?= $catClass ?>">
                                        <i class="fa <?= $catIcon ?>"></i> <?= $catLabel ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fb-msg-preview">
                                        <?= $msgShort ?>
                                    </div>
                                    <a href="javascript:void(0)" onclick="openFeedbackDetail(<?= htmlspecialchars(json_encode($fb), ENT_QUOTES, 'UTF-8') ?>)" style="font-size: 11.5px; font-weight: 700; color: #3b82f6; text-decoration: none; display: inline-block; margin-top: 4px;">
                                        อ่านเนื้อหาเต็ม &rarr;
                                    </a>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b; font-size: 13px;">
                                        <?= $contact ?>
                                    </div>
                                    <?php if (!empty($fb['contact']) && filter_var($fb['contact'], FILTER_VALIDATE_EMAIL)): ?>
                                        <a href="mailto:<?= htmlspecialchars($fb['contact']) ?>?subject=ตอบกลับข้อเสนอแนะแอปข่าวสาร" style="font-size: 11.5px; color: #3b82f6; text-decoration: none;">
                                            <i class="fa fa-reply"></i> ส่งอีเมลตอบกลับ
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="margin-bottom: 4px;">
                                        <span class="badge-platform <?= $platClass ?>">
                                            <i class="fa <?= $platIcon ?>"></i> <?= $platName ?>
                                        </span>
                                    </div>
                                    <span style="font-size: 11px; color: #64748b; font-weight: 600;">
                                        เวอร์ชัน: <code><?= $version ?></code>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #334155; font-size: 12.5px;">
                                        <?= $timeStr ?>
                                    </div>
                                    <span style="font-size: 11px; color: #94a3b8;">
                                        <i class="fa fa-clock-o"></i> บันทึกเข้าระบบ
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="fb-btn-action btn-view" title="ดูรายละเอียด" onclick="openFeedbackDetail(<?= htmlspecialchars(json_encode($fb), ENT_QUOTES, 'UTF-8') ?>)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" class="fb-btn-action btn-del" title="ลบข้อความ" onclick="confirmDeleteFeedback('<?= htmlspecialchars($fb['id'], ENT_QUOTES, 'UTF-8') ?>')">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </section>
</div>

<!-- 🔍 Modal View Feedback Detail -->
<div class="modal fade" id="modalFeedbackDetail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 16px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0f172a, #1e1b4b); color: #fff; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 17px; display: flex; align-items: center; gap: 8px;">
                    <i class="mdi mdi-message-text"></i> รายละเอียดข้อเสนอแนะ
                </h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span id="modalFbCat" class="badge-cat badge-cat-suggestion">ข้อเสนอแนะ</span>
                    <span id="modalFbPlat" class="badge-platform plat-android">Android</span>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                    <label style="font-size: 11.5px; text-transform: uppercase; color: #64748b; font-weight: 800; display: block; margin-bottom: 6px;">
                        ข้อความที่ผู้ใช้ส่งมา:
                    </label>
                    <div id="modalFbMessage" style="font-size: 14.5px; color: #0f172a; line-height: 1.6; white-space: pre-wrap; font-weight: 500;">
                        -
                    </div>
                </div>

                <div class="row" style="font-size: 13px; color: #475569;">
                    <div class="col-xs-6">
                        <strong>ช่องทางติดต่อ:</strong>
                        <div id="modalFbContact" style="color: #1e293b; font-weight: 700; margin-top: 2px;">-</div>
                    </div>
                    <div class="col-xs-6">
                        <strong>เวอร์ชันแอป:</strong>
                        <div id="modalFbVersion" style="color: #1e293b; font-weight: 700; margin-top: 2px;">-</div>
                    </div>
                </div>
                <div class="row" style="font-size: 13px; color: #475569; margin-top: 10px;">
                    <div class="col-xs-12">
                        <strong>เวลาที่บันทึก:</strong>
                        <div id="modalFbDate" style="color: #64748b; margin-top: 2px;">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 600;">
                    ปิดหน้าต่าง
                </button>
                <a id="modalFbReplyBtn" href="#" class="btn btn-primary" style="border-radius: 10px; font-weight: 700;">
                    <i class="fa fa-reply"></i> ตอบกลับผู้ใช้
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 🗑️ Modal Confirm Delete Feedback -->
<div class="modal fade" id="modalDeleteFeedback" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <form method="POST" action="<?= admin_url("feedbacks") ?>">
            <input type="hidden" name="delete_feedback" value="1">
            <input type="hidden" name="feedback_id" id="deleteFbId" value="">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 16px 40px rgba(0,0,0,0.2);">
                <div class="modal-body text-center" style="padding: 28px 24px;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #ef4444; font-size: 26px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa fa-trash-o"></i>
                    </div>
                    <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">ยืนยันลบข้อเสนอแนะ?</h4>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        ข้อมูลนี้จะถูกลบออกจากฐานข้อมูล Supabase ถาวร ไม่สามารถกู้คืนได้
                    </p>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; gap: 10px; padding: 12px 20px;">
                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; margin: 0;">
                        ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-danger btn-block" style="border-radius: 10px; font-weight: 700; margin: 0;">
                        ลบทิ้งทันที
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function filterFeedbackTable() {
    var val = (document.getElementById('fbSearchInput').value || '').toLowerCase();
    var rows = document.querySelectorAll('#feedbacksTable tbody tr.fb-row');
    rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        if (text.indexOf(val) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openFeedbackDetail(fb) {
    if (!fb) return;
    document.getElementById('modalFbMessage').textContent = fb.message || '-';
    document.getElementById('modalFbContact').textContent = fb.contact || 'ไม่ระบุ';
    document.getElementById('modalFbVersion').textContent = fb.app_version ? 'v' + fb.app_version : '-';
    document.getElementById('modalFbDate').textContent = fb.created_at ? new Date(fb.created_at).toLocaleString('th-TH') : '-';

    var catEl = document.getElementById('modalFbCat');
    var cat = (fb.category || '').toLowerCase();
    if (cat.indexOf('bug') !== -1 || cat.indexOf('ปัญหา') !== -1) {
        catEl.className = 'badge-cat badge-cat-bug';
        catEl.innerHTML = '<i class="fa fa-bug"></i> แจ้งบั๊ก / ปัญหา';
    } else if (cat.indexOf('feature') !== -1 || cat.indexOf('ฟีเจอร์') !== -1) {
        catEl.className = 'badge-cat badge-cat-feature';
        catEl.innerHTML = '<i class="fa fa-star"></i> ขอฟีเจอร์';
    } else if (cat.indexOf('suggest') !== -1 || cat.indexOf('แนะ') !== -1) {
        catEl.className = 'badge-cat badge-cat-suggestion';
        catEl.innerHTML = '<i class="fa fa-lightbulb-o"></i> ข้อเสนอแนะ';
    } else {
        catEl.className = 'badge-cat badge-cat-other';
        catEl.innerHTML = '<i class="fa fa-comment-o"></i> ' + (fb.category || 'ทั่วไป');
    }

    var platEl = document.getElementById('modalFbPlat');
    var plat = (fb.device_platform || '').toLowerCase();
    if (plat.indexOf('android') !== -1) {
        platEl.className = 'badge-platform plat-android';
        platEl.innerHTML = '<i class="fa fa-android"></i> Android';
    } else if (plat.indexOf('ios') !== -1) {
        platEl.className = 'badge-platform plat-ios';
        platEl.innerHTML = '<i class="fa fa-apple"></i> iOS';
    } else {
        platEl.className = 'badge-platform plat-web';
        platEl.innerHTML = '<i class="fa fa-globe"></i> Web Browser';
    }

    var replyBtn = document.getElementById('modalFbReplyBtn');
    if (fb.contact && fb.contact.indexOf('@') !== -1) {
        replyBtn.style.display = 'inline-block';
        replyBtn.href = 'mailto:' + encodeURIComponent(fb.contact) + '?subject=' + encodeURIComponent('ตอบกลับข้อเสนอแนะแอปข่าวสาร');
    } else {
        replyBtn.style.display = 'none';
    }

    $('#modalFeedbackDetail').modal('show');
}

function confirmDeleteFeedback(id) {
    document.getElementById('deleteFbId').value = id;
    $('#modalDeleteFeedback').modal('show');
}
</script>
