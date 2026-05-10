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
session_start();

$conn = new mysqli("127.0.0.1","root","","blog_db",3306);
$data = json_decode(file_get_contents("php://input"),true);
$otpInput = $data["otp"];
$password = $data['password'];
$confirmPassword = $data['confirmPassword'];
if ($password != $confirmPassword) {
    echo json_encode([
        "success" => false,
        "message" => "Password khong khop"
    ]);
    exit;
}
if (!isset($_SESSION['otp'])) {
    echo json_encode([
        "success" => false,
        "message" => "Chua co OTP"
    
    ]);
    exit;
}
if (time() - $_SESSION['otp_time'] > 300) {

    unset($_SESSION['otp']);
    unset($_SESSION['otp_time']);

    echo json_encode([
        "success" => false,
        "message" => "OTP da het han"
    ]);
    exit;
}

if ($otpInput != $_SESSION['otp']) {
    echo json_encode([
        "success" => false,
        "message" => "OTP sai"
    ]);
    exit;
}
$email = $_SESSION['email'];
$newPassword = password_hash($password, PASSWORD_BCRYPT);

$sql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
$conn->query($sql);

unset($_SESSION['otp']);
unset($_SESSION['otp_time']);
unset($_SESSION['email']);

echo json_encode([
    "success" => true,
    "message" => "Doi mat khau thanh cong"
]);
?>