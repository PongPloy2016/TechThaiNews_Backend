<?php
/**
 * Class ThemeService
 *
 * Manages theme presets, background styles, color accents,
 * and typography for both Frontend News Portal and Admin Dashboard.
 *
 * File: home/ThemeService.php
 */

class ThemeService {

    private static string $configFile = __DIR__ . '/cache/theme_config.json';
    private static ?array $cached = null;

    /**
     * Default Theme Settings
     */
    public static function getDefaults(): array {
        return [
            'theme_mode'          => 'dark',            // 'dark', 'light', 'system'
            'accent_color'        => '#0ea5e9',         // Primary Accent (Sky Blue)
            'bg_style'            => 'mesh_glow',       // 'mesh_glow', 'minimal_solid', 'midnight_aurora', 'warm_paper'
            'font_family'         => 'Prompt',          // 'Prompt', 'Plus Jakarta Sans', 'Inter', 'Kanit'
            'card_radius'         => '14px',            // '6px', '10px', '14px', '20px'
            'card_layout'         => 'magazine',        // 'magazine', 'grid', 'compact'
            'admin_bg_style'      => 'mesh_canvas',     // 'mesh_canvas', 'pure_white', 'dark_slate', 'warm_paper'
            'admin_sidebar_style' => 'midnight',        // 'midnight', 'deep_slate', 'clean_light'
            'admin_primary_color' => '#2563eb',         // '#2563eb', '#0ea5e9', '#6366f1', '#10b981', '#f43f5e'
            'updated_at'          => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Curated Presets
     */
    public static function getPresets(): array {
        return [
            'cyber_sky' => [
                'name'                => 'Cyber Sky (ค่าเริ่มต้น)',
                'description'         => 'ทันสมัยสไตล์พอร์ทัลข่าวเทค สว่างสดใส คอนทราสต์สูง',
                'theme_mode'          => 'dark',
                'accent_color'        => '#0ea5e9',
                'bg_style'            => 'mesh_glow',
                'font_family'         => 'Prompt',
                'card_radius'         => '14px',
                'card_layout'         => 'magazine',
                'admin_bg_style'      => 'mesh_canvas',
                'admin_sidebar_style' => 'midnight',
                'admin_primary_color' => '#0ea5e9',
                'badge_color'         => '#0ea5e9',
            ],
            'royal_indigo' => [
                'name'                => 'Royal Indigo (พรีเมียมเทค)',
                'description'         => 'เฉดสีม่วงครามลุ่มลึก สไตล์แอป SaaS ระดับโลก',
                'theme_mode'          => 'dark',
                'accent_color'        => '#6366f1',
                'bg_style'            => 'mesh_glow',
                'font_family'         => 'Plus Jakarta Sans',
                'card_radius'         => '16px',
                'card_layout'         => 'magazine',
                'admin_bg_style'      => 'mesh_canvas',
                'admin_sidebar_style' => 'midnight',
                'admin_primary_color' => '#6366f1',
                'badge_color'         => '#6366f1',
            ],
            'emerald_mint' => [
                'name'                => 'Emerald Mint (ธรรมชาติ & สบายตา)',
                'description'         => 'โทนเขียวมรกตสดชื่น อ่านง่าย สบายสายตาทั้งกลางวันและกลางคืน',
                'theme_mode'          => 'light',
                'accent_color'        => '#10b981',
                'bg_style'            => 'mesh_glow',
                'font_family'         => 'Prompt',
                'card_radius'         => '12px',
                'card_layout'         => 'grid',
                'admin_bg_style'      => 'mesh_canvas',
                'admin_sidebar_style' => 'deep_slate',
                'admin_primary_color' => '#10b981',
                'badge_color'         => '#10b981',
            ],
            'warm_editorial' => [
                'name'                => 'Warm Editorial (นิตยสารกระดาษนวล)',
                'description'         => 'โทนสีทองอบอุ่น นุ่มนวลตา สไตล์ Monocle & Financial Times',
                'theme_mode'          => 'light',
                'accent_color'        => '#f59e0b',
                'bg_style'            => 'warm_paper',
                'font_family'         => 'Prompt',
                'card_radius'         => '10px',
                'card_layout'         => 'magazine',
                'admin_bg_style'      => 'warm_paper',
                'admin_sidebar_style' => 'deep_slate',
                'admin_primary_color' => '#d97706',
                'badge_color'         => '#f59e0b',
            ],
            'midnight_obsidian' => [
                'name'                => 'Midnight Obsidian (มืดสนิท คอนทราสต์สูง)',
                'description'         => 'พื้นหลังดำสนิท ล้ำสมัย เหมาะกับสายฮาร์ดคอร์และจอ OLED',
                'theme_mode'          => 'dark',
                'accent_color'        => '#f43f5e',
                'bg_style'            => 'midnight_aurora',
                'font_family'         => 'Kanit',
                'card_radius'         => '14px',
                'card_layout'         => 'magazine',
                'admin_bg_style'      => 'dark_slate',
                'admin_sidebar_style' => 'midnight',
                'admin_primary_color' => '#f43f5e',
                'badge_color'         => '#f43f5e',
            ],
        ];
    }

    /**
     * Get active theme settings
     */
    public static function getSettings(): array {
        if (self::$cached !== null) {
            return self::$cached;
        }

        $defaults = self::getDefaults();

        if (file_exists(self::$configFile)) {
            $raw = @file_get_contents(self::$configFile);
            $saved = json_decode($raw, true);
            if (is_array($saved)) {
                self::$cached = array_merge($defaults, $saved);
                return self::$cached;
            }
        }

        // Initialize local file immediately with defaults
        $cacheDir = dirname(self::$configFile);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }
        @file_put_contents(self::$configFile, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        self::$cached = $defaults;
        return self::$cached;
    }

    /**
     * Save theme settings to local JSON and sync to Supabase
     */
    public static function saveSettings(array $data): bool {
        $current = self::getSettings();
        $updated = array_merge($current, $data);
        $updated['updated_at'] = date('Y-m-d H:i:s');

        $cacheDir = dirname(self::$configFile);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0777, true);
        }

        $res = @file_put_contents(self::$configFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Sync theme_mode & card_layout to Supabase app_settings
        if (class_exists('SupabaseFeedService')) {
            try {
                $sbData = SupabaseFeedService::getAppSettings();
                $sbData['default_theme_mode'] = $updated['theme_mode'] ?? 'system';
                $sbData['default_card_layout'] = $updated['card_layout'] ?? 'magazine';
                SupabaseFeedService::updateAppSettings($sbData);
            } catch (Throwable $e) {
                // Silent
            }
        }

        return $res !== false;
    }

    /**
     * Apply a preset by key
     */
    public static function applyPreset(string $presetKey): bool {
        $presets = self::getPresets();
        if (isset($presets[$presetKey])) {
            return self::saveSettings($presets[$presetKey]);
        }
        return false;
    }

    /**
     * Generate dynamic CSS overrides for Admin Header
     */
    public static function renderAdminCss(): string {
        $theme = self::getSettings();
        $primary = $theme['admin_primary_color'] ?? '#2563eb';
        $bgStyle = $theme['admin_bg_style'] ?? 'mesh_canvas';
        $sidebar = $theme['admin_sidebar_style'] ?? 'midnight';
        $font = $theme['font_family'] ?? 'Prompt';

        // Background styling
        $bgCss = "background-color: #f8fafc !important;";
        if ($bgStyle === 'mesh_canvas') {
            $bgCss = "background-color: #f8fafc !important;
                      background-image: 
                          radial-gradient(ellipse 80% 50% at 50% -20%, rgba(37, 99, 235, 0.05), transparent 70%),
                          radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.04), transparent 50%),
                          radial-gradient(circle at 0% 100%, rgba(14, 165, 233, 0.035), transparent 50%) !important;
                      background-attachment: fixed !important;";
        } elseif ($bgStyle === 'pure_white') {
            $bgCss = "background-color: #ffffff !important; background-image: none !important;";
        } elseif ($bgStyle === 'dark_slate') {
            $bgCss = "background-color: #0f172a !important; color: #f8fafc !important; background-image: none !important;";
        } elseif ($bgStyle === 'warm_paper') {
            $bgCss = "background-color: #faf8f5 !important;
                      background-image: radial-gradient(circle at 50% 0%, rgba(245, 158, 11, 0.04), transparent 60%) !important;
                      background-attachment: fixed !important;";
        }

