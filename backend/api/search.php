<?php

// =============================================
// api/search.php — Endpoint tìm kiếm toàn trang
// GET /api/search.php?q=...           → Tìm kiếm bài viết       [public]
// GET /api/search.php?q=...&page=2    → Trang kết quả tiếp theo  [public]
// =============================================

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Post.php';