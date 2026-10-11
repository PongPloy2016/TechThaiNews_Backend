<?php
/**
 * View: Manage Feed Groups (Supabase public.feed_groups)
 * Path: views/backend/admin-v3/feed_groups.php
 */

$allGroups = $groups ?? [];
$activeCount = 0;
foreach ($allGroups as $g) {
    if (!empty($g['is_active'])) $activeCount++;
}
$inactiveCount = count($allGroups) - $activeCount;
?>

<style>
/* Modern Feed Groups Manager Styles */
:root {
    --fg-primary: #1e40af;
    --fg-primary-dark: #0f172a;
    --fg-emerald: #10b981;
    --fg-rose: #ef4444;
    --fg-amber: #f59e0b;
    --fg-slate: #1e293b;
    --fg-border: #e2e8f0;
}

.fg-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.fg-hero-banner::before {
    content: '📁';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.1;
    pointer-events: none;
}
.fg-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.fg-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #94a3b8;
    line-height: 1.5;
}

.fg-stats-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.fg-stat-chip {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #ffffff;
}

.fg-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.fg-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.fg-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Custom switch toggle */
.fg-switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
}
.fg-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.fg-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 30px;
}
.fg-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
input:checked + .fg-slider {
    background: linear-gradient(135deg, #10b981, #059669);
}
input:checked + .fg-slider:before {
    transform: translateX(22px);
}

.table-fg {
    width: 100%;
    margin-bottom: 0;
}
.table-fg th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 13px 16px;
    border-top: none;
    border-bottom: 2px solid #e2e8f0;
}
.table-fg td {
    padding: 14px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13.5px;
}
.table-fg tr:hover td {
    background-color: #f8fafc;
}

