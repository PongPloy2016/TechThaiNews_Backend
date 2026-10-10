<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo htmlspecialchars($page_title ?? 'IT News App - ข่าวเทคโนโลยีและฟีด RSS'); ?></title>
    <meta name="description" content="แอปพลิเคชันและเว็บไซต์ข่าวสารไอที ดึงข้อมูลจาก RSS/Atom Feeds เชื่อมต่อ Supabase">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Schema.org News Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsMediaOrganization",
      "name": "IT News App",
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

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Prompt', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #090d16;
            color: #f8fafc;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Responsive Container */
        .app-root-container {
            width: 100%;
            min-height: 100vh;
            background-color: #090d16;
        }
    </style>
</head>
<body>
<div class="app-root-container">
