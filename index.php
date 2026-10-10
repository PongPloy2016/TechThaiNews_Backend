<?php
/**
 * TechThaiNews & Nearby System Router
 * - Routes /admin, /admin/*, /user/login, /cms/* -> CodeIgniter Admin Panel
 * - Routes / or /index.php -> TechThaiNews Editorial Portal
 */

$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$queryString = $_SERVER['QUERY_STRING'] ?? '';

// Check if request is targeting CodeIgniter Backend Admin or system routes
$isCiRoute = preg_match('#/(admin|user|cms|api|clientRequest|ajax)(/.*)?$#i', $requestUri) 
          || preg_match('#^(admin|user|cms|api|clientRequest|ajax)#i', $queryString);

try {
    if ($isCiRoute) {
        require_once __DIR__ . '/index_ci_backup.php';
        exit;
    }

    // Default: TechThaiNews Editorial Magazine & Feed Portal
    require_once __DIR__ . '/home/index.php';
} catch (\Throwable $e) {
    if ($isCiRoute) {
        // Backend Admin routes MUST NOT silently fallback to frontend magazine page!
        http_response_code(500);
        $errMsg = htmlspecialchars($e->getMessage());
        $errTrace = htmlspecialchars($e->getTraceAsString());
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>TechThaiNews Admin Error</title>'
           . '<style>body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:#0f172a;color:#f8fafc;padding:40px;margin:0;display:flex;justify-content:center;align-items:center;min-height:80vh;}'
           . '.card{background:#1e293b;border:1px solid #334155;border-radius:18px;padding:32px 36px;max-width:760px;width:100%;box-shadow:0 14px 34px rgba(0,0,0,0.5);}'
           . 'h2{color:#ef4444;margin-top:0;font-size:22px;display:flex;align-items:center;gap:10px;}'
           . 'p{color:#cbd5e1;line-height:1.6;font-size:14.5px;}'
           . '.msg-box{background:#0b1120;border-left:4px solid #ef4444;padding:14px 18px;border-radius:6px;font-family:monospace;font-size:13.5px;color:#fca5a5;word-break:break-word;margin:18px 0;}'
           . 'pre{background:#0b1120;padding:16px;border-radius:8px;overflow-x:auto;color:#94a3b8;font-size:12px;max-height:280px;}'
           . 'a{color:#38bdf8;text-decoration:none;font-weight:600;}a:hover{text-decoration:underline;}'
           . '.btn{display:inline-block;padding:10px 18px;border-radius:10px;background:#3b82f6;color:#ffffff;text-decoration:none;font-weight:700;margin-right:10px;font-size:13px;}'
           . '</style></head><body>'
           . '<div class="card">'
           . '<h2>⚠️ ระบบผู้ดูแลระบบ (Admin) เกิดข้อผิดพลาด</h2>'
           . '<p>เกิดข้อผิดพลาดในการโหลดระบบจัดการ CodeIgniter หรือฐานข้อมูล MySQL:</p>'
           . '<div class="msg-box">' . $errMsg . '</div>'
           . (isset($_GET['debug']) ? '<h4>Stack Trace:</h4><pre>' . $errTrace . '</pre>' : '<p><a href="' . htmlspecialchars(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH)) . '?debug=1">🔍 คลิกที่นี่เพื่อเปิดโหมด Debug แบบละเอียด</a></p>')
           . '<div style="margin-top:24px;">'
           . '<a href="' . htmlspecialchars(dirname($_SERVER['SCRIPT_NAME'] ?? '')) . '/admin" class="btn">ไปยังแดชบอร์ด Admin</a>'
           . '<a href="' . htmlspecialchars(dirname($_SERVER['SCRIPT_NAME'] ?? '')) . '/" style="color:#94a3b8;font-size:13px;">กลับหน้าหลัก TechThaiNews</a>'
           . '</div>'
           . '</div></body></html>';
        exit;
    }

    if (isset($_GET['debug'])) {
        echo '<h3>TechThaiNews Error Diagnostic:</h3><pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
    } else {
        // Fallback to frontend view with bundled data
        $page_title = 'TechThaiNews • The Digital Chronicle';
        @require_once __DIR__ . '/views/frontend/home.php';
    }
}
