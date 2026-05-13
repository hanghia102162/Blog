<?php

class Comment {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Lấy ra tất cả các bình luận của bài post
    public function getByPostId(int $postId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.content, c.created_at,
                u.id       AS user_id,
                u.username AS author,
                u.avatar   AS author_avatar
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.post_id = :post_id
              AND c.status  = 'approved'
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll();
    }

    // Lấy ra các bình luận mới với số lượng limit
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

    // Lấy tất cả các comment với từng loại status
    public function getAll(string $status = ''): array {
        $where = $status !== '' ? "WHERE c.status = :status" : '';
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.content, c.status, c.created_at,
                u.id       AS user_id,
                u.username AS author,
                u.avatar   AS author_avatar,
                p.title    AS post_title,
                p.slug     AS post_slug,
                p.id       AS post_id
            FROM comments c
            JOIN users u ON u.id = c.user_id
            JOIN posts p ON p.id = c.post_id
            $where
            ORDER BY c.created_at DESC
        ");
        if ($status !== '') {
            $stmt->bindValue(':status', $status);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy comment của ,ột bài post
    public function getByPostIdAdmin(int $postId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.content, c.status, c.created_at,
                u.id       AS user_id,
                u.username AS author,
                u.avatar   AS author_avatar
            FROM comments c
            JOIN users u ON u.id = c.user_id
            WHERE c.post_id = :post_id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll();
    }

    // Create comment mới
    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (post_id, user_id, content, status)
            VALUES (:post_id, :user_id, :content, :status)
        ");
        $stmt->execute([
            ':post_id' => $data['post_id'],
            ':user_id' => $data['user_id'],
            ':content' => $data['content'],
            ':status'  => 'approved',
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // đánh spam
    public function markSpam(int $id): bool {
        $stmt = $this->pdo->prepare("
            UPDATE comments SET status = 'spam' WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    // khôi phục comment thành approve
    public function restore(int $id): bool {
        $stmt = $this->pdo->prepare("
            UPDATE comments SET status = 'approved' WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }

    // Xoá
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}