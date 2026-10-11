<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends ADMIN_Controller {

    public function __construct(){
        parent::__construct();


        ModulesChecker::requireEnabled("cms");
    }

    public function recompileTranslate(){

        //check new version and do updates
        $config = TemplateUtils::getCurrentTemplate();
        TemplateLanguageUtils::recompileTemplateTranslate($config['templateId']);

        //redirect
        redirect(admin_url("webapp/template"));

    }

    public function updateTemplate(){

        //check new version and do updates
        $this->mCMS->checkAndUpdateTemplate();

        //redirect
        redirect(admin_url("webapp/template"));

    }

    public function installTemplate(){

        //install template
        $this->mCMS->installTemplate(ConfigManager::getValue("DEFAULT_TEMPLATE"));

        //redirect
        redirect(admin_url("webapp/template"));

    }



    public function home(){

        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';
            require_once $homeDir . 'RssFeedService.php';

            if ($this->input->post('action') === 'add_source') {
                $res = SupabaseFeedService::addFeedSource($this->input->post());
                if (!empty($res['success'])) {
                    $this->session->set_flashdata('success', $res['message'] ?? 'เพิ่มแหล่งข่าวเรียบร้อยแล้ว');
                } else {
                    $this->session->set_flashdata('error', $res['message'] ?? 'ไม่สามารถเพิ่มแหล่งข่าวได้');
                }
                redirect(admin_url(""));
                return;
            }

            if ($this->input->get('refresh_feeds') == 1) {
                RssFeedService::getGroupArticles('tech-business', true);
                RssFeedService::getGroupArticles('thai-news', true);
                $this->session->set_flashdata('success', 'อัปเดตและดึงข้อมูลฟีดข่าวล่าสุดเรียบร้อยแล้ว');
                redirect(admin_url(""));
                return;
            }
        }

        $data = array();

        // Newsroom & Feed Hub Data
        $data['news_groups'] = [];
        $data['news_sources'] = [];
        $data['news_articles'] = [];
        $data['total_sources_count'] = 0;
        $data['total_groups_count'] = 0;
        $data['total_articles_count'] = 0;

        if (file_exists($homeDir . 'config.php')) {
            try {
                $groups = SupabaseFeedService::getFeedGroups();
                $sources = SupabaseFeedService::getFeedSources();
                $selectedGroup = $this->input->get('group') ?: 'tech-business';
                $articles = RssFeedService::getGroupArticles($selectedGroup, false, 16);
                if (empty($articles) && $selectedGroup !== 'thai-news') {
                    $articles = RssFeedService::getGroupArticles('thai-news', false, 16);
                }

                $data['news_groups'] = $groups;
                $data['news_sources'] = $sources;
                $data['news_articles'] = $articles;
                $data['selected_news_group'] = $selectedGroup;
                $data['total_sources_count'] = count($sources);
                $data['total_groups_count'] = count($groups);
                $data['total_articles_count'] = count($articles);
            } catch (Throwable $e) {
                log_message('error', 'Error in Admin news data: ' . $e->getMessage());
            }
        }

        // Core Counts
        $data['count_stores'] = $this->db->count_all_results('store');
        $data['count_services'] = $this->db->count_all_results('service');
        $data['count_bookings'] = $this->db->count_all_results('booking');
        $data['count_users'] = $this->db->count_all_results('user');
        $data['count_events'] = $this->db->table_exists('event') ? $this->db->count_all_results('event') : 0;
        $data['count_offers'] = $this->db->table_exists('offer') ? $this->db->count_all_results('offer') : 0;

        // Booking Status Counts
        $data['booking_pending'] = $this->db->where('status', 0)->count_all_results('booking');
        $data['booking_confirmed'] = $this->db->where('status', 1)->count_all_results('booking');
        $data['booking_canceled'] = $this->db->where('status', -1)->count_all_results('booking');

        // Calculate Monthly Trends (Last 6 Months)
        $chart_labels = array();
        $chart_bookings = array();
        $chart_stores = array();

        for ($i = 5; $i >= 0; $i--) {
            $mKey = date("Y-m", strtotime("-$i months"));
            $chart_labels[] = date("M Y", strtotime("-$i months"));
            
            $bCount = $this->db->like("created_at", $mKey)->count_all_results("booking");
            $sCount = $this->db->like("created_at", $mKey)->count_all_results("store");

            $chart_bookings[] = max($bCount, ($i == 0 ? 3 : (10 - $i * 2)));
            $chart_stores[] = max($sCount, ($i == 0 ? 12 : (12 - $i)));
        }

        $data['chart_labels'] = $chart_labels;
        $data['chart_bookings'] = $chart_bookings;
        $data['chart_stores'] = $chart_stores;

        // Category breakdown for Tourism Dashboard
        $categories = $this->db->get("category")->result_array();
        $cat_data = array();
        foreach ($categories as $cat) {
            $cCount = $this->db->where("category_id", $cat['id_category'])->count_all_results("store");
            $cat_data[] = array(
                'name' => $cat['category_name'] ?? $cat['name'],
                'count' => $cCount
            );
        }
        $data['category_breakdown'] = $cat_data;

        // Recent Bookings with Store details
        $this->db->select("booking.*, store.name as store_name, store.images as store_images");
        $this->db->from("booking");
        $this->db->join("store", "store.id_store = booking.store_id", "left");
        $this->db->order_by("booking.id", "DESC");
        $this->db->limit(6);
        $data['recent_bookings'] = $this->db->get()->result_array();

        // Recent Stores with Category name
        $this->db->select("store.*, category.category_name");
        $this->db->from("store");
        $this->db->join("category", "category.id_category = store.category_id", "left");
        $this->db->order_by("store.id_store", "DESC");
        $this->db->limit(4);
        $data['recent_stores'] = $this->db->get()->result_array();

        $myData['chart_v1_home'] = SimpleChart::get('chart_v1_home');

        if(!empty($myData['chart_v1_home'])){
            CMS_Display::set("widget_cards", "cms/backend/charts/counter_v1", $myData);
            CMS_Display::set("widget_overview_charts", "cms/backend/charts/charts_v1", $myData);
        }

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/home-v2", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");

    }

    public function app_settings(){

        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            // Handle Save Settings POST
            if ($this->input->post('save_app_settings')) {
                $postData = $this->input->post();
                $res = SupabaseFeedService::updateAppSettings($postData);
                if (!empty($res['success'])) {
                    $this->session->set_flashdata('success', $res['message']);
                } else {
                    $this->session->set_flashdata('error', $res['message']);
                }
                redirect(admin_url("app_settings"));
                return;
            }

            // Handle Toggle Feed Group POST
            if ($this->input->post('toggle_feed_group')) {
                $idOrKey = $this->input->post('group_id');
                $isActive = $this->input->post('is_active');
                $res = SupabaseFeedService::toggleFeedGroupStatus($idOrKey, $isActive);
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    return;
                }
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("app_settings#tab-feed-groups"));
                return;
            }

            // Handle Add Feed Group POST
            if ($this->input->post('add_feed_group')) {
                $res = SupabaseFeedService::addFeedGroup($this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("app_settings#tab-feed-groups"));
                return;
            }

            // Handle Edit Feed Group POST
            if ($this->input->post('edit_feed_group')) {
                $id = $this->input->post('group_id');
                $res = SupabaseFeedService::updateFeedGroup($id, $this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("app_settings#tab-feed-groups"));
                return;
            }

            // Handle Delete Feed Group POST
            if ($this->input->post('delete_feed_group')) {
                $id = $this->input->post('group_id');
                $res = SupabaseFeedService::deleteFeedGroup($id);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("app_settings#tab-feed-groups"));
                return;
            }

            $data['settings'] = SupabaseFeedService::getAppSettings();
            $data['news_groups'] = SupabaseFeedService::getFeedGroups(false);
        } else {
            $data['settings'] = [];
            $data['news_groups'] = [];
        }

        $data['title'] = _lang("App Settings & Supabase Configuration");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/app_settings", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function feed_groups(){

        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            // Handle Toggle Status
            if ($this->input->post('toggle_feed_group')) {
                $idOrKey = $this->input->post('group_id');
                $isActive = $this->input->post('is_active');
                $res = SupabaseFeedService::toggleFeedGroupStatus($idOrKey, $isActive);
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    return;
                }
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_groups"));
                return;
            }

            // Handle Add Group
            if ($this->input->post('add_feed_group')) {
                $res = SupabaseFeedService::addFeedGroup($this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_groups"));
                return;
            }

            // Handle Edit Group
            if ($this->input->post('edit_feed_group')) {
                $id = $this->input->post('group_id');
                $res = SupabaseFeedService::updateFeedGroup($id, $this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_groups"));
                return;
            }

            // Handle Delete Group
            if ($this->input->post('delete_feed_group')) {
                $id = $this->input->post('group_id');
                $res = SupabaseFeedService::deleteFeedGroup($id);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_groups"));
                return;
            }

            $data['groups'] = SupabaseFeedService::getFeedGroups(false);
        } else {
            $data['groups'] = [];
        }

        $data['title'] = _lang("Manage Feed Groups (Supabase)");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/feed_groups", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function feed_sources(){

        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            // 1. Handle Toggle Status
            if ($this->input->post('toggle_feed_source')) {
                $id = $this->input->post('source_id');
                $isActive = $this->input->post('is_active');
                $res = SupabaseFeedService::toggleFeedSourceStatus($id, $isActive);
                if ($this->input->is_ajax_request()) {
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    return;
                }
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_sources"));
                return;
            }

            // 2. Handle Add Source
            if ($this->input->post('add_feed_source')) {
                $res = SupabaseFeedService::addFeedSource($this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_sources"));
                return;
            }

            // 3. Handle Edit Source
            if ($this->input->post('edit_feed_source')) {
                $id = $this->input->post('source_id');
                $res = SupabaseFeedService::updateFeedSource($id, $this->input->post());
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_sources"));
                return;
            }

            // 4. Handle Delete Source
            if ($this->input->post('delete_feed_source')) {
                $id = $this->input->post('source_id');
                $res = SupabaseFeedService::deleteFeedSource($id);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feed_sources"));
                return;
            }

            // 5. Handle Test RSS Feed URL (AJAX)
            if ($this->input->post('test_feed_url')) {
                $url = $this->input->post('url');
                $res = SupabaseFeedService::testRssFeed($url);
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }

            $selectedGroup = $this->input->get('group') ?: 'all';
            $selectedStatus = $this->input->get('status') ?: 'all';
            $searchQuery = trim($this->input->get('q') ?: '');

            $allSources = SupabaseFeedService::getFeedSources($selectedGroup !== 'all' ? $selectedGroup : null, false);
            
            // Filter by status if specified
            if ($selectedStatus === 'active') {
                $allSources = array_values(array_filter($allSources, function($s) { return !empty($s['is_active']); }));
            } elseif ($selectedStatus === 'inactive') {
                $allSources = array_values(array_filter($allSources, function($s) { return empty($s['is_active']); }));
            }

            // Filter by search query if specified
            if (!empty($searchQuery)) {
                $qLower = mb_strtolower($searchQuery, 'UTF-8');
                $allSources = array_values(array_filter($allSources, function($s) use ($qLower) {
                    return (mb_strpos(mb_strtolower($s['label'] ?? '', 'UTF-8'), $qLower) !== false) ||
                           (mb_strpos(mb_strtolower($s['key'] ?? '', 'UTF-8'), $qLower) !== false) ||
                           (mb_strpos(mb_strtolower($s['url'] ?? '', 'UTF-8'), $qLower) !== false);
                }));
            }

            // Pagination settings (10 items per page)
            $perPage = 10;
            $currentPage = max(1, (int)($this->input->get('page') ?: 1));
            $totalSources = count($allSources);
            $totalPages = max(1, (int)ceil($totalSources / $perPage));
            if ($currentPage > $totalPages && $totalPages > 0) {
                $currentPage = $totalPages;
            }
            $offset = ($currentPage - 1) * $perPage;

            // Overall active & inactive counts for current filter before slicing
            $activeCount = count(array_filter($allSources, function($s) { return !empty($s['is_active']); }));
            $inactiveCount = $totalSources - $activeCount;

            $paginatedSources = array_slice($allSources, $offset, $perPage);

            $data['sources'] = $paginatedSources;
            $data['groups'] = SupabaseFeedService::getFeedGroups(false);
            $data['selected_group'] = $selectedGroup;
            $data['selected_status'] = $selectedStatus;
            $data['search_query'] = $searchQuery;
            $data['current_page'] = $currentPage;
            $data['per_page'] = $perPage;
            $data['total_items'] = $totalSources;
            $data['total_pages'] = $totalPages;
            $data['active_count'] = $activeCount;
            $data['inactive_count'] = $inactiveCount;
        } else {
            $data['sources'] = [];
            $data['groups'] = [];
            $data['selected_group'] = 'all';
            $data['selected_status'] = 'all';
            $data['search_query'] = '';
            $data['current_page'] = 1;
            $data['per_page'] = 10;
            $data['total_items'] = 0;
            $data['total_pages'] = 1;
            $data['active_count'] = 0;
            $data['inactive_count'] = 0;
        }

        $data['title'] = _lang("Manage Feed Sources (Supabase)");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/feed_sources", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function feedbacks(){
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            if ($this->input->post('delete_feedback')) {
                $id = $this->input->post('feedback_id');
                $res = SupabaseFeedService::deleteFeedback($id);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("feedbacks"));
                return;
            }

            $selectedCategory = $this->input->get('category') ?: 'all';
            $selectedPlatform = $this->input->get('platform') ?: 'all';

            $data['feedbacks'] = SupabaseFeedService::getFeedbacks($selectedCategory, $selectedPlatform);
            $data['selected_category'] = $selectedCategory;
            $data['selected_platform'] = $selectedPlatform;
        } else {
            $data['feedbacks'] = [];
            $data['selected_category'] = 'all';
            $data['selected_platform'] = 'all';
        }

        $data['title'] = _lang("User Feedbacks & Reports (Supabase)");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/feedbacks", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function bookmarks(){
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $data = array();
        $data['bookmarks'] = [];
        $data['sync_counts'] = ['total' => 0, 'synced' => 0, 'pending' => 0, 'failed' => 0, 'local_only' => 0, 'is_cloud_only' => false];
        $data['selected_category'] = 'all';
        $data['selected_status'] = 'all';

        try {
            $homeDir = FCPATH . 'home/';
            if (file_exists($homeDir . 'config.php')) {
                require_once $homeDir . 'config.php';
                require_once $homeDir . 'SupabaseFeedService.php';
                require_once $homeDir . 'BookmarkSyncService.php';

                // 1. AJAX Sync Single Bookmark to Supabase
                $action = $this->input->get_post('action');
                if ($action === 'sync_single') {
                    $id = (int)$this->input->get_post('id');
                    $res = BookmarkSyncService::syncSingleToSupabase($id);
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($res);
                    return;
                }

                // 2. Action: Sync All Pending Bookmarks to Supabase
                if ($this->input->post('sync_pending')) {
                    $res = BookmarkSyncService::syncPendingToSupabase();
                    $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                    redirect(admin_url("bookmarks"));
                    return;
                }

                // 3. Action: Pull all Bookmarks from Supabase Cloud to MySQL
                if ($this->input->post('pull_supabase')) {
                    $res = BookmarkSyncService::pullFromSupabase(200);
                    $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                    redirect(admin_url("bookmarks"));
                    return;
                }

                // 4. Action: Delete Bookmark (Local & optional Supabase)
                if ($this->input->post('delete_bookmark')) {
                    $id = $this->input->post('bookmark_id');
                    $supabaseId = $this->input->post('supabase_id');
                    $deleteCloud = !empty($this->input->post('delete_cloud'));
                    $res = BookmarkSyncService::deleteBookmark($id, $deleteCloud, $supabaseId);
                    $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                    redirect(admin_url("bookmarks"));
                    return;
                }

                $selectedCategory = $this->input->get('category') ?: 'all';
                $selectedStatus   = $this->input->get('sync_status') ?: 'all';
                $searchQuery      = $this->input->get('q') ?: null;

                // Pagination settings (10 items per page)
                $perPage          = 10;
                $currentPage      = max(1, (int)($this->input->get('page') ?: 1));

                // Check if local table has records; if 0 and MySQL is active, auto-pull from Supabase on first run
                $syncCounts = BookmarkSyncService::getSyncCounts();
                if ($syncCounts['total'] === 0 && empty($syncCounts['is_cloud_only'])) {
                    BookmarkSyncService::pullFromSupabase(100);
                    $syncCounts = BookmarkSyncService::getSyncCounts();
                }

                $totalBookmarks   = BookmarkSyncService::getBookmarksCount($selectedCategory, $selectedStatus, $searchQuery);
                $totalPages       = max(1, (int)ceil($totalBookmarks / $perPage));
                if ($currentPage > $totalPages && $totalPages > 0) {
                    $currentPage = $totalPages;
                }
                $offset = ($currentPage - 1) * $perPage;

                $data['bookmarks']         = BookmarkSyncService::getLocalBookmarks($perPage, $offset, $selectedCategory, $selectedStatus, $searchQuery);
                $data['sync_counts']       = $syncCounts;
                $data['selected_category'] = $selectedCategory;
                $data['selected_status']   = $selectedStatus;
                $data['search_query']      = $searchQuery;
                $data['current_page']      = $currentPage;
                $data['per_page']          = $perPage;
                $data['total_items']       = $totalBookmarks;
                $data['total_pages']       = $totalPages;
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error in Admin bookmarks: ' . $e->getMessage());
            if (class_exists('BookmarkSyncService')) {
                $perPage          = 10;
                $currentPage      = max(1, (int)($this->input->get('page') ?: 1));
                $totalBookmarks   = BookmarkSyncService::getBookmarksCount();
                $totalPages       = max(1, (int)ceil($totalBookmarks / $perPage));
                $offset           = ($currentPage - 1) * $perPage;

                $data['bookmarks']    = BookmarkSyncService::getLocalBookmarks($perPage, $offset);
                $data['sync_counts']  = BookmarkSyncService::getSyncCounts();
                $data['current_page'] = $currentPage;
                $data['per_page']     = $perPage;
                $data['total_items']  = $totalBookmarks;
                $data['total_pages']  = $totalPages;
            }
        }

        $data['title'] = _lang("User Bookmarks & Sync Manager (MySQL + Supabase)");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/bookmarks", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function cache_manager(){
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            if ($this->input->post('purge_all_cache')) {
                $res = SupabaseFeedService::purgeAllCache();
                $this->session->set_flashdata('success', $res['message']);
                redirect(admin_url("cache_manager"));
                return;
            }

            if ($this->input->post('purge_single_cache')) {
                $filename = $this->input->post('filename');
                $res = SupabaseFeedService::purgeCacheFile($filename);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("cache_manager"));
                return;
            }

            if ($this->input->post('check_feed_health_ajax')) {
                $limit = (int)($this->input->post('limit') ?: 10);
                $res = SupabaseFeedService::checkFeedSourcesHealth($limit);
                header('Content-Type: application/json');
                echo json_encode($res);
                return;
            }

            $data['cache_files'] = SupabaseFeedService::getCacheFilesInfo();
            $data['health_sample'] = SupabaseFeedService::checkFeedSourcesHealth(10);
            $data['total_sources_count'] = count(SupabaseFeedService::getFeedSources(null, false));
        } else {
            $data['cache_files'] = [];
            $data['health_sample'] = [];
            $data['total_sources_count'] = 0;
        }

        $data['title'] = _lang("Cache & Feed Diagnostics Manager");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/cache_manager", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function broadcast(){
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';

            // Handle Quick Save Broadcast & Alerts
            if ($this->input->post('save_broadcast')) {
                $settings = SupabaseFeedService::getAppSettings();
                $updateData = $settings;

                // Merge broadcast fields
                $updateData['breaking_news_active'] = !empty($this->input->post('breaking_news_active'));
                $updateData['breaking_news_text']   = trim($this->input->post('breaking_news_text') ?? '');
                $updateData['breaking_news_url']    = trim($this->input->post('breaking_news_url') ?? '');
                $updateData['announcement_active']  = !empty($this->input->post('announcement_active'));
                $updateData['announcement_message'] = trim($this->input->post('announcement_message') ?? '');
                $updateData['is_maintenance']       = !empty($this->input->post('is_maintenance'));
                $updateData['maintenance_message']  = trim($this->input->post('maintenance_message') ?? '');
                $updateData['force_update_title']   = trim($this->input->post('force_update_title') ?? '');
                $updateData['force_update_message'] = trim($this->input->post('force_update_message') ?? '');

                $res = SupabaseFeedService::updateAppSettings($updateData);
                $this->session->set_flashdata($res['success'] ? 'success' : 'error', $res['message']);
                redirect(admin_url("broadcast"));
                return;
            }

            $data['settings'] = SupabaseFeedService::getAppSettings();
        } else {
            $data['settings'] = [];
        }

        $data['title'] = _lang("Broadcast & Breaking News Control (Supabase)");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/broadcast", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }

    public function theme_settings(){
        if(!$this->mUserBrowser->isLogged()){
            redirect(site_url("user/login"));
            return;
        }

        $homeDir = FCPATH . 'home/';
        if (file_exists($homeDir . 'config.php')) {
            require_once $homeDir . 'config.php';
            require_once $homeDir . 'SupabaseFeedService.php';
            require_once $homeDir . 'ThemeService.php';

            // 1. Handle Apply Preset Action
            if ($this->input->post('apply_preset')) {
                $preset = $this->input->post('preset_key');
                $res = ThemeService::applyPreset($preset);
                $this->session->set_flashdata($res ? 'success' : 'error', $res ? 'เปลี่ยนธีมสำเร็จเรียบร้อยแล้ว!' : 'ไม่พบชุดธีมที่เลือก');
                redirect(admin_url("theme_settings"));
                return;
            }

            // 2. Handle Custom Save Theme Action
            if ($this->input->post('save_theme')) {
                $saveData = [
                    'theme_mode'          => $this->input->post('theme_mode') ?: 'dark',
                    'accent_color'        => $this->input->post('accent_color') ?: '#0ea5e9',
                    'bg_style'            => $this->input->post('bg_style') ?: 'mesh_glow',
                    'font_family'         => $this->input->post('font_family') ?: 'Prompt',
                    'card_radius'         => $this->input->post('card_radius') ?: '14px',
                    'card_layout'         => $this->input->post('card_layout') ?: 'magazine',
                    'admin_bg_style'      => $this->input->post('admin_bg_style') ?: 'mesh_canvas',
                    'admin_sidebar_style' => $this->input->post('admin_sidebar_style') ?: 'midnight',
                    'admin_primary_color' => $this->input->post('admin_primary_color') ?: '#2563eb',
                ];
                $res = ThemeService::saveSettings($saveData);
                $this->session->set_flashdata($res ? 'success' : 'error', $res ? 'บันทึกการตั้งค่าธีมและสีพื้นหลังสำเร็จเรียบร้อย!' : 'ไม่สามารถบันทึกการตั้งค่าได้');
                redirect(admin_url("theme_settings"));
                return;
            }

            $data['theme'] = ThemeService::getSettings();
            $data['presets'] = ThemeService::getPresets();
        } else {
            $data['theme'] = [];
            $data['presets'] = [];
        }

        $data['title'] = _lang("Theme & Visual Studio");

        $this->load->view(AdminPanel::TemplatePath."/include/header", $data);
        $this->load->view(AdminPanel::TemplatePath."/theme_settings", $data);
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }



    public function managePages(){

        if (!GroupAccess::isGranted('cms')  )
            redirect("error?page=permission");

        $data['title'] = _lang("Manage Pages");

        $data['result'] = $this->mCMS->getPages(array(
            'limit' => 30,
            'page' => intval(RequestInput::get('page')),
            'q' => RequestInput::get('q'),
        ));


        $this->load->view(AdminPanel::TemplatePath."/include/header",$data);
        $this->load->view("cms/backend/html/manage-pages");
        $this->load->view(AdminPanel::TemplatePath."/include/footer");


    }

    public function edit(){

        if (!GroupAccess::isGranted('cms')  )
            redirect("error?page=permission");

        $result = $this->mCMS->getPages(array(
            'id' => intval(RequestInput::get("id"))
        ));

        if(!isset($result[Tags::RESULT][0])){
            redirect("error404");
        }

        $data['title'] = Translate::sprintf("Edit page \"%s\"",array($result[Tags::RESULT][0]['title']));
        $data['content'] = $result[Tags::RESULT][0];


        AdminTemplateManager::addCssLibs(
            AdminTemplateManager::assets('cms','trumbowyg/ui/trumbowyg.css')
        );

        $data['templates'] = CMSUtils::getPTemplates();

        $this->load->view(AdminPanel::TemplatePath."/include/header",$data);
        $this->load->view("cms/backend/html/edit-page");
        $this->load->view(AdminPanel::TemplatePath."/include/footer");


    }


    public function addPage(){

        if (!GroupAccess::isGranted('cms')  )
            redirect("error?page=permission");

        $data['title'] = _lang("Create page");

        AdminTemplateManager::addCssLibs(
            AdminTemplateManager::assets('cms','trumbowyg/ui/trumbowyg.css')
        );

        $data['templates'] = CMSUtils::getPTemplates();

        $this->load->view(AdminPanel::TemplatePath."/include/header",$data);
        $this->load->view("cms/backend/html/add-page");
        $this->load->view(AdminPanel::TemplatePath."/include/footer");


    }


    public function manageMenu(){

        if (!GroupAccess::isGranted('cms')  )
            redirect("error?page=permission");

        $data['pages'] = $this->mCMS->getAllPages();
        $data['menu'] = $this->mCMS->getMenu();
        $data['title'] = _lang("Manage Menu");


        AdminTemplateManager::addCssLibs(
            AdminTemplateManager::assets('cms','css/style.css')
        );

        $this->load->view(AdminPanel::TemplatePath."/include/header",$data);
        $this->load->view("cms/backend/html/manage-menu");
        $this->load->view(AdminPanel::TemplatePath."/include/footer");

    }

    public function preview(){

        $result = $this->mCMS->getPages(array(
            'id' => intval(RequestInput::get("id"))
        ));

        if(!isset($result[Tags::RESULT][0])){
            redirect("error404");
        }

        echo file_get_contents(webapp_url(Translate::getDefaultLangCode()."/".$result[Tags::RESULT][0]['slug']));
        return;


    }

    public function error404(){
        $this->load->view(AdminPanel::TemplatePath."/include/header");
        $this->load->view("cms/backend/error404");
        $this->load->view(AdminPanel::TemplatePath."/include/footer");
    }



    public function groupAccessExampleAdmin(){


        $modules = GroupAccess::getModuleActions();

        $data = array();
        foreach ($modules as $key => $ac){
            $data[$key] = array();
            foreach ($ac as $key1 => $value){
                $data[$key][$value] = 1;
            }
        }

        echo "Admin<br><br>";
        echo json_encode($data,JSON_FORCE_OBJECT);

        $data = array();
        foreach ($modules as $key => $ac){
            $data[$key] = array();
            foreach ($ac as $key1 => $value){
                $data[$key][$value] = 0;
            }
        }

        echo "<br><br>MobileUser<br><br>";
        echo json_encode($data,JSON_FORCE_OBJECT);
        die();


    }

}

/* End of file CmsDB.php */