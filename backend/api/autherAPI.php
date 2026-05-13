<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../config/database1.php";
require_once "../models/AutherModel.php";
require_once "../controllers/AutherController.php";

$model = new AutherModel($conn);
$controller = new AutherController($model);

$data = json_decode(file_get_contents("php://input"),true);
$action = $data['action'];

// =========login================
if($_SERVER['REQUEST_METHOD'] === "POST" && $action === "login"){
    $email = $data['email'] ?? "";
    $password = $data['password'] ?? "";
    echo json_encode($controller->login($email, $password));
    exit();
}
// ==========register========================
if($_SERVER['REQUEST_METHOD'] ==="POST" && $action ==="register"){
    $email = $data['email'] ?? "";
    $password = $data['password'] ??"";
    $confirmPassword = $data['confirmPassword'] ?? "";
    echo json_encode($controller->Register($email,$password,$confirmPassword));
    exit();
}
// =========gửi otp quên mật khẩu ==========
if ($_SERVER['REQUEST_METHOD'] === "POST" && $action === "email") {
    $email = $data['email'] ?? "";
    echo json_encode($controller->sendOtp($email));
    exit();
}
// ===========mật khẩu mới =}}}==================================
if($_SERVER['REQUEST_METHOD'] === "POST" && $action ==="reissue"){
    $otp = $data['otp'];
    $confirmPassword = $data['confirmPassword'];
    $password = $data['password'];
    echo json_encode($controller->checkOTP($otp,$confirmPassword,$password));
}

?>