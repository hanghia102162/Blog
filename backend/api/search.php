<?php
// =============================================
// api/search.php — Endpoint tìm kiếm toàn trang
// GET /api/search.php?q=...           → Tìm kiếm bài viết       [public]
// GET /api/search.php?q=...&page=2    → Trang kết quả tiếp theo  [public]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ GET']);
    exit;
}

// Lấy từ khóa tìm kiếm
$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Từ khóa tối thiểu 2 ký tự']);
    exit;
}

$page    = max(1, (int)($_GET['page']     ?? 1));
$perPage = min(20, (int)($_GET['per_page'] ?? 10)); // Tối đa 20 kết quả mỗi trang

$post   = new Post($pdo);
$result = $post->search($q, $page, $perPage);

echo json_encode([
    'success'  => true,
    'query'    => $q,
    ...$result,
]);