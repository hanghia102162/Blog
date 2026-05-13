<?php
// =============================================
// api/posts.php — Endpoint bài viết
// GET    /api/posts.php              → Danh sách bài viết       [public]
// GET    /api/posts.php?slug=...     → Chi tiết một bài         [public]
// GET    /api/posts.php?search=...   → Tìm kiếm                 [public]
// POST   /api/posts.php              → Tạo bài mới              [author/admin]
// PUT    /api/posts.php?id=...       → Sửa bài                  [author/admin]
// DELETE /api/posts.php?id=...       → Xóa bài                  [admin]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../models/Post.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$post   = new Post($pdo);
$method = $_SERVER['REQUEST_METHOD'];

// ─────────────────────────────────────────
// GET — Public, không cần login
// ─────────────────────────────────────────
if ($method === 'GET') {

    // GET ?mine=1 → Bài viết của tôi (dùng cho trang Quản lý bài viết)
    // Admin → thấy TẤT CẢ bài (mọi status)
    // Author → chỉ thấy bài CỦA MÌNH (mọi status, kể cả draft)
    if (!empty($_GET['mine'])) {
        $user = Auth::requireAuthor(); // Chỉ author/admin mới được vào

        $filters = [
            'include_drafts' => true,
            // Admin không lọc user_id → thấy tất cả. Author → lọc theo user_id của mình
            'user_id' => ($user['role'] !== 'admin') ? $user['id'] : null,
            'page'     => (int)($_GET['page']     ?? 1),
            'per_page' => (int)($_GET['per_page'] ?? 20),
        ];
        $result = $post->getAll($filters);
        echo json_encode(['success' => true, ...$result]);
        exit;
    }

    // GET ?slug=... → Chi tiết bài viết (công khai)
    if (!empty($_GET['slug'])) {
        $result = $post->getBySlug($_GET['slug']);
        if (!$result) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $result]);
        exit;
    }

    // GET ?id=... → Lấy bài viết theo ID (cho admin/author sửa bài)
    if (!empty($_GET['id'])) {
        $result = $post->getById((int)$_GET['id']);
        if (!$result) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy bài viết']);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $result]);
        exit;
    }

    // GET ?search=... → Tìm kiếm full-text
    if (!empty($_GET['search'])) {
        $result = $post->search(
            $_GET['search'],
            (int)($_GET['page']     ?? 1),
            (int)($_GET['per_page'] ?? 10)
        );
        echo json_encode(['success' => true, ...$result]);
        exit;
    }

    // GET danh sách bài (lọc theo category, tag, page)
    $filters = [
        'category' => $_GET['category'] ?? null,
        'tag'      => $_GET['tag']      ?? null,
        'page'     => $_GET['page']     ?? 1,
        'per_page' => $_GET['per_page'] ?? 10,
    ];
    $result = $post->getAll($filters);
    echo json_encode(['success' => true, ...$result]);
    exit;
}

// ─────────────────────────────────────────
// POST — Tạo bài mới [author/admin]
// ─────────────────────────────────────────
// if ($method === 'POST') {
//     $user = Auth::requireAuthor(); // ← Kiểm tra JWT, tự trả 401/403 nếu không hợp lệ
//     $data = json_decode(file_get_contents('php://input'), true);

//     if (empty($data['title']) || empty($data['content']) || empty($data['slug'])) {
//         http_response_code(400);
//         echo json_encode(['success' => false, 'message' => 'Thiếu title, slug hoặc content']);
//         exit;
//     }

//     // Lấy user_id từ token (không hardcode nữa)
//     $data['user_id'] = $user['id'];

//     try {
//         $id = $post->create($data);
//         http_response_code(201);
//         echo json_encode(['success' => true, 'message' => 'Tạo bài viết thành công', 'id' => $id]);
//     } catch (PDOException $e) {
//         http_response_code(500);
//         $msg = str_contains($e->getMessage(), 'Duplicate entry')
//             ? 'Slug đã tồn tại, vui lòng dùng slug khác'
//             : 'Lỗi server';
//         echo json_encode(['success' => false, 'message' => $msg]);
//     }
//     exit;
// }
if ($method === 'POST') {

    $user = Auth::requireAuthor();

    // =====================
    // 1. LẤY DATA (JSON + FORM DATA)
    // =====================
    $data = $_POST;

    if (empty($data)) {
        $data = json_decode(file_get_contents('php://input'), true);
    }

    if (!$data) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Không nhận được dữ liệu"
        ]);
        exit;
    }

    if (empty($data['title']) || empty($data['content']) || empty($data['slug'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Thiếu dữ liệu"
        ]);
        exit;
    }

    // =====================
    // 2. HANDLE IMAGE
    // =====================
    $thumbnail = null;

    if (!empty($_FILES['thumbnail'])) {
        $file = $_FILES['thumbnail'];

        $name = time() . '_' . $file['name'];
        $path = __DIR__ . '/../uploads/' . $name;

        move_uploaded_file($file['tmp_name'], $path);

        $thumbnail = $name;
    }

    $data['thumbnail'] = $thumbnail;
    $data['user_id'] = $user['id'];

    try {
        $id = $post->create($data);

        echo json_encode([
            "success" => true,
            "id" => $id
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }

    exit;
}
// ─────────────────────────────────────────
// PUT — Sửa bài [author/admin]
// ─────────────────────────────────────────
if ($method === 'PUT') {
    $user = Auth::requireAuthor();

    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['title']) || empty($data['content']) || empty($data['slug'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu title, slug hoặc content']);
        exit;
    }

    try {
        $ok = $post->update($id, $data, $user['id'], $user['role']);
        echo json_encode(['success' => $ok, 'message' => $ok ? 'Cập nhật thành công' : 'Không tìm thấy bài']);
    } catch (PDOException $e) {
        http_response_code(500);
        $msg = str_contains($e->getMessage(), 'Duplicate entry')
            ? 'Slug đã tồn tại, vui lòng dùng slug khác'
            : 'Lỗi server';
        echo json_encode(['success' => false, 'message' => $msg]);
    }
    exit;
}

// ─────────────────────────────────────────
// DELETE — Xóa bài [admin only]
// ─────────────────────────────────────────
if ($method === 'DELETE') {
    $user = Auth::requireAuthor(); // Cả admin và author đều được phép vào

    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Thiếu id']);
        exit;
    }

    $ok = $post->delete($id, $user['id'], $user['role']);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Xóa thành công' : 'Không tìm thấy bài hoặc bạn không có quyền']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method không được hỗ trợ']);