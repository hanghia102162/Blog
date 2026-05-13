<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "blog_db";
$port = "3306";
$conn = new mysqli($host, $user, $password, $database, $port);
if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]));
}
$conn->set_charset("utf8mb4");
?>