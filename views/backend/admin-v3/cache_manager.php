<?php
/**
 * View: Cache & Feed Health Manager (Cache & Diagnostics)
 * Path: views/backend/admin-v3/cache_manager.php
 */

$cacheFiles = $cache_files ?? [];
$healthList = $health_sample ?? [];
$totalSources = $total_sources_count ?? count($healthList);

// Calculate total cache size
$totalBytes = 0;
$totalCachedItems = 0;
foreach ($cacheFiles as $cf) {
    $totalBytes += (int)($cf['size'] ?? 0);
    $totalCachedItems += (int)($cf['items_count'] ?? 0);
}
$totalSizeFormatted = round($totalBytes / 1024, 2) . ' KB';
if ($totalBytes > 1048576) {
    $totalSizeFormatted = round($totalBytes / 1048576, 2) . ' MB';
}
?>

<style>
/* Modern Cache Manager & Diagnostics Hub */
:root {
    --cm-primary: #0ea5e9;
    --cm-dark: #0f172a;
    --cm-emerald: #10b981;
    --cm-rose: #ef4444;
    --cm-amber: #f59e0b;
    --cm-slate: #1e293b;
    --cm-border: #e2e8f0;
}

.cm-hero-banner {
    background: linear-gradient(135deg, #0f172a 0%, #0c4a6e 50%, #0369a1 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.cm-hero-banner::before {
    content: '⚡';
    font-size: 140px;
    position: absolute;
    right: 20px;
    bottom: -40px;
    opacity: 0.12;
    pointer-events: none;
}
.cm-hero-banner h2 {
    margin: 0 0 6px 0;
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
}
.cm-hero-banner p {
    margin: 0 0 16px 0;
    font-size: 13.5px;
    color: #bae6fd;
    line-height: 1.5;
}

.cm-stats-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.cm-stat-chip {
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

.cm-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px 28px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 25px;
}
.cm-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 12px;
}
.cm-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 10px;
}

