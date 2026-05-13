<?php
// =============================================
// api/upload.php — Endpoint upload ảnh
// POST /api/upload.php?type=thumbnail  → Upload ảnh bài viết  [author/admin]
// POST /api/upload.php?type=avatar     → Upload ảnh đại diện  [login]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Chỉ hỗ trợ POST']);
    exit;
}

// ─────────────────────────────────────────
// CẤU HÌNH UPLOAD
// ─────────────────────────────────────────
$type          = $_GET['type'] ?? 'thumbnail'; // thumbnail | avatar
$allowedTypes  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$maxSize       = 3 * 1024 * 1024; // 3MB

// Thư mục lưu ảnh theo loại
$uploadDirs = [
    'thumbnail' => __DIR__ . '/../uploads/thumbnails/',
    'avatar'    => __DIR__ . '/../uploads/avatars/',
];

if (!array_key_exists($type, $uploadDirs)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => "Type '$type' không hợp lệ. Dùng: thumbnail | avatar"]);
    exit;
}

// ─────────────────────────────────────────
// KIỂM TRA QUYỀN
// thumbnail → author/admin, avatar → chỉ cần login
// ─────────────────────────────────────────
if ($type === 'thumbnail') {
    $user = Auth::requireAuthor();
} else {
    $user = Auth::requireLogin();
}

// ─────────────────────────────────────────
// KIỂM TRA FILE UPLOAD
// ─────────────────────────────────────────
if (empty($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Không có file được gửi lên (field name: file)']);
    exit;
}

$file = $_FILES['file'];

// Kiểm tra lỗi upload từ PHP
if ($file['error'] !== UPLOAD_ERR_OK) {
    $phpErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File vượt quá giới hạn upload_max_filesize trong php.ini',
        UPLOAD_ERR_FORM_SIZE  => 'File vượt quá giới hạn MAX_FILE_SIZE trong form',
        UPLOAD_ERR_PARTIAL    => 'File chỉ được upload một phần',
        UPLOAD_ERR_NO_FILE    => 'Không có file nào được upload',
        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file vào ổ đĩa',
    ];
    $msg = $phpErrors[$file['error']] ?? 'Lỗi upload không xác định';
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Kiểm tra kích thước
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File quá lớn, tối đa 3MB']);
    exit;
}

// Kiểm tra MIME type thật (không tin vào $_FILES['type'])
$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận ảnh JPG, PNG, WEBP, GIF']);
    exit;
}

// ─────────────────────────────────────────
// LƯU FILE
// ─────────────────────────────────────────
$uploadDir = $uploadDirs[$type];

// Tạo thư mục nếu chưa có
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Đặt tên file: {type}_{userId}_{timestamp}.{ext}
$ext      = match($mimeType) {
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif',
};
$filename = "{$type}_{$user['user_id']}_" . time() . ".$ext";
$destPath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu file, kiểm tra quyền thư mục uploads/']);
    exit;
}

// ─────────────────────────────────────────
// TRẢ VỀ ĐƯỜNG DẪN (lưu vào DB)
// ─────────────────────────────────────────
$relativePath = "uploads/{$type}s/$filename"; // vd: uploads/thumbnails/thumbnail_1_1234567890.jpg

echo json_encode([
    'success' => true,
    'message' => 'Upload thành công',
    'path'    => $relativePath,  // ← Giá trị này lưu vào cột thumbnail/avatar trong DB
]);