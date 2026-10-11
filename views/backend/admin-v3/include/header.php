<?php
?>
<!DOCTYPE html>
<html>
<head>

    <base href="<?= base_url() ?>"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Translate::sprint("Dashboard") ?> | <?= APP_NAME ?></title>

    <link rel="icon" href="<?= adminAssets() ?>/images/favicon.ico" type="image/x-icon">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?= adminAssets() ?>/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/bootstrap-5.0.2/css/bootstrap-grid.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=adminAssets()?>/plugins/select2/select2.min.css">

    <link rel="stylesheet" href="<?=adminAssets()?>/dist/css/admin.css">
    <link rel="stylesheet" href="<?=adminAssets()?>/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/dist/css/skins/skin-light.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet"
          href="<?= adminAssets() ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/datatables/dataTables.bootstrap.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/minified/themes/default.min.css"
          type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/datatables/jquery.dataTables.min.css"
          type="text/css" media="all"/>
    <link rel="stylesheet" href="<?= adminAssets() ?>/plugins/colorpicker/bootstrap-colorpicker.css">

    <!-- Google Material design icons -->
    <link rel="stylesheet" href="<?=adminAssets()?>/icons/materialdesignicons/css/materialdesignicons.min.css">
    <!-- Font awsome 4.4.0 icons -->
    <link rel="stylesheet" href="<?=adminAssets()?>/icons/font-awsome/css/font-awesome-4.4.0.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=adminAssets()?>/icons/ionicons/css/ionicons.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= adminAssets() ?>/custom_skin/style.css">
    <link rel="stylesheet" href="<?= adminAssets() ?>/custom_skin/shimmer-loading.css">
<?php if (Translate::getDir() == "rtl"): ?>
        <link rel="stylesheet" href="<?= adminAssets() ?>/custom_skin/rtl.css">
<?php endif; ?>

    <!-- External libraries -->
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-112054244-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '<?=defined('DASHBOARD_ANALYTICS') ? DASHBOARD_ANALYTICS : ''?>');
    </script>

    <script src="<?= adminAssets("plugins/jQuery/jQuery-2.1.4.min.js") ?>"></script>
