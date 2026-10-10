<?php
/**
 * Web Navbar Component
 * Desktop & mobile responsive header with search and action buttons
 */
?>

<style>
    .site-navbar {
        background: var(--bg-navbar);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--border-card);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .navbar-container {
        max-width: var(--max-w);
        margin: 0 auto;
        padding: 0 20px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .brand-link {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }

    .brand-logo-badge {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: 0 4px 14px var(--primary-glow);
    }

    .brand-title-text {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }

    .brand-title-text span {
        color: var(--primary-light);
    }

    .brand-sub-tagline {
        font-size: 0.72rem;
        color: var(--text-muted);
        letter-spacing: 0.2px;
    }

    /* Search Box */
    .nav-search-wrap {
        flex: 1;
        max-width: 440px;
        position: relative;
    }

    .nav-search-input {
        width: 100%;
        padding: 9px 16px 9px 38px;
        background: #0d1322;
        border: 1.5px solid var(--border-card);
        border-radius: 999px;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.88rem;
        outline: none;
        transition: all 0.2s;
    }

    .nav-search-input:focus {
        border-color: var(--primary-light);
        background: #11192d;
        box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .nav-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 18px;
        pointer-events: none;
    }

    /* Actions */
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-nav-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.86rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-nav-item.refresh {
        background: #151e32;
        color: var(--text-sub);
        border-color: var(--border-card);
    }

    .btn-nav-item.refresh:hover {
        background: #1c2842;
        color: #ffffff;
        border-color: #3b82f6;
    }

    .btn-nav-item.add-feed {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 12px var(--primary-glow);
    }

    .btn-nav-item.add-feed:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.45);
    }

    .nav-page-links {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-right: 6px;
    }

    .nav-page-links a {
        font-size: 0.86rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .nav-page-links a:hover {
        color: var(--primary-light);
    }

    @media (max-width: 992px) {
        .nav-page-links {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .nav-search-wrap {
            display: none;
        }
        .btn-nav-item span {
            display: none;
        }
        .btn-nav-item {
            padding: 8px;
            border-radius: 50%;
        }
    }
</style>

<header class="site-navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="index.php" class="brand-link">
            <div class="brand-logo-badge">
                <i class="material-icons">feed</i>
            </div>
            <div>
                <div class="brand-title-text">TechThai<span>News</span></div>
                <div class="brand-sub-tagline">IT News &amp; RSS Feeds Hub</div>
            </div>
        </a>

        <!-- Live Search -->
        <div class="nav-search-wrap">
            <i class="material-icons nav-search-icon">search</i>
            <input type="text" id="header-search-box" class="nav-search-input" 
                   placeholder="ค้นหาหัวข้อข่าว, นักเขียน, สำนักข่าว..." 
                   oninput="handleFilterSearch(this.value)">
        </div>

        <!-- Links & Action Buttons -->
        <div class="nav-actions">
            <div class="nav-page-links">
                <a href="contact.php">ติดต่อเรา</a>
                <a href="about.php">กองบรรณาธิการ</a>
                <a href="privacy.php">นโยบายความเป็นส่วนตัว</a>
            </div>

            <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>&refresh=1" 
               class="btn-nav-item refresh" title="รีเฟรชดึงฟีดสด">
                <i class="material-icons" style="font-size: 16px;">refresh</i>
                <span>รีเฟรชฟีด</span>
            </a>

            <button type="button" class="btn-nav-item add-feed" onclick="openAddFeedModal()">
                <i class="material-icons" style="font-size: 16px;">add_circle</i>
                <span>เพิ่มฟีด (Supabase)</span>
            </button>
        </div>
    </div>
</header>
