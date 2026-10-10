<!DOCTYPE html>
<html lang="th" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title ?? 'TechThai News • The Digital Chronicle'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'พอร์ทัลข่าวเทคโนโลยี ธุรกิจดิจิทัล และนวัตกรรม AI คัดสรรจากสำนักข่าวชั้นนำทั่วโลกแบบเรียลไทม์'); ?>">
    <meta name="keywords" content="TechThai, Tech News, AI Innovation, Digital Economy, ข่าวไอที">
    <meta name="robots" content="max-image-preview:large">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Open Graph / Facebook / LINE -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title ?? 'TechThai News'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description ?? 'พอร์ทัลข่าวเทคโนโลยี ธุรกิจดิจิทัล และนวัตกรรม AI'); ?>">
    <?php if (!empty($page_image)): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($page_image); ?>">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title ?? 'TechThai News'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description ?? 'พอร์ทัลข่าวเทคโนโลยี ธุรกิจดิจิทัล และนวัตกรรม AI'); ?>">
    <?php if (!empty($page_image)): ?>
    <meta name="twitter:image" content="<?php echo htmlspecialchars($page_image); ?>">
    <?php endif; ?>

    <!-- Fonts: Plus Jakarta Sans, Prompt, Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Schema.org News Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "TechThai News",
      "url": "https://<?php echo $_SERVER['HTTP_HOST'] ?? 'localhost'; ?>",
      "contactPoint": [{
        "@type": "ContactPoint",
        "telephone": "+66-83-904-0153",
        "contactType": "editorial",
        "email": "pongku71@gmail.com",
        "availableLanguage": ["Thai", "English"]
      }]
    }
    </script>

    <!-- Theme State Initializer -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('varient_theme') || 'dark';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <style>
        /* ==========================================================
           HUMAN-CRAFTED EDITORIAL DESIGN SYSTEM
           Inspired by The Verge, Wired, Monocle & Varient Premium
           ========================================================== */
        :root {
            --font-editorial-heading: 'Plus Jakarta Sans', 'Prompt', system-ui, -apple-system, sans-serif;
            --font-editorial-body: 'Prompt', 'Inter', system-ui, -apple-system, sans-serif;
            --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --transition-smooth: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            --fs-height-desktop: 520px;
        }

        [data-bs-theme="dark"] {
            --bg-page: #0a0e17;
            --bg-topbar: #07090f;
            --bg-surface: #111726;
            --bg-surface-hover: #171f33;
            --bg-surface-elevated: #1a233a;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-strong: rgba(255, 255, 255, 0.15);
            --text-heading: #f8fafc;
            --text-body: #cbd5e1;
            --text-muted: #94a3b8;
            --text-faint: #64748b;
            --accent-brand: #0ea5e9; /* Sky Blue */
            --accent-teal: #10b981;  /* Emerald */
            --accent-coral: #f43f5e; /* Crimson */
            --accent-amber: #f59e0b; /* Warm Gold */
            --shadow-subtle: 0 4px 20px rgba(0, 0, 0, 0.35);
            --shadow-card: 0 2px 10px rgba(0, 0, 0, 0.25);
            --shadow-hover: 0 12px 30px rgba(0, 0, 0, 0.45);
            --input-bg: #141b2d;
        }

        [data-bs-theme="light"] {
            --bg-page: #f8fafc;
            --bg-topbar: #ffffff;
            --bg-surface: #ffffff;
            --bg-surface-hover: #f1f5f9;
            --bg-surface-elevated: #f8fafc;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;
            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-faint: #94a3b8;
            --accent-brand: #0284c7;
            --accent-teal: #059669;
            --accent-coral: #e11d48;
            --accent-amber: #d97706;
            --shadow-subtle: 0 4px 16px rgba(15, 23, 42, 0.04);
            --shadow-card: 0 1px 3px rgba(15, 23, 42, 0.06), 0 4px 12px rgba(15, 23, 42, 0.03);
            --shadow-hover: 0 10px 25px -4px rgba(15, 23, 42, 0.1);
            --input-bg: #f1f5f9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-editorial-body);
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(14, 165, 233, 0.12), transparent 75%),
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.06), transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(16, 185, 129, 0.04), transparent 40%);
            background-attachment: fixed;
            color: var(--text-body);
            line-height: 1.65;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            letter-spacing: -0.01em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        [data-bs-theme="light"] body {
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(2, 132, 199, 0.05), transparent 75%),
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.035), transparent 45%),
                radial-gradient(circle at 0% 100%, rgba(16, 185, 129, 0.025), transparent 40%);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-editorial-heading);
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.025em;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        /* 1. Sleek Editorial Topbar */
        .editorial-topbar {
            background-color: var(--bg-topbar);
            border-bottom: 1px solid var(--border-subtle);
            font-size: 0.8125rem;
            padding: 8px 0;
            color: var(--text-muted);
            letter-spacing: 0.01em;
        }
        .editorial-topbar a:hover {
            color: var(--accent-brand);
        }

        /* 2. Brand Masthead */
        .editorial-masthead {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            padding: 22px 0;
        }
        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            color: var(--text-heading);
        }
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            font-weight: 800;
            font-family: var(--font-editorial-heading);
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
        }
        .brand-wordmark {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.04em;
        }
        .brand-tagline {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* 3. Sticky Category Navigation Bar */
        .editorial-nav-bar {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .nav-category-link {
            font-size: 0.88rem;
            font-weight: 600;
            padding: 14px 16px;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            position: relative;
            white-space: nowrap;
            transition: var(--transition-smooth);
        }
        .nav-category-link:hover {
            color: var(--text-heading);
        }
        .nav-category-link.active {
            color: var(--accent-brand);
            font-weight: 700;
        }
        .nav-category-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 14px;
            right: 14px;
            height: 2px;
            background-color: var(--accent-brand);
            border-radius: 2px;
        }

        /* 4. Minimalist Newsticker */
        .editorial-ticker {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            height: 44px;
            margin: 20px 0;
            display: flex;
            align-items: center;
            overflow: hidden;
            box-shadow: var(--shadow-card);
        }
        .ticker-pill {
            background-color: var(--accent-coral);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0 14px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .ticker-body {
            flex-grow: 1;
            overflow: hidden;
            white-space: nowrap;
            padding: 0 18px;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .ticker-body a {
            color: var(--text-heading);
        }
        .ticker-body a:hover {
            color: var(--accent-brand);
        }

        /* 5. Asymmetrical Editorial Hero Grid */
        .hero-magazine-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
            margin-bottom: 34px;
        }
        @media (min-width: 992px) {
            .hero-magazine-grid {
                grid-template-areas:
                    "lead sub1"
                    "lead sub2";
                grid-template-columns: 1.35fr 1fr;
                grid-template-rows: 246px 246px;
                height: 508px;
            }
            .grid-area-lead { grid-area: lead; height: 100%; }
            .grid-area-sub1 { grid-area: sub1; height: 100%; }
            .grid-area-sub2 { grid-area: sub2; height: 100%; }
        }
        .sub-dual-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            height: 100%;
        }

        .magazine-hero-card {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            height: 100%;
            min-height: 240px;
            box-shadow: var(--shadow-card);
            transition: var(--transition-smooth);
        }
        .magazine-hero-card:hover {
            border-color: var(--border-strong);
            box-shadow: var(--shadow-hover);
        }
        .hero-card-overlay-link {
            position: absolute;
            inset: 0;
            z-index: 3;
            cursor: pointer;
        }
        .hero-card-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
            filter: brightness(0.9);
        }
        .magazine-hero-card:hover .hero-card-img {
            transform: scale(1.04);
            filter: brightness(0.98);
        }
        .magazine-hero-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 14, 23, 0) 15%, rgba(10, 14, 23, 0.55) 55%, rgba(10, 14, 23, 0.94) 100%);
            pointer-events: none;
            z-index: 1;
        }
        .hero-card-content {
            position: relative;
            z-index: 2;
            padding: 22px 24px;
            color: #ffffff;
        }
        .editorial-kicker {
            display: inline-block;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            color: #ffffff;
        }
        .hero-lead-title {
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1.3;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.03em;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .hero-sub-title {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .hero-mini-title {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 6px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .hero-card-meta {
            font-size: 0.78rem;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .author-avatar-sm {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--accent-brand);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* 6. Main Feed Cards */
        .section-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--border-subtle);
        }
        .section-headline {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.025em;
        }

        /* Editorial Article Card */
        .article-feed-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-card);
        }
        .article-feed-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-strong);
            box-shadow: var(--shadow-hover);
        }
        .feed-card-media {
            position: relative;
            width: 100%;
            padding-top: 58%; /* 16:10 ratio */
            background-color: var(--bg-surface-elevated);
            overflow: hidden;
        }
        .feed-card-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .article-feed-card:hover .feed-card-img {
            transform: scale(1.05);
        }
        .feed-card-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            padding: 3px 8px;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-radius: 4px;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .feed-card-body {
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .feed-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 8px;
            color: var(--text-heading);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.15s ease;
        }
        .article-feed-card:hover .feed-card-title {
            color: var(--accent-brand);
        }
        .feed-card-snippet {
            font-size: 0.84rem;
            color: var(--text-muted);
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
            line-height: 1.55;
        }
        .feed-card-footer {
            border-top: 1px solid var(--border-subtle);
            padding-top: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--text-faint);
        }

        /* 7. Sidebar Widgets */
        .editorial-widget {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 22px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-card);
        }
        .widget-headline {
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Trending list */
        .trending-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-subtle);
            transition: transform 0.2s;
        }
        .trending-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .trending-rank-num {
            font-size: 1.5rem;
            font-weight: 800;
            font-family: var(--font-editorial-heading);
            color: var(--text-faint);
            width: 30px;
            flex-shrink: 0;
            line-height: 1;
        }
        .trending-row:nth-child(1) .trending-rank-num { color: var(--accent-amber); }
        .trending-row:nth-child(2) .trending-rank-num { color: #94a3b8; }
        .trending-row:nth-child(3) .trending-rank-num { color: #b45309; }
        .trending-row-thumb {
            width: 72px;
            height: 52px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            flex-shrink: 0;
            background-color: var(--bg-surface-elevated);
        }
        .trending-row-title {
            font-size: 0.88rem;
            font-weight: 600;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--text-heading);
        }
        .trending-row-title:hover {
            color: var(--accent-brand);
        }

        /* Source filter pills */
        .filter-tab-pill {
            background-color: transparent;
            border: 1px solid var(--border-subtle);
            color: var(--text-muted);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-smooth);
            white-space: nowrap;
        }
        .filter-tab-pill:hover {
            background-color: var(--bg-surface-hover);
            color: var(--text-heading);
        }
        .filter-tab-pill.active {
            background-color: var(--accent-brand);
            border-color: var(--accent-brand);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
        }

        /* 8. Footer */
        .editorial-footer {
            background-color: var(--bg-topbar);
            border-top: 1px solid var(--border-subtle);
            color: var(--text-muted);
            padding: 56px 0 28px 0;
            margin-top: 60px;
            font-size: 0.88rem;
        }
        .editorial-footer h5 {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-heading);
            margin-bottom: 16px;
        }
        .editorial-footer a:hover {
            color: var(--accent-brand);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-page);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        /* ==========================================================
           MULTI-CATEGORY SECTION STYLING (NEWS TIMES MAGAZINE GRID)
           ========================================================== */
        .category-quick-nav-wrapper {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            box-shadow: var(--shadow-card);
        }
        .category-pill-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background-color: var(--bg-surface-elevated);
            border: 1px solid var(--border-subtle);
            color: var(--text-body);
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: var(--transition-smooth);
        }
        .category-pill-link:hover {
            background-color: var(--bg-surface-hover);
            color: var(--text-heading);
            border-color: var(--border-strong);
            transform: translateY(-1px);
        }

        .category-block-section {
            position: relative;
        }

        .category-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 12px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-subtle);
            position: relative;
        }
        .category-header-bar::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 80px;
            height: 2px;
            background-color: var(--accent-brand);
        }
        .category-header-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-radius: var(--radius-sm);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        .category-header-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-heading);
        }
        .category-header-count {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--text-faint);
            background: rgba(255, 255, 255, 0.06);
            padding: 3px 8px;
            border-radius: 999px;
            border: 1px solid var(--border-subtle);
        }
        .category-view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--accent-brand);
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(14, 165, 233, 0.08);
            border: 1px solid rgba(14, 165, 233, 0.2);
            transition: var(--transition-smooth);
        }
        .category-view-all-link:hover {
            background: var(--accent-brand);
            color: #ffffff;
            border-color: var(--accent-brand);
            transform: translateX(2px);
        }

        /* 4-Column Category News Card */
        .category-news-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-card);
        }
        .category-news-card:hover {
            transform: translateY(-4px);
            border-color: var(--border-strong);
            box-shadow: var(--shadow-hover);
        }
        .cat-card-media {
            position: relative;
            width: 100%;
            padding-top: 60%; /* 16:9.6 ratio */
            background-color: var(--bg-surface-elevated);
            overflow: hidden;
        }
        .cat-card-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .category-news-card:hover .cat-card-img {
            transform: scale(1.06);
        }
        /* Vibrant Floating Category Pill Badge on Image (matches NEWS TIMES) */
        .cat-card-pill {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            padding: 3px 8px;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-radius: 3px;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4);
            line-height: 1.2;
        }
        /* Publisher Badge at bottom-right of image */
        .cat-card-source {
            position: absolute;
            bottom: 8px;
            right: 8px;
            z-index: 2;
            padding: 2px 6px;
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 3px;
            background: rgba(10, 14, 23, 0.75);
            backdrop-filter: blur(4px);
            color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.12);
        }
        .cat-card-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .cat-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 8px;
            color: var(--text-heading);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.15s ease;
        }
        .category-news-card:hover .cat-card-title a {
            color: var(--accent-brand);
        }
        .cat-card-title a {
            color: inherit;
            text-decoration: none;
        }
        .cat-card-snippet {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
            line-height: 1.5;
        }
        .cat-card-footer {
            border-top: 1px solid var(--border-subtle);
            padding-top: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            color: var(--text-faint);
        }
        .cat-card-time {
            display: inline-flex;
            align-items: center;
        }
        .cat-card-read-more {
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.15s ease;
        }
        .cat-card-read-more:hover {
            transform: translateX(2px);
        }
    </style>
<?php
$themeServicePath = dirname(dirname(dirname(__DIR__))) . '/home/ThemeService.php';
if (file_exists($themeServicePath)) {
    require_once $themeServicePath;
    echo ThemeService::renderFrontendCss();
}
?>
</head>
<body>
