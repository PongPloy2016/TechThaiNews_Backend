<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ติดต่อเรา | ข้อมูลกองบรรณาธิการและผู้เผยแพร่ (Contact Us & Publisher Info)</title>
    <meta name="description" content="หน้าติดต่อเราและข้อมูลกองบรรณาธิการอย่างเป็นทางการ ข้อมูลติดต่อสำหรับผู้ใช้งานแอปพลิเคชันและเว็บไซต์ข่าว">
    <link rel="icon" href="../assets/images/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- JSON-LD Structured Data for Google News Verification -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "<?php echo CONTACT_PUBLISHER_NAME; ?>",
      "url": "https://<?php echo $_SERVER['HTTP_HOST'] ?? 'example.com'; ?>",
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
            --primary: #1a73e8;
            --primary-dark: #1557b0;
            --primary-light: #e8f0fe;
            --text-main: #202124;
            --text-sub: #5f6368;
            --border: #dadce0;
            --bg-page: #f8f9fa;
            --card-bg: #ffffff;
            --accent: #ea4335;
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
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(26, 115, 232, 0.06), transparent 70%),
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.04), transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(234, 67, 53, 0.025), transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
        }

        .brand-badge {
            background: var(--primary);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-sub);
            font-weight: 500;
            margin-left: 20px;
            transition: color 0.2s;
            font-size: 0.95rem;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }

        .hero {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            padding: 45px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.05rem;
            opacity: 0.9;
            max-width: 650px;
            margin: 0 auto;
        }

        .main-wrapper {
            max-width: 1100px;
            margin: -30px auto 40px auto;
            padding: 0 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            .hero h1 {
                font-size: 1.6rem;
            }
            .nav-container {
                flex-direction: column;
                gap: 10px;
            }
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-main);
            border-bottom: 2px solid #f1f3f4;
            padding-bottom: 12px;
        }

        .contact-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 18px;
        }

        .contact-icon {
            background: var(--primary-light);
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-content .label {
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-bottom: 2px;
        }

        .contact-content .value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-main);
        }

        .contact-content .value a {
            color: var(--primary);
            text-decoration: none;
        }

        .contact-content .value a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .policy-card {
            background: #fdfdfd;
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 22px;
            margin-top: 30px;
        }

        .policy-card h3 {
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-main);
        }

        .policy-card p {
            font-size: 0.92rem;
            color: var(--text-sub);
            margin-bottom: 10px;
        }

        .policy-card ul {
            margin-left: 20px;
            font-size: 0.92rem;
            color: var(--text-sub);
        }

        .policy-card li {
            margin-bottom: 6px;
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-sub);
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            margin-top: 60px;
            background: #ffffff;
        }

        footer a {
            color: var(--primary);
            text-decoration: none;
            margin: 0 10px;
        }

        .alert-success {
            background: #e6f4ea;
            color: #137333;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #b7e1cd;
        }

        .alert-error {
            background: #fde8e8;
            color: #9b1c1c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #f8b4b4;
        }
    </style>
</head>
<body>

    <?php
        $is_in_contact_subfolder = (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/contact/') !== false);
        $nav_home = $is_in_contact_subfolder ? '../index.php' : 'index.php';
        $nav_add  = $is_in_contact_subfolder ? '../index.php#add-section' : 'index.php#add-section';
        $nav_contact = $is_in_contact_subfolder ? 'index.php' : 'contact.php';
        $nav_privacy = $is_in_contact_subfolder ? '../privacy_policy.html' : 'privacy_policy.html';
    ?>
    <header>
        <div class="nav-container">
            <a href="<?php echo $nav_home; ?>" class="brand">
                <span class="brand-badge">NEWS</span>
                <span>แอปพลิเคชันและเว็บไซต์ข่าวสาร</span>
            </a>
            <nav class="nav-links">
                <a href="<?php echo $nav_home; ?>">หน้าแรก (Home)</a>
                <a href="<?php echo $nav_add; ?>">เพิ่มฟีดข่าว (Add Feeds)</a>
                <a href="<?php echo $nav_contact; ?>" class="active">ติดต่อเรา (Contact Us)</a>
                <a href="<?php echo $nav_privacy; ?>">นโยบายความเป็นส่วนตัว (Privacy Policy)</a>
            </nav>
        </div>
    </header>
