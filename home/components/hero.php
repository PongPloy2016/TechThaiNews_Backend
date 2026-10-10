<?php
/**
 * Hero Component
 * Displays banner, quick statistics, and primary call-to-action buttons
 */
$totalGroups = count($groups ?? []);
$totalSources = count($sources ?? []);
$activeSources = 0;
foreach (($sources ?? []) as $s) {
    if (!empty($s['is_active'])) {
        $activeSources++;
    }
}
?>

<style>
    .hero-section {
        background: radial-gradient(circle at 80% 20%, rgba(37, 99, 235, 0.25) 0%, transparent 50%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        padding: 56px 24px 64px 24px;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .hero-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-badge-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseAnimation 2s infinite;
    }

    @keyframes pulseAnimation {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .hero-title {
        font-size: 2.4rem;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
        max-width: 900px;
    }

    .hero-title .gradient-text {
        background: linear-gradient(135deg, #60a5fa, #93c5fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        color: #cbd5e1;
        font-size: 1.05rem;
        line-height: 1.7;
        max-width: 780px;
        margin-bottom: 32px;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }

    .btn-hero {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition);
        border: none;
        text-decoration: none;
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
    }

    .btn-hero-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.5);
    }

    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
    }

    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.18);
        transform: translateY(-2px);
    }

    .hero-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 10px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: var(--radius);
        padding: 18px 22px;
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: var(--transition);
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(96, 165, 250, 0.4);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-num {
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.1;
        color: #ffffff;
    }

    .stat-label {
        font-size: 0.84rem;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.85rem;
        }
        .hero-subtitle {
            font-size: 0.95rem;
        }
    }
</style>

<section class="hero-section">
    <div class="hero-container">
        <div class="hero-badge-row">
            <span class="status-pill">
                <span class="pulse-dot"></span> Supabase Database Online
            </span>
            <span style="font-size: 0.82rem; color: #94a3b8; display: inline-flex; align-items: center; gap: 4px;">
                <i class="material-icons" style="font-size: 16px; color: #60a5fa;">dns</i> zpigezwdsjelwvdpyahb.supabase.co
            </span>
        </div>

        <h1 class="hero-title">
            ระบบจัดการกลุ่มฟีดและแหล่งข้อมูลข่าวสาร <span class="gradient-text">(RSS & Atom Hub)</span>
        </h1>

        <p class="hero-subtitle">
            โครงสร้างตามข้อกำหนด <code>schema.sql</code> จัดการตาราง <strong>public.feed_groups</strong> และ <strong>public.feed_sources</strong> เพิ่ม ลบ ค้นหา และเชื่อมต่อข้อมูลข่าวสารแบบ Real-time เข้ากับระบบแอปพลิเคชันอย่างเป็นทางการ
        </p>

        <div class="hero-actions">
            <a href="#add-section" class="btn-hero btn-hero-primary" onclick="switchAddTab('source');">
                <i class="material-icons">add_circle</i> เพิ่มแหล่งข่าวใหม่ (Add Source)
            </a>
            <a href="#add-section" class="btn-hero btn-hero-secondary" onclick="switchAddTab('group');">
                <i class="material-icons">create_new_folder</i> เพิ่มกลุ่มฟีดใหม่ (Add Group)
            </a>
            <a href="#feeds-directory" class="btn-hero btn-hero-secondary">
                <i class="material-icons">manage_search</i> สำรวจฟีดข่าวทั้งหมด
            </a>
        </div>

        <div class="hero-stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="material-icons">folder_special</i></div>
                <div>
                    <div class="stat-num" id="stat-groups-count"><?php echo $totalGroups; ?></div>
                    <div class="stat-label">กลุ่มฟีดข่าวสาร (Feed Groups)</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2); color: #34d399;"><i class="material-icons">rss_feed</i></div>
                <div>
                    <div class="stat-num" id="stat-sources-count"><?php echo $totalSources; ?></div>
                    <div class="stat-label">แหล่งข่าว RSS/Atom (Feed Sources)</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;"><i class="material-icons">check_circle</i></div>
                <div>
                    <div class="stat-num"><?php echo $activeSources; ?></div>
                    <div class="stat-label">แหล่งข่าวที่เปิดใช้งาน (Active)</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.2); color: #c084fc;"><i class="material-icons">verified</i></div>
                <div>
                    <div class="stat-num">Google Play</div>
                    <div class="stat-label">News Policy Compliant</div>
                </div>
            </div>
        </div>
    </div>
</section>
