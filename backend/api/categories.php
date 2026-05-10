<?php
// =============================================
// api/categories.php — Endpoint danh mục
// GET    /api/categories.php            → Tất cả danh mục (dùng cho menu)
// GET    /api/categories.php?slug=...   → Chi tiết một danh mục
// POST   /api/categories.php            → Tạo danh mục mới  [admin]
// PUT    /api/categories.php?id=...     → Sửa danh mục      [admin]
// DELETE /api/categories.php?id=...     → Xóa danh mục      [admin]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$category = new Category($pdo);
$method   = $_SERVER['REQUEST_METHOD'];

// ─────────────────────────────────────────
// GET
// ─────────────────────────────────────────
if ($method === 'GET') {

    // GET ?slug=... → Chi tiết một danh mục
    if (!empty($_GET['slug'])) {
        $result = $category->getBySlug($_GET['slug']);
        if (!$result) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy danh mục']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $result]);
        exit;
    }

    // GET tất cả danh mục
    $result = $category->getAll();
    echo json_encode(['success' => true, 'data' => $result]);
    exit;
}

// ─────────────────────────────────────────
// POST — Tạo danh mục
// ─────────────────────────────────────────
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['name']) || empty($data['slug'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu name hoặc slug']);
        exit;
    }

    try {
        $id = $category->create($data);
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Tạo danh mục thành công', 'id' => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        $msg = str_contains($e->getMessage(), 'Duplicate entry')
            ? 'Slug đã tồn tại'
            : 'Lỗi server';
        echo json_encode(['success' => false, 'message' => $msg]);
    }
    exit;
}

// ─────────────────────────────────────────
// PUT — Cập nhật danh mục
// ─────────────────────────────────────────
if ($method === 'PUT') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['name']) || empty($data['slug'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu name hoặc slug']);
        exit;
    }

    try {
        $ok = $category->update($id, $data);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'Cập nhật thành công' : 'Không tìm thấy danh mục']);
    } catch (PDOException $e) {
        http_response_code(500);
        $msg = str_contains($e->getMessage(), 'Duplicate entry')
            ? 'Slug đã tồn tại'
            : 'Lỗi server';
        echo json_encode(['success' => false, 'message' => $msg]);
    }
    exit;
}

// ─────────────────────────────────────────
// DELETE — Xóa danh mục
// ─────────────────────────────────────────
if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $ok = $category->delete($id);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Xóa thành công' : 'Không tìm thấy danh mục']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method không được hỗ trợ']);