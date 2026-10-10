<?php
/**
 * Web Header Component (Modern News Portal Layout)
 * Full-width responsive header and category navigation
 */

function getCategoryIcon($key) {
    $icons = [
        'tech-business'    => 'laptop_mac',
        'international'    => 'public',
        'thai-news'        => 'newspaper',
        'mobile'           => 'smartphone',
        'computer-games'   => 'sports_esports',
        'ai-innovation'    => 'smart_toy',
        'weather-forecast' => 'cloud_queue',
        'cybersecurity'    => 'security',
        'ev-vehicles'      => 'electric_car',
        'science-space'    => 'rocket_launch',
        'fintech-crypto'   => 'currency_bitcoin',
        'developer-coding' => 'terminal'
    ];
    return $icons[$key] ?? 'folder';
}
?>

<style>
    :root {
        --web-bg: #0b0f19;
        --web-surface: #111827;
        --web-card: #162032;
        --web-card-hover: #1c2940;
        --web-border: #1f293d;
        --web-border-light: rgba(255, 255, 255, 0.08);
        --web-primary: #2563eb;
        --web-primary-light: #3b82f6;
        --web-primary-glow: rgba(37, 99, 235, 0.25);
        --web-text-main: #f8fafc;
        --web-text-sub: #cbd5e1;
        --web-text-muted: #94a3b8;
        --web-radius: 14px;
        --web-max-w: 1240px;
    }

    /* Top Utility Bar */
    .top-utility-bar {
        background: #070a12;
        border-bottom: 1px solid var(--web-border);
        font-size: 0.8rem;
        color: var(--web-text-muted);
        padding: 6px 0;
    }

    .top-utility-inner {
        max-width: var(--web-max-w);
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .utility-links {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .utility-links a {
        color: var(--web-text-muted);
        transition: color 0.2s;
    }

    .utility-links a:hover {
        color: var(--web-primary-light);
    }

    /* Main Navigation Bar */
    .web-navbar {
        background: rgba(17, 24, 39, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--web-border);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .navbar-inner {
        max-width: var(--web-max-w);
        margin: 0 auto;
        padding: 0 20px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .brand-logo-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .brand-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 4px 14px var(--web-primary-glow);
    }

    .brand-text-col {
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }

    .brand-name span {
        color: var(--web-primary-light);
    }

    .brand-desc {
        font-size: 0.75rem;
        color: var(--web-text-muted);
        letter-spacing: 0.2px;
    }

    /* Search Bar in Header */
    .header-search-wrap {
        flex: 1;
        max-width: 420px;
        position: relative;
    }

    .header-search-input {
        width: 100%;
        padding: 9px 16px 9px 38px;
        background: #0d1322;
        border: 1.5px solid var(--web-border);
        border-radius: 999px;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.88rem;
        outline: none;
        transition: all 0.2s;
    }

    .header-search-input:focus {
        border-color: var(--web-primary-light);
        background: #11192d;
        box-shadow: 0 0 0 3px var(--web-primary-glow);
    }

    .header-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--web-text-muted);
        font-size: 18px;
        pointer-events: none;
    }

    /* Action Nav Buttons */
    .header-nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-web-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-web-action.secondary {
        background: #1a233a;
        color: var(--web-text-sub);
        border-color: var(--web-border);
    }

    .btn-web-action.secondary:hover {
        background: #222e4d;
        color: #ffffff;
        border-color: #3b82f6;
    }

    .btn-web-action.primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 12px var(--web-primary-glow);
    }

    .btn-web-action.primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
    }

    /* Category Bar (Web Portal Level) */
    .category-strip {
        background: #0d121f;
        border-bottom: 1px solid var(--web-border);
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: #1f293d transparent;
    }

    .category-strip::-webkit-scrollbar {
        height: 4px;
    }
    .category-strip::-webkit-scrollbar-thumb {
        background: #1f293d;
        border-radius: 4px;
    }

    .category-strip-inner {
        max-width: var(--web-max-w);
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cat-nav-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 12px 14px;
        color: var(--web-text-muted);
        font-size: 0.88rem;
        font-weight: 500;
        white-space: nowrap;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .cat-nav-link i {
        font-size: 17px;
    }

    .cat-nav-link:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.03);
    }

    .cat-nav-link.active {
        color: #60a5fa;
        border-bottom-color: #3b82f6;
        font-weight: 600;
        background: rgba(37, 99, 235, 0.08);
    }

    @media (max-width: 900px) {
        .header-search-wrap {
            display: none;
        }
        .top-utility-bar {
            display: none;
        }
    }
</style>

<!-- Top Utility Bar -->
<div class="top-utility-bar">
    <div class="top-utility-inner">
        <div>
            <span>⚡ พอร์ทัลข่าวสารและระบบจัดการ RSS Feeds เชื่อมต่อ Supabase Cloud</span>
        </div>
        <div class="utility-links">
            <a href="contact.php"><i class="material-icons" style="font-size: 13px; vertical-align: middle;">contact_mail</i> ติดต่อกองบรรณาธิการ</a>
            <a href="about.php"><i class="material-icons" style="font-size: 13px; vertical-align: middle;">verified</i> มาตรฐานจรรยาบรรณข่าว</a>
            <a href="privacy.php"><i class="material-icons" style="font-size: 13px; vertical-align: middle;">policy</i> นโยบายความเป็นส่วนตัว</a>
        </div>
    </div>
</div>

<!-- Main Web Navbar -->
<header class="web-navbar">
    <div class="navbar-inner">
        <!-- Logo -->
        <a href="index.php" class="brand-logo-wrap">
            <div class="brand-icon">
                <i class="material-icons">feed</i>
            </div>
            <div class="brand-text-col">
                <span class="brand-name">TechThai<span>News</span></span>
                <span class="brand-desc">IT &amp; Business News Portal</span>
            </div>
        </a>

        <!-- Live Search -->
        <div class="header-search-wrap">
            <i class="material-icons header-search-icon">search</i>
            <input type="text" id="live-search-input" class="header-search-input" 
                   placeholder="ค้นหาหัวข้อข่าว, นักเขียน, สำนักข่าว..." 
                   oninput="handleLiveSearch(this.value)">
        </div>

        <!-- Actions -->
        <div class="header-nav-actions">
            <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>&refresh=1" 
               class="btn-web-action secondary" title="รีเฟรชดึงฟีดสด">
                <i class="material-icons" style="font-size: 16px;">refresh</i>
                <span>รีเฟรชฟีด</span>
            </a>

            <button type="button" class="btn-web-action primary" onclick="openAddFeedModal()">
                <i class="material-icons" style="font-size: 16px;">add_circle</i>
                <span>เพิ่มฟีด (Supabase)</span>
            </button>
        </div>
    </div>
</header>

<!-- Category Strip (12 Feed Groups from schema.sql) -->
<nav class="category-strip">
    <div class="category-strip-inner">
        <?php foreach (($groups ?? []) as $grp): ?>
            <?php 
                $gKey = $grp['key'];
                $isActive = ($gKey === $currentGroupKey);
            ?>
            <a href="index.php?group=<?php echo urlencode($gKey); ?>" 
               class="cat-nav-link <?php echo $isActive ? 'active' : ''; ?>">
                <i class="material-icons"><?php echo getCategoryIcon($gKey); ?></i>
                <span><?php echo htmlspecialchars($grp['label']); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
