<?php

class Post
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function search($keyword, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // =========================
        // Đếm tổng bài viết
        // =========================
        $countSql = "
            SELECT COUNT(*) 
            FROM posts
            WHERE title LIKE :keyword
               OR content LIKE :keyword
        ";

        $countStmt = $this->pdo->prepare($countSql);

        $search = "%$keyword%";

        $countStmt->bindParam(':keyword', $search);
        $countStmt->execute();

        $total = $countStmt->fetchColumn();

        // =========================
        // Lấy danh sách bài viết
        // =========================
        $sql = "
            SELECT id, title, slug, thumbnail, created_at
            FROM posts
            WHERE title LIKE :keyword
               OR content LIKE :keyword
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':keyword', $search, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $posts,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => (int)$total,
                'total_pages' => ceil($total / $perPage)
            ]
        ];
    }
}