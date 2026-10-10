<?php
/**
 * Contact Page Controller & View Assembler
 * Modular Architecture with Supabase Integration
 */

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Load Configurations & Services
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/SupabaseClient.php';

// 2. Fetch Publisher Info from Database (if available)
$publisher_content = '';
$privacy_link = file_exists(__DIR__ . '/../privacy_policy.html') 
    ? '../privacy_policy.html' 
    : (file_exists(__DIR__ . '/../privacy.php') ? '../privacy.php' : '#');

if (file_exists(__DIR__ . '/../includes/config.php')) {
    try {
        @include_once(__DIR__ . '/../includes/config.php');
        if (isset($connect) && $connect instanceof \mysqli && !$connect->connect_error) {
            $checkTable = @$connect->query("SHOW TABLES LIKE 'tbl_settings'");
            if ($checkTable && $checkTable->num_rows > 0) {
                $res = @$connect->query("SELECT publisher_info FROM tbl_settings ORDER BY id DESC LIMIT 1");
                if ($res && $row = $res->fetch_assoc()) {
                    $publisher_content = trim($row['publisher_info'] ?? '');
                }
            }
        }
    } catch (\Throwable $e) {
        $publisher_content = '';
    }
}

// 3. Process Form Submission (Send Message to Supabase)
$send_status = null;
$status_msg  = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['btnSendContact'])) {
    $result = SupabaseClient::sendFeedback(
        $_POST['name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['subject'] ?? '',
        $_POST['message'] ?? ''
    );

    $send_status = $result['success'] ? 'success' : 'error';
    $status_msg  = $result['message'];

    // Clear POST data on success so form inputs are reset
    if ($result['success']) {
        $_POST = [];
    }
}

// 4. Render Modular Components
include __DIR__ . '/components/header.php';
include __DIR__ . '/components/hero.php';
?>

    <main class="main-wrapper">
        <div class="grid">
            <?php include __DIR__ . '/components/info_card.php'; ?>
            <?php include __DIR__ . '/components/form_card.php'; ?>
        </div>

        <?php include __DIR__ . '/components/policy_card.php'; ?>
    </main>

<?php 
include __DIR__ . '/components/footer.php'; 
?>
