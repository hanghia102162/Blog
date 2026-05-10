<?php
// =============================================
// config/jwt.php — Dùng firebase/php-jwt
// =============================================

require_once __DIR__ . '/../vendor/autoload.php'; // ← Load thư viện Composer

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

define('JWT_SECRET', 'blog_secret_key_doi_chuoi_nay_thanh_gi_do_that_kho_doan_2026');
define('JWT_EXPIRE',  60 * 60 * 24 * 7); // 7 ngày

class JWT {

    public static function encode(array $payload): string {
        $payload['iat'] = time();
        $payload['exp'] = time() + JWT_EXPIRE;

        // Firebase xử lý toàn bộ việc tạo token
        return FirebaseJWT::encode($payload, JWT_SECRET, 'HS256');
    }

    public static function decode(string $token): array {
        // Key object chỉ định thuật toán rõ ràng — bảo mật hơn
        $decoded = FirebaseJWT::decode($token, new Key(JWT_SECRET, 'HS256'));

        // Firebase trả về object stdClass, cast sang array cho đồng nhất
        return (array) $decoded;
    }
}