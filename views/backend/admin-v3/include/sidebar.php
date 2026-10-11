<?php

$uri_m = $this->uri->segment(2);
$uri_parent = $this->uri->segment(3);
$uri_child = $this->uri->segment(4);

// Current action segment for admin modules (e.g. admin/theme_settings -> segment 2)
$currentPage = $uri_m ?: '';
$isDashboardActive = ($currentPage === '' || $currentPage === 'index' || $currentPage === 'home');

$isNewsActive     = in_array($currentPage, ["feed_groups", "feed_sources", "bookmarks", "broadcast"]);
$isAudienceActive = in_array($currentPage, ["bookmarks", "feedbacks"]);
$isSystemActive   = in_array($currentPage, ["theme_settings", "cache_manager", "app_settings"]);
?>

<ul class="sidebar-menu">

    <li id="menu-search" class="header menu-search hidden">
        <form autocomplete="off">
            <input type="text" placeholder="<?= _lang("Quick search...") ?>" autocomplete="off"/>
            <i class="mdi mdi-magnify"></i>
        </form>
    </li>

    <?php CMS_Display::render('sidebarTopHook') ?>
    <li class="header"><?= Translate::sprint("MENU", "") ?></li>

    <!-- 1. แดชบอร์ด (Dashboard) -->
    <li class="<?php if ($isDashboardActive) echo "active"; ?>">
        <a href="<?= admin_url("") ?>"><i class="mdi mdi-chart-line"></i> &nbsp;<span>
                        <?= Translate::sprint("Dashboard") ?></span></a>
    </li>

    <!-- 2. ข่าวสาร & สื่อ (News & Media) Dropdown -->
    <li class="treeview <?= $isNewsActive ? 'active menu-open' : '' ?>">
        <a href="javascript:void(0);">
            <i class="mdi mdi-newspaper"></i> &nbsp;<span><?= Translate::sprint("ข่าวสาร & สื่อ (News)") ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu" style="<?= $isNewsActive ? 'display: block;' : '' ?>">
            <li class="<?= $currentPage == "feed_groups" ? 'active' : '' ?>">
                <a href="<?= admin_url("feed_groups") ?>"><i class="mdi mdi-view-grid-plus-outline"></i> &nbsp;<span>หมวดหมู่ข่าว (Feed Groups)</span></a>
            </li>
            <li class="<?= $currentPage == "feed_sources" ? 'active' : '' ?>">
                <a href="<?= admin_url("feed_sources") ?>"><i class="mdi mdi-rss-box"></i> &nbsp;<span>แหล่งข่าว RSS (Feed Sources)</span></a>
            </li>
            <li class="<?= $currentPage == "bookmarks" ? 'active' : '' ?>">
                <a href="<?= admin_url("bookmarks") ?>"><i class="mdi mdi-bookmark-check-outline"></i> &nbsp;<span>บุ๊กมาร์กข่าว (Bookmarks)</span></a>
            </li>
            <li class="<?= $currentPage == "broadcast" ? 'active' : '' ?>">
                <a href="<?= admin_url("broadcast") ?>"><i class="mdi mdi-bullhorn"></i> &nbsp;<span>ประกาศ & ข่าวด่วน (Broadcast)</span></a>
            </li>
        </ul>
    </li>

    <!-- 3. ผู้อ่าน & สถิติ (Audience & Engagement) Dropdown -->
    <li class="treeview <?= $isAudienceActive ? 'active menu-open' : '' ?>">
        <a href="javascript:void(0);">
            <i class="mdi mdi-account-group-outline"></i> &nbsp;<span><?= Translate::sprint("ผู้อ่าน & สมาชิก (Audience)") ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu" style="<?= $isAudienceActive ? 'display: block;' : '' ?>">
            <li class="<?= $currentPage == "bookmarks" ? 'active' : '' ?>">
                <a href="<?= admin_url("bookmarks") ?>"><i class="mdi mdi-bookmark-multiple-outline"></i> &nbsp;<span>ข่าวที่บันทึก (Saved News)</span></a>
            </li>
            <li class="<?= $currentPage == "feedbacks" ? 'active' : '' ?>">
                <a href="<?= admin_url("feedbacks") ?>"><i class="mdi mdi-message-alert-outline"></i> &nbsp;<span>ข้อเสนอแนะผู้ใช้ (Feedbacks)</span></a>
            </li>
        </ul>
    </li>

    <!-- 4. ตั้งค่า & ระบบ (Settings & System) Dropdown -->
    <li class="treeview <?= $isSystemActive ? 'active menu-open' : '' ?>">
        <a href="javascript:void(0);">
            <i class="mdi mdi-cog-sync-outline"></i> &nbsp;<span><?= Translate::sprint("ตั้งค่า & ระบบ (System)") ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu" style="<?= $isSystemActive ? 'display: block;' : '' ?>">
            <li class="<?= $currentPage == "theme_settings" ? 'active' : '' ?>">
                <a href="<?= admin_url("theme_settings") ?>"><i class="mdi mdi-palette-swatch-outline"></i> &nbsp;<span>ปรับแต่งธีม & สี (Theme Studio)</span></a>
            </li>
            <li class="<?= $currentPage == "cache_manager" ? 'active' : '' ?>">
                <a href="<?= admin_url("cache_manager") ?>"><i class="mdi mdi-flash-circle"></i> &nbsp;<span>จัดการแคช & สุขภาพระบบ</span></a>
            </li>
            <li class="<?= $currentPage == "app_settings" ? 'active' : '' ?>">
                <a href="<?= admin_url("app_settings") ?>"><i class="mdi mdi-tune-vertical"></i> &nbsp;<span>ตั้งค่าแอปพลิเคชัน (Settings)</span></a>
            </li>
        </ul>
    </li>

    <?php

    $loader = $this;
    $safeLoadView = function($path, $vars = NULL, $return = FALSE) use ($loader) {
        if (empty($path)) return $return ? "" : NULL;

        $exists = FALSE;
        if (class_exists('Modules')) {
            list($modPath) = Modules::find($path, NULL, 'views/');
            if ($modPath !== FALSE) {
                $exists = TRUE;
            }
        }
        if (!$exists) {
            $ext = pathinfo($path, PATHINFO_EXTENSION) ? '' : '.php';
            if (defined('VIEWPATH') && file_exists(VIEWPATH . $path . $ext)) {
                $exists = TRUE;
            } elseif (defined('APPPATH') && file_exists(APPPATH . 'views/' . $path . $ext)) {
                $exists = TRUE;
            }
        }

        if ($exists) {
            try {
                return $loader->load->view($path, $vars, $return);
            } catch (Throwable $e) {
                log_message('error', 'Error rendering sidebar view ' . $path . ': ' . $e->getMessage());
                return $return ? "" : NULL;
            }
        }
        return $return ? "" : NULL;
    };

    $adminMenu = AdminTemplateManager::loadMenu(FALSE, "menu");
    $adminMenu2 = AdminTemplateManager::loadMenu(FALSE, "Admin");
    $clientMenu = AdminTemplateManager::loadMenu(FALSE, "Client");

        if (!empty($clientMenu)) {
            foreach ($clientMenu as $menu) {
                $safeLoadView($menu['path']);
            }
        }



        if (!empty($adminMenu)) {
            $h = "";
            foreach ($adminMenu as $menu) {
                $h .= $safeLoadView($menu['path'],NULL,TRUE);
            }
            $h = trim($h);
            if(!empty($adminMenu) && !empty($clientMenu) && trim($h)!=""){
                echo '<li class="header">'.Translate::sprint("MANAGEMENT").'</li>';
                echo $h;
            }
        }


    if (!empty($adminMenu2)) {
        $h = "";
        foreach ($adminMenu2 as $menu) {
            $h .= $safeLoadView($menu['path'],NULL,TRUE);
        }
        $h = trim($h);
        if($h!="" && !empty($adminMenu2)){
            echo '<li class="header">'.Translate::sprint("ADMINISTRATIVE").'</li>';
            echo $h;
        }
    }

    ?>

    <?php if (GroupAccess::isGranted('setting')) { ?>
        <li class="treeview <?php if (AdminTemplateManager::isSettingActive()) echo 'active' ?>">
            <a href="<?= admin_url("application") ?>"><i class="mdi mdi-cog-outline"></i> &nbsp;
                <span> <?= Translate::sprint("Application") ?></span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>

            <ul class="treeview-menu">

                <?php

                $menuList = AdminTemplateManager::loadMenuSetting();
                if (!empty($menuList)) {
                    foreach ($menuList as $menu) {
                        $safeLoadView($menu['path']);
                    }
                }

                ?>


            </ul>
        </li>
    <?php } ?>


</ul>

<script>
$(document).ready(function() {
    // Smooth treeview slideToggle for custom dropdowns
    $('.sidebar-menu').on('click', '.treeview > a', function(e) {
        var $this = $(this);
        var $parent = $this.parent('li');
        var $submenu = $parent.find('> .treeview-menu');

        if ($submenu.length) {
            e.preventDefault();
            if ($parent.hasClass('menu-open')) {
                $parent.removeClass('menu-open');
                $submenu.slideUp(200);
            } else {
                $parent.addClass('menu-open active');
                $submenu.slideDown(200);
            }
        }
    });
});
</script>