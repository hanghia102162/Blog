<?php

class AuthorRequest {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // kiểm tra user đã gửi yêu cầu chua
    public function findByUserId(int $userId): array|false {
        $stmt = $this->pdo->prepare("
            SELECT ar.*, u.username, u.email
            FROM author_requests ar
            JOIN users u ON u.id = ar.user_id
            WHERE ar.user_id = :user_id
            ORDER BY ar.created_at DESC
            LIMIT 1
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }

    // admin lấy tất cả yêu cầu, có thể lấy theo status 
    public function getAll(string $status = ''): array {
        $where  = $status ? "WHERE ar.status = :status" : '';
        $params = $status ? [':status' => $status] : [];

        $stmt = $this->pdo->prepare("
            SELECT
                ar.id, ar.topics, ar.reason, ar.status,
                ar.admin_note, ar.created_at, ar.updated_at,
                u.id       AS user_id,
                u.username AS username,
                u.email    AS email
            FROM author_requests ar
            JOIN users u ON u.id = ar.user_id
            $where
            ORDER BY ar.created_at ASC
        ");
        $stmt->execute($params);

        $rows = $stmt->fetchAll();

        // Decode JSON topics thành array cho từng dòng
        foreach ($rows as &$row) {
            $row['topics'] = json_decode($row['topics'], true) ?? [];
        }

        return $rows;
    }

    // lấy infor yêu cầu từ id trong bảng ar
    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT
                ar.*, u.username, u.email, u.role
            FROM author_requests ar
            JOIN users u ON u.id = ar.user_id
            WHERE ar.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
            $row['topics'] = json_decode($row['topics'], true) ?? [];
        }

        return $row;
    }

    // tạo yêu cầu
    public function create(int $userId, array $topics, string $reason): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO author_requests (user_id, topics, reason, status)
            VALUES (:user_id, :topics, :reason, 'pending')
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':topics'  => json_encode($topics, JSON_UNESCAPED_UNICODE),
            ':reason'  => $reason,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // duyệt yêu cầu từ user lên author
    public function approve(int $id, string $adminNote = ''): bool {
        // Lấy user_id từ id yêu cầu
        $request = $this->findById($id);
        if (!$request) return false;

        // trạng thái yêu cầu thành approved
        $stmt = $this->pdo->prepare("
            UPDATE author_requests
            SET status = 'approved', admin_note = :note
            WHERE id = :id
        ");
        $stmt->execute([':note' => $adminNote, ':id' => $id]);

        $roleStmt = $this->pdo->prepare("
            UPDATE users SET role = 'author'
            WHERE id = :user_id AND role = 'reader'
        ");
        $roleStmt->execute([':user_id' => $request['user_id']]);

        return true;
    }

    // từ chối yêu cầu
    public function reject(int $id, string $adminNote = ''): bool {
        $stmt = $this->pdo->prepare("
            UPDATE author_requests
            SET status = 'rejected', admin_note = :note
            WHERE id = :id
        ");
        return $stmt->execute([':note' => $adminNote, ':id' => $id]);
    }

    // xoá yêu cầu
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM author_requests WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }


    // hạ role từ author xuống user
    public function revokeAuthor(int $requestId): bool {
    // lấy request
    $request = $this->findById($requestId);
    if (!$request) return false;

    // xoá yêu cầu luôn
    $stmt = $this->delete($requestId);

    $roleStmt = $this->pdo->prepare("
        UPDATE users SET role = 'reader'
        WHERE id = :user_id AND role = 'author'
    ");

    $roleStmt->execute([
        ':user_id' => $request['user_id']
    ]);

    return true;
}
}
