    <?php
        $is_in_contact_sub = (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/contact/') !== false);
        $ft_home = $is_in_contact_sub ? '../index.php' : 'index.php';
        $ft_contact = $is_in_contact_sub ? 'index.php' : 'contact.php';
        $ft_about = $is_in_contact_sub ? '../about.php' : 'about.php';
        $ft_privacy = $is_in_contact_sub ? '../privacy_policy.html' : 'privacy_policy.html';
    ?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> <?php echo CONTACT_PUBLISHER_NAME; ?>. All rights reserved.</p>
        <p style="margin-top: 8px;">
            <a href="<?php echo $ft_home; ?>">หน้าแรก (Home)</a> | 
            <a href="<?php echo $ft_home; ?>#add-section">เพิ่มฟีดข่าว</a> | 
            <a href="<?php echo $ft_contact; ?>">ติดต่อเรา</a> | 
            <a href="<?php echo $ft_about; ?>">กองบรรณาธิการ</a> | 
            <a href="<?php echo $ft_privacy; ?>">นโยบายความเป็นส่วนตัว</a>
        </p>
    </footer>

</body>
</html>
