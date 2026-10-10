<?php
/**
 * View: User Bookmarks & Dual-Sync Manager (MySQL + Supabase)
 * Path: views/backend/admin-v3/bookmarks.php
 */

$allBookmarks = $bookmarks ?? [];
$syncCounts   = $sync_counts ?? ['total' => count($allBookmarks), 'synced' => 0, 'pending' => 0, 'failed' => 0, 'local_only' => 0];
$selectedCat  = $selected_category ?? 'all';
$selectedStat = $selected_status ?? 'all';

$totalCount   = $syncCounts['total'] ?? count($allBookmarks);
$syncedCount  = $syncCounts['synced'] ?? 0;
$pendingCount = $syncCounts['pending'] ?? 0;
$failedCount  = $syncCounts['failed'] ?? 0;
$localCount   = $syncCounts['local_only'] ?? 0;

// Category distribution
$catCounts = [];
foreach ($allBookmarks as $bm) {
    $c = !empty($bm['category']) ? $bm['category'] : 'ทั่วไป';
    $catCounts[$c] = ($catCounts[$c] ?? 0) + 1;
}
arsort($catCounts);
?>

<style>
/* Modern Bookmarks Dual-Sync Hub Styles */
:root {
    --bm-primary: #6366f1;
    --bm-indigo: #4f46e5;
    --bm-emerald: #10b981;
    --bm-rose: #ef4444;
    --bm-amber: #f59e0b;
    --bm-slate: #0f172a;
    --bm-border: #e2e8f0;
}

.bm-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #311042 50%, #4338ca 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.bm-hero-banner::before {
    content: '🔖';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.12;
    pointer-events: none;
}
.bm-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.bm-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #cbd5e1;
    line-height: 1.5;
}

