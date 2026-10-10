<?php
/**
 * Footer Component
 * Links, compliance information, and contact details
 */
?>

<style>
    .site-footer {
        background: #0f172a;
        color: #94a3b8;
        border-top: 1px solid #1e293b;
        padding: 50px 24px 30px 24px;
        font-size: 0.9rem;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-top-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-col h4 {
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .footer-col p {
        line-height: 1.7;
        font-size: 0.88rem;
    }

    .footer-links-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .footer-links-list a {
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
    }

    .footer-links-list a:hover {
        color: #60a5fa;
        transform: translateX(4px);
    }

    .footer-bottom-row {
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.82rem;
    }

    @media (max-width: 768px) {
        .footer-top-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }
        .footer-bottom-row {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-top-grid">
            <div class="footer-col">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <span class="brand-badge">TECHTHAI</span>
                    <strong style="color: #ffffff; font-size: 1.1rem;"><?php echo htmlspecialchars(APP_NAME); ?></strong>
                </div>
                <p>
                    ศูนย์รวมข้อมูลและระบบบริหารจัดการฟีดข่าวสารอย่างเป็นทางการ ขับเคลื่อนด้วยฐานข้อมูล Supabase PostgreSQL ตามมาตรฐานความโปร่งใสของ Google Play News Policy และ Schema.org
                </p>
                <div style="margin-top: 14px; font-size: 0.85rem; color: #cbd5e1;">
                    <div><i class="material-icons" style="font-size: 16px; vertical-align: middle; color: #60a5fa;">email</i> อีเมล: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color:#60a5fa;"><?php echo CONTACT_EMAIL; ?></a></div>
                    <div style="margin-top: 4px;"><i class="material-icons" style="font-size: 16px; vertical-align: middle; color: #60a5fa;">phone</i> โทรศัพท์: <a href="tel:<?php echo CONTACT_PHONE; ?>" style="color:#60a5fa;"><?php echo CONTACT_PHONE_DISPLAY; ?></a></div>
                </div>
            </div>

            <div class="footer-col">
                <h4>เมนูและระบบจัดการ</h4>
                <ul class="footer-links-list">
                    <li><a href="index.php"><i class="material-icons" style="font-size: 14px;">home</i> หน้าแรก</a></li>
                    <li><a href="#add-section" onclick="switchAddTab('source');"><i class="material-icons" style="font-size: 14px;">add_circle</i> เพิ่มแหล่งข่าว RSS</a></li>
                    <li><a href="#add-section" onclick="switchAddTab('group');"><i class="material-icons" style="font-size: 14px;">create_new_folder</i> เพิ่มกลุ่มฟีดข่าว</a></li>
                    <li><a href="#feeds-directory"><i class="material-icons" style="font-size: 14px;">rss_feed</i> สำรวจฟีดข่าวทั้งหมด</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>นโยบายและข้อมูลติดต่อ</h4>
                <ul class="footer-links-list">
                    <li><a href="contact.php"><i class="material-icons" style="font-size: 14px;">contact_mail</i> ติดต่อเรา (Contact Us)</a></li>
                    <li><a href="about.php"><i class="material-icons" style="font-size: 14px;">info</i> กองบรรณาธิการและจรรยาบรรณ</a></li>
                    <li><a href="privacy_policy.html"><i class="material-icons" style="font-size: 14px;">policy</i> นโยบายความเป็นส่วนตัว</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom-row">
            <div>
                &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(APP_NAME); ?>. All Rights Reserved. Google Play News Policy Verified.
            </div>
            <div>
                Powered by Supabase Cloud &amp; REST Engine
            </div>
        </div>
    </div>
</footer>

</body>
</html>
