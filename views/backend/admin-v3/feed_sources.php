<?php
/**
 * View: Manage Feed Sources (Supabase public.feed_sources)
 * Path: views/backend/admin-v3/feed_sources.php
 */

$allSources = $sources ?? [];
$allGroups = $groups ?? [];
$selGroup = $selected_group ?? 'all';
$selStatus = $selected_status ?? 'all';
$searchQ = $search_query ?? '';

// Build group lookup map
$groupMap = [];
foreach ($allGroups as $g) {
    $groupMap[$g['key']] = $g;
}

$activeCount = 0;
foreach ($allSources as $s) {
    if (!empty($s['is_active'])) $activeCount++;
}
$inactiveCount = count($allSources) - $activeCount;
?>

<style>
/* Modern Feed Sources Manager Styles */
:root {
    --fs-primary: #1e40af;
    --fs-primary-dark: #0f172a;
    --fs-emerald: #10b981;
    --fs-rose: #ef4444;
    --fs-amber: #f59e0b;
    --fs-indigo: #4f46e5;
    --fs-slate: #1e293b;
    --fs-border: #e2e8f0;
}

.fs-hero-banner {
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
.fs-hero-banner::before {
    content: '📡';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.1;
    pointer-events: none;
}
.fs-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.fs-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #94a3b8;
    line-height: 1.5;
}

.fs-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.fs-stat-chip {
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

.fs-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.fs-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.fs-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Filter Bar */
.fs-filter-bar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
}
.fs-filter-controls {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    flex: 1;
}

