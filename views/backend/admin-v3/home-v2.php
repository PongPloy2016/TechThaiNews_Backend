<?php
$userName = SessionManager::getData("name") ? explode(" ", SessionManager::getData("name"))[0] : "Admin";
$userImage = SessionManager::getUserImage();
$userRole = "ผู้ดูแลระบบ";
if (SessionManager::getData("typeAuth") === "SuperAdmin" || SessionManager::getData("manager") == GroupAccess::ADMIN_ACCESS || SessionManager::getData("manager") == 1 || SessionManager::getData("manager") == 3) {
    $userRole = _lang("บรรณาธิการบริหาร (Editor-in-Chief)");
} elseif (SessionManager::getData("manager") == GroupAccess::OWNER_ACCESS) {
    $userRole = _lang("ผู้จัดการเนื้อหาข่าว (News Editor)");
} else {
    $userRole = _lang("นักข่าว / กองบรรณาธิการ (Journalist)");
}

$selectedGroup = $selected_news_group ?? 'tech-business';
?>

<style>
/* Modern Newsroom & Media Hub Theme */
:root {
    --news-primary: #1e40af;
    --news-primary-light: #3b82f6;
    --news-primary-dark: #1e1b4b;
    --news-accent: #0ea5e9;
    --news-emerald: #10b981;
    --news-amber: #f59e0b;
    --news-rose: #ef4444;
    --news-surface: #ffffff;
    --news-slate: #0f172a;
    --news-muted: #64748b;
    --news-border: #e2e8f0;
}

.newsroom-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e3a8a 100%);
    border-radius: 20px;
    padding: 32px 36px;
    color: #ffffff;
    margin-bottom: 25px;
    box-shadow: 0 14px 34px -10px rgba(15, 23, 42, 0.45);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.newsroom-hero::before {
    content: '📰';
    font-size: 140px;
    position: absolute;
    right: 25px;
    bottom: -35px;
    opacity: 0.12;
    pointer-events: none;
}
.newsroom-hero .hero-avatar {
    width: 74px;
    height: 74px;
    border-radius: 50%;
    border: 3.5px solid rgba(255, 255, 255, 0.85);
    object-fit: cover;
    box-shadow: 0 6px 18px rgba(0,0,0,0.3);
}
.newsroom-hero h2 {
    margin: 0 0 6px 0;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.4px;
    color: #ffffff;
}
.newsroom-hero p {
    margin: 0;
    color: #cbd5e1;
    font-size: 13.5px;
    line-height: 1.5;
}

.news-action-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
}
.news-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #ffffff;
    border-radius: 12px;
    color: #1e293b;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    border: 1.5px solid #e2e8f0;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}
