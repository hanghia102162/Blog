<?php
// =============================================
// models/Category.php — Tầng truy xuất database cho danh mục
// =============================================

class Category {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ─────────────────────────────────────────
    // LẤY TẤT CẢ DANH MỤC (dùng cho menu)
    // ─────────────────────────────────────────
    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT
                c.id, c.name, c.slug, c.description, c.sort_order,
                COUNT(p.id) AS post_count
            FROM categories c
            LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ");
        return $stmt->fetchAll();
    }

    // ─────────────────────────────────────────
    // LẤY MỘT DANH MỤC THEO SLUG
    // ─────────────────────────────────────────
    public function getBySlug(string $slug): array|false {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.name, c.slug, c.description, c.sort_order,
                COUNT(p.id) AS post_count
            FROM categories c
            LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
            WHERE c.slug = :slug
            GROUP BY c.id
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    // ─────────────────────────────────────────
    // TẠO DANH MỤC MỚI
    // ─────────────────────────────────────────
    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, slug, description, sort_order)
            VALUES (:name, :slug, :description, :sort_order)
        ");
        $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description']  ?? null,
            ':sort_order'  => $data['sort_order']   ?? 0,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // ─────────────────────────────────────────
    // CẬP NHẬT DANH MỤC
    // ─────────────────────────────────────────
    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE categories SET
                name        = :name,
                slug        = :slug,
                description = :description,
                sort_order  = :sort_order
            WHERE id = :id
        ");
        return $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $data['slug'],
            ':description' => $data['description']  ?? null,
            ':sort_order'  => $data['sort_order']   ?? 0,
            ':id'          => $id,
        ]);
    }

    // ─────────────────────────────────────────
    // XÓA DANH MỤC
    // ─────────────────────────────────────────
    public function delete(int $id): bool {
        // Các bài viết thuộc danh mục này sẽ có category_id = NULL (do ON DELETE SET NULL)
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}