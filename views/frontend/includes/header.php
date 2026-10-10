<?php
/**
 * Editorial Masthead Header Component
 * Brand mark, search bar, and clean editorial actions
 */
?>

<header class="editorial-masthead">
    <div class="container-xl">
        <div class="row align-items-center g-3">
            <!-- Brand Column -->
            <div class="col-12 col-md-5 d-flex align-items-center justify-content-between justify-content-md-start">
                <a href="index.php" class="brand-logo-wrap text-decoration-none">
                    <span class="brand-mark">
                        TN
                    </span>
                    <div>
                        <div class="brand-wordmark">
                            <span>TechThai</span><span style="color: var(--accent-brand);">.</span>
                        </div>
                        <div class="brand-tagline">THE DIGITAL & TECH CHRONICLE</div>
                    </div>
                </a>

                <!-- Mobile menu toggle button -->
                <button class="btn btn-outline-secondary d-md-none border-0 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuDrawer" aria-label="Open mobile menu">
                    <i class="bi bi-list fs-3"></i>
                </button>
            </div>

            <!-- Search Box Column -->
            <div class="col-12 col-md-4">
                <form action="index.php" method="get" class="position-relative" onsubmit="return handleClientSearch(event)">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--border-subtle); color: var(--text-muted); border-radius: 999px 0 0 999px; padding-left: 16px;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="mainHeaderSearchInput" class="form-control border-start-0" style="background-color: var(--input-bg); border-color: var(--border-subtle); color: var(--text-heading); border-radius: 0 999px 999px 0; font-size: 0.88rem; padding-right: 16px;" placeholder="ค้นหาบทความ, เทคโนโลยี, AI..." aria-label="Search">
                        <button class="btn btn-outline-secondary d-none" type="submit">ค้นหา</button>
                    </div>
                </form>
            </div>

            <!-- Actions Column: Contributor & Settings -->
            <div class="col-12 col-md-3 text-md-end d-flex align-items-center justify-content-md-end gap-2">
                <button type="button" class="btn btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold text-white" style="background-color: var(--accent-brand); border: none; border-radius: 999px; font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalAddSource">
                    <i class="bi bi-plus-lg"></i> แนะนำฟีดข่าว
                </button>
                <a href="index.php?refresh=1" class="btn btn-sm btn-outline-secondary border-0 d-inline-flex align-items-center gap-1 p-2" title="อัปเดตข้อมูลสด">
                    <i class="bi bi-arrow-clockwise fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</header>