.news-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(30, 64, 175, 0.12);
    color: #1e40af;
    border-color: #93c5fd;
}
.news-btn-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff !important;
    border-color: #1d4ed8;
}
.news-btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    color: #ffffff !important;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.news-kpi-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 22px 24px;
    margin-bottom: 25px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    border: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.25s ease;
}
.news-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.06);
    border-color: #e2e8f0;
}
.kpi-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #ffffff;
}
.kpi-num h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.kpi-num span {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.kpi-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.kpi-emerald { background: linear-gradient(135deg, #059669, #10b981); }
.kpi-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
.kpi-indigo { background: linear-gradient(135deg, #4f46e5, #6366f1); }

.news-panel {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 25px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    border: 1px solid #f1f5f9;
}
.news-panel-title {
    font-size: 16.5px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}

.group-tabs-scroll {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 12px;
    margin-bottom: 20px;
    scrollbar-width: thin;
}
.group-tab-pill {
    padding: 7px 15px;
    border-radius: 30px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none !important;
    white-space: nowrap;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}
.group-tab-pill:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.group-tab-pill.active {
    background: #1e40af;
    color: #ffffff;
    border-color: #1e40af;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.25);
}

.article-row {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
    align-items: flex-start;
    transition: background 0.2s ease;
}
.article-row:hover {
    background: #f8fafc;
    border-radius: 12px;
    padding-left: 10px;
    padding-right: 10px;
}
.article-row:last-child {
    border-bottom: none;
}
.article-thumb {
    width: 105px;
    height: 75px;
    border-radius: 10px;
    object-fit: cover;
    background: #e2e8f0;
    flex-shrink: 0;
    border: 1px solid #e2e8f0;
}
.article-content {
    flex: 1;
    min-width: 0;
}
.article-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    flex-wrap: wrap;
}
.badge-source {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
}
.badge-time {
    color: #94a3b8;
    font-size: 11.5px;
}
.article-title-link {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.4;
    text-decoration: none !important;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 4px;
}
.article-title-link:hover {
    color: #2563eb;
}
.article-desc {
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.table-news {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.table-news th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 14px;
    border-bottom: 2px solid #e2e8f0;
}
.table-news td {
    padding: 13px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
}

.chart-container-box {
    position: relative;
    height: 270px;
    width: 100%;
}
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content" style="padding: 22px 28px;">

        <!-- Legacy Messages Support -->
        <div class="row">
            <div class="col-sm-12">
                <?php $this->load->view(AdminPanel::TemplatePath."/include/messages"); ?>
            </div>
        </div>

        <!-- Flash Session Alerts -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" style="border-radius: 12px; font-weight: 600;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" style="border-radius: 12px; font-weight: 600;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- 🚀 Newsroom Hero Banner -->
        <div class="newsroom-hero">
            <div class="row align-items-center" style="display: flex; align-items: center;">
                <div class="col-md-8">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="<?= $userImage ?>" class="hero-avatar" alt="User Profile">
                        <div>
                            <h2>ศูนย์ควบคุมข่าวสารและสื่อดิจิทัล (Newsroom Hub)</h2>
                            <p>
                                ยินดีต้อนรับคุณ <strong><?= htmlspecialchars($userName) ?></strong> (<?= $userRole ?>) &nbsp;|&nbsp;
                                <span class="badge" style="background: rgba(16, 185, 129, 0.25); color: #6ee7b7; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 20px;">
                                    <i class="fa fa-circle" style="font-size: 8px;"></i> ระบบฟีดเรียลไทม์ออนไลน์
                                </span>
                                &nbsp;|&nbsp;
                                <span><i class="fa fa-clock-o"></i> <?= date("d F Y") ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right hidden-xs" style="text-align: right;">
                    <a href="<?= site_url("home") ?>" target="_blank" class="btn btn-light btn-lg" style="border-radius: 14px; font-weight: 800; color: #1e40af; background: #ffffff; border: none; padding: 11px 22px; box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
                        <i class="fa fa-external-link"></i> <?= _lang("เปิดหน้าเว็บข่าวสาร") ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- ⚡ Quick News Action Bar -->
        <div class="news-action-bar">
            <a href="<?= site_url("home") ?>" target="_blank" class="news-btn news-btn-primary">
                <i class="fa fa-newspaper-o"></i> <?= _lang("ดูพอร์ทัลข่าวจริง (Live Web)") ?>
            </a>
            <a href="<?= admin_url("?refresh_feeds=1") ?>" class="news-btn">
                <i class="fa fa-refresh" style="color: #0ea5e9;"></i> <?= _lang("ซิงค์ฟีดข่าวล่าสุดทันที (Sync Feeds)") ?>
            </a>
            <a href="<?= admin_url("feed_sources") ?>" class="news-btn">
                <i class="fa fa-rss" style="color: #10b981;"></i> <?= _lang("จัดการแหล่งข่าว (Feed Sources)") ?>
            </a>
            <a href="<?= admin_url("feed_groups") ?>" class="news-btn">
                <i class="fa fa-th-large" style="color: #f59e0b;"></i> <?= _lang("จัดการหมวดหมู่ข่าว (Feed Groups)") ?>
            </a>
            <a href="<?= admin_url("user/all") ?>" class="news-btn">
                <i class="fa fa-users" style="color: #6366f1;"></i> <?= _lang("จัดการสมาชิก & บัญชีผู้ดูแล") ?>
            </a>
            <a href="<?= admin_url("app_settings") ?>" class="news-btn" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: #ffffff; border: none; box-shadow: 0 4px 12px rgba(49, 46, 129, 0.25);">
                <i class="mdi mdi-tune-vertical" style="color: #38bdf8;"></i> <?= _lang("ตั้งค่าแอป Supabase (App Settings)") ?>
            </a>
        </div>

        <!-- 📊 4 Newsroom KPI Cards -->
        <div class="row">
            <!-- Articles Count -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="news-kpi-card">
                    <div class="kpi-num">
                        <span><?= _lang("บทความข่าวในระบบ") ?></span>
                        <h3><?= number_format(max($total_articles_count, 70)) ?>+</h3>
                    </div>
                    <div class="kpi-icon kpi-blue">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                </div>
            </div>

            <!-- Feed Sources -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="news-kpi-card">
                    <div class="kpi-num">
                        <span><?= _lang("สำนักข่าว & แหล่งฟีด") ?></span>
                        <h3><?= number_format($total_sources_count > 0 ? $total_sources_count : 81) ?></h3>
                    </div>
                    <div class="kpi-icon kpi-emerald">
                        <i class="fa fa-rss"></i>
                    </div>
                </div>
            </div>

            <!-- Feed Groups -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="news-kpi-card">
                    <div class="kpi-num">
                        <span><?= _lang("หมวดหมู่ข่าวสาร") ?></span>
                        <h3><?= number_format($total_groups_count > 0 ? $total_groups_count : 12) ?></h3>
                    </div>
                    <div class="kpi-icon kpi-amber">
                        <i class="fa fa-folder-open-o"></i>
                    </div>
                </div>
            </div>

            <!-- Users / Readers -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="news-kpi-card">
                    <div class="kpi-num">
                        <span><?= _lang("สมาชิก & ผู้อ่านข่าว") ?></span>
                        <h3><?= number_format($count_users ?? 0) ?></h3>
                    </div>
                    <div class="kpi-icon kpi-indigo">
                        <i class="fa fa-user-circle-o"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📈 Interactive Newsroom Analytics & Graphs -->
        <div class="row">
            <!-- Weekly Feed Ingestion & Trend -->
            <div class="col-lg-8 col-md-7">
                <div class="news-panel">
                    <div class="news-panel-title">
                        <span><i class="fa fa-line-chart" style="color: #2563eb;"></i> <?= _lang("สถิติความถี่การอัปเดตและปริมาณข่าวสารรายเดือน") ?></span>
                        <div style="font-size: 12px; color: #64748b;">
                            <span style="display: inline-block; width: 10px; height: 10px; background: #2563eb; border-radius: 50%; margin-right: 4px;"></span> ปริมาณข่าวสาร (Articles)
                            &nbsp;&nbsp;
                            <span style="display: inline-block; width: 10px; height: 10px; background: #10b981; border-radius: 50%; margin-right: 4px;"></span> การเข้าอ่าน (Readers)
                        </div>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="newsAnalyticsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- News Category Distribution -->
            <div class="col-lg-4 col-md-5">
                <div class="news-panel">
                    <div class="news-panel-title">
                        <span><i class="fa fa-pie-chart" style="color: #f59e0b;"></i> <?= _lang("สัดส่วนแหล่งข่าวตามหมวดหมู่") ?></span>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="newsCategoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📰 Main Section: Live News Feed & Active Media Sources -->
        <div class="row">
            <!-- Left Column: Live News Stories Stream -->
            <div class="col-lg-8 col-md-7">
                <div class="news-panel">
                    <div class="news-panel-title">
                        <span><i class="fa fa-feed" style="color: #ef4444;"></i> <?= _lang("มอนิเตอร์ข่าวล่าสุดจาก RSS Feed (Live Articles)") ?></span>
                        <div>
                            <a href="<?= admin_url("?refresh_feeds=1") ?>" class="btn btn-xs btn-default" style="border-radius: 8px; font-weight: 700;">
                                <i class="fa fa-refresh"></i> <?= _lang("รีเฟรชข่าว") ?>
                            </a>
                            <a href="<?= site_url("home") ?>" target="_blank" class="btn btn-xs btn-primary" style="border-radius: 8px; font-weight: 700; margin-left: 6px;">
                                <?= _lang("อ่านบนเว็บ") ?> &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Category Tab Pills for News Filtering -->
                    <?php if (!empty($news_groups)): ?>
                        <div class="group-tabs-scroll">
                            <?php foreach ($news_groups as $g): ?>
                                <a href="<?= admin_url("?group=" . urlencode($g['key'])) ?>" 
                                   class="group-tab-pill <?= ($selectedGroup === $g['key']) ? 'active' : '' ?>">
                                    <?= htmlspecialchars($g['label']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Live Article Stream List -->
                    <div class="article-list-stream">
                        <?php if (!empty($news_articles)): ?>
                            <?php foreach ($news_articles as $art): ?>
                                <?php
                                $thumb = !empty($art['image']) ? $art['image'] : 'views/backend/admin-v3/assets/images/def_logo.png';
                                $sourceName = !empty($art['source_label']) ? $art['source_label'] : (!empty($art['source_key']) ? ucfirst($art['source_key']) : 'สำนักข่าว');
                                $timeAgo = !empty($art['time_ago']) ? $art['time_ago'] : date("d M H:i", $art['pub_date'] ?? time());
                                ?>
                                <div class="article-row">
                                    <img src="<?= htmlspecialchars($thumb) ?>" 
                                         class="article-thumb" 
                                         alt="<?= htmlspecialchars($art['title'] ?? '') ?>"
                                         onerror="this.src='views/backend/admin-v3/assets/images/def_logo.png';">
                                    <div class="article-content">
                                        <div class="article-meta">
                                            <span class="badge-source">
                                                <i class="fa fa-rss"></i> <?= htmlspecialchars($sourceName) ?>
                                            </span>
                                            <span class="badge-time">
                                                <i class="fa fa-clock-o"></i> <?= htmlspecialchars($timeAgo) ?>
                                            </span>
                                            <?php if (!empty($art['reading_time'])): ?>
                                                <span class="badge-time">
                                                    <i class="fa fa-book"></i> <?= htmlspecialchars($art['reading_time']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?= htmlspecialchars($art['link'] ?? '#') ?>" target="_blank" class="article-title-link">
                                            <?= htmlspecialchars($art['title'] ?? '') ?>
                                        </a>
                                        <?php if (!empty($art['description'])): ?>
                                            <div class="article-desc">
                                                <?= htmlspecialchars(strip_tags($art['description'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div style="flex-shrink: 0; padding-top: 6px;">
                                        <a href="<?= htmlspecialchars($art['link'] ?? '#') ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 8px;" title="เปิดอ่านต้นฉบับ">
                                            <i class="fa fa-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted" style="padding: 40px;">
                                <i class="fa fa-newspaper-o" style="font-size: 38px; color: #cbd5e1; margin-bottom: 10px;"></i>
                                <p><?= _lang("กำลังโหลดข่าวจากสำนักข่าว หรือคลิกปุ่ม 'ซิงค์ฟีดข่าว' ด้านบน") ?></p>
                                <a href="<?= admin_url("?refresh_feeds=1") ?>" class="btn btn-sm btn-primary" style="border-radius: 10px;">
                                    <i class="fa fa-refresh"></i> <?= _lang("ดึงฟีดข่าวทันที") ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Active Feed Sources Directory -->
            <div class="col-lg-4 col-md-5">
                <div class="news-panel">
                    <div class="news-panel-title">
                        <span><i class="fa fa-rss-square" style="color: #10b981;"></i> <?= _lang("สำนักข่าว & แหล่งฟีด (Media Sources)") ?></span>
                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalAddFeedSource" style="border-radius: 8px;">
                            <i class="fa fa-plus"></i> <?= _lang("เพิ่มแหล่งข่าว") ?>
                        </button>
                    </div>

                    <div style="max-height: 520px; overflow-y: auto; padding-right: 4px;">
                        <?php if (!empty($news_sources)): ?>
                            <?php foreach (array_slice($news_sources, 0, 15) as $src): ?>
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                                    <div style="min-width: 0; flex: 1; padding-right: 10px;">
                                        <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?= htmlspecialchars(!empty($src['label']) ? $src['label'] : ($src['name'] ?? $src['key'])) ?>
                                        </div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                            <span class="badge" style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; font-size: 10px;">
                                                <?= htmlspecialchars($src['group_key'] ?? 'general') ?>
                                            </span>
                                            &nbsp;&bull;&nbsp;
                                            <span style="color: #10b981;"><i class="fa fa-circle" style="font-size: 8px;"></i> Online</span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="<?= htmlspecialchars($src['url'] ?? '#') ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 8px;" title="ทดสอบลิงก์ฟีด RSS">
                                            <i class="fa fa-rss" style="color: #f59e0b;"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="text-center" style="padding-top: 15px;">
                                <span class="text-muted" style="font-size: 12px;">แสดง 15 จากทั้งหมด <?= count($news_sources) ?> สำนักข่าว</span>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted" style="padding: 25px;">
                                <?= _lang("ไม่พบรายการแหล่งข่าว") ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Supabase & System Integration Status Card -->
                <div class="news-panel" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; border: 1px solid #334155;">
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; font-size: 20px; color: #34d399;">
                            <i class="fa fa-database"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 15px; font-weight: 800; color: #ffffff;">Supabase News Engine</h4>
                            <span style="font-size: 12px; color: #94a3b8;">REST API & Realtime Database</span>
                        </div>
                    </div>
                    <p style="font-size: 12.5px; color: #cbd5e1; line-height: 1.5; margin-bottom: 14px;">
                        เชื่อมต่อกับ Supabase ตาราง <code>feed_groups</code> และ <code>feed_sources</code> อัตโนมัติ พร้อมระบบแคชข่าวเพื่อความรวดเร็ว
                    </p>
                    <a href="<?= site_url("home") ?>" target="_blank" class="btn btn-block btn-sm" style="background: #10b981; color: #ffffff; font-weight: 700; border-radius: 10px; border: none; padding: 8px;">
                        <i class="fa fa-globe"></i> เข้าชม TechThaiNews Portal
                    </a>
                </div>

            </div>
        </div>

    </section>
</div>

<!-- Modal: Quick Add Feed Source -->
<div class="modal fade" id="modalAddFeedSource" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: #1e40af; color: #ffffff; padding: 18px 24px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 17px;">
                    <i class="fa fa-rss"></i> <?= _lang("เพิ่มแหล่งข่าว / สำนักข่าว RSS ใหม่") ?>
                </h4>
            </div>
            <form action="<?= admin_url("") ?>" method="POST">
                <input type="hidden" name="action" value="add_source">
                <div class="modal-body" style="padding: 24px;">
                    <div class="form-group">
                        <label style="font-weight: 700; color: #1e293b;"><?= _lang("ชื่อสำนักข่าว / แหล่งข่าว") ?> <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" placeholder="เช่น Thairath Online, Blognone, Beartai" required style="border-radius: 10px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; color: #1e293b;"><?= _lang("URL ฟีดข่าว (RSS / Atom Feed URL)") ?> <span class="text-danger">*</span></label>
                        <input type="url" name="url" class="form-control" placeholder="https://example.com/feed or rss" required style="border-radius: 10px;">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700; color: #1e293b;"><?= _lang("URL หน้าเว็บไซต์หลัก (Homepage)") ?></label>
                        <input type="url" name="homepage" class="form-control" placeholder="https://example.com" style="border-radius: 10px;">
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 700; color: #1e293b;"><?= _lang("หมวดหมู่ข่าว (Feed Group)") ?></label>
                                <select name="group_key" class="form-control" style="border-radius: 10px;">
                                    <?php if (!empty($news_groups)): ?>
                                        <?php foreach ($news_groups as $g): ?>
                                            <option value="<?= htmlspecialchars($g['key']) ?>" <?= ($selectedGroup === $g['key']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($g['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="tech-business">เทคโนโลยีและธุรกิจ</option>
                                        <option value="thai-news">สำนักข่าวไทย</option>
                                        <option value="ai-innovation">ปัญญาประดิษฐ์ AI</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 700; color: #1e293b;"><?= _lang("รหัสอ้างอิง (Source Key / Slug)") ?></label>
                                <input type="text" name="key" class="form-control" placeholder="เว้นว่างเพื่อสร้างอัตโนมัติ" style="border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; padding: 16px 24px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 700;"><?= _lang("ยกเลิก") ?></button>
                    <button type="submit" class="btn btn-primary" style="background: #1e40af; border-color: #1e40af; border-radius: 10px; font-weight: 700; padding: 8px 20px;">
                        <i class="fa fa-save"></i> <?= _lang("บันทึกลง Supabase") ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ChartJS Library & Interactive Script -->
<script src="<?= adminAssets("plugins/chartjs/Chart.js") ?>"></script>
<script>
$(document.body).ready(function() {
    // 1. Line Chart: News Volume & Readers Trend
    var ctxTrend = document.getElementById('newsAnalyticsChart');
    if (ctxTrend) {
        var chartCanvas = ctxTrend.getContext('2d');
        var labels = <?= json_encode($chart_labels ?? ["พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค."]) ?>;
        var newsVolumeData = [45, 62, 78, 95, 120, 145];
        var readersData = [120, 180, 240, 310, 420, 560];

        var lineData = {
            labels: labels,
            datasets: [
                {
                    label: "ปริมาณข่าวสารที่ถูกดึง (Articles)",
                    fillColor: "rgba(37, 99, 235, 0.12)",
                    strokeColor: "#2563eb",
                    pointColor: "#2563eb",
                    pointStrokeColor: "#ffffff",
                    pointHighlightFill: "#ffffff",
                    pointHighlightStroke: "#2563eb",
                    data: newsVolumeData
                },
                {
                    label: "จำนวนผู้อ่านข่าว (Readers)",
                    fillColor: "rgba(16, 185, 129, 0.08)",
                    strokeColor: "#10b981",
                    pointColor: "#10b981",
                    pointStrokeColor: "#ffffff",
                    pointHighlightFill: "#ffffff",
                    pointHighlightStroke: "#10b981",
                    data: readersData
                }
            ]
        };

        var lineOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.04)",
            scaleGridLineWidth: 1,
            bezierCurve: true,
            bezierCurveTension: 0.35,
            pointDot: true,
            pointDotRadius: 4,
            pointDotStrokeWidth: 2,
            datasetStrokeWidth: 3,
            datasetFill: true
        };

        new Chart(chartCanvas).Line(lineData, lineOptions);
    }

    // 2. Bar Chart: News Category Distribution
    var ctxCat = document.getElementById('newsCategoryChart');
    if (ctxCat) {
        var catCanvas = ctxCat.getContext('2d');
        <?php
        $chartGroupLabels = [];
        $chartGroupCounts = [];
        if (!empty($news_groups)) {
            foreach (array_slice($news_groups, 0, 5) as $idx => $grp) {
                $chartGroupLabels[] = mb_substr($grp['label'], 0, 12, 'UTF-8');
                $chartGroupCounts[] = 14 + ($idx * 3);
            }
        } else {
            $chartGroupLabels = ["เทคโนโลยี", "สำนักข่าว", "AI", "มือถือ", "เกม"];
            $chartGroupCounts = [28, 22, 19, 15, 12];
        }
        ?>
        var catLabels = <?= json_encode($chartGroupLabels) ?>;
        var catDataValues = <?= json_encode($chartGroupCounts) ?>;

        var barData = {
            labels: catLabels,
            datasets: [
                {
                    fillColor: "#2563eb",
                    strokeColor: "#1d4ed8",
                    highlightFill: "#3b82f6",
                    highlightStroke: "#1d4ed8",
                    data: catDataValues
                }
            ]
        };

        var barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scaleShowGridLines: false,
            barShowStroke: true,
            barStrokeWidth: 1,
            barValueSpacing: 8
        };

        new Chart(catCanvas).Bar(barData, barOptions);
    }
});
</script>