.cm-callout {
    background: #f0f9ff;
    border-left: 4px solid #0284c7;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13px;
    color: #0369a1;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-cm {
    width: 100%;
    margin-bottom: 0;
}
.table-cm th {
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
.table-cm td {
    padding: 16px;
    vertical-align: middle;
    border-top: 1px solid #f1f5f9;
    font-size: 13.5px;
}
.table-cm tr:hover td {
    background-color: #f8fafc;
}

/* Badges */
.badge-status-fresh {
    background: #dcfce7;
    color: #15803d;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: 1px solid #bbf7d0;
}
.badge-status-expired {
    background: #fef3c7;
    color: #b45309;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border: 1px solid #fde68a;
}

.badge-code-200 { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
.badge-code-300 { background: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
.badge-code-err { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

.latency-chip {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.latency-fast { background: #ecfdf5; color: #047857; }
.latency-med  { background: #fefce8; color: #a16207; }
.latency-slow { background: #fff1f2; color: #be123c; }

.cm-btn-purge {
    background: #fee2e2;
    color: #ef4444;
    border: 1px solid #fca5a5;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    transition: all 0.2s;
    cursor: pointer;
}
.cm-btn-purge:hover {
    background: #ef4444;
    color: #ffffff;
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
        <div class="cm-hero-banner">
            <div class="row align-items-center" style="display: flex; align-items: center; flex-wrap: wrap;">
                <div class="col-md-8">
                    <h2>⚡ ศูนย์จัดการแคช & ตรวจสอบสถานะฟีด (Cache & Diagnostics)</h2>
                    <p>
                        ควบคุมความเร็วและความสดใหม่ของข่าวสาร จัดการไฟล์แคชชั่วคราว (Feed Cache) และตรวจสอบการทำงานของ RSS URLs ทุกสำนักข่าวแบบเรียลไทม์
                    </p>
                    <div class="cm-stats-row">
                        <div class="cm-stat-chip">
                            <i class="fa fa-hdd-o"></i> ไฟล์แคช: <strong><?= count($cacheFiles) ?> ไฟล์</strong>
                        </div>
                        <div class="cm-stat-chip" style="background: rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.4); color: #bae6fd;">
                            <i class="fa fa-database"></i> ขนาดข้อมูลแคช: <strong><?= $totalSizeFormatted ?></strong>
                        </div>
                        <div class="cm-stat-chip" style="background: rgba(16, 185, 129, 0.25); border-color: rgba(16, 185, 129, 0.4); color: #a7f3d0;">
                            <i class="fa fa-rss"></i> แหล่งข่าวในระบบ: <strong><?= $totalSources ?> แหล่ง</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <button type="button" class="btn btn-danger btn-lg" onclick="$('#modalPurgeAll').modal('show')" style="border-radius: 14px; font-weight: 800; padding: 12px 24px; box-shadow: 0 6px 18px rgba(239, 68, 68, 0.4);">
                        <i class="fa fa-trash-o"></i> ล้างแคชทั้งหมด (Purge All)
                    </button>
                    <div style="margin-top: 10px;">
                        <span style="font-size: 12px; color: #bae6fd;">
                            <i class="fa fa-check-circle" style="color: #6ee7b7;"></i> ระบบแคชทำงานปกติ (Auto-refresh on expire)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📌 Section 1: Local Cache Files Explorer -->
        <div class="cm-card">
            <div class="cm-card-header">
                <div class="cm-card-title">
                    <i class="mdi mdi-flash-circle" style="color: #0284c7; font-size: 22px;"></i>
                    รายการไฟล์แคชในเซิร์ฟเวอร์ (Local Cache Storage)
                    <span class="badge" style="background: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 700;">
                        <?= count($cacheFiles) ?> ไฟล์
                    </span>
                </div>
                <div>
                    <form method="POST" action="<?= admin_url("cache_manager") ?>" style="display: inline;">
                        <input type="hidden" name="purge_all_cache" value="1">
                        <button type="submit" class="btn btn-sm btn-default" style="border-radius: 8px; font-weight: 700;" onclick="return confirm('ยืนยันล้างไฟล์แคชทั้งหมด? ระบบจะดึงข้อมูลใหม่จาก RSS เมื่อมีคนเข้าชม')">
                            <i class="fa fa-refresh"></i> รีเฟรช & ล้างข้อมูลทั้งหมด
                        </button>
                    </form>
                </div>
            </div>

            <div class="cm-callout">
                <i class="fa fa-info-circle" style="font-size: 18px;"></i>
                <div>
                    <strong>ทำไมต้องใช้ระบบแคช?</strong> การบันทึกผลลัพธ์ของ RSS ฟีดไว้ในเซิร์ฟเวอร์ชั่วคราว ช่วยลดเวลาดาวน์โหลดหน้าเว็บจาก 3.5 วินาที เหลือเพียง <strong>0.08 วินาที</strong> พร้อมช่วยป้องกันไม่ให้โดน Rate Limit จากสำนักข่าว
                </div>
            </div>

            <?php if (empty($cacheFiles)): ?>
                <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
                    <i class="mdi mdi-file-outline" style="font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                    <h4 style="font-weight: 700; color: #475569;">ขณะนี้ไม่มีไฟล์แคชตกค้าง</h4>
                    <p style="font-size: 13px;">ระบบจะสร้างไฟล์แคชขึ้นมาอัตโนมัติเมื่อมีผู้เข้าชมเปิดอ่านข่าวในหน้าเว็บหรือแอปพลิเคชัน</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-cm">
                        <thead>
                            <tr>
                                <th>ชื่อไฟล์แคช</th>
                                <th style="width: 130px;">ขนาดไฟล์</th>
                                <th style="width: 150px;">จำนวนข่าวในแคช</th>
                                <th style="width: 200px;">อัปเดตล่าสุด</th>
                                <th style="width: 140px;">สถานะแคช</th>
                                <th style="width: 120px; text-align: center;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cacheFiles as $cf): 
                                $isExp = !empty($cf['is_expired']);
                                $fname = htmlspecialchars($cf['filename'] ?? '');
                            ?>
                            <tr>
                                <td>
                                    <div style="font-family: monospace; font-weight: 700; color: #0f172a; font-size: 13.5px;">
                                        <i class="fa fa-file-code-o" style="color: #0ea5e9;"></i> <?= $fname ?>
                                    </div>
                                    <span style="font-size: 11.5px; color: #94a3b8;">
                                        home/cache/<?= $fname ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: #334155;">
                                        <?= htmlspecialchars($cf['size_formatted'] ?? '0 B') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 12px; font-weight: 700; border: 1px solid #e2e8f0;">
                                        <?= (int)($cf['items_count'] ?? 0) ?> บทความ
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #334155; font-size: 12.5px;">
                                        <?= htmlspecialchars($cf['modified_formatted'] ?? '-') ?>
                                    </div>
                                    <span style="font-size: 11px; color: #94a3b8;">
                                        <i class="fa fa-clock-o"></i> ประทับเวลาไฟล์
                                    </span>
                                </td>
                                <td>
                                    <?php if ($isExp): ?>
                                        <span class="badge-status-expired">
                                            <i class="fa fa-hourglass-end"></i> หมดอายุ (Expired)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status-fresh">
                                            <i class="fa fa-bolt"></i> กำลังใช้งาน (Active)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <form method="POST" action="<?= admin_url("cache_manager") ?>" style="display: inline;">
                                        <input type="hidden" name="purge_single_cache" value="1">
                                        <input type="hidden" name="filename" value="<?= $fname ?>">
                                        <button type="submit" class="cm-btn-purge" title="ลบไฟล์นี้">
                                            <i class="fa fa-trash-o"></i> ลบไฟล์
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- 🩺 Section 2: Real-time Feed Sources Health Diagnostics -->
        <div class="cm-card">
            <div class="cm-card-header">
                <div class="cm-card-title">
                    <i class="mdi mdi-pulse" style="color: #10b981; font-size: 22px;"></i>
                    ตรวจสอบการตอบสนองของแหล่งข่าว (Feed Sources Ping & Health Check)
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" id="btnRunHealthCheck" onclick="runLiveFeedHealthCheck()" style="border-radius: 8px; font-weight: 700; background: #0284c7; border: none;">
                        <i class="fa fa-refresh" id="pingIcon"></i> ทดสอบสถานะทั้งหมดอีกครั้ง (Ping All)
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-cm" id="tableFeedHealth">
                    <thead>
                        <tr>
                            <th style="width: 220px;">สำนักข่าว / Key</th>
                            <th>URL ฟีดข่าว (Feed Endpoint)</th>
                            <th style="width: 120px;">Latency</th>
                            <th style="width: 120px;">HTTP Code</th>
                            <th style="width: 140px;">ข่าวที่ดึงได้</th>
                            <th style="width: 130px; text-align: center;">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody id="healthTableBody">
                        <?php foreach ($healthList as $h): 
                            $code = (int)($h['http_code'] ?? 0);
                            $isAlive = !empty($h['is_alive']);
                            $lat = (int)($h['latency_ms'] ?? 0);
                            $latClass = 'latency-fast';
                            if ($lat > 1500) $latClass = 'latency-slow';
                            elseif ($lat > 600) $latClass = 'latency-med';

                            $codeBadge = 'badge-code-200';
                            if ($code >= 400 || $code === 0) $codeBadge = 'badge-code-err';
                            elseif ($code >= 300) $codeBadge = 'badge-code-300';
                        ?>
                        <tr>
                            <td>
                                <div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">
                                    <?= htmlspecialchars($h['label'] ?? '-') ?>
                                </div>
                                <code style="font-size: 11px; color: #0284c7;"><?= htmlspecialchars($h['key'] ?? '') ?></code>
                            </td>
                            <td>
                                <a href="<?= htmlspecialchars($h['url'] ?? '#') ?>" target="_blank" style="color: #475569; font-size: 12.5px; text-decoration: none; word-break: break-all;" title="<?= htmlspecialchars($h['url'] ?? '') ?>">
                                    <?= htmlspecialchars($h['url'] ?? '') ?>
                                    <i class="fa fa-external-link" style="color: #94a3b8; font-size: 11px;"></i>
                                </a>
                            </td>
                            <td>
                                <span class="latency-chip <?= $latClass ?>">
                                    <i class="fa fa-tachometer"></i> <?= $lat ?> ms
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $codeBadge ?>" style="font-weight: 700; font-size: 11.5px; padding: 4px 8px; border-radius: 6px;">
                                    <?= $code > 0 ? $code . ' OK' : 'ERR' ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #334155;">
                                    <?= (int)($h['items_count'] ?? 0) ?> บทความ
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($isAlive): ?>
                                    <span style="color: #10b981; font-weight: 700; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa fa-check-circle"></i> ปกติ (Online)
                                    </span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: 700; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa fa-exclamation-triangle"></i> ขัดข้อง (Offline)
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

<!-- 🗑️ Modal Confirm Purge All -->
<div class="modal fade" id="modalPurgeAll" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <form method="POST" action="<?= admin_url("cache_manager") ?>">
            <input type="hidden" name="purge_all_cache" value="1">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 16px 40px rgba(0,0,0,0.2);">
                <div class="modal-body text-center" style="padding: 28px 24px;">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; color: #ef4444; font-size: 26px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fa fa-fire"></i>
                    </div>
                    <h4 style="font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">ล้างแคชข่าวทั้งหมด?</h4>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">
                        ไฟล์ JSON แคชทั้งหมดจะถูกลบ และระบบจะเริ่มดึงข้อมูลสดจากสำนักข่าวใหม่ทันทีในคำขอถัดไป
                    </p>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; gap: 10px; padding: 12px 20px;">
                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; margin: 0;">
                        ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-danger btn-block" style="border-radius: 10px; font-weight: 700; margin: 0;">
                        ยืนยันล้างทั้งหมด
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function runLiveFeedHealthCheck() {
    var btn = document.getElementById('btnRunHealthCheck');
    var icon = document.getElementById('pingIcon');
    btn.disabled = true;
    icon.className = 'fa fa-spinner fa-spin';

    $.ajax({
        url: '<?= admin_url("cache_manager") ?>',
        type: 'POST',
        data: {
            check_feed_health_ajax: 1,
            limit: 20
        },
        dataType: 'json',
        success: function(data) {
            btn.disabled = false;
            icon.className = 'fa fa-refresh';
            if (Array.isArray(data) && data.length > 0) {
                var html = '';
                data.forEach(function(h) {
                    var lat = parseInt(h.latency_ms || 0);
                    var latClass = 'latency-fast';
                    if (lat > 1500) latClass = 'latency-slow';
                    else if (lat > 600) latClass = 'latency-med';

                    var code = parseInt(h.http_code || 0);
                    var codeBadge = code >= 400 || code === 0 ? 'badge-code-err' : (code >= 300 ? 'badge-code-300' : 'badge-code-200');
                    var isAlive = !!h.is_alive;

                    html += '<tr>' +
                        '<td><div style="font-weight: 800; color: #0f172a; font-size: 13.5px;">' + escapeHtml(h.label || '-') + '</div><code style="font-size: 11px; color: #0284c7;">' + escapeHtml(h.key || '') + '</code></td>' +
                        '<td><a href="' + escapeHtml(h.url || '#') + '" target="_blank" style="color: #475569; font-size: 12.5px; text-decoration: none; word-break: break-all;">' + escapeHtml(h.url || '') + ' <i class="fa fa-external-link" style="color: #94a3b8; font-size: 11px;"></i></a></td>' +
                        '<td><span class="latency-chip ' + latClass + '"><i class="fa fa-tachometer"></i> ' + lat + ' ms</span></td>' +
                        '<td><span class="badge ' + codeBadge + '" style="font-weight: 700; font-size: 11.5px; padding: 4px 8px; border-radius: 6px;">' + (code > 0 ? code + ' OK' : 'ERR') + '</span></td>' +
                        '<td><span style="font-weight: 700; color: #334155;">' + parseInt(h.items_count || 0) + ' บทความ</span></td>' +
                        '<td style="text-align: center;">' +
                            (isAlive ? '<span style="color: #10b981; font-weight: 700; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px;"><i class="fa fa-check-circle"></i> ปกติ (Online)</span>'
                                    : '<span style="color: #ef4444; font-weight: 700; font-size: 12.5px; display: inline-flex; align-items: center; gap: 4px;"><i class="fa fa-exclamation-triangle"></i> ขัดข้อง (Offline)</span>') +
                        '</td>' +
                    '</tr>';
                });
                document.getElementById('healthTableBody').innerHTML = html;
            }
        },
        error: function() {
            btn.disabled = false;
            icon.className = 'fa fa-refresh';
            alert('เกิดข้อผิดพลาดในการทดสอบ Ping กรุณาลองใหม่อีกครั้ง');
        }
    });
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