.color-dot-badge {
    display: inline-block;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    vertical-align: middle;
    margin-right: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.badge-active-pill {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
    font-weight: 800;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.badge-inactive-pill {
    background: rgba(100, 116, 139, 0.12);
    color: #64748b;
    font-weight: 800;
    font-size: 11.5px;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
.fg-banner-actions {
    text-align: right;
}
@media (max-width: 991px) {
    .fg-banner-actions {
        text-align: left !important;
        margin-top: 18px !important;
    }
    .fg-banner-actions .btn-success {
        width: 100% !important;
    }
    .fg-banner-actions .fg-quick-links {
        justify-content: flex-start !important;
        flex-wrap: wrap !important;
    }
}

@media (max-width: 767px) {
    .fg-hero-banner {
        padding: 20px 16px !important;
        border-radius: 16px !important;
    }
    .fg-hero-banner h2 {
        font-size: 19px !important;
        line-height: 1.35 !important;
    }
    .fg-hero-banner p {
        font-size: 13px !important;
    }
    .fg-stats-row {
        gap: 8px !important;
    }
    .fg-stat-chip {
        font-size: 12px !important;
        padding: 6px 12px !important;
    }
    .fg-card {
        padding: 16px 14px !important;
        border-radius: 14px !important;
    }
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
        <div class="fg-hero-banner">
            <div class="row align-items-center">
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <h2>📁 จัดการหมวดหมู่ข่าวสาร (Feed Groups Hub)</h2>
                    <p>
                        เชื่อมต่อตาราง <code>public.feed_groups</code> ใน Supabase — สามารถคลิกสวิตช์ <strong>เปิด / ปิด (Enable/Disable)</strong> หมวดหมู่ข่าวแบบเรียลไทม์ได้ทันที โดยหมวดที่ปิดใช้งานจะไม่แสดงในเมนูหน้าแรกของแอปพลิเคชัน
                    </p>
                    <div class="fg-stats-row">
                        <div class="fg-stat-chip">
                            <i class="fa fa-th-large"></i> ทั้งหมด: <strong><?= count($allGroups) ?> หมวด</strong>
                        </div>
                        <div class="fg-stat-chip" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.35); color: #6ee7b7;">
                            <i class="fa fa-check-circle"></i> เปิดใช้งาน: <strong><?= $activeCount ?> หมวด</strong>
                        </div>
                        <div class="fg-stat-chip" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                            <i class="fa fa-eye-slash"></i> ซ่อน / ปิดใช้งาน: <strong><?= $inactiveCount ?> หมวด</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12 fg-banner-actions">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalAddGroup" style="border-radius: 14px; font-weight: 800; padding: 12px 24px; box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);">
                        <i class="fa fa-plus-circle"></i> เพิ่มหมวดหมู่ใหม่
                    </button>
                    <div class="fg-quick-links" style="margin-top: 10px; display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= admin_url("feed_sources") ?>" class="btn btn-link" style="color: #94a3b8; font-size: 12px; text-decoration: none;">
                            <i class="mdi mdi-rss-box"></i> ไปยังหน้า แหล่งข่าว (Feed Sources) &rarr;
                        </a>
                        <a href="<?= admin_url("app_settings#tab-feed-groups") ?>" class="btn btn-link" style="color: #94a3b8; font-size: 12px; text-decoration: none;">
                            <i class="mdi mdi-tune-vertical"></i> App Settings &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📑 Main Feed Groups Card -->
        <div class="fg-card">
            <div class="fg-card-header">
                <div>
                    <h3 class="fg-card-title">
                        <i class="mdi mdi-format-list-bulleted-type" style="color: #2563eb;"></i>
                        รายการหมวดหมู่ข่าวสารทั้งหมดในระบบ (<?= count($allGroups) ?> รายการ)
                    </h3>
                    <p style="margin: 3px 0 0 0; font-size: 12.5px; color: #64748b;">
                        คลิกที่สวิตช์ในคอลัมน์ <strong>เปิด/ปิด</strong> เพื่อเปิดหรือซ่อนหมวดหมู่นั้นจากแอปและหน้าเว็บทันที
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddGroup" style="border-radius: 12px; font-weight: 700;">
                        <i class="fa fa-plus"></i> เพิ่มหมวดหมู่
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-fg">
                    <thead>
                        <tr>
                            <th style="width: 70px; text-align: center;">ลำดับ</th>
                            <th>ชื่อหมวดหมู่ (Label)</th>
                            <th>รหัส Key (Slug)</th>
                            <th>สีประจำหมวด (Color)</th>
                            <th style="width: 140px; text-align: center;">สถานะ (Status)</th>
                            <th style="width: 110px; text-align: center;">เปิด / ปิด</th>
                            <th style="width: 130px; text-align: right;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allGroups)): ?>
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 40px; color: #94a3b8;">
                                    <i class="fa fa-folder-open-o" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                    ยังไม่มีข้อมูลกลุ่มฟีดในตาราง <code>public.feed_groups</code>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allGroups as $grp): ?>
                                <?php
                                $gid = $grp['id'] ?? '';
                                $gkey = $grp['key'] ?? '';
                                $glabel = $grp['label'] ?? '';
                                $gcolor = $grp['color'] ?? '#2F6FED';
                                $gorder = (int)($grp['order_index'] ?? 0);
                                $gactive = !empty($grp['is_active']);
                                ?>
                                <tr id="row-<?= htmlspecialchars($gid) ?>">
                                    <td style="text-align: center; font-weight: 800; color: #64748b;">
                                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 12px; border-radius: 8px;">
                                            #<?= $gorder ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: #0f172a; font-size: 14px;"><?= htmlspecialchars($glabel) ?></strong>
                                    </td>
                                    <td>
                                        <code style="background: #f1f5f9; color: #2563eb; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;">
                                            <?= htmlspecialchars($gkey) ?>
                                        </code>
                                    </td>
                                    <td>
                                        <span class="color-dot-badge" style="background-color: <?= htmlspecialchars($gcolor) ?>;"></span>
                                        <span style="font-size: 12px; font-family: monospace; color: #475569;"><?= htmlspecialchars($gcolor) ?></span>
                                    </td>
                                    <td style="text-align: center;" id="status-cell-<?= htmlspecialchars($gid) ?>">
                                        <?php if ($gactive): ?>
                                            <span class="badge-active-pill">
                                                <i class="fa fa-check-circle"></i> เปิดใช้งาน
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-inactive-pill">
                                                <i class="fa fa-ban"></i> ปิดใช้งาน
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <label class="fg-switch" title="เปิด/ปิดหมวดหมู่นี้">
                                            <input type="checkbox" class="toggle-group-switch" data-id="<?= htmlspecialchars($gid) ?>" data-key="<?= htmlspecialchars($gkey) ?>" <?= $gactive ? 'checked' : '' ?>>
                                            <span class="fg-slider"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" class="btn btn-default btn-xs btn-edit-group" 
                                                data-id="<?= htmlspecialchars($gid) ?>"
                                                data-key="<?= htmlspecialchars($gkey) ?>"
                                                data-label="<?= htmlspecialchars($glabel) ?>"
                                                data-color="<?= htmlspecialchars($gcolor) ?>"
                                                data-order="<?= $gorder ?>"
                                                data-active="<?= $gactive ? '1' : '0' ?>"
                                                style="border-radius: 8px; padding: 4px 9px;" title="แก้ไข">
                                            <i class="fa fa-pencil" style="color: #2563eb;"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-xs btn-delete-group" 
                                                data-id="<?= htmlspecialchars($gid) ?>"
                                                data-label="<?= htmlspecialchars($glabel) ?>"
                                                style="border-radius: 8px; padding: 4px 9px;" title="ลบ">
                                            <i class="fa fa-trash-o" style="color: #ef4444;"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 🛠️ Supabase SQL DDL Guide Card -->
        <div class="fg-card">
            <div class="fg-card-header">
                <div>
                    <h3 class="fg-card-title"><i class="mdi mdi-database-cog" style="color: #8b5cf6;"></i> คำสั่ง SQL DDL สำหรับตาราง `public.feed_groups`</h3>
                    <p style="margin: 3px 0 0 0; font-size: 12.5px; color: #64748b;">
                        เพื่อให้ตารางใน Supabase รองรับคอลัมน์สถานะเปิด/ปิด (<code>is_active</code>) อย่างสมบูรณ์แบบ แนะนำให้รันคำสั่งด้านล่างนี้ใน Supabase SQL Editor
                    </p>
                </div>
                <button type="button" class="btn btn-xs btn-default" onclick="copyAlterSql();" style="border-radius: 8px; font-weight: 700;">
                    <i class="fa fa-copy"></i> คัดลอกคำสั่ง ALTER
                </button>
            </div>

            <div style="background: #0f172a; border-radius: 12px; padding: 16px 20px; color: #e2e8f0; font-family: monospace; font-size: 12px; line-height: 1.6; position: relative;">
                <div style="color: #94a3b8; margin-bottom: 6px;">-- 1. เพิ่มคอลัมน์ is_active ในตารางเดิม (รันคำสั่งนี้ 1 บรรทัด):</div>
                <span id="alterSqlText" style="color: #10b981; font-weight: bold;">ALTER TABLE public.feed_groups ADD COLUMN IF NOT EXISTS is_active boolean NOT NULL DEFAULT true;</span>

                <hr style="border-color: #334155; margin: 12px 0;">

                <div style="color: #94a3b8; margin-bottom: 6px;">-- 2. หรือกรณีสร้างตารางใหม่ (Full Table Definition):</div>
                <pre style="background: transparent; border: none; padding: 0; margin: 0; color: #38bdf8; font-size: 11.5px;">create table public.feed_groups (
  id uuid not null default gen_random_uuid (),
  key text not null,
  label text not null,
  color text not null default '#2F6FED'::text,
  order_index integer not null default 0,
  is_active boolean not null default true,
  created_at timestamp with time zone not null default timezone ('utc'::text, now()),
  constraint feed_groups_pkey primary key (id),
  constraint feed_groups_key_key unique (key)
) TABLESPACE pg_default;</pre>
            </div>
        </div>

    </section>
</div>

<!-- ========================================================
     MODAL: เพิ่มหมวดหมู่ใหม่ (ADD GROUP MODAL)
     ======================================================== -->
<div class="modal fade" id="modalAddGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_groups") ?>" method="POST">
                <input type="hidden" name="add_feed_group" value="1">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e40af, #2563eb); color: #ffffff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 17px;"><i class="fa fa-plus-circle"></i> เพิ่มหมวดหมู่ข่าวใหม่</h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ชื่อหมวดหมู่ (Label) *</label>
                        <input type="text" name="label" class="form-control" placeholder="เช่น ข่าวปัญญาประดิษฐ์และ AI" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">รหัสกลุ่ม (Key / Slug)</label>
                        <input type="text" name="key" class="form-control" placeholder="เช่น ai-innovation (หากเว้นว่างจะสร้างอัตโนมัติ)" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                        <span class="help-block" style="font-size: 11.5px; color: #64748b;">ใช้ตัวอักษรภาษาอังกฤษ ตัวเลข และขีดกลาง (-) เช่น tech-business</span>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">สีประจำหมวด</label>
                                <input type="color" name="color" value="#2F6FED" class="form-control" style="border-radius: 10px; height: 42px; padding: 4px; cursor: pointer;">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ลำดับแสดงผล</label>
                                <input type="number" name="order_index" value="<?= count($allGroups) + 1 ?>" class="form-control" min="0" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                    </div>
                    <div class="checkbox" style="margin-top: 5px;">
                        <label style="font-weight: 700; color: #0f172a; font-size: 13px;">
                            <input type="checkbox" name="is_active" value="1" checked> เปิดใช้งานหมวดหมู่นี้ทันที (Active)
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 700;">ยกเลิก</button>
                    <button type="submit" class="btn btn-success" style="border-radius: 10px; font-weight: 800; padding: 8px 20px;">
                        <i class="fa fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: แก้ไขหมวดหมู่ (EDIT GROUP MODAL)
     ======================================================== -->
<div class="modal fade" id="modalEditGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_groups") ?>" method="POST">
                <input type="hidden" name="edit_feed_group" value="1">
                <input type="hidden" name="group_id" id="edit_group_id">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: #ffffff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 17px;"><i class="fa fa-pencil"></i> แก้ไขหมวดหมู่ข่าว</h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ชื่อหมวดหมู่ (Label) *</label>
                        <input type="text" name="label" id="edit_label" class="form-control" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                    </div>
                    <div class="form-group" style="margin-bottom: 18px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">รหัสกลุ่ม (Key / Slug)</label>
                        <input type="text" name="key" id="edit_key" class="form-control" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">สีประจำหมวด</label>
                                <input type="color" name="color" id="edit_color" class="form-control" style="border-radius: 10px; height: 42px; padding: 4px; cursor: pointer;">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ลำดับแสดงผล</label>
                                <input type="number" name="order_index" id="edit_order_index" class="form-control" min="0" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                    </div>
                    <div class="checkbox" style="margin-top: 5px;">
                        <label style="font-weight: 700; color: #0f172a; font-size: 13px;">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"> เปิดใช้งานหมวดหมู่นี้ (Active)
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 700;">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; font-weight: 800; padding: 8px 20px;">
                        <i class="fa fa-save"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: ยืนยันการลบหมวดหมู่ (DELETE MODAL)
     ======================================================== -->
