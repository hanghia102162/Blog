<?php

// api/posts.php — Endpoint bài viết
// GET    /api/posts.php              → Danh sách bài viết       [public]
// GET    /api/posts.php?slug=...     → Chi tiết một bài         [public]
// GET    /api/posts.php?search=...   → Tìm kiếm                 [public]
// POST   /api/posts.php              → Tạo bài mới              [author/admin]
// PUT    /api/posts.php?id=...       → Sửa bài                  [author/admin]
// DELETE /api/posts.php?id=...       → Xóa bài                  [admin]