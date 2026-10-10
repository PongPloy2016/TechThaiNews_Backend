<?php
/**
 * Web Footer Component
 * 4-column modern news portal footer
 */
?>

<style>
    .web-portal-footer {
        background: #080c14;
        border-top: 1px solid var(--web-border);
        color: var(--web-text-muted);
        padding: 60px 20px 30px 20px;
        font-size: 0.88rem;
    }

    .footer-inner-max {
        max-width: var(--web-max-w);
        margin: 0 auto;
    }

    .footer-columns-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr 1.2fr;
        gap: 40px;
        margin-bottom: 50px;
    }

    .footer-col-title {
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 18px;
        letter-spacing: -0.2px;
    }

    .footer-nav-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-nav-list a {
        color: var(--web-text-muted);
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .footer-nav-list a:hover {
        color: var(--web-primary-light);
        transform: translateX(3px);
    }

    .footer-bottom-bar {
        border-top: 1px solid var(--web-border-light);
        padding-top: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.82rem;
    }

    @media (max-width: 900px) {
        .footer-columns-grid {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 600px) {
        .footer-columns-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<footer class="web-portal-footer">
    <div class="footer-inner-max">
        <div class="footer-columns-grid">
            <!-- Col 1: About -->
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <span style="background: #2563eb; color: #fff; font-size: 0.75rem; font-weight: 800; padding: 4px 8px; border-radius: 6px;">PORTAL</span>
                    <strong style="color: #ffffff; font-size: 1.2rem;">TechThaiNews</strong>
                </div>
                <p style="line-height: 1.7; margin-bottom: 16px;">
                    ศูนย์รวมข้อมูลและระบบจัดการข่าวสารไอที ธุรกิจดิจิทัล และนวัตกรรมเทคโนโลยี รวบรวมฟีดข่าวจากสำนักข่าวชั้นนำของประเทศไทยและต่างประเทศ ขับเคลื่อนด้วยฐานข้อมูล Supabase PostgreSQL
                </p>
                <div style="font-size: 0.82rem; color: #34d399; display: flex; align-items: center; gap: 6px;">
                    <i class="material-icons" style="font-size: 16px;">verified</i>
                    <span>Google Play News and Magazines Policy Compliant</span>
                </div>
            </div>

            <!-- Col 2: Categories -->
            <div>
                <h4 class="footer-col-title">หมวดหมู่ข่าวสาร</h4>
                <ul class="footer-nav-list">
                    <li><a href="index.php?group=tech-business">เทคโนโลยีและธุรกิจดิจิทัล</a></li>
                    <li><a href="index.php?group=international">ข่าวไอทีต่างประเทศ</a></li>
                    <li><a href="index.php?group=ai-innovation">ปัญญาประดิษฐ์และ AI</a></li>
                    <li><a href="index.php?group=mobile">ข่าวมือถือและอุปกรณ์</a></li>
                    <li><a href="index.php?group=ev-vehicles">ยานยนต์ไฟฟ้า EV</a></li>
                </ul>
            </div>

            <!-- Col 3: Management -->
            <div>
                <h4 class="footer-col-title">ระบบจัดการฟีด (Supabase)</h4>
                <ul class="footer-nav-list">
                    <li><a href="javascript:void(0)" onclick="openAddFeedModal(); switchModalAddTab('source');"><i class="material-icons" style="font-size: 14px;">add_circle</i> เพิ่มแหล่งข่าว RSS</a></li>
                    <li><a href="javascript:void(0)" onclick="openAddFeedModal(); switchModalAddTab('group');"><i class="material-icons" style="font-size: 14px;">create_new_folder</i> เพิ่มกลุ่มฟีดใหม่</a></li>
                    <li><a href="index.php?refresh=1"><i class="material-icons" style="font-size: 14px;">refresh</i> ซิงค์ฟีดสดทันที</a></li>
                    <li><a href="add_feed.php"><i class="material-icons" style="font-size: 14px;">input</i> ทางลัดหน้าเพิ่มฟีด</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact & Policy -->
            <div>
                <h4 class="footer-col-title">ข้อมูลติดต่อและนโยบาย</h4>
                <div style="line-height: 1.8; margin-bottom: 12px; font-size: 0.84rem;">
                    <div><i class="material-icons" style="font-size: 15px; vertical-align: middle; color: #60a5fa;">email</i> <?php echo CONTACT_EMAIL; ?></div>
                    <div><i class="material-icons" style="font-size: 15px; vertical-align: middle; color: #60a5fa;">phone</i> <?php echo CONTACT_PHONE_DISPLAY; ?></div>
                </div>
                <ul class="footer-nav-list">
                    <li><a href="contact.php"><i class="material-icons" style="font-size: 14px;">contact_mail</i> ติดต่อเรา (Contact Us)</a></li>
                    <li><a href="about.php"><i class="material-icons" style="font-size: 14px;">info</i> กองบรรณาธิการและจรรยาบรรณ</a></li>
                    <li><a href="privacy.php"><i class="material-icons" style="font-size: 14px;">policy</i> นโยบายความเป็นส่วนตัว</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <div>
                &copy; <?php echo date('Y'); ?> TechThaiNews. All rights reserved. Powered by Supabase REST API &amp; RSS Engine.
            </div>
            <div>
                <a href="privacy.php" style="color: var(--web-text-muted); margin-right: 14px;">Privacy Policy</a>
                <a href="about.php" style="color: var(--web-text-muted); margin-right: 14px;">Editorial Guidelines</a>
                <a href="contact.php" style="color: var(--web-text-muted);">Contact Editorial Team</a>
            </div>
        </div>
    </div>
</footer>
