<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo htmlspecialchars($page_title ?? (APP_NAME . ' - ' . APP_TAGLINE)); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_desc ?? 'ระบบจัดการฟีดข่าวสารและแหล่งข้อมูล RSS/Atom เชื่อมต่อ Supabase สำหรับแอปพลิเคชันข่าวสารอย่างเป็นทางการ'); ?>">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Schema.org News Organization Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "<?php echo htmlspecialchars(APP_NAME); ?>",
      "url": "https://<?php echo $_SERVER['HTTP_HOST'] ?? 'localhost'; ?>",
      "contactPoint": [{
        "@type": "ContactPoint",
        "telephone": "+66-83-904-0153",
        "contactType": "editorial",
        "email": "<?php echo CONTACT_EMAIL; ?>",
        "availableLanguage": ["Thai", "English"]
      }]
    }
    </script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --primary-border: #bfdbfe;
            --secondary: #0f172a;
            --secondary-light: #1e293b;
            --accent: #3b82f6;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Prompt', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(37, 99, 235, 0.06), transparent 70%),
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.04), transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(14, 165, 233, 0.03), transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: var(--transition);
        }

        /* Top Navbar */
        .site-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--secondary);
        }

        .brand-badge {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            padding: 4px 9px;
            border-radius: 6px;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 1.15rem;
            line-height: 1.2;
            color: var(--secondary);
            font-weight: 700;
        }

        .brand-sub {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item {
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .nav-item:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .nav-item.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 600;
        }

        .nav-item.cta-btn {
            background: var(--primary);
            color: #ffffff;
            font-weight: 600;
            padding: 8px 16px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }

        .nav-item.cta-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4);
        }

        /* Mobile Hamburger */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: var(--secondary);
        }

        @media (max-width: 900px) {
            .mobile-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background: #ffffff;
                flex-direction: column;
                padding: 16px 24px;
                border-bottom: 1px solid var(--border);
                box-shadow: var(--shadow-lg);
                gap: 6px;
            }

            .nav-menu.open {
                display: flex;
            }

            .nav-item {
                width: 100%;
                padding: 10px 14px;
            }
        }
    </style>
</head>
<body>

    <header class="site-header">
        <div class="nav-container">
            <a href="index.php" class="brand-link" id="brand-link">
                <span class="brand-badge">SUPABASE</span>
                <div class="brand-text">
                    <span class="brand-title"><?php echo htmlspecialchars(APP_NAME); ?></span>
                    <span class="brand-sub">RSS Feed & Groups Manager</span>
                </div>
            </a>

            <button class="mobile-toggle" id="mobile-toggle" aria-label="เปิดเมนูนำทาง">
                <i class="material-icons">menu</i>
            </button>

            <nav class="nav-menu" id="nav-menu">
                <a href="index.php" class="nav-item <?php echo empty($_GET['tab']) || $_GET['tab'] === 'home' ? 'active' : ''; ?>">
                    <i class="material-icons" style="font-size: 18px;">home</i> หน้าแรก
                </a>
                <a href="#add-section" class="nav-item cta-btn" onclick="switchAddTab('source');">
                    <i class="material-icons" style="font-size: 18px;">add_circle</i> เพิ่มแหล่งข่าว / กลุ่มฟีด
                </a>
                <a href="#feeds-directory" class="nav-item">
                    <i class="material-icons" style="font-size: 18px;">rss_feed</i> สำรวจฟีด (<?php echo count($sources ?? []); ?>)
                </a>
                <a href="contact.php" class="nav-item">
                    <i class="material-icons" style="font-size: 18px;">contact_mail</i> ติดต่อเรา
                </a>
                <a href="about.php" class="nav-item">
                    <i class="material-icons" style="font-size: 18px;">info</i> กองบรรณาธิการ
                </a>
                <a href="privacy_policy.html" class="nav-item">
                    <i class="material-icons" style="font-size: 18px;">privacy_tip</i> นโยบายความเป็นส่วนตัว
                </a>
            </nav>
        </div>
    </header>

    <script>
        document.getElementById('mobile-toggle')?.addEventListener('click', function() {
            document.getElementById('nav-menu')?.classList.toggle('open');
        });
    </script>
