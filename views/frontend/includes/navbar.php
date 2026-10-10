<?php
/**
 * Editorial Category Navigation Bar & Mobile Offcanvas
 * Clean typographic tabs without cheesy emojis
 */
?>

<div class="editorial-nav-bar">
    <div class="container-xl">
        <!-- Desktop Nav -->
        <nav class="d-none d-md-flex align-items-center justify-content-between overflow-x-auto" style="scrollbar-width: none;">
            <div class="d-flex align-items-center">
                <a href="index.php" class="nav-category-link <?php echo empty($_GET['group']) || $currentGroupKey === 'all' ? 'active' : ''; ?>">
                    หน้าแรก (รวมทุกหมวด)
                </a>

                <?php foreach (($groups ?? []) as $grp): 
                    $gKey = $grp['key'];
                    $gLabel = $grp['label'];
                    // Clean label without emojis if any
                    $cleanLabel = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $gLabel);
                    $cleanLabel = trim($cleanLabel);
                    $isActive = ($currentGroupKey === $gKey);
                ?>
                    <a href="index.php?group=<?php echo urlencode($gKey); ?>" class="nav-category-link <?php echo $isActive ? 'active' : ''; ?>">
                        <span><?php echo htmlspecialchars($cleanLabel ?: $gLabel); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Right Utility: Live Status Pill -->
            <div class="d-flex align-items-center ms-3 flex-shrink-0">
                <span class="d-inline-flex align-items-center gap-1 small text-muted" style="font-size: 0.78rem;">
                    <span class="badge rounded-circle p-1 bg-success" style="width: 6px; height: 6px;"></span>
                    <span>ฟีดข่าวสด</span>
                </span>
            </div>
        </nav>
    </div>
</div>

<!-- Mobile Offcanvas Drawer -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenuDrawer" aria-labelledby="mobileMenuDrawerLabel" style="background-color: var(--bg-surface); color: var(--text-heading);">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-subtle) !important; padding: 20px;">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-mark" style="width: 34px; height: 34px; font-size: 1rem;">TN</span>
            <div class="fw-bold fs-5">TechThai<span style="color: var(--accent-brand);">.</span></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
        <!-- Quick Actions -->
        <div class="p-3 border-bottom d-flex gap-2" style="border-color: var(--border-subtle) !important;">
            <button class="btn btn-sm w-50 fw-semibold text-white py-2" style="background-color: var(--accent-brand); border-radius: var(--radius-sm);" data-bs-toggle="modal" data-bs-target="#modalAddSource">
                + แนะนำฟีด
            </button>
            <button class="btn btn-sm btn-outline-secondary w-50 py-2" onclick="toggleVarientTheme()" style="border-radius: var(--radius-sm);">
                สลับธีมสี
            </button>
        </div>

        <!-- Category Links -->
        <div class="p-3">
            <div class="text-uppercase fw-bold text-muted small mb-2" style="font-size: 0.72rem; letter-spacing: 0.1em;">หมวดหมู่ข่าวสาร</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-transparent border-0 py-2 d-flex align-items-center justify-content-between <?php echo empty($_GET['group']) || $currentGroupKey === 'all' ? 'fw-bold' : ''; ?>" style="<?php echo empty($_GET['group']) || $currentGroupKey === 'all' ? 'color: var(--accent-brand);' : 'color: var(--text-heading);'; ?>">
                    <span>หน้าแรก (รวมทุกหมวด)</span>
                    <i class="bi bi-chevron-right small text-muted"></i>
                </a>
                <?php foreach (($groups ?? []) as $grp): 
                    $gKey = $grp['key'];
                    $gLabel = $grp['label'];
                    $cleanLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $gLabel));
                    $isActive = ($currentGroupKey === $gKey);
                ?>
                    <a href="index.php?group=<?php echo urlencode($gKey); ?>" class="list-group-item list-group-item-action bg-transparent border-0 py-2 d-flex align-items-center justify-content-between <?php echo $isActive ? 'fw-bold' : ''; ?>" style="<?php echo $isActive ? 'color: var(--accent-brand);' : 'color: var(--text-heading);'; ?>">
                        <span><?php echo htmlspecialchars($cleanLabel ?: $gLabel); ?></span>
                        <i class="bi bi-chevron-right small text-muted"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="p-3 border-top mt-auto" style="border-color: var(--border-subtle) !important;">
            <div class="d-flex flex-column gap-2 small text-muted">
                <a href="about.php" class="text-decoration-none text-muted">กองบรรณาธิการ</a>
                <a href="contact.php" class="text-decoration-none text-muted">ติดต่อเรา</a>
                <a href="privacy.php" class="text-decoration-none text-muted">นโยบายความเป็นส่วนตัว</a>
                <a href="admin" class="text-decoration-none fw-semibold text-warning"><i class="bi bi-shield-lock me-1"></i> เข้าสู่ระบบแอดมิน (Admin)</a>
                <span class="mt-2 opacity-50">&copy; <?php echo date('Y'); ?> TechThai News.</span>
            </div>
        </div>
    </div>
</div>