<div class="modal fade" id="modalDeleteGroup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_groups") ?>" method="POST">
                <input type="hidden" name="delete_feed_group" value="1">
                <input type="hidden" name="group_id" id="delete_group_id">
                <div class="modal-body" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                        <i class="fa fa-trash-o"></i>
                    </div>
                    <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 8px;">ยืนยันการลบหมวดหมู่นี้?</h4>
                    <p style="color: #64748b; font-size: 13px; margin: 0;" id="delete_group_name"></p>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 24px; text-align: center;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 700;">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 10px; font-weight: 800; padding: 8px 22px;">
                        ยืนยันการลบ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Instant Switch Toggle AJAX
    var toggleSwitches = document.querySelectorAll('.toggle-group-switch');
    toggleSwitches.forEach(function(sw) {
        sw.addEventListener('change', function() {
            var groupId = this.getAttribute('data-id');
            var isChecked = this.checked ? '1' : '0';
            var statusCell = document.getElementById('status-cell-' + groupId);

            // Optimistic UI update
            if (statusCell) {
                if (isChecked === '1') {
                    statusCell.innerHTML = '<span class="badge-active-pill"><i class="fa fa-check-circle"></i> เปิดใช้งาน</span>';
                } else {
                    statusCell.innerHTML = '<span class="badge-inactive-pill"><i class="fa fa-ban"></i> ปิดใช้งาน</span>';
                }
            }

            // Send AJAX POST
            var formData = new FormData();
            formData.append('toggle_feed_group', '1');
            formData.append('group_id', groupId);
            formData.append('is_active', isChecked);

            fetch('<?= admin_url("feed_groups") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data.success) {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            })
            .catch(function(err) {
                console.error(err);
            });
        });
    });

    // 2. Edit Modal Trigger
    var editBtns = document.querySelectorAll('.btn-edit-group');
    editBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit_group_id').value = this.getAttribute('data-id');
            document.getElementById('edit_key').value = this.getAttribute('data-key');
            document.getElementById('edit_label').value = this.getAttribute('data-label');
            document.getElementById('edit_color').value = this.getAttribute('data-color');
            document.getElementById('edit_order_index').value = this.getAttribute('data-order');
            document.getElementById('edit_is_active').checked = this.getAttribute('data-active') === '1';
            $('#modalEditGroup').modal('show');
        });
    });

    // 3. Delete Modal Trigger
    var deleteBtns = document.querySelectorAll('.btn-delete-group');
    deleteBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('delete_group_id').value = this.getAttribute('data-id');
            document.getElementById('delete_group_name').innerText = 'คุณต้องการลบหมวดหมู่ "' + this.getAttribute('data-label') + '" ออกจาก Supabase ใช่หรือไม่?';
            $('#modalDeleteGroup').modal('show');
        });
    });
});

function copyAlterSql() {
    var text = document.getElementById('alterSqlText').innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert('คัดลอกคำสั่ง ALTER TABLE เรียบร้อยแล้ว! สามารถนำไปรันใน Supabase SQL Editor ได้ทันที');
    }).catch(function() {
        alert('กรุณาคัดลอกด้วยตนเอง');
    });
}
</script>