<?php AdminTemplateManager::loadCssLibs() ?>


    <style>
        /* ==========================================================
           MODERN ULTRA-CLEAN ADMIN THEME & BACKGROUND SYSTEM
           ========================================================== */
        :root {
            --adm-primary: #2563eb;
            --adm-primary-dark: #1d4ed8;
            --adm-bg-canvas: #f8fafc;
            --adm-border: #e2e8f0;
        }

        body, .wrapper {
            font-family: 'Prompt', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            -webkit-font-smoothing: antialiased;
        }

        /* 🌟 Ambient Silk Mesh Canvas Background */
        .content-wrapper, .right-side {
            background-color: #f8fafc !important;
            background-image: 
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(37, 99, 235, 0.05), transparent 70%),
                radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.04), transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(14, 165, 233, 0.035), transparent 50%) !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }

        /* 💎 Modern Glassmorphic Top Navbar */
        .main-header {
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04) !important;
        }
        .main-header .navbar {
            background: transparent !important;
        }
        .main-header .logo {
            background: #0b0f19 !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            font-weight: 800 !important;
            font-family: 'Plus Jakarta Sans', 'Prompt', sans-serif !important;
            height: 50px !important;
            line-height: 50px !important;
            padding: 0 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            overflow: hidden !important;
        }
        .main-header .logo:hover {
            background: #0f172a !important;
            color: #ffffff !important;
        }
        .main-header .logo .logo-lg {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            width: 100% !important;
        }
        .main-header .logo .logo-mini {
            display: none !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
        }
        .sidebar-collapse .main-header .logo .logo-lg {
            display: none !important;
        }
        .sidebar-collapse .main-header .logo .logo-mini {
            display: flex !important;
        }
        .admin-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            flex-shrink: 0;
            line-height: 1;
        }
        .admin-brand-text {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .main-header .navbar .sidebar-toggle {
            color: #475569 !important;
            transition: all 0.2s ease !important;
        }
        .main-header .navbar .sidebar-toggle:hover {
            color: #2563eb !important;
            background: #f1f5f9 !important;
        }

        /* 🖤 Ultra-Premium Dark Sidebar with Optimized 265px Width */
        @media (min-width: 768px) {
            .main-sidebar, .left-side {
                width: 265px !important;
            }
            .main-header .logo {
                width: 265px !important;
            }
            .main-header .navbar {
                margin-left: 265px !important;
            }
            .content-wrapper, .right-side, .main-footer {
                margin-left: 265px !important;
            }
        }
        @media (max-width: 767px) {
            .main-sidebar, .left-side {
                width: 265px !important;
                -webkit-transform: translate(-265px, 0) !important;
                -ms-transform: translate(-265px, 0) !important;
                transform: translate(-265px, 0) !important;
            }
            .sidebar-open .main-sidebar, .sidebar-open .left-side {
                -webkit-transform: translate(0, 0) !important;
                -ms-transform: translate(0, 0) !important;
                transform: translate(0, 0) !important;
            }
        }
        .sidebar-collapse .main-sidebar, .sidebar-collapse .left-side {
            width: 50px !important;
        }
        .sidebar-collapse .content-wrapper, .sidebar-collapse .right-side, .sidebar-collapse .main-footer {
            margin-left: 50px !important;
        }
        .sidebar-collapse .main-header .navbar {
            margin-left: 50px !important;
        }
        .sidebar-collapse .main-header .logo {
            width: 50px !important;
        }

        .main-sidebar, .left-side {
            background: linear-gradient(180deg, #0b0f19 0%, #0f172a 60%, #090d16 100%) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15) !important;
        }
        .sidebar-menu > li.header {
            color: #475569 !important;
            background: transparent !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            padding: 18px 22px 8px !important;
            text-transform: uppercase !important;
        }
        .sidebar-menu > li > a {
            color: #94a3b8 !important;
            padding: 12px 18px !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
            border-left: 3px solid transparent !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            align-items: center !important;
            white-space: nowrap !important;
        }
        .sidebar-menu > li > a span {
            white-space: nowrap !important;
        }
        .sidebar-menu > li > a i {
            font-size: 18px !important;
            color: #64748b !important;
            transition: color 0.2s ease !important;
            margin-right: 10px !important;
            flex-shrink: 0 !important;
        }
        .sidebar-menu > li:hover > a {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.06) !important;
        }
        .sidebar-menu > li:hover > a i {
            color: #38bdf8 !important;
        }
        .sidebar-menu > li.active > a {
            color: #ffffff !important;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.28) 0%, rgba(37, 99, 235, 0.06) 100%) !important;
            border-left: 3px solid #3b82f6 !important;
            font-weight: 700 !important;
        }
        .sidebar-menu > li.active > a i {
            color: #60a5fa !important;
        }

        /* 📂 Dropdown Treeview Styles */
        .sidebar-menu .treeview > a {
            cursor: pointer;
            position: relative;
        }
        .sidebar-menu .treeview .pull-right-container {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.25s ease;
        }
        .sidebar-menu .treeview.active > a > .pull-right-container > i,
        .sidebar-menu .treeview.menu-open > a > .pull-right-container > i {
            transform: rotate(-90deg);
        }
        .sidebar-menu .treeview-menu {
            background: rgba(0, 0, 0, 0.25) !important;
            padding: 4px 0 8px 10px !important;
            list-style: none !important;
            margin: 0 !important;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-menu .treeview-menu > li > a {
            color: #94a3b8 !important;
            padding: 9px 14px 9px 12px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            border-left: 2px solid transparent !important;
            transition: all 0.2s ease !important;
            border-radius: 0 8px 8px 0 !important;
            margin: 2px 6px 2px 0 !important;
            white-space: nowrap !important;
        }
        .sidebar-menu .treeview-menu > li > a span {
            white-space: nowrap !important;
        }
        .sidebar-menu .treeview-menu > li > a i {
            font-size: 16px !important;
            color: #64748b !important;
            margin-right: 4px !important;
            transition: color 0.2s ease !important;
        }
        .sidebar-menu .treeview-menu > li:hover > a {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.05) !important;
            border-left-color: #38bdf8 !important;
        }
        .sidebar-menu .treeview-menu > li:hover > a i {
            color: #38bdf8 !important;
        }
        .sidebar-menu .treeview-menu > li.active > a {
            color: #ffffff !important;
            background: rgba(37, 99, 235, 0.2) !important;
            border-left-color: #3b82f6 !important;
            font-weight: 700 !important;
        }
        .sidebar-menu .treeview-menu > li.active > a i {
            color: #60a5fa !important;
        }

        /* 📦 Elevation for Cards & Panels */
        .box, .panel {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03) !important;
        }
        .box-header {
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        a {
            color: var(--primary-color);
        }

        .skin-blue .main-header .navbar .sidebar-toggle {
            color: var(--primary-color);
        }

        .skin-blue .main-header .navbar .sidebar-toggle:hover {
            background-color: var(--primary-color);
        }

        .image-uploaded #delete {
            background-color: var(--primary-color);
        }

        #progress {
            border: 1px solid var(--primary-color);
        }

        #progress .percent {
            background: var(--primary-color);
        }

        .direct-chat-primary .right .direct-chat-text {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
        }

        .nsup-btn {
            background: var(--primary-color);
        }

        .nsup-btn strong{
            color: #ffffff;
        }

        .full-width{
            width: 100%;
        }

    </style>
