<?php
// middleware/AdminOnly.php
require_once __DIR__ . '/../config/jwt.php';
require_once __DIR__ . '/../middleware/Auth.php';

$adminUser = Auth::requireAdmin();