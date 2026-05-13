<?php
// =============================================
// models/Post.php — Tầng truy xuất database cho bài viết
// =============================================

class Post {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ─────────────────────────────────────────
    // LẤY DANH SÁCH BÀI VIẾT (có phân trang)
    // ─────────────────────────────────────────
    public function getAll(array $filters = []): array {
        $where  = [];
        $params = [];

        // Mặc định chỉ lấy published — trừ khi ở trang quản lý (include_drafts = true)
        if (empty($filters['include_drafts'])) {
            $where[] = "p.status = 'published'";
        }

        // Lọc theo tác giả cụ thể (dùng cho trang quản lý của author)
        if (!empty($filters['user_id'])) {
            $where[]           = 'p.user_id = :user_id';
            $params[':user_id'] = $filters['user_id'];
        }
        // Lọc theo danh mục
        if (!empty($filters['category'])) {
            $where[]              = 'c.slug = :category';
            $params[':category']  = $filters['category'];
        }

        // Lọc theo tag
        if (!empty($filters['tag'])) {
            $where[]    = 'EXISTS (
                SELECT 1 FROM post_tags pt
                JOIN tags t ON t.id = pt.tag_id
                WHERE pt.post_id = p.id AND t.slug = :tag
            )';
            $params[':tag'] = $filters['tag'];
        }

        // Phân trang
        $page    = max(1, (int)($filters['page'] ?? 1));
        $perPage = (int)($filters['per_page'] ?? 10);
        $offset  = ($page - 1) * $perPage;

