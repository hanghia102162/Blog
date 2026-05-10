<?php
// =============================================
// models/Comment.php — Tầng truy xuất database cho bình luận
// =============================================

class Comment {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ─────────────────────────────────────────
    // LẤY TẤT CẢ BÌNH LUẬN CỦA MỘT BÀI VIẾT
    // ─────────────────────────────────────────
   

    // ─────────────────────────────────────────
    // LẤY RECENT COMMENTS
    // ─────────────────────────────────────────
    public function getRecent(int $limit = 5): array {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.content, c.created_at,
                u.username AS author,
                p.title    AS post_title,
                p.slug     AS post_slug
            FROM comments c
            JOIN users u ON u.id = c.user_id
            JOIN posts p ON p.id = c.post_id
            WHERE c.status = 'approved'
            ORDER BY c.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ─────────────────────────────────────────
    // LẤY TẤT CẢ BÌNH LUẬN CHỜ DUYỆT (admin)
    // ─────────────────────────────────────────
   

    // ─────────────────────────────────────────
    // TẠO BÌNH LUẬN MỚI
    // ─────────────────────────────────────────
    public function create(array $data): int { 
        // trả về id của bình luận đó
        return 0;
    }

    // ─────────────────────────────────────────
    // DUYỆT BÌNH LUẬN (admin)
    // ─────────────────────────────────────────
    // public function approve(int $id): bool {
    
    // }

    // ─────────────────────────────────────────
    // ĐÁNH DẤU SPAM (admin)
    // ─────────────────────────────────────────
    // public function markSpam(int $id): bool {

    // }

    // ─────────────────────────────────────────
    // XÓA BÌNH LUẬN (admin)
    // Các reply của comment này cũng tự xóa theo (ON DELETE CASCADE)
    // ─────────────────────────────────────────
    // public function delete(int $id): bool {
    // }
}