<?php
require_once "../config/database.php";
require_once "../models/UserModel.php";
require_once "../controllers/UserController.php";

$model = new UserModel($conn);
$controller = new UserController($model);

$method = $_SERVER['REQUEST_METHOD'];
// ==============lấy thông tin của user=====
if ($method === 'GET') {

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

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'];
$headers = getallheaders();
$token = str_replace("Bearer ", "", $headers['Authorization'] ?? '');
$user = $model->findByToken($token);

if (!$user) {
    echo json_encode(["status" => false, "message" => "Token không hợp lệ"]);
    exit();
}

if($method === "POST" && $action === "changePassword"){
        echo json_encode($controller->changePassword($user, $data));
        exit();
}
if($method === "POST" && $action ==="updateProfile"){
        echo json_encode($controller->updateProfile($user,$data));
        exit();
}

?>