# TechThaiNews_Backend

ระบบหลังบ้านและเว็บพอร์ทัลรวบรวมข่าวสารไอที เทคโนโลยี และดิจิทัล (Tech & Digital News Portal Backend)

## 📌 คุณสมบัติเด่น (Features)
- **สตรีมข่าวสารและ RSS Feed แบบเรียลไทม์**: ดึงและอัปเดตฟีดข่าวอัตโนมัติจากสำนักข่าวชั้นนำทั้งในและต่างประเทศ
- **โครงสร้างการแสดงผลแบบ Multi-Category Magazine**: หน้าแรกแยกข่าวสารเป็นหมวดหมู่ 4 คอลัมน์ (เช่น AI & นวัตกรรม, มือถือ, คอมพิวเตอร์ & เกม, ธุรกิจไอที, ความปลอดภัยไซเบอร์, สำนักข่าวไทย ฯลฯ)
- **หน้ารายละเอียดบทความ (News Detail)**: พร้อมฟีเจอร์ปรับขนาดตัวอักษร, บุ๊กมาร์ก (Bookmarks), แชร์โซเชียลมีเดีย, และแสดงเนื้อหาฉบับสมบูรณ์
- **ระบบธีม Dark / Light Mode**: รองรับการสลับธีมและปรับแต่งคู่สีได้ตามใจชอบ
- **เชื่อมต่อฐานข้อมูล Supabase & MySQL**: จัดการแหล่งข่าว (Feed Sources) และหมวดหมู่ข่าว (Feed Groups) ผ่านคลาวด์และโลคอล

## 🚀 สถาปัตยกรรม (Architecture)
- **Backend**: PHP (MVC Architecture / Vanilla PHP)
- **Database**: Supabase REST API & MySQL
- **Frontend**: Bootstrap 5.3, Bootstrap Icons, Vanilla CSS
- **Cache**: Fast JSON File Cache สำหรับลดภาระการดึง RSS ซ้ำซ้อน

## 🛠️ การติดตั้งและใช้งาน
1. ติดตั้งบนเว็บเซิร์ฟเวอร์ Apache / Nginx หรือจำลองผ่าน MAMP / XAMPP
2. คอนฟิกค่าการเชื่อมต่อที่ `home/config.php`
3. เปิดใช้งานผ่านเบราว์เซอร์ที่ `http://localhost/PHP-source-nearby/`
