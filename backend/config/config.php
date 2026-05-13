<?php
/**
 * backend/config/config.php
 * ─────────────────────────────────────────────────────────
 * Hằng số toàn cục, CORS headers, và cấu hình chung.
 *
 * Load file này đầu tiên ở mọi entry point (index.php / api/*.php).
 */

// ─── Môi trường ──────────────────────────────────────────
define('APP_ENV', 'development'); // 'development' | 'production'
define('APP_NAME', 'Blog Platform');
define('APP_URL', 'http://localhost/Blog-platform');

// ─── JWT ─────────────────────────────────────────────────
// JWT_SECRET và JWT_EXPIRE được định nghĩa trong config/jwt.php
// (dùng firebase/php-jwt). Không define lại ở đây để tránh lỗi.

// ─── Upload ──────────────────────────────────────────────
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// ─── Phân trang ──────────────────────────────────────────
define('POSTS_PER_PAGE', 10);

// ─── CORS Headers ────────────────────────────────────────
// Cho phép frontend Vue (Vite dev server) gọi API
$allowedOrigins = [
    'http://localhost:5173',   // Vite dev server mặc định
    'http://localhost:3000',
    APP_URL,
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$origin}");
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Xử lý preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ─── Error reporting ─────────────────────────────────────
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
