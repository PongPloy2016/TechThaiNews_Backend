<?php
/**
 * App Header Component (Mobile App Style)
 * Matches the IT News App dark theme header
 */
?>

<style>
    :root {
        --app-bg: #090d16;
        --app-card: #131b2e;
        --app-card-border: #1e293b;
        --app-primary: #2563eb;
        --app-primary-light: #3b82f6;
        --app-accent-red: #ef4444;
        --app-text-main: #f8fafc;
        --app-text-sub: #94a3b8;
        --app-text-muted: #64748b;
        --app-pill-bg: #1e293b;
    }

    /* App Bar */
    .app-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(9, 13, 22, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        padding: 12px 18px;
    }

    .app-header-inner {
        max-width: 680px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .app-title-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .btn-icon-nav {
        background: none;
        border: none;
        color: #f1f5f9;
        font-size: 26px;
        cursor: pointer;
        padding: 6px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-icon-nav:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #3b82f6;
    }

    .app-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .app-actions-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-header-action {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-header-action:hover {
        background: rgba(37, 99, 235, 0.2);
        border-color: rgba(59, 130, 246, 0.5);
        color: #60a5fa;
    }

    .btn-header-action.cta {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }

    .btn-header-action.cta:hover {
        background: #1d4ed8;
    }

    /* Side Drawer */
    .drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .drawer-overlay.open {
        opacity: 1;
        pointer-events: auto;
    }

    .drawer-panel {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: 300px;
        max-width: 85%;
        background: #0f172a;
        border-right: 1px solid #1e293b;
        z-index: 999;
        transform: translateX(-100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        padding: 24px;
        box-shadow: 10px 0 25px rgba(0, 0, 0, 0.5);
    }

    .drawer-panel.open {
        transform: translateX(0);
    }

    .drawer-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        color: #cbd5e1;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .drawer-menu-item:hover, .drawer-menu-item.active {
        background: rgba(37, 99, 235, 0.15);
        color: #60a5fa;
    }
</style>

<header class="app-header">
    <div class="app-header-inner">
        <div class="app-title-group">
            <button type="button" class="btn-icon-nav" id="drawer-toggle-btn" aria-label="เปิดเมนู">
                <i class="material-icons">menu</i>
            </button>
            <span class="app-title">IT News App</span>
        </div>

        <div class="app-actions-group">
            <a href="index.php?group=<?php echo urlencode($currentGroupKey); ?>&refresh=1" 
               class="btn-header-action" title="รีเฟรชดึงฟีดสด">
                <i class="material-icons" style="font-size: 16px;">refresh</i>
                <span>รีเฟรช</span>
            </a>
            <button type="button" class="btn-header-action cta" onclick="openAddFeedModal()">
                <i class="material-icons" style="font-size: 16px;">add</i>
                <span>เพิ่มฟีด</span>
            </button>
        </div>
    </div>
</header>

<!-- Drawer -->
<div class="drawer-overlay" id="drawer-overlay"></div>
<aside class="drawer-panel" id="drawer-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #1e293b;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="background: #2563eb; color: #fff; font-size: 0.75rem; font-weight: 800; padding: 4px 8px; border-radius: 6px;">NEWS</span>
            <strong style="color: #fff; font-size: 1.15rem;">IT News App</strong>
        </div>
        <button type="button" class="btn-icon-nav" id="drawer-close-btn" style="padding: 4px;">
            <i class="material-icons">close</i>
        </button>
    </div>

    <nav style="display: flex; flex-direction: column; gap: 6px; flex: 1;">
        <a href="index.php" class="drawer-menu-item active">
            <i class="material-icons">newspaper</i> ข่าวล่าสุด (Latest Feeds)
        </a>
        <a href="javascript:void(0)" class="drawer-menu-item" onclick="openAddFeedModal(); closeDrawer();">
            <i class="material-icons">add_circle</i> เพิ่มแหล่งข่าว / กลุ่มฟีด (Supabase)
        </a>
        <a href="contact.php" class="drawer-menu-item">
            <i class="material-icons">contact_mail</i> ติดต่อเรา (Contact Us)
        </a>
        <a href="about.php" class="drawer-menu-item">
            <i class="material-icons">info</i> กองบรรณาธิการ (Editorial)
        </a>
        <a href="privacy.php" class="drawer-menu-item">
            <i class="material-icons">privacy_tip</i> นโยบายความเป็นส่วนตัว
        </a>
    </nav>

    <div style="padding-top: 16px; border-top: 1px solid #1e293b; font-size: 0.8rem; color: #64748b;">
        <div>ฐานข้อมูล: Supabase Cloud</div>
        <div style="margin-top: 4px;">Google Play News Policy Compliant</div>
    </div>
</aside>

<script>
    const drawerToggle = document.getElementById('drawer-toggle-btn');
    const drawerOverlay = document.getElementById('drawer-overlay');
    const drawerPanel = document.getElementById('drawer-panel');
    const drawerClose = document.getElementById('drawer-close-btn');

    function openDrawer() {
        drawerOverlay?.classList.add('open');
        drawerPanel?.classList.add('open');
    }

    function closeDrawer() {
        drawerOverlay?.classList.remove('open');
        drawerPanel?.classList.remove('open');
    }

    drawerToggle?.addEventListener('click', openDrawer);
    drawerClose?.addEventListener('click', closeDrawer);
    drawerOverlay?.addEventListener('click', closeDrawer);
</script>
