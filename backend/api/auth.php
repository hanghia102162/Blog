<?php
// =============================================
// api/auth.php — Endpoint xác thực người dùng
//
// POST /api/auth.php?action=register  → Đăng ký
// POST /api/auth.php?action=login     → Đăng nhập → trả JWT token
// GET  /api/auth.php?action=me        → Lấy thông tin user đang login
// POST /api/auth.php?action=logout    → Đăng xuất (xử lý ở frontend)
// PUT  /api/auth.php?action=profile   → Cập nhật profile
// PUT  /api/auth.php?action=password  → Đổi mật khẩu
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/User.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$userModel = new User($pdo);
$method    = $_SERVER['REQUEST_METHOD'];
$action    = $_GET['action'] ?? '';


// ─────────────────────────────────────────
// LẤY THÔNG TIN USER ĐANG LOGIN — GET ?action=me
// ─────────────────────────────────────────
if ($method === 'GET' && $action === 'me') {
    $payload = Auth::requireLogin();

    $user = $userModel->findById($payload['id']);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy user']);
        exit;
    }

    echo json_encode(['success' => true, 'user' => $user]);
    exit;
}

// ─────────────────────────────────────────
// CẬP NHẬT PROFILE — PUT ?action=profile
// ─────────────────────────────────────────
if ($method === 'PUT' && $action === 'profile') {
    $payload = Auth::requireLogin();
    $data    = json_decode(file_get_contents('php://input'), true);

    if (empty($data['username'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu username']);
        exit;
    }

    $ok = $userModel->updateProfile($payload['id'], $data);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Cập nhật profile thành công' : 'Cập nhật thất bại',
    ]);
    exit;
}

// ─────────────────────────────────────────
// ĐỔI MẬT KHẨU — PUT ?action=password
// ─────────────────────────────────────────
if ($method === 'PUT' && $action === 'password') {
    $payload = Auth::requireLogin();
    $data    = json_decode(file_get_contents('php://input'), true);

    if (empty($data['current_password']) || empty($data['new_password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu current_password hoặc new_password']);
        exit;
    }

    if (strlen($data['new_password']) < 6) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới tối thiểu 6 ký tự']);
        exit;
    }

    // Xác nhận mật khẩu hiện tại đúng không
    $user = $userModel->findByEmail($payload['email']);
    if (!$userModel->verifyPassword($data['current_password'], $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Mật khẩu hiện tại không đúng']);
        exit;
    }

    $ok = $userModel->updatePassword($payload['id'], $data['new_password']);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Đổi mật khẩu thành công' : 'Đổi mật khẩu thất bại',
    ]);
    exit;
}

// ─────────────────────────────────────────
// ĐĂNG XUẤT — POST ?action=logout
// JWT là stateless nên logout xử lý ở frontend
// (xóa token khỏi localStorage)
// ─────────────────────────────────────────
if ($method === 'POST' && $action === 'logout') {
    echo json_encode(['success' => true, 'message' => 'Đăng xuất thành công']);
    exit;
}

// Action không hợp lệ
http_response_code(400);
echo json_encode(['success' => false, 'message' => "Action '$action' không hợp lệ"]);