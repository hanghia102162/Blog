<?php
// =============================================
// backend/index.php — Entry Point duy nhất của PHP Backend
//
// Mọi request đều đi qua đây trước.
// Đọc URI → map sang file api/*.php tương ứng → require file đó.
//
// Cấu trúc URL (sau khi .htaccess rewrite):
//   /api/posts           → api/posts.php
//   /api/categories      → api/categories.php
//   /api/comments        → api/comments.php
//   /api/auth            → api/auth.php
//   /api/search          → api/search.php
//   /api/upload          → api/upload.php
//   /api/author_requests → api/author_requests.php
// =============================================

// ─────────────────────────────────────────
// CORS — Phải set trước mọi thứ khác
// ─────────────────────────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ─────────────────────────────────────────
// LẤY URI VÀ CHUẨN HÓA
// Ví dụ: /Blog/blog/backend/api/posts → posts
// ─────────────────────────────────────────
$requestUri  = $_SERVER['REQUEST_URI'];
$basePath    = '/Blog-platform/backend/api/'; // Khớp với laragon/www/Blog-platform/

// Lấy phần sau /api/
$path = parse_url($requestUri, PHP_URL_PATH);        // Bỏ query string
$path = str_replace($basePath, '', $path);           // Bỏ prefix base path
$path = trim($path, '/');                            // Bỏ dấu / đầu cuối
$resource = strtolower(explode('/', $path)[0]);      // Lấy phần đầu tiên: posts, auth...

// ─────────────────────────────────────────
// MAP URI → FILE (ROUTE MAP)
// ─────────────────────────────────────────
$routes = [
    'posts'           => __DIR__ . '/api/posts.php',
    'categories'      => __DIR__ . '/api/categories.php',
    'comments'        => __DIR__ . '/api/comments.php',
    'auth'            => __DIR__ . '/api/auth.php',
    'search'          => __DIR__ . '/api/search.php',
    'upload'          => __DIR__ . '/api/upload.php',
    'author_requests' => __DIR__ . '/api/author_requests.php',
];

// ─────────────────────────────────────────
// DISPATCH
// ─────────────────────────────────────────
if (array_key_exists($resource, $routes)) {
    require $routes[$resource];
} else {
    http_response_code(404);
    echo json_encode([
        'success'   => false,
        'message'   => "Endpoint '/$resource' không tồn tại",
        'available' => array_keys($routes),
    ]);
}