        // Sidebar styling
        $sbCss = "background: linear-gradient(180deg, #0b0f19 0%, #0f172a 60%, #090d16 100%) !important;";
        if ($sidebar === 'deep_slate') {
            $sbCss = "background: #1e293b !important;";
        } elseif ($sidebar === 'clean_light') {
            $sbCss = "background: #ffffff !important; border-right: 1px solid #e2e8f0 !important;";
        }

        return "
        <style id='theme-dynamic-admin-css'>
            :root {
                --adm-primary: {$primary};
                --primary-color: {$primary} !important;
            }
            body, .wrapper {
                font-family: '{$font}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            }
            .content-wrapper, .right-side {
                {$bgCss}
            }
            .main-sidebar, .left-side {
                {$sbCss}
            }
            .btn-primary, .btn-primary:active, .btn-primary:focus {
                background-color: {$primary} !important;
                border-color: {$primary} !important;
            }
            .sidebar-menu > li.active > a {
                border-left-color: {$primary} !important;
            }
            .sidebar-menu > li.active > a i {
                color: {$primary} !important;
            }
        </style>
        ";
    }

    /**
     * Generate dynamic CSS overrides for Frontend Head
     */
    public static function renderFrontendCss(): string {
        $theme = self::getSettings();
        $accent = $theme['accent_color'] ?? '#0ea5e9';
        $radius = $theme['card_radius'] ?? '14px';
        $font   = $theme['font_family'] ?? 'Prompt';
        $bgStyle = $theme['bg_style'] ?? 'mesh_glow';

        $bgGlowDark = "radial-gradient(ellipse 80% 50% at 50% -20%, rgba(14, 165, 233, 0.12), transparent 75%),
                       radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.06), transparent 45%),
                       radial-gradient(circle at 0% 100%, rgba(16, 185, 129, 0.04), transparent 40%)";
        $bgGlowLight = "radial-gradient(ellipse 80% 50% at 50% -20%, rgba(2, 132, 199, 0.05), transparent 75%),
                        radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.035), transparent 45%),
                        radial-gradient(circle at 0% 100%, rgba(16, 185, 129, 0.025), transparent 40%)";

        if ($bgStyle === 'minimal_solid') {
            $bgGlowDark = "none";
            $bgGlowLight = "none";
        } elseif ($bgStyle === 'midnight_aurora') {
            $bgGlowDark = "radial-gradient(ellipse 90% 60% at 50% -20%, rgba(244, 63, 94, 0.14), transparent 75%),
                           radial-gradient(circle at 100% 0%, rgba(139, 92, 246, 0.09), transparent 50%),
                           radial-gradient(circle at 0% 100%, rgba(14, 165, 233, 0.06), transparent 50%)";
        } elseif ($bgStyle === 'warm_paper') {
            $bgGlowLight = "radial-gradient(circle at 50% 0%, rgba(245, 158, 11, 0.05), transparent 60%)";
        }

        return "
        <style id='theme-dynamic-frontend-css'>
            :root {
                --accent-brand: {$accent} !important;
                --radius-lg: {$radius} !important;
                --font-editorial-body: '{$font}', 'Prompt', system-ui, sans-serif !important;
            }
            [data-bs-theme='dark'] body {
                background-image: {$bgGlowDark} !important;
            }
            [data-bs-theme='light'] body {
                background-image: {$bgGlowLight} !important;
            }
        </style>
        ";
    }
}
