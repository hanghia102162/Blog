<?php
// =============================================
// api/author_requests.php — Endpoint đăng ký tác giả
//
// GET  /api/author_requests.php              → Danh sách tất cả yêu cầu     [admin]
// GET  /api/author_requests.php?status=pending → Lọc theo trạng thái        [admin]
// GET  /api/author_requests.php?my=1         → Xem yêu cầu của chính mình   [login]
// POST /api/author_requests.php              → Gửi yêu cầu đăng ký          [login]
// PUT  /api/author_requests.php?id=&action=  → Duyệt / từ chối              [admin]
// DEL  /api/author_requests.php?id=          → Xóa yêu cầu                  [admin]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/AuthorRequest.php';

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$authorRequest = new AuthorRequest($pdo);
$method        = $_SERVER['REQUEST_METHOD'];

// ─────────────────────────────────────────
// GET
// ─────────────────────────────────────────
if ($method === 'GET') {

    // GET ?my=1 → Xem trạng thái yêu cầu của chính mình
    if (!empty($_GET['my'])) {
        $user    = Auth::requireLogin();
        $request = $authorRequest->findByUserId($user['id']);

        if (!$request) {
            echo json_encode(['success' => true, 'data' => null, 'message' => 'Bạn chưa gửi yêu cầu nào']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $request]);
        exit;
    }

    // GET danh sách tất cả → chỉ admin
    Auth::requireAdmin();
    $status = $_GET['status'] ?? ''; // pending | approved | rejected | (trống = tất cả)
    $list   = $authorRequest->getAll($status);
    echo json_encode(['success' => true, 'data' => $list, 'total' => count($list)]);
    exit;
}

// ─────────────────────────────────────────
// POST — Gửi yêu cầu đăng ký tác giả [login]
// ─────────────────────────────────────────
if ($method === 'POST') {
    $user = Auth::requireLogin();
    $data = json_decode(file_get_contents('php://input'), true);

    // Chỉ reader mới được gửi yêu cầu
    if ($user['role'] !== 'reader') {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $user['role'] === 'author'
                ? 'Bạn đã là tác giả rồi'
                : 'Admin không cần đăng ký tác giả',
        ]);
        exit;
    }

    // Validate
    $errors = [];
    if (empty($data['topics']) || !is_array($data['topics'])) {
        $errors[] = 'Vui lòng chọn ít nhất một chủ đề';
    }
    if (empty(trim($data['reason'] ?? ''))) {
        $errors[] = 'Vui lòng điền lý do đăng ký';
    }
    if (strlen(trim($data['reason'] ?? '')) < 20) {
        $errors[] = 'Lý do cần ít nhất 20 ký tự';
    }

    if ($errors) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode('. ', $errors)]);
        exit;
    }

    // Kiểm tra đã có yêu cầu pending chưa
    $existing = $authorRequest->findByUserId($user['id']);
    if ($existing && $existing['status'] === 'pending') {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Bạn đã có một yêu cầu đang chờ duyệt, vui lòng đợi admin xem xét',
        ]);
        exit;
    }

    // Làm sạch danh sách chủ đề — chỉ giữ string, loại bỏ giá trị rỗng
    $topics = array_values(array_filter(
        array_map('trim', $data['topics']),
        fn($t) => $t !== ''
    ));

    if (empty($topics)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Danh sách chủ đề không hợp lệ']);
        exit;
    }

    try {
        $id = $authorRequest->create($user['id'], $topics, trim($data['reason']));
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Gửi yêu cầu thành công! Admin sẽ xem xét và phản hồi sớm.',
            'id'      => $id,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Lỗi server khi gửi yêu cầu']);
    }
    exit;
}

// ─────────────────────────────────────────
// PUT — Duyệt hoặc từ chối [admin]
// ?id=...&action=approve
// ?id=...&action=reject
// Body JSON: { "admin_note": "..." }  (tuỳ chọn)
// ─────────────────────────────────────────
if ($method === 'PUT') {
    Auth::requireAdmin();

    $id     = (int)($_GET['id'] ?? 0);
    $action = $_GET['action'] ?? '';

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $data      = json_decode(file_get_contents('php://input'), true);
    $adminNote = trim($data['admin_note'] ?? '');

    if ($action === 'approve') {
        $ok = $authorRequest->approve($id, $adminNote);
        echo json_encode([
            'success' => $ok,
            'message' => $ok
                ? 'Đã duyệt! Role của user đã được nâng lên author.'
                : 'Không tìm thấy yêu cầu',
        ]);
        exit;
    }

    if ($action === 'reject') {
        // if (!$adminNote) {
        //     http_response_code(400);
        //     echo json_encode(['success' => false, 'message' => 'Vui lòng điền lý do từ chối vào admin_note']);
        //     exit;
        // }
        $ok = $authorRequest->reject($id, $adminNote);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Đã từ chối yêu cầu' : 'Không tìm thấy yêu cầu',
        ]);
        exit;
    }
    //   if ($action === 'revokeAuthor') {
    //     $ok = $authorRequest->revokeAuthor($id);
    //     echo json_encode([
    //         'success' => $ok,
    //         'message' => $ok ? 'Đã xoá quyền author của user.' : 'Không thể hạ role'
    //     ]);
    //     exit;
    // }
    if ($action === 'revokeAuthor') {
    $userId = $id;

    $stmt = $pdo->prepare("
        UPDATE users 
        SET role = 'reader' 
        WHERE id = :id
    ");

    $ok = $stmt->execute([
        ':id' => $userId
    ]);

    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Đã hạ quyền về reader' : 'Không thể hạ role'
    ]);
    exit;
}


    http_response_code(400);
    echo json_encode(['success' => false, 'message' => "Action '$action' không hợp lệ. Dùng: approve | reject"]);
    exit;
}

// ─────────────────────────────────────────
// DELETE — Xóa yêu cầu [admin]
// ─────────────────────────────────────────
if ($method === 'DELETE') {
    Auth::requireAdmin();

    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $ok = $authorRequest->delete($id);
    echo json_encode([
        'success' => $ok,
        'message' => $ok ? 'Đã xóa yêu cầu' : 'Không tìm thấy yêu cầu',
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method không được hỗ trợ']);
