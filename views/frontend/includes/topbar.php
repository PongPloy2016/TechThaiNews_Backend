<?php
/**
 * Editorial Topbar Component
 * Edition info, date, editorial navigation, and theme switch
 */

$thaiDayNames = [
    'Sunday'    => 'วันอาทิตย์',
    'Monday'    => 'วันจันทร์',
    'Tuesday'   => 'วันอังคาร',
    'Wednesday' => 'วันพุธ',
    'Thursday'  => 'วันพฤหัสบดี',
    'Friday'    => 'วันศุกร์',
    'Saturday'  => 'วันเสาร์'
];
$thaiMonthsFull = [
    1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
    7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
];

$dayName = $thaiDayNames[date('l')] ?? date('l');
$monthName = $thaiMonthsFull[(int)date('n')] ?? date('F');
$editorialDate = $dayName . 'ที่ ' . date('j') . ' ' . $monthName . ' ' . (date('Y') + 543);
?>

<div class="editorial-topbar d-none d-md-block">
    <div class="container-xl">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Left Info -->
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center gap-1 text-muted">
                    <i class="bi bi-geo-alt"></i> Bangkok, TH
                </span>
                <span class="opacity-25">&bull;</span>
                <span><?php echo htmlspecialchars($editorialDate); ?></span>
                <span class="opacity-25">&bull;</span>
                <span class="badge rounded-1 font-monospace" style="background: rgba(14, 165, 233, 0.12); color: var(--accent-brand); font-size: 0.7rem; font-weight: 600;">
                    LIVE DISPATCH
                </span>
            </div>

            <!-- Right Links & Theme Switcher -->
            <div class="d-flex align-items-center gap-3">
                <a href="about.php" class="text-decoration-none">กองบรรณาธิการ</a>
                <span class="opacity-25">&bull;</span>
                <span class="opacity-25">&bull;</span>
                <a href="privacy.php" class="text-decoration-none">นโยบายความเป็นส่วนตัว</a>
                <span class="opacity-25">&bull;</span>
                <a href="admin" class="text-decoration-none d-inline-flex align-items-center gap-1" title="เข้าสู่ระบบผู้ดูแลระบบ (Admin Panel)">
                    <i class="bi bi-shield-lock"></i> จัดการระบบ (Admin)
                </a>
                <span class="opacity-25">|</span>


                <!-- Theme Toggle Button -->
                <button type="button" class="btn btn-sm text-reset p-0 border-0 text-decoration-none d-inline-flex align-items-center gap-1" id="themeToggleBtn" aria-label="Toggle theme" onclick="toggleVarientTheme()" style="font-size: 0.8125rem;">
                    <i class="bi bi-moon-stars" id="themeIcon"></i>
                    <span id="themeLabel">Dark</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateThemeUI(theme) {
        const icon = document.getElementById('themeIcon');
        const label = document.getElementById('themeLabel');
        if (!icon) return;
        if (theme === 'dark') {
            icon.className = 'bi bi-moon-stars';
            if (label) label.textContent = 'Dark';
        } else {
            icon.className = 'bi bi-sun text-warning';
            if (label) label.textContent = 'Light';
        }
    }

    function toggleVarientTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
        const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', nextTheme);
        localStorage.setItem('varient_theme', nextTheme);
        updateThemeUI(nextTheme);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('varient_theme') || 'dark';
        updateThemeUI(savedTheme);
    });
</script>
