<?php
require_once "../config/database1.php";
require_once "../models/UserModel.php";
require_once "../controllers/UserController.php";

$model = new UserModel($conn);
$controller = new UserController($model);

$method = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);

// lấy action từ GET hoặc POST
$action = $_GET['action'] ?? ($data['action'] ?? '');
$headers = getallheaders();
$token = str_replace("Bearer ", "", $headers['Authorization'] ?? '');
$user = $model->findByToken($token);

if (!$user) {
    echo json_encode(["status" => false, "message" => "Token không hợp lệ"]);
    exit();
}

// ==============lấy thông tin của user=====
// $headers = getallheaders();
// $token = str_replace("Bearer ", "", $headers['Authorization'] ?? '');
if ($method === 'GET' && $action =="test") {

    $headers = getallheaders();
    $token = str_replace("Bearer ", "", $headers['Authorization'] ?? '');

    $user = $model->findByToken($token);

    if (!$user) {
        echo json_encode([
            "status" => false,
            "message" => "Token không hợp lệ"
        ]);
        exit();
    }

    echo json_encode([
        "status" => true,
        "data" => $user   
    ]);
    exit();
}
// ================================================
// =====================================================
if ($method === "GET" && $action === "getUser") {
    try {
        $users = $model->getAllUsers(); // bạn cần tạo hàm này trong model

        echo json_encode([
            "success" => true,
            "data" => $users
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}

// ============================================================


if($method === "POST" && $action === "changePassword"){
        echo json_encode($controller->changePassword($user, $data));
        exit();
}
if($method === "POST" && $action ==="updateProfile"){
        echo json_encode($controller->updateProfile($user,$data));
        exit();
}

// =================================


?>