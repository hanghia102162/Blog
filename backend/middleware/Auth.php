<?php
// =============================================
// middleware/Auth.php — Xác thực JWT từ request header
// =============================================

require_once __DIR__ . '/../config/jwt.php';

class Auth {

    // ─────────────────────────────────────────
    // LẤY PAYLOAD TỪ TOKEN (không bắt buộc đăng nhập)
    // Dùng cho các route vừa public vừa có thể login
    // Trả về payload nếu có token hợp lệ, null nếu không có
    // ─────────────────────────────────────────
    public static function getUser(): ?array {
        $token = self::extractToken();
        if (!$token) return null;

        try {
            return JWT::decode($token);
        } catch (Exception $e) {
            return null;
        }
    }

    // ─────────────────────────────────────────
    // YÊU CẦU ĐĂNG NHẬP (bắt buộc)
    // Dùng cho các route cần login (tạo bài, comment...)
    // Tự động trả 401 và exit nếu không hợp lệ
    // ─────────────────────────────────────────
    public static function requireLogin(): array {
        $token = self::extractToken();

        if (!$token) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }

        try {
            return JWT::decode($token);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    // ─────────────────────────────────────────
    // YÊU CẦU QUYỀN ADMIN
    // Dùng cho các route chỉ admin mới được (xóa bài, quản lý user...)
    // Tự động trả 403 và exit nếu không phải admin
    // ─────────────────────────────────────────
    public static function requireAdmin(): array {
        $user = self::requireLogin(); // Kiểm tra login trước

        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Không có quyền thực hiện thao tác này']);
            exit;
        }

        return $user;
    }

    // ─────────────────────────────────────────
    // YÊU CẦU QUYỀN AUTHOR HOẶC ADMIN
    // Dùng cho các route tạo/sửa bài viết
    // ─────────────────────────────────────────
    public static function requireAuthor(): array {
        $user = self::requireLogin();

        if (!in_array($user['role'], ['author', 'admin'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Chỉ tác giả hoặc admin mới được thực hiện']);
            exit;
        }

        return $user;
    }

    // ─────────────────────────────────────────
    // HELPER: Lấy token từ Authorization header
    // Header có dạng: Authorization: Bearer <token>
    // ─────────────────────────────────────────
    private static function extractToken(): ?string {
        $header = $_SERVER['HTTP_AUTHORIZATION']
               ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
               ?? $_SERVER['Authorization']
               ?? '';

        if (!$header && function_exists('getallheaders')) {
            $headers = getallheaders();
            $header = $headers['Authorization']
                   ?? $headers['authorization']
                   ?? '';
        }

        if (!$header && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization']
                   ?? $headers['authorization']
                   ?? '';
        }

        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7); // Cắt bỏ "Bearer " lấy phần token
        }

        return null;
    }
}