/* Custom switch toggle */
.fs-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}
.fs-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.fs-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 30px;
}
.fs-slider:before {
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
input:checked + .fs-slider {
    background: linear-gradient(135deg, #10b981, #059669);
}
input:checked + .fs-slider:before {
    transform: translateX(20px);
}

.table-fs {
    width: 100%;
    margin-bottom: 0;
}
.table-fs th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 13px 14px;
    border-top: none;
    border-bottom: 2px solid #e2e8f0;
}
.table-fs td {
    padding: 13px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}
.table-fs tr:hover td {
    background-color: #f8fafc;
}

.badge-active-pill {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
    font-weight: 800;
    font-size: 11px;
    padding: 4px 9px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.badge-inactive-pill {
    background: rgba(100, 116, 139, 0.12);
    color: #64748b;
    font-weight: 800;
    font-size: 11px;
    padding: 4px 9px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}
.badge-group-chip {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
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
        <div class="fs-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <h2>📡 จัดการแหล่งข่าว & RSS ฟีด (Feed Sources Hub)</h2>
                    <p>
                        เชื่อมต่อตาราง <code>public.feed_sources</code> ใน Supabase — จัดการแหล่งข่าว RSS/Atom ทั้งหมด ควบคุมการเปิด/ปิด (<code>is_active</code>) การจัดหมวดหมู่ข่าวสาร และทดสอบการเชื่อมต่อ URL ฟีดแบบเรียลไทม์
                    </p>
                    <div class="fs-stats-row">
                        <div class="fs-stat-chip">
                            <i class="fa fa-rss"></i> แสดงผล: <strong><?= count($allSources) ?> แหล่งข่าว</strong>
                        </div>
                        <div class="fs-stat-chip" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.35); color: #6ee7b7;">
                            <i class="fa fa-check-circle"></i> เปิดใช้งาน: <strong><?= $activeCount ?> รายการ</strong>
                        </div>
                        <div class="fs-stat-chip" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fca5a5;">
                            <i class="fa fa-eye-slash"></i> ปิดใช้งาน: <strong><?= $inactiveCount ?> รายการ</strong>
                        </div>
                        <div class="fs-stat-chip" style="background: rgba(99, 102, 241, 0.2); border-color: rgba(99, 102, 241, 0.35); color: #c7d2fe;">
                            <i class="fa fa-folder"></i> <strong><?= count($allGroups) ?> หมวดหมู่</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalAddSource" style="border-radius: 14px; font-weight: 800; padding: 12px 24px; box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);">
                        <i class="fa fa-plus-circle"></i> เพิ่มแหล่งข่าวใหม่
                    </button>
                    <div style="margin-top: 10px; display: flex; gap: 15px; justify-content: flex-end;">
                        <a href="<?= admin_url("feed_groups") ?>" style="color: #94a3b8; font-size: 12px; text-decoration: none;">
                            <i class="fa fa-th-large"></i> จัดการหมวดหมู่ &rarr;
                        </a>
                        <a href="<?= admin_url("app_settings") ?>" style="color: #94a3b8; font-size: 12px; text-decoration: none;">
                            <i class="mdi mdi-tune-vertical"></i> ตั้งค่าระบบ &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔍 Filter & Search Bar -->
        <div class="fs-filter-bar">
            <form action="<?= admin_url("feed_sources") ?>" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%; align-items: center;">
                <div style="min-width: 200px;">
                    <select name="group" class="form-control" onchange="this.form.submit();" style="border-radius: 10px; font-weight: 600;">
                        <option value="all">📁 ทุกหมวดหมู่ข่าว (All Groups)</option>
                        <?php foreach ($allGroups as $g): ?>
                            <option value="<?= htmlspecialchars($g['key']) ?>" <?= $selGroup === $g['key'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g['label']) ?> (<?= htmlspecialchars($g['key']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="min-width: 150px;">
                    <select name="status" class="form-control" onchange="this.form.submit();" style="border-radius: 10px; font-weight: 600;">
                        <option value="all" <?= $selStatus === 'all' ? 'selected' : '' ?>>⚡ ทุกสถานะ (All Status)</option>
                        <option value="active" <?= $selStatus === 'active' ? 'selected' : '' ?>>🟢 เฉพาะที่เปิดใช้งาน (Active)</option>
                        <option value="inactive" <?= $selStatus === 'inactive' ? 'selected' : '' ?>>⚪ เฉพาะที่ปิดใช้งาน (Inactive)</option>
                    </select>
                </div>

                <div style="flex: 1; min-width: 220px; display: flex; gap: 8px;">
                    <input type="text" name="q" value="<?= htmlspecialchars($searchQ) ?>" class="form-control" placeholder="ค้นหาชื่อสำนักข่าว, รหัส Key หรือ URL ฟีด..." style="border-radius: 10px;">
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px; font-weight: 700;">
                        <i class="fa fa-search"></i> ค้นหา
                    </button>
                    <?php if ($selGroup !== 'all' || $selStatus !== 'all' || !empty($searchQ)): ?>
                        <a href="<?= admin_url("feed_sources") ?>" class="btn btn-default" style="border-radius: 10px;" title="ล้างตัวกรอง">
                            <i class="fa fa-times"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- 📑 Main Feed Sources Card -->
        <div class="fs-card">
            <div class="fs-card-header">
                <div>
                    <h3 class="fs-card-title">
                        <i class="mdi mdi-rss" style="color: #2563eb;"></i>
                        รายการแหล่งข่าวสารทั้งหมด (<?= count($allSources) ?> รายการ)
                    </h3>
                    <p style="margin: 3px 0 0 0; font-size: 12.5px; color: #64748b;">
                        คลิกที่สวิตช์ในคอลัมน์ <strong>เปิด/ปิด</strong> เพื่อเปิดหรือระงับการดึงฟีดข่าวจากสำนักข่าวนั้นแบบเรียลไทม์
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddSource" style="border-radius: 12px; font-weight: 700;">
                        <i class="fa fa-plus"></i> เพิ่มแหล่งข่าว
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-fs">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">ลำดับ</th>
                            <th>สำนักข่าว / แหล่งข้อมูล</th>
                            <th>รหัส Key</th>
                            <th>หมวดหมู่ที่สังกัด</th>
                            <th style="width: 80px; text-align: center;">ประเภท</th>
                            <th>URL ฟีด RSS/Atom</th>
                            <th style="width: 120px; text-align: center;">สถานะ</th>
                            <th style="width: 90px; text-align: center;">เปิด / ปิด</th>
                            <th style="width: 130px; text-align: right;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allSources)): ?>
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 40px; color: #94a3b8;">
                                    <i class="fa fa-rss" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                                    ไม่พบข้อมูลแหล่งข่าวตามเงื่อนไขที่เลือก
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allSources as $src): ?>
                                <?php
                                $sid = $src['id'] ?? '';
                                $skey = $src['key'] ?? '';
                                $slabel = $src['label'] ?? '';
                                $surl = $src['url'] ?? '';
                                $shome = $src['homepage'] ?? '';
                                $stype = strtoupper($src['type'] ?? 'RSS');
                                $sgkey = $src['group_key'] ?? '';
                                $sorder = (int)($src['order_index'] ?? 0);
                                $sactive = !empty($src['is_active']);

                                $groupInfo = $groupMap[$sgkey] ?? null;
                                $groupColor = $groupInfo['color'] ?? '#64748b';
                                $groupLabel = $groupInfo['label'] ?? $sgkey;
                                ?>
                                <tr id="row-<?= htmlspecialchars($sid) ?>">
                                    <td style="text-align: center; font-weight: 800; color: #64748b;">
                                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 11.5px; border-radius: 8px;">
                                            #<?= $sorder ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: #0f172a; font-size: 13.5px; display: block;"><?= htmlspecialchars($slabel) ?></strong>
                                        <?php if (!empty($shome)): ?>
                                            <a href="<?= htmlspecialchars($shome) ?>" target="_blank" style="font-size: 11px; color: #64748b; text-decoration: none;">
                                                <i class="fa fa-globe"></i> <?= htmlspecialchars(parse_url($shome, PHP_URL_HOST) ?? $shome) ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <code style="background: #f1f5f9; color: #2563eb; padding: 2px 7px; border-radius: 6px; font-size: 11.5px;">
                                            <?= htmlspecialchars($skey) ?>
                                        </code>
                                    </td>
                                    <td>
                                        <?php if (!empty($sgkey)): ?>
                                            <span class="badge-group-chip" style="background: <?= htmlspecialchars($groupColor) ?>18; color: <?= htmlspecialchars($groupColor) ?>; border: 1px solid <?= htmlspecialchars($groupColor) ?>40;">
                                                <span style="width: 8px; height: 8px; border-radius: 50%; background: <?= htmlspecialchars($groupColor) ?>; display: inline-block;"></span>
                                                <?= htmlspecialchars($groupLabel) ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color: #94a3b8; font-size: 12px;">- ไม่ระบุ -</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3; font-weight: 800; font-size: 10.5px; padding: 3px 7px; border-radius: 6px;">
                                            <?= htmlspecialchars($stype) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 6px; max-width: 260px;">
                                            <span style="font-size: 12px; font-family: monospace; color: #475569; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($surl) ?>">
                                                <?= htmlspecialchars($surl) ?>
                                            </span>
                                            <a href="<?= htmlspecialchars($surl) ?>" target="_blank" class="btn btn-default btn-xs" style="border-radius: 6px; padding: 2px 6px;" title="เปิดดูฟีดดิบ">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td style="text-align: center;" id="status-cell-<?= htmlspecialchars($sid) ?>">
                                        <?php if ($sactive): ?>
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
                                        <label class="fs-switch" title="เปิด/ปิดการดึงฟีดจากแหล่งข่าวนี้">
                                            <input type="checkbox" class="toggle-source-switch" data-id="<?= htmlspecialchars($sid) ?>" <?= $sactive ? 'checked' : '' ?>>
                                            <span class="fs-slider"></span>
                                        </label>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" class="btn btn-default btn-xs btn-test-feed" data-url="<?= htmlspecialchars($surl) ?>" data-label="<?= htmlspecialchars($slabel) ?>" style="border-radius: 8px; padding: 4px 8px;" title="ทดสอบเชื่อมต่อฟีด">
                                            <i class="fa fa-bolt" style="color: #f59e0b;"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-xs btn-edit-source" 
                                                data-id="<?= htmlspecialchars($sid) ?>"
                                                data-key="<?= htmlspecialchars($skey) ?>"
                                                data-label="<?= htmlspecialchars($slabel) ?>"
                                                data-url="<?= htmlspecialchars($surl) ?>"
                                                data-homepage="<?= htmlspecialchars($shome) ?>"
                                                data-type="<?= htmlspecialchars(strtolower($stype)) ?>"
                                                data-group="<?= htmlspecialchars($sgkey) ?>"
                                                data-order="<?= $sorder ?>"
                                                data-active="<?= $sactive ? '1' : '0' ?>"
                                                style="border-radius: 8px; padding: 4px 8px;" title="แก้ไข">
                                            <i class="fa fa-pencil" style="color: #2563eb;"></i>
                                        </button>
                                        <button type="button" class="btn btn-default btn-xs btn-delete-source" 
                                                data-id="<?= htmlspecialchars($sid) ?>"
                                                data-label="<?= htmlspecialchars($slabel) ?>"
                                                style="border-radius: 8px; padding: 4px 8px;" title="ลบ">
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
        <div class="fs-card">
            <div class="fs-card-header">
                <div>
                    <h3 class="fs-card-title"><i class="mdi mdi-database-cog" style="color: #8b5cf6;"></i> โครงสร้าง Supabase SQL Table (`public.feed_sources`)</h3>
                    <p style="margin: 3px 0 0 0; font-size: 12.5px; color: #64748b;">
                        ตาราง <code>public.feed_sources</code> เชื่อมโยง Foreign Key กับตาราง <code>public.feed_groups</code> อัตโนมัติ
                    </p>
                </div>
                <button type="button" class="btn btn-xs btn-default" onclick="copySourceSql();" style="border-radius: 8px; font-weight: 700;">
                    <i class="fa fa-copy"></i> คัดลอกโค้ด SQL
                </button>
            </div>

            <div style="background: #0f172a; border-radius: 12px; padding: 16px 20px; color: #e2e8f0; font-family: monospace; font-size: 12px; line-height: 1.6;">
                <pre id="sourceSqlText" style="background: transparent; border: none; padding: 0; margin: 0; color: #38bdf8; font-size: 11.5px;">create table public.feed_sources (
  id uuid not null default gen_random_uuid (),
  key text not null,
  label text not null,
  url text not null,
  homepage text not null,
  type text not null default 'rss'::text,
  group_key text null,
  order_index integer not null default 0,
  is_active boolean not null default true,
  created_at timestamp with time zone not null default timezone ('utc'::text, now()),
  constraint feed_sources_pkey primary key (id),
  constraint feed_sources_key_key unique (key),
  constraint feed_sources_group_key_fkey foreign KEY (group_key) references feed_groups (key) on delete set null
) TABLESPACE pg_default;</pre>
            </div>
        </div>

    </section>
</div>

<!-- ========================================================
     MODAL: เพิ่มแหล่งข่าวใหม่ (ADD SOURCE MODAL)
     ======================================================== -->
<div class="modal fade" id="modalAddSource" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 540px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_sources") ?>" method="POST" id="formAddSource">
                <input type="hidden" name="add_feed_source" value="1">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e40af, #2563eb); color: #ffffff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 17px;"><i class="fa fa-plus-circle"></i> เพิ่มแหล่งข่าว / สำนักข่าวใหม่</h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ชื่อสำนักข่าว / แหล่งข้อมูล (Label) *</label>
                        <input type="text" name="label" class="form-control" placeholder="เช่น Blognone หรือ DroidSans" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">URL ฟีด RSS/Atom *</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="url" name="url" id="add_src_url" class="form-control" placeholder="https://www.blognone.com/atom.xml" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            <button type="button" class="btn btn-default" onclick="testModalUrl('add_src_url', 'add_test_status');" style="border-radius: 10px; font-weight: 700; white-space: nowrap;">
                                <i class="fa fa-bolt" style="color: #f59e0b;"></i> ตรวจสอบ
                            </button>
                        </div>
                        <span id="add_test_status" style="font-size: 12px; margin-top: 5px; display: block;"></span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">หน้าแรกเว็บไซต์ (Homepage)</label>
                                <input type="url" name="homepage" class="form-control" placeholder="https://www.blognone.com" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">หมวดหมู่ที่สังกัด (Group)</label>
                                <select name="group_key" class="form-control" style="border-radius: 10px; padding: 8px 12px; height: auto;">
                                    <option value="">- ไม่ระบุหมวดหมู่ -</option>
                                    <?php foreach ($allGroups as $g): ?>
                                        <option value="<?= htmlspecialchars($g['key']) ?>">
                                            <?= htmlspecialchars($g['label']) ?> (<?= htmlspecialchars($g['key']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">รหัส Key (Slug)</label>
                                <input type="text" name="key" class="form-control" placeholder="auto-slug" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ประเภทฟีด</label>
                                <select name="type" class="form-control" style="border-radius: 10px; padding: 8px 12px; height: auto;">
                                    <option value="rss">RSS 2.0</option>
                                    <option value="atom">Atom XML</option>
                                    <option value="json">JSON Feed</option>
                                    <option value="api">REST API</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ลำดับแสดงผล</label>
                                <input type="number" name="order_index" value="<?= count($allSources) + 1 ?>" class="form-control" min="0" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                    </div>

                    <div class="checkbox" style="margin-top: 5px;">
                        <label style="font-weight: 700; color: #0f172a; font-size: 13px;">
                            <input type="checkbox" name="is_active" value="1" checked> เปิดใช้งานแหล่งข่าวนี้ทันที (Active)
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
     MODAL: แก้ไขแหล่งข่าว (EDIT SOURCE MODAL)
     ======================================================== -->
<div class="modal fade" id="modalEditSource" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 540px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_sources") ?>" method="POST" id="formEditSource">
                <input type="hidden" name="edit_feed_source" value="1">
                <input type="hidden" name="source_id" id="edit_source_id">
                <div class="modal-header" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: #ffffff; padding: 18px 24px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                    <h4 class="modal-title" style="font-weight: 800; font-size: 17px;"><i class="fa fa-pencil"></i> แก้ไขแหล่งข่าว</h4>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ชื่อสำนักข่าว / แหล่งข้อมูล (Label) *</label>
                        <input type="text" name="label" id="edit_label" class="form-control" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="font-size: 13px; font-weight: 700; color: #1e293b;">URL ฟีด RSS/Atom *</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="url" name="url" id="edit_url" class="form-control" required style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            <button type="button" class="btn btn-default" onclick="testModalUrl('edit_url', 'edit_test_status');" style="border-radius: 10px; font-weight: 700; white-space: nowrap;">
                                <i class="fa fa-bolt" style="color: #f59e0b;"></i> ตรวจสอบ
                            </button>
                        </div>
                        <span id="edit_test_status" style="font-size: 12px; margin-top: 5px; display: block;"></span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">หน้าแรกเว็บไซต์ (Homepage)</label>
                                <input type="url" name="homepage" id="edit_homepage" class="form-control" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">หมวดหมู่ที่สังกัด (Group)</label>
                                <select name="group_key" id="edit_group_key" class="form-control" style="border-radius: 10px; padding: 8px 12px; height: auto;">
                                    <option value="">- ไม่ระบุหมวดหมู่ -</option>
                                    <?php foreach ($allGroups as $g): ?>
                                        <option value="<?= htmlspecialchars($g['key']) ?>">
                                            <?= htmlspecialchars($g['label']) ?> (<?= htmlspecialchars($g['key']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">รหัส Key (Slug)</label>
                                <input type="text" name="key" id="edit_key" class="form-control" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ประเภทฟีด</label>
                                <select name="type" id="edit_type" class="form-control" style="border-radius: 10px; padding: 8px 12px; height: auto;">
                                    <option value="rss">RSS 2.0</option>
                                    <option value="atom">Atom XML</option>
                                    <option value="json">JSON Feed</option>
                                    <option value="api">REST API</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b;">ลำดับแสดงผล</label>
                                <input type="number" name="order_index" id="edit_order_index" class="form-control" min="0" style="border-radius: 10px; padding: 10px 14px; height: auto;">
                            </div>
                        </div>
                    </div>

                    <div class="checkbox" style="margin-top: 5px;">
                        <label style="font-weight: 700; color: #0f172a; font-size: 13px;">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1"> เปิดใช้งานแหล่งข่าวนี้ (Active)
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
     MODAL: ยืนยันการลบแหล่งข่าว (DELETE MODAL)
     ======================================================== -->
<div class="modal fade" id="modalDeleteSource" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <form action="<?= admin_url("feed_sources") ?>" method="POST">
                <input type="hidden" name="delete_feed_source" value="1">
                <input type="hidden" name="source_id" id="delete_source_id">
                <div class="modal-body" style="padding: 30px; text-align: center;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;">
                        <i class="fa fa-trash-o"></i>
                    </div>
                    <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 8px;">ยืนยันการลบแหล่งข่าวนี้?</h4>
                    <p style="color: #64748b; font-size: 13px; margin: 0;" id="delete_source_name"></p>
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
    var toggleSwitches = document.querySelectorAll('.toggle-source-switch');
    toggleSwitches.forEach(function(sw) {
        sw.addEventListener('change', function() {
            var sourceId = this.getAttribute('data-id');
            var isChecked = this.checked ? '1' : '0';
            var statusCell = document.getElementById('status-cell-' + sourceId);

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
            formData.append('toggle_feed_source', '1');
            formData.append('source_id', sourceId);
            formData.append('is_active', isChecked);

            fetch('<?= admin_url("feed_sources") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
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
    var editBtns = document.querySelectorAll('.btn-edit-source');
    editBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit_source_id').value = this.getAttribute('data-id');
            document.getElementById('edit_key').value = this.getAttribute('data-key');
            document.getElementById('edit_label').value = this.getAttribute('data-label');
            document.getElementById('edit_url').value = this.getAttribute('data-url');
            document.getElementById('edit_homepage').value = this.getAttribute('data-homepage');
            document.getElementById('edit_type').value = this.getAttribute('data-type');
            document.getElementById('edit_group_key').value = this.getAttribute('data-group');
            document.getElementById('edit_order_index').value = this.getAttribute('data-order');
            document.getElementById('edit_is_active').checked = this.getAttribute('data-active') === '1';
            document.getElementById('edit_test_status').innerHTML = '';
            $('#modalEditSource').modal('show');
        });
    });

    // 3. Delete Modal Trigger
    var deleteBtns = document.querySelectorAll('.btn-delete-source');
    deleteBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('delete_source_id').value = this.getAttribute('data-id');
            document.getElementById('delete_source_name').innerText = 'คุณต้องการลบแหล่งข่าว "' + this.getAttribute('data-label') + '" ออกจาก Supabase ใช่หรือไม่?';
            $('#modalDeleteSource').modal('show');
        });
    });

    // 4. Test Feed Button in Table
    var testBtns = document.querySelectorAll('.btn-test-feed');
    testBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = this.getAttribute('data-url');
            var label = this.getAttribute('data-label');
            var origHtml = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            var self = this;

            var fd = new FormData();
            fd.append('test_feed_url', '1');
            fd.append('url', url);

            fetch('<?= admin_url("feed_sources") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: fd
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                self.innerHTML = origHtml;
                if (data.success) {
                    alert('✅ ' + label + '\n\n' + data.message + '\nหัวข้อฟีด: ' + (data.title || '-') + '\nจำนวนข่าวที่พบ: ' + data.items_count + ' รายการ');
                } else {
                    alert('❌ ไม่สามารถอ่านฟีดได้:\n' + data.message);
                }
            })
            .catch(function(err) {
                self.innerHTML = origHtml;
                alert('เกิดข้อผิดพลาดในการตรวจสอบ');
            });
        });
    });
});