<?php AdminTemplateManager::loadScriptsLibs() ?>

<?php
$themeServicePath = FCPATH . 'home/ThemeService.php';
if (file_exists($themeServicePath)) {
    require_once $themeServicePath;
    echo ThemeService::renderAdminCss();
}
?>

</head>

<body class="hold-transition skin-blue sidebar-mini skin-custom-sf" dir="<?= Translate::getDir() ?>">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
    <?php if (!isMobile()) : ?>
        <?php if(isset($imageUrlLg) && !empty($imageUrlLg)): ?>
            <a href="<?= site_url("") ?>" class="logo">
                <span class="logo-lg"><img src="<?=$imageUrlLg?>"/></span>
                <span class="logo-mini"><img src="<?=$imageUrlLg?>"/></span>
            </a>
        <?php else: 
            $appName = ConfigManager::getValue("APP_NAME") ?: "TechThaiNews";
            // Highlight 'News' if present for extra polish
            if (preg_match('/^(.*?)(News)(.*)$/i', $appName, $m)) {
                $formattedName = htmlspecialchars($m[1]) . '<span style="color:#38bdf8;">' . htmlspecialchars($m[2]) . '</span>' . htmlspecialchars($m[3]);
            } else {
                $formattedName = htmlspecialchars($appName);
            }
        ?>
            <a href="<?= site_url("") ?>" class="logo">
                <span class="logo-lg">
                    <span class="admin-brand-icon"><i class="mdi mdi-newspaper-variant-outline"></i></span>
                    <span class="admin-brand-text"><b><?= $formattedName ?></b></span>
                </span>
                <span class="logo-mini">
                    <span class="admin-brand-icon"><i class="mdi mdi-newspaper-variant-outline"></i></span>
                </span>
            </a>
        <?php endif; ?>
    <?php endif; ?>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= Translate::sprint("Toggle navigation", "") ?></span>
            </a>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                <?php CMS_Display::render('subscription_status_v1')?>
                <?php CMS_Display::render('campaigns_pending_list_v1')?>

                    <!-- Control Sidebar Toggle Button -->
                <?php CMS_Display::render('dropdown_v1')?>
                <?php CMS_Display::render('language_dropdown_v1')?>

                <?php CMS_Display::render('user_v1');?>



                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <!-- Sidebar Menu -->
        <?php $this->load->view(AdminPanel::TemplatePath."/include/sidebar"); ?>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>