.bm-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.bm-stat-chip {
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

/* 4 Summary Stat Cards */
.sync-summary-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 18px 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}
.sync-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}
.sync-summary-card .card-label {
    font-size: 12.5px;
    font-weight: 700;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.sync-summary-card .card-val {
    font-size: 26px;
    font-weight: 900;
    line-height: 1.1;
}

.card-theme-all { border-top: 4px solid #64748b; }
.card-theme-all .card-label { color: #64748b; }
.card-theme-all .card-val { color: #0f172a; }

.card-theme-synced { border-top: 4px solid #10b981; background: #f0fdf4; }
.card-theme-synced .card-label { color: #047857; }
.card-theme-synced .card-val { color: #065f46; }

.card-theme-pending { border-top: 4px solid #f59e0b; background: #fffbeb; }
.card-theme-pending .card-label { color: #b45309; }
.card-theme-pending .card-val { color: #92400e; }

.card-theme-failed { border-top: 4px solid #ef4444; background: #fef2f2; }
.card-theme-failed .card-label { color: #b91c1c; }
.card-theme-failed .card-val { color: #991b1b; }

.bm-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.bm-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.bm-card-title {
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
    font-size: 12px;
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
    background: #4338ca;
    color: #ffffff;
    border-color: #4338ca;
    box-shadow: 0 2px 8px rgba(67, 56, 202, 0.25);
}

.search-box-wrap {
    position: relative;
    min-width: 250px;
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
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}
.search-box-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
}

/* Bookmark item table */
.table-bm {
    width: 100%;
    margin-bottom: 0;
}
.table-bm th {
    background: #f8fafc;
    color: #475569;
    font-weight: 800;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 14px;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
}
.table-bm td {
    padding: 14px;
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
    font-size: 13px;
}
.table-bm tr:hover td {
    background-color: #f8fafc;
}

.bm-thumb {
    width: 60px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    background: #e2e8f0;
    border: 1px solid #cbd5e1;
    display: block;
}
.bm-thumb-placeholder {
    width: 60px;
    height: 48px;
    border-radius: 8px;
    background: #e0e7ff;
    color: #4338ca;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.bm-title-link {
    font-weight: 700;
    color: #0f172a;
    text-decoration: none;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 3px;
    font-size: 13.5px;
}
.bm-title-link:hover {
    color: #4338ca;
    text-decoration: underline;
}

/* Sync Status Badges */
.badge-sync-synced {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.badge-sync-pending {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.badge-sync-failed {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: help;
}
.badge-sync-local {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.device-chip {
    font-family: monospace;
    font-size: 11px;
    background: #f1f5f9;
    padding: 3px 8px;
    border-radius: 6px;
    color: #475569;
    border: 1px solid #e2e8f0;
    display: inline-block;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btn-sync-single {
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: 1px solid #c7d2fe;
    background: #eef2ff;
    color: #4338ca;
    transition: all 0.2s;
    cursor: pointer;
}
.btn-sync-single:hover {
    background: #4338ca;
    color: #ffffff;
    border-color: #4338ca;
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
        <div class="bm-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-7">
                    <h2>🔖 จัดการบุ๊กมาร์ก & ระบบซิงค์ข้อมูล (MySQL + Supabase)</h2>
                    <p>
                        รองรับการจัดเก็บข้อมูลแบบสองทาง: ในฐานข้อมูลเครื่อง <strong>Local MySQL (bookmarks)</strong> และคลาวด์ <strong>Supabase (user_bookmarks)</strong> พร้อมสถานะการซิงค์แบบเรียลไทม์
                    </p>
                    <div class="bm-stats-row">
                        <div class="bm-stat-chip">
                            <i class="fa fa-database"></i> ฐานข้อมูล: <strong>MySQL & Supabase</strong>
                        </div>
                        <div class="bm-stat-chip" style="background: rgba(16, 185, 129, 0.2); border-color: rgba(16, 185, 129, 0.35); color: #6ee7b7;">
                            <i class="fa fa-cloud"></i> สถานะซิงค์: <strong><?= $syncedCount ?> / <?= $totalCount ?> สำเร็จ</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-right hidden-xs" style="text-align: right;">
                    <div style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                        <!-- Sync Pending Button -->
                        <form method="POST" action="<?= admin_url("bookmarks") ?>" style="display: inline;">
                            <input type="hidden" name="sync_pending" value="1">
                            <button type="submit" class="btn btn-warning btn-md" style="border-radius: 12px; font-weight: 800; padding: 10px 18px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);" title="ส่งรายการที่ค้างอยู่ขึ้น Supabase">
                                <i class="fa fa-cloud-upload"></i> ซิงค์รายการค้าง (<?= $pendingCount + $failedCount ?>)
                            </button>
                        </form>

                        <!-- Pull From Supabase Button -->
                        <form method="POST" action="<?= admin_url("bookmarks") ?>" style="display: inline;">
                            <input type="hidden" name="pull_supabase" value="1">
                            <button type="submit" class="btn btn-primary btn-md" style="border-radius: 12px; font-weight: 800; padding: 10px 18px; box-shadow: 0 4px 14px rgba(67, 56, 202, 0.35);" title="ดึงข้อมูลล่าสุดจาก Supabase ลง MySQL">
                                <i class="fa fa-cloud-download"></i> ดึงจาก Supabase
                            </button>
                        </form>
                    </div>
                    <div style="margin-top: 10px;">
                        <a href="<?= admin_url("cache_manager") ?>" style="color: #cbd5e1; font-size: 12px; text-decoration: none;">
                            <i class="mdi mdi-flash-circle"></i> ดูระบบจัดการแคช (Cache Manager) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 4 Summary Stat Cards -->
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-md-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="sync-summary-card card-theme-all">
                    <div class="card-label">
                        <i class="fa fa-database"></i> ทั้งหมดในระบบ
                    </div>
                    <div class="card-val"><?= $totalCount ?> <span style="font-size: 14px; font-weight: 600; color: #64748b;">รายการ</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="sync-summary-card card-theme-synced">
                    <div class="card-label">
                        <i class="fa fa-check-circle"></i> ซิงค์กับ Supabase แล้ว
                    </div>
                    <div class="card-val"><?= $syncedCount ?> <span style="font-size: 14px; font-weight: 600; color: #047857;">รายการ</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="sync-summary-card card-theme-pending">
                    <div class="card-label">
                        <i class="fa fa-hourglass-half"></i> รอการซิงค์ (Pending)
                    </div>
                    <div class="card-val"><?= $pendingCount ?> <span style="font-size: 14px; font-weight: 600; color: #b45309;">รายการ</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-bottom: 12px;">
                <div class="sync-summary-card card-theme-failed">
                    <div class="card-label">
                        <i class="fa fa-exclamation-triangle"></i> ซิงค์ไม่สำเร็จ (Failed)
                    </div>
                    <div class="card-val"><?= $failedCount ?> <span style="font-size: 14px; font-weight: 600; color: #b91c1c;">รายการ</span></div>
                </div>
            </div>
        </div>

        <!-- 📑 Main Bookmarks Card -->
        <div class="bm-card">
            <div class="bm-card-header">
                <div class="bm-card-title">
                    <i class="mdi mdi-bookmark-check" style="color: #4338ca; font-size: 22px;"></i>
                    รายการบุ๊กมาร์กในระบบ (Bookmarks Repository)
                    <span class="badge" style="background: #e0e7ff; color: #4338ca; font-size: 12px; font-weight: 700;">
                        แสดง <?= count($allBookmarks) ?> รายการ
                    </span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=pending") ?>" class="btn btn-xs btn-warning" style="border-radius: 6px; font-weight: 700;">
                        <i class="fa fa-filter"></i> ดูกลุ่มรอซิงค์ (<?= $pendingCount ?>)
                    </a>
                    <?php if ($failedCount > 0): ?>
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=failed") ?>" class="btn btn-xs btn-danger" style="border-radius: 6px; font-weight: 700;">
                        <i class="fa fa-warning"></i> ดูกลุ่มผิดพลาด (<?= $failedCount ?>)
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-pills">
                    <span style="font-size: 12px; font-weight: 800; color: #64748b; margin-right: 4px; display: inline-flex; align-items: center;">
                        <i class="fa fa-filter"></i> สถานะ:
                    </span>
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=all") ?>" class="filter-pill <?= $selectedStat === 'all' ? 'active' : '' ?>">
                        ทั้งหมด (<?= $totalCount ?>)
                    </a>
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=synced") ?>" class="filter-pill <?= $selectedStat === 'synced' ? 'active' : '' ?>">
                        <i class="fa fa-check" style="color: #10b981;"></i> ซิงค์แล้ว (<?= $syncedCount ?>)
                    </a>
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=pending") ?>" class="filter-pill <?= $selectedStat === 'pending' ? 'active' : '' ?>">
                        <i class="fa fa-clock-o" style="color: #f59e0b;"></i> รอซิงค์ (<?= $pendingCount ?>)
                    </a>
                    <a href="<?= admin_url("bookmarks?category=".$selectedCat."&sync_status=failed") ?>" class="filter-pill <?= $selectedStat === 'failed' ? 'active' : '' ?>">
                        <i class="fa fa-times" style="color: #ef4444;"></i> ผิดพลาด (<?= $failedCount ?>)
                    </a>
                </div>

                <!-- Search input -->
                <div class="search-box-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" id="bmSearchInput" placeholder="ค้นหาหัวข้อข่าว, อุปกรณ์, สำนักข่าว..." onkeyup="filterBookmarksTable()">
                </div>
            </div>

            <!-- Bookmarks Table -->
            <?php if (empty($allBookmarks)): ?>
                <div style="text-align: center; padding: 60px 20px; color: #94a3b8;">
                    <i class="mdi mdi-bookmark-outline" style="font-size: 54px; margin-bottom: 14px; display: block; color: #cbd5e1;"></i>
                    <h4 style="font-weight: 700; color: #475569;">ไม่พบบุ๊กมาร์กตามเงื่อนไข</h4>
                    <p style="font-size: 13px; max-width: 440px; margin: 0 auto;">
                        ยังไม่มีรายการในตาราง หรือกดปุ่ม <strong>"ดึงจาก Supabase"</strong> ด้านบนเพื่อดึงข้อมูลบุ๊กมาร์กที่ผู้อ่านเซฟไว้ในแอปพลิเคชัน
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bm" id="bookmarksTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 75px;">รูปภาพ</th>
                                <th>หัวข้อข่าว & สำนักข่าว</th>
                                <th style="width: 150px;">อุปกรณ์ (Device ID)</th>
                                <th style="width: 170px;">สถานะซิงค์ (Sync Status)</th>
                                <th style="width: 130px;">ซิงค์ล่าสุด</th>
                                <th style="width: 130px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allBookmarks as $bm): 
                                $id = (int)$bm['id'];
                                $imgUrl = !empty($bm['image_url']) ? $bm['image_url'] : '';
                                $title = htmlspecialchars($bm['title'] ?? 'ไม่มีหัวข้อข่าว');
                                $source = htmlspecialchars($bm['source'] ?? 'เว็บข่าว');
                                $cat = htmlspecialchars($bm['category'] ?? 'ทั่วไป');
                                $link = !empty($bm['link']) ? $bm['link'] : '#';
                                $devId = !empty($bm['device_id']) ? htmlspecialchars($bm['device_id']) : 'admin-web';
                                $status = $bm['sync_status'] ?? 'pending';
                                $syncErr = htmlspecialchars($bm['sync_error'] ?? '');
                                $syncedAt = !empty($bm['synced_at']) ? date('d/m/Y H:i', strtotime($bm['synced_at'])) : '-';
                            ?>
                            <tr class="bm-row" id="row-<?= $id ?>" data-search="<?= strtolower(htmlspecialchars($title . ' ' . $source . ' ' . $cat . ' ' . $devId)) ?>">
                                <td style="font-weight: 700; color: #64748b;">
                                    #<?= $id ?>
                                </td>
                                <td>
                                    <?php if (!empty($imgUrl)): ?>
                                        <img src="<?= htmlspecialchars($imgUrl) ?>" class="bm-thumb" alt="thumb" onerror="this.onerror=null; this.src='https://placehold.co/60x48/e2e8f0/475569?text=News';">
                                    <?php else: ?>
                                        <div class="bm-thumb-placeholder">
                                            <i class="fa fa-newspaper-o"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= htmlspecialchars($link) ?>" target="_blank" class="bm-title-link" title="<?= $title ?>">
                                        <?= $title ?>
                                        <i class="fa fa-external-link" style="font-size: 11px; color: #94a3b8; margin-left: 3px;"></i>
                                    </a>
                                    <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                                        <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 10.5px; border: 1px solid #e2e8f0;">
                                            <i class="fa fa-rss"></i> <?= $source ?>
                                        </span>
                                        <span class="badge" style="background: #e0e7ff; color: #4338ca; font-size: 10.5px; border: 1px solid #c7d2fe;">
                                            <i class="fa fa-tag"></i> <?= $cat ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="device-chip" title="<?= $devId ?>">
                                        <i class="fa fa-mobile"></i> <?= $devId ?>
                                    </span>
                                </td>

                                <!-- คอลัมน์สถานะการซิงค์ -->
                                <td id="sync-badge-<?= $id ?>">
                                    <?php if ($status === 'synced'): ?>
                                        <span class="badge-sync-synced">
                                            <i class="fa fa-cloud"></i> ซิงค์แล้ว (Supabase)
                                        </span>
                                    <?php elseif ($status === 'pending'): ?>
                                        <span class="badge-sync-pending">
                                            <i class="fa fa-hourglass-half"></i> รอซิงค์
                                        </span>
                                    <?php elseif ($status === 'failed'): ?>
                                        <span class="badge-sync-failed" title="<?= $syncErr ?>">
                                            <i class="fa fa-exclamation-triangle"></i> ผิดพลาด
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-sync-local">
                                            <i class="fa fa-hdd-o"></i> เฉพาะในเครื่อง
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-muted" style="font-size: 12px;" id="synced-time-<?= $id ?>">
                                    <?= $syncedAt ?>
                                </td>

                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 4px; justify-content: center; align-items: center;">
                                        <!-- ปุ่มซิงค์ทันที (AJAX) -->
                                        <button type="button" class="btn-sync-single" id="btn-sync-<?= $id ?>" onclick="syncSingleBookmark(<?= $id ?>)" title="ซิงค์ขึ้น Supabase เดี๋ยวนี้">
                                            <i class="fa fa-refresh"></i> ซิงค์ทันที
                                        </button>
                                        <!-- ปุ่มลบ -->
                                        <button type="button" class="btn btn-xs btn-default" style="border-radius: 6px; color: #ef4444;" title="ลบรายการ" onclick="confirmDeleteBookmark(<?= $id ?>, '<?= htmlspecialchars($bm['supabase_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>')">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
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

<!-- 🗑️ Modal Confirm Delete Bookmark -->
<div class="modal fade" id="modalDeleteBookmark" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <form method="POST" action="<?= admin_url("bookmarks") ?>">
            <input type="hidden" name="delete_bookmark" value="1">
            <input type="hidden" name="bookmark_id" id="deleteBmId" value="">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 16px 40px rgba(0,0,0,0.2);">
                <div class="modal-body text-center" style="padding: 26px 20px;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #fee2e2; color: #ef4444; font-size: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
                        <i class="fa fa-trash-o"></i>
                    </div>
                    <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">ยืนยันลบบุ๊กมาร์ก?</h4>
                    <p style="font-size: 12.5px; color: #64748b; margin-bottom: 14px;">
                        เลือกว่าต้องการลบข้อมูลที่ใดบ้าง
                    </p>
                    <div style="text-align: left; background: #f8fafc; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                        <label style="font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 0; cursor: pointer;">
                            <input type="checkbox" name="delete_cloud" value="1" checked> ลบบน Supabase Cloud ด้วย (ถ้ามี)
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; gap: 8px; padding: 12px 18px;">
                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; margin: 0;">
                        ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-danger btn-block" style="border-radius: 10px; font-weight: 700; margin: 0;">
                        ลบทิ้ง
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Filter table client-side
function filterBookmarksTable() {
    var val = (document.getElementById('bmSearchInput').value || '').toLowerCase();
    var rows = document.querySelectorAll('#bookmarksTable tbody tr.bm-row');
    rows.forEach(function(row) {
        var text = row.getAttribute('data-search') || '';
        if (text.indexOf(val) !== -1) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Real-time AJAX Sync Single Bookmark to Supabase
async function syncSingleBookmark(bookmarkId) {
    const badgeEl = document.getElementById('sync-badge-' + bookmarkId);
    const btnEl = document.getElementById('btn-sync-' + bookmarkId);
    const timeEl = document.getElementById('synced-time-' + bookmarkId);

    if (!badgeEl || !btnEl) return;

    btnEl.disabled = true;
    badgeEl.innerHTML = '<span class="badge-sync-pending"><i class="fa fa-spinner fa-spin"></i> กำลังซิงค์...</span>';

    try {
        const response = await fetch('<?= admin_url("bookmarks?action=sync_single&id=") ?>' + bookmarkId);
        const data = await response.json();

        btnEl.disabled = false;

        if (data.success) {
            badgeEl.innerHTML = '<span class="badge-sync-synced"><i class="fa fa-cloud"></i> ซิงค์แล้ว (Supabase)</span>';
            if (timeEl) {
                var now = new Date();
                var d = ('0' + now.getDate()).slice(-2);
                var m = ('0' + (now.getMonth() + 1)).slice(-2);
                var y = now.getFullYear();
                var h = ('0' + now.getHours()).slice(-2);
                var min = ('0' + now.getMinutes()).slice(-2);
                timeEl.textContent = d + '/' + m + '/' + y + ' ' + h + ':' + min;
            }
        } else {
            var err = data.error || data.message || 'เกิดข้อผิดพลาด';
            badgeEl.innerHTML = '<span class="badge-sync-failed" title="' + escapeHtml(err) + '"><i class="fa fa-exclamation-triangle"></i> ผิดพลาด</span>';
            alert('ซิงค์ล้มเหลว: ' + err);
        }
    } catch (err) {
        btnEl.disabled = false;
        badgeEl.innerHTML = '<span class="badge-sync-failed" title="' + escapeHtml(err.message) + '"><i class="fa fa-exclamation-triangle"></i> ผิดพลาด</span>';
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + err.message);
    }
}

function confirmDeleteBookmark(id, sbId) {
    document.getElementById('deleteBmId').value = id;
    $('#modalDeleteBookmark').modal('show');
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
