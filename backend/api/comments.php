<?php

// =============================================
// api/comments.php — Endpoint bình luận
// GET  /api/comments.php?post_id=...  → Lấy comments của bài    [public]
// GET  /api/comments.php?recent=1     → Recent comments sidebar  [public]
// GET  /api/comments.php?pending=1    → Comments chờ duyệt       [admin]
// POST /api/comments.php              → Gửi bình luận mới        [login]
// PUT  /api/comments.php?id=&action=  → Duyệt / đánh spam        [admin]
// DEL  /api/comments.php?id=...       → Xóa bình luận            [admin]
// =============================================