function testModalUrl(inputId, statusId) {
    var url = document.getElementById(inputId).value.trim();
    var statusEl = document.getElementById(statusId);
    if (!url) {
        alert('กรุณากรอก URL ฟีดก่อนทำการตรวจสอบ');
        return;
    }

    statusEl.innerHTML = '<span style="color: #64748b;"><i class="fa fa-spinner fa-spin"></i> กำลังตรวจสอบการเชื่อมต่อ...</span>';

    var fd = new FormData();
    fd.append('test_feed_url', '1');
    fd.append('url', url);

    fetch('<?= admin_url("feed_sources") ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            statusEl.innerHTML = '<span style="color: #10b981; font-weight: 700;"><i class="fa fa-check-circle"></i> ' + data.message + ' (พบ ' + data.items_count + ' ข่าว, ชื่อ: ' + (data.title || '') + ')</span>';
        } else {
            statusEl.innerHTML = '<span style="color: #ef4444; font-weight: 700;"><i class="fa fa-exclamation-circle"></i> ' + data.message + '</span>';
        }
    })
    .catch(function(err) {
        statusEl.innerHTML = '<span style="color: #ef4444;">เกิดข้อผิดพลาดในการทดสอบ</span>';
    });
}

function copySourceSql() {
    var text = document.getElementById('sourceSqlText').innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert('คัดลอกโค้ด SQL ตาราง public.feed_sources เรียบร้อยแล้ว!');
    }).catch(function() {
        alert('กรุณาคัดลอกด้วยตนเอง');
    });
}
</script>
