<?php

// Test bằng Postman

// GET  /api/comments.php?post_id=...      ->  Lấy comments approved của bài
// GET  /api/comments.php?recent=1        -> Lấy ra các comment mới nhất với số lượng limit
// GET  /api/comments.php?admin=1        -> Lấy tất cả comments với mọi statuss
// GET  /api/comments.php?admin=1&post_id=...   -> Lấy tất cả comments của một post
// GET  /api/comments.php?admin=1&status=spam    -> Lọc comments theo status
// POST /api/comments.php                        Gửi bình luận mới
// PUT  /api/comments.php?id=&action=spam       Đánh spam
// PUT  /api/comments.php?id=&action=restore     Khôi phục spam thành approved
// DELETE /api/comments.php?id=...              Xóa bình luận

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Comment.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$comment = new Comment($pdo);
$method  = $_SERVER['REQUEST_METHOD'];

// GET
if ($method === 'GET') {

    // Admin có thể lấy tất các comment
    if (!empty($_GET['admin'])) {
        Auth::requireAdmin();

        // Admin lấy ra comment của bài viết cụ thể
        if (!empty($_GET['post_id'])) {
            $data = $comment->getByPostIdAdmin((int)$_GET['post_id']);
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        }

        // Admin có thể lấy ra các comment theo status
        $status = $_GET['status'] ?? '';
        $data   = $comment->getAll($status);
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    // Lấy số limit comment gần nhất - lấy comment approved
    if (!empty($_GET['recent'])) {
        $limit  = (int)($_GET['limit'] ?? 5);
        echo json_encode(['success' => true, 'data' => $comment->getRecent($limit)]);
        exit;
    }

    // Tất cả comment approved của post
    if (!empty($_GET['post_id'])) {
        $data = $comment->getByPostId((int)$_GET['post_id']);
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu post_id, recent hoặc admin']);
    exit;
}


// Gửi bình luận mới với user đã login
if ($method === 'POST') {
    $user = Auth::requireLogin();
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['post_id']) || empty($data['content'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu post_id hoặc content']);
        exit;
    }

    $data['user_id'] = $user['id'];
    $id = $comment->create($data);

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Bình luận đã được đăng',
        'id'      => $id,
    ]);
    exit;
}

// PUT
// Admin có thể đánh spam comment của người dùng
// admin cũng có thể khổi phục lại những comment đã sapm
if ($method === 'PUT') {
    Auth::requireAdmin();

    $id     = (int)($_GET['id'] ?? 0);
    $action = $_GET['action'] ?? '';

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    if ($action === 'spam') {
        $ok = $comment->markSpam($id);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'Đã đánh dấu spam' : 'Không tìm thấy']);
        exit;
    }

    if ($action === 'restore') {
        $ok = $comment->restore($id);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'Đã khôi phục bình luận' : 'Không tìm thấy']);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => "Action '$action' không hợp lệ."]);
    exit;
}


// DELETE 
if ($method === 'DELETE') {
    Auth::requireAdmin();

    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $ok = $comment->delete($id);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Đã xóa bình luận' : 'Không tìm thấy']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method không được hỗ trợ']);