        $whereSQL = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Đếm tổng số bài để tính tổng trang
        $countStmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            $whereSQL
        ");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Lấy danh sách bài
        $stmt = $this->pdo->prepare("
            SELECT
                p.id, p.title, p.slug, p.excerpt, p.status, p.content,
                p.thumbnail, p.views, p.published_at, p.created_at,
                c.name AS category_name, c.slug AS category_slug,
                u.username AS author
            FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN users      u ON u.id = p.user_id
            $whereSQL
            ORDER BY p.published_at DESC
            LIMIT :limit OFFSET :offset
        ");

        // LIMIT và OFFSET phải bind riêng vì PDO cần kiểu int
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        $posts = $stmt->fetchAll();

        // Gắn tags vào từng bài
        foreach ($posts as &$post) {
            $post['tags'] = $this->getTagsByPostId($post['id']);
        }

        return [
            'data'        => $posts,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    // ─────────────────────────────────────────
    // LẤY MỘT BÀI VIẾT THEO SLUG
    // ─────────────────────────────────────────
    public function getBySlug(string $slug): array|false {
        $stmt = $this->pdo->prepare("
            SELECT
                p.*,
                c.name AS category_name, c.slug AS category_slug,
                u.username AS author, u.avatar AS author_avatar, u.bio AS author_bio
            FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN users      u ON u.id = p.user_id
            WHERE p.slug = :slug AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        $post = $stmt->fetch();

        if (!$post) return false;

        // Tăng lượt xem
        $this->incrementViews($post['id']);
        $post['views']++;

        // Gắn tags
        $post['tags'] = $this->getTagsByPostId($post['id']);

        return $post;
    }

    // ─────────────────────────────────────────
    // LẤY MỘT BÀI VIẾT THEO ID
    // ─────────────────────────────────────────
    public function getById(int $id): array|false {
    $stmt = $this->pdo->prepare("
        SELECT 
            p.*,
            c.name AS category_name,
            u.id AS author_id,
            u.username AS author_name,
            u.email AS author_email
        FROM posts p
        LEFT JOIN categories c ON c.id = p.category_id
        LEFT JOIN users u ON u.id = p.user_id
        WHERE p.id = ?
    ");

    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        $post['tags'] = $this->getTagsByPostId($id);
    }

    return $post;
}

    // ─────────────────────────────────────────
    // TẠO BÀI VIẾT MỚI
    // ─────────────────────────────────────────
    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO posts
                (user_id, category_id, title, slug, content, excerpt, thumbnail, status, published_at)
            VALUES
                (:user_id, :category_id, :title, :slug, :content, :excerpt, :thumbnail, :status, :published_at)
        ");
        $stmt->execute([
            ':user_id'      => $data['user_id'],
            ':category_id'  => $data['category_id'] ?? null,
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':content'      => $data['content'],
            ':excerpt'      => $data['excerpt'] ?? null,
            ':thumbnail'    => $data['thumbnail'] ?? null,
            ':status'       => $data['status'] ?? 'draft',
            ':published_at' => $data['status'] === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        $postId = (int)$this->pdo->lastInsertId();

        // Gắn tags nếu có
        if (!empty($data['tag_ids'])) {
            $this->syncTags($postId, $data['tag_ids']);
        }

        return $postId;
    }

    // ─────────────────────────────────────────
    // CẬP NHẬT BÀI VIẾT
    // ─────────────────────────────────────────
    public function update(int $id, array $data, int $currentUserId = 0, string $role = ''): bool {
        // Kiểm tra quyền sở hữu (Admin được quyền sửa tất cả)
        if ($role !== 'admin' && $currentUserId > 0) {
            $check = $this->pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
            $check->execute([$id]);
            if ($check->fetchColumn() != $currentUserId) return false;
        }

        // Nếu chuyển sang published và chưa có published_at thì ghi lại
        $publishedAt = null;
        if (($data['status'] ?? '') === 'published') {
            $current = $this->pdo->prepare("SELECT published_at FROM posts WHERE id = ?");
            $current->execute([$id]);
            $row = $current->fetch();
            $publishedAt = $row['published_at'] ?? date('Y-m-d H:i:s');
        }

        $stmt = $this->pdo->prepare("
            UPDATE posts SET
                category_id  = :category_id,
                title        = :title,
                slug         = :slug,
                content      = :content,
                excerpt      = :excerpt,
                thumbnail    = :thumbnail,
                status       = :status,
                published_at = :published_at
            WHERE id = :id
        ");
        $result = $stmt->execute([
            ':category_id'  => $data['category_id'] ?? null,
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':content'      => $data['content'],
            ':excerpt'      => $data['excerpt'] ?? null,
            ':thumbnail'    => $data['thumbnail'] ?? null,
            ':status'       => $data['status'] ?? 'draft',
            ':published_at' => $publishedAt,
            ':id'           => $id,
        ]);

        // Cập nhật tags
        if (isset($data['tag_ids'])) {
            $this->syncTags($id, $data['tag_ids']);
        }

        return $result;
    }

    // ─────────────────────────────────────────
    // XÓA BÀI VIẾT
    // ─────────────────────────────────────────
    public function delete(int $id, int $currentUserId = 0, string $role = ''): bool {
        // Kiểm tra quyền sở hữu (Admin được xóa tất cả)
        if ($role !== 'admin' && $currentUserId > 0) {
            $check = $this->pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
            $check->execute([$id]);
            if ($check->fetchColumn() != $currentUserId) return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ─────────────────────────────────────────
    // TÌM KIẾM FULL-TEXT
    // ─────────────────────────────────────────
    public function search(string $query, int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM posts
            WHERE status = 'published'
            AND MATCH(title, content) AGAINST (:q IN BOOLEAN MODE)
        ");
        $countStmt->execute([':q' => $query . '*']);
        $total = (int)$countStmt->fetchColumn();

        $stmt = $this->pdo->prepare("
            SELECT
                p.id, p.title, p.slug, p.excerpt,
                p.thumbnail, p.views, p.published_at,
                c.name AS category_name, c.slug AS category_slug,
                u.username AS author,
                MATCH(p.title, p.content) AGAINST (:q IN BOOLEAN MODE) AS relevance
            FROM posts p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN users      u ON u.id = p.user_id
            WHERE p.status = 'published'
            AND MATCH(p.title, p.content) AGAINST (:q2 IN BOOLEAN MODE)
            ORDER BY relevance DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q',      $query . '*');
        $stmt->bindValue(':q2',     $query . '*');
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data'        => $stmt->fetchAll(),
            'total'       => $total,
            'page'        => $page,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    // ─────────────────────────────────────────
    // HELPERS (dùng nội bộ)
    // ─────────────────────────────────────────

    private function incrementViews(int $postId): void {
        $stmt = $this->pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
        $stmt->execute([$postId]);
    }

    private function getTagsByPostId(int $postId): array {
        $stmt = $this->pdo->prepare("
            SELECT t.id, t.name, t.slug
            FROM tags t
            JOIN post_tags pt ON pt.tag_id = t.id
            WHERE pt.post_id = :post_id
        ");
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll();
    }

    // Đồng bộ tags cho bài viết (xóa cũ, thêm mới)
    private function syncTags(int $postId, array $tagIds): void {
        // Xóa toàn bộ tags cũ
        $del = $this->pdo->prepare("DELETE FROM post_tags WHERE post_id = ?");
        $del->execute([$postId]);

        // Thêm tags mới
        $ins = $this->pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
        foreach ($tagIds as $tagId) {
            $ins->execute([$postId, (int)$tagId]);
        }
    }
}