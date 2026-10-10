<?php
/**
 * Editorial Masthead Footer Component
 * 4-Column Publication Footer & Bootstrap Scripts
 */
?>

<footer class="editorial-footer">
    <div class="container-xl">
        <div class="row g-4 pb-4">
            <!-- Col 1: Brand & Publication Statement -->
            <div class="col-12 col-md-6 col-lg-4">
                <a href="index.php" class="brand-logo-wrap text-decoration-none mb-3 d-inline-flex">
                    <span class="brand-mark" style="width: 38px; height: 38px; font-size: 1.15rem;">TN</span>
                    <div class="brand-wordmark" style="font-size: 1.45rem;">
                        <span>TechThai</span><span style="color: var(--accent-brand);">.</span>
                    </div>
                </a>
                <p class="small text-muted mt-2 mb-3" style="line-height: 1.75; font-size: 0.84rem;">
                    พอร์ทัลข่าวสารเทคโนโลยีและนิตยสารดิจิทัล นำเสนอความเคลื่อนไหววงการไอที ปัญญาประดิษฐ์ (AI) เศรษฐกิจดิจิทัล และนวัตกรรมเปลี่ยนโลก คัดกรองจากสำนักข่าวชั้นนำระดับสากลและในประเทศอย่างเป็นกลาง
                </p>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-shield-check text-success"></i> เนื้อหาคัดสรรและอัปเดตแบบเรียลไทม์
                    </span>
                </div>
            </div>

            <!-- Col 2: News Sections -->
            <div class="col-6 col-md-3 col-lg-2">
                <h5>หมวดหมู่ข่าวสาร</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 small" style="font-size: 0.84rem;">
                    <?php 
                    $footGroups = array_slice($groups ?? [], 0, 6);
                    foreach ($footGroups as $fg): 
                        $cleanFootLabel = trim(preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $fg['label']));
                    ?>
                        <li>
                            <a href="index.php?group=<?php echo urlencode($fg['key']); ?>" class="text-muted text-decoration-none">
                                <?php echo htmlspecialchars($cleanFootLabel ?: $fg['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Col 3: Editorial Links -->
            <div class="col-6 col-md-3 col-lg-2">
                <h5>ข้อมูลกองบรรณาธิการ</h5>
                <ul class="list-unstyled d-flex flex-column gap-2 small" style="font-size: 0.84rem;">
                    <li><a href="about.php" class="text-muted text-decoration-none">เกี่ยวกับกองบรรณาธิการ</a></li>
                    <li><a href="contact.php" class="text-muted text-decoration-none">ส่งข่าวประชาสัมพันธ์</a></li>
                    <li><a href="privacy.php" class="text-muted text-decoration-none">นโยบายความเป็นส่วนตัว</a></li>
                    <li><a href="index.php?refresh=1" class="text-muted text-decoration-none">อัปเดตฟีดข่าวสด</a></li>
                    <li><a href="admin" class="text-warning text-decoration-none fw-medium"><i class="bi bi-shield-lock me-1"></i> เข้าสู่ระบบแอดมิน (Admin)</a></li>
                </ul>

            </div>

            <!-- Col 4: Contact & Masthead Desk -->
            <div class="col-12 col-md-6 col-lg-4">
                <h5>ติดต่อทีมงาน</h5>
                <p class="small text-muted mb-3" style="font-size: 0.84rem;">สามารถติดต่อทีมงานเพื่อลงข่าวประชาสัมพันธ์ หรือสอบถามข้อมูลเพิ่มเติม:</p>
                <div class="small d-flex flex-column gap-2 mb-3" style="font-size: 0.84rem;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-primary"></i>
                        <a href="mailto:pongku71@gmail.com" class="text-white fw-semibold text-decoration-none">pongku71@gmail.com</a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone text-success"></i>
                        <span class="text-white fw-semibold">083-904-0153</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt text-muted"></i>
                        <span class="text-muted">Bangkok, Thailand</span>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary py-1 px-3" onclick="toggleVarientTheme()" title="สลับโหมดมืด/สว่าง" style="border-radius: var(--radius-sm); font-size: 0.8rem;">
                        <i class="bi bi-circle-half me-1"></i> สลับธีมสี
                    </button>
                    <a href="contact.php" class="btn btn-sm text-white fw-medium py-1 px-3" style="background-color: var(--accent-brand); border-radius: var(--radius-sm); font-size: 0.8rem;">
                        ติดต่อเรา
                    </a>
                </div>
            </div>
        </div>

        <!-- Sub Footer -->
        <div class="pt-3 border-top d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 small text-muted" style="border-color: var(--border-subtle) !important; font-size: 0.8125rem;">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>TechThai News</strong> &bull; The Digital & Tech Chronicle.
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="privacy.php" class="text-muted text-decoration-none">Privacy Policy</a>
                <span class="opacity-25">&bull;</span>
                <a href="contact.php" class="text-muted text-decoration-none">Contact Us</a>
                <span class="opacity-25">&bull;</span>
                <a href="index.php" class="text-muted text-decoration-none">Back to Top</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
