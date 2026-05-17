<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';

// =========================
// HEADERS
// =========================
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// =========================
// OPTIONS
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// =========================
// METHOD CHECK
// =========================
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Chỉ hỗ trợ GET'
    ]);

    exit;
}

// =========================
// GET QUERY
// =========================
$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Từ khóa tối thiểu 2 ký tự'
    ]);

    exit;
}

// =========================
// PAGINATION
// =========================
$page = max(1, (int)($_GET['page'] ?? 1));

$perPage = min(
    20,
    max(1, (int)($_GET['per_page'] ?? 10))
);

// =========================
// SEARCH
// =========================
$postModel = new Post($pdo);

$result = $postModel->search($q, $page, $perPage);

// =========================
// RESPONSE
// =========================
echo json_encode([
    'success' => true,
    'query' => $q,
    ...$result
]);