<?php
    ob_start();
    session_start();
    if (file_exists(__DIR__ . '/includes/config.php')) {
        @include_once(__DIR__ . '/includes/config.php');
    }
    if (file_exists(__DIR__ . '/includes/strings.php')) {
        @include_once(__DIR__ . '/includes/strings.php');
    }
    $app_name = $app_name ?? 'TechThaiNews (IT News App)';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกี่ยวกับเราและจรรยาบรรณกองบรรณาธิการ (Editorial Guidelines) | <?php echo htmlspecialchars($app_name ?? 'Android News App'); ?></title>
    <meta name="description" content="มาตรฐานจรรยาบรรณวิชาชีพสื่อมวลชน นโยบายการตรวจสอบข้อเท็จจริง และข้อมูลกองบรรณาธิการ">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        :root {
            --primary: #1a73e8;
            --primary-dark: #1557b0;
            --secondary: #0f172a;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --border: #e2e8f0;
            --bg-page: #f8fafc;
            --card-bg: #ffffff;
            --radius: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Prompt', 'Inter', sans-serif; background-color: var(--bg-page); color: var(--text-main); line-height: 1.8; }
        a { color: inherit; text-decoration: none; transition: 0.2s; }
        .container { max-width: 960px; margin: 0 auto; padding: 0 20px; }

        .header { background: #fff; border-bottom: 1px solid var(--border); padding: 16px 0; }
        .header-wrap { display: flex; justify-content: space-between; align-items: center; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 20px; color: var(--secondary); }
        .brand i { color: var(--primary); font-size: 28px; }
        .nav-links { display: flex; gap: 20px; font-size: 15px; font-weight: 500; }
        .nav-links a:hover { color: var(--primary); }

        .page-hero { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; padding: 50px 0; text-align: center; margin-bottom: 36px; border-radius: 0 0 20px 20px; }
        .page-hero h1 { font-size: 30px; font-weight: 700; margin-bottom: 10px; }
        .page-hero p { color: #94a3b8; font-size: 15px; max-width: 600px; margin: 0 auto; }

        .content-card { background: #fff; border-radius: var(--radius); padding: 36px; border: 1px solid var(--border); margin-bottom: 30px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }
        .section-title { font-size: 20px; font-weight: 700; color: var(--secondary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #f1f5f9; padding-bottom: 8px; }
        .section-title i { color: var(--primary); }

        .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
        .team-card { background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 20px; text-align: center; }
        .team-avatar { width: 64px; height: 64px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto; font-size: 28px; }
        .team-name { font-size: 15px; font-weight: 600; color: var(--secondary); }
        .team-role { font-size: 12px; color: var(--text-sub); }

        .policy-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 5px solid #16a34a; border-radius: 8px; padding: 18px; margin: 18px 0; font-size: 14px; color: #166534; }

        @media (max-width: 768px) {
            .team-grid { grid-template-columns: 1fr; }
            .content-card { padding: 22px; }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="container header-wrap">
            <a href="index.php" class="brand">
                <i class="material-icons">newspaper</i>
                <span><?php echo htmlspecialchars($app_name ?? 'Android News App'); ?></span>
            </a>
            <nav class="nav-links">
                <a href="index.php">หน้าแรก</a>
                <a href="about.php" style="color:var(--primary);">กองบรรณาธิการ</a>
                <a href="contact.php">ติดต่อเรา</a>
                <a href="privacy.php">นโยบายความเป็นส่วนตัว</a>
            </nav>
        </div>
    </header>

    <div class="page-hero">
        <div class="container">
            <h1>จรรยาบรรณและมาตรฐานกองบรรณาธิการ</h1>
            <p>Editorial Guidelines, Fact-Checking Standards & Ownership Transparency</p>
        </div>
    </div>

    <main class="container">
        <!-- Mission & Editorial Standards -->
        <section class="content-card">
            <h2 class="section-title"><i class="material-icons">policy</i> พันธกิจและมาตรฐานวิชาชีพข่าว</h2>
            <p>
                <strong><?php echo htmlspecialchars($app_name ?? 'Android News App'); ?></strong> มุ่งมั่นผลิตและเผยแพร่ข้อมูลข่าวสารที่มีคุณภาพ เที่ยงตรง และเป็นประโยชน์ต่อสาธารณชน โดยยึดมั่นในหลักการทำงานดังต่อไปนี้:
            </p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>ความถูกต้องแม่นยำ (Accuracy):</strong> ทุกข้อมูลต้องผ่านการคัดกรอง ตรวจสอบแหล่งที่มา และยืนยันข้อเท็จจริงก่อนนำเสนอ</li>
                <li><strong>ความเป็นกลางและความเป็นอิสระ (Impartiality):</strong> รายงานสถานการณ์รอบด้านอย่างตรงไปตรงมา ปราศจากอคติหรือผลประโยชน์ทับซ้อน</li>
                <li><strong>ความรับผิดชอบและความโปร่งใส (Accountability):</strong> ระบุชื่อผู้เขียนหรือสำนักข่าวต้นทางอย่างชัดเจนในทุกบทความ</li>
            </ul>

            <div class="policy-box">
                <strong>การปฏิบัติตามนโยบาย Google Play News Policy:</strong><br>
                แอปพลิเคชันและเว็บไซต์ข่าวแห่งนี้ปฏิบัติตามข้อกำหนดสากลว่าด้วยความโปร่งใสของผู้เผยแพร่ข่าวสาร (News Transparency), การแสดงข้อมูลติดต่อที่ตรวจสอบได้ และการระบุแหล่งที่มาของบทความอย่างเปิดเผย
            </div>
        </section>

        <!-- Fact Checking & Corrections -->
        <section class="content-card" id="corrections">
            <h2 class="section-title"><i class="material-icons">fact_check</i> นโยบายตรวจสอบข้อเท็จจริงและการแก้ไขข้อผิดพลาด</h2>
            <p>
                <strong>นโยบายตรวจสอบข้อเท็จจริง (Fact-Checking Policy):</strong><br>
                ทีมข่าวของเราทำการตรวจสอบข้อมูลจากแหล่งข่าวปฐมภูมิ (Primary Sources), เอกสารทางราชการ, แถลงการณ์อย่างเป็นทางการ และผู้เชี่ยวชาญเฉพาะทางก่อนเผยแพร่ทุกครั้ง
            </p>
            <br>
            <p>
                <strong>นโยบายการแก้ไขข้อผิดพลาด (Corrections Policy):</strong><br>
                หากพบว่ามีข้อมูลใดในบทความที่คลาดเคลื่อนหรือไม่ถูกต้อง กองบรรณาธิการจะดำเนินการแก้ไขและระบุหมายเหตุการแก้ไข (Correction Note) ไว้ที่ท้ายบทความทันที ทั้งนี้ผู้อ่านสามารถแจ้งข้อผิดพลาดได้ผ่าน <a href="contact.php" style="color:var(--primary); font-weight:600;">หน้าติดต่อกองบรรณาธิการ (Contact Us)</a>
            </p>
        </section>

        <!-- Editorial Team -->
        <section class="content-card">
            <h2 class="section-title"><i class="material-icons">groups</i> คณะทำงานและกองบรรณาธิการ (Editorial Board)</h2>
            <p>โครงสร้างทีมงานผู้ดูแลและรับผิดชอบการเผยแพร่เนื้อหา:</p>
            
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar"><i class="material-icons">person</i></div>
                    <div class="team-name">หัวหน้ากองบรรณาธิการ</div>
                    <div class="team-role">Chief Editor / Publisher Lead</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar"><i class="material-icons">edit</i></div>
                    <div class="team-name">ทีมผู้สื่อข่าวและนักเขียน</div>
                    <div class="team-role">Senior News Journalists & Writers</div>
                </div>
                <div class="team-card">
                    <div class="team-avatar"><i class="material-icons">verified</i></div>
                    <div class="team-name">ทีมตรวจสอบข้อเท็จจริง</div>
                    <div class="team-role">Fact-Checking & Quality Assurance Desk</div>
                </div>
            </div>
        </section>

        <!-- Contact CTA -->
        <div style="text-align: center; margin: 40px 0;">
            <p style="font-size: 15px; color: var(--text-sub); margin-bottom: 12px;">ต้องการสอบถามข้อมูลข่าว ส่งข่าวประชาสัมพันธ์ หรือติดต่อนักพัฒนา?</p>
            <a href="contact.php" style="display:inline-flex; align-items:center; gap:8px; background:var(--primary); color:#fff; padding:12px 24px; border-radius:8px; font-weight:600;">
                <i class="material-icons">contact_mail</i> ไปที่หน้าข้อมูลติดต่อผู้เผยแพร่ (Contact Us)
            </a>
        </div>
    </main>

    <footer style="background:#0f172a; color:#94a3b8; padding:30px 0; text-align:center; font-size:13px; margin-top:50px;">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($app_name ?? 'Android News App'); ?>. All Rights Reserved.</p>
            <p style="margin-top:6px;">
                <a href="index.php" style="color:#94a3b8;">หน้าแรก</a> &nbsp;|&nbsp; 
                <a href="about.php" style="color:#94a3b8;">กองบรรณาธิการ</a> &nbsp;|&nbsp; 
                <a href="contact.php" style="color:#94a3b8;">ติดต่อเรา</a> &nbsp;|&nbsp; 
                <a href="privacy.php" style="color:#94a3b8;">นโยบายความเป็นส่วนตัว</a>
            </p>
        </div>
    </footer>

</body>
</html>
