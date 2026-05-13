<?php
require "../vendor/autoload.php";
require "../config/jwt.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    class AutherController{

        private $model;

    public function __construct($model)
    {
        return $this->model = $model;
    }
        
    public function login($email,$password){
        $user = $this->model->findUserByEmail($email);
        if(!$user){
            return [
                "message" =>"email ko hợp lệ",
                "success"=>false
            ];
        }
        if (!password_verify($password, $user['password'])) {
            return [
                "success" => false,
                "message" => "Thông tin tài khoản hoặc mật khẩu không đúng!"
            ];
        }
        $payload = [
            "id" => $user['id'],
            "email" => $user['email'],
            "username" => $user['username'],
            "role" => $user['role'],
            "iat" => time(), // thời gian tạo
            "exp" => time() + (60 * 60 * 24) // hết hạn sau 1 ngày
        ];

        $token = JWT::encode($payload);
        $this->model->updateToken($user['id'], $token);
        return [
            "success" => true,
            "message" => "Đăng nhập thành công!",
            "token" => $token,
            "user" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];
    }
    public function Register($email,$password,$confirmPassword){
        if ($password != $confirmPassword) {
        return [
            "message" =>"xác nhận mật khẩu không khớp!",
            "susses"=>false
        ];
    }
        $check = $this->model->findUserByEmail($email);
        if ($check){
            return[
                "success"=>false,
                "message"=>"email đã tồn tại !"
            ];
        }
        $hash = password_hash($password,PASSWORD_DEFAULT);
        $username = explode("@", $email)[0];
        $created = $this->model->createUser($username, $email, $hash);
        if($created){
            return [
                "success"=>true,
                "message"=>"tạo thanh công tài khoản!"
            ];
        }
    }

    public function sendOtp($email){
        $check = $this->model->findUserByEmail($email);
        if ($check) {
        $otp = rand(100000, 999999);

        // lưu OTP vào session 
        session_start();
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;
        $_SESSION['otp_time'] = time();

    // gửi mail
        $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hanghia368@gmail.com'; 
        $mail->Password = 'toxzekkbubqkssvg';  
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('hanghia368@gmail.com', 'Blog System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Ma OTP xac thuc";
        $mail->Body = "Ma OTP cua ban la: <b>$otp</b> sẽ hết hạn trong 5 phút nữa";

        $mail->send();

        return [
        "success" => true,
        "message" => "Da gui OTP vao email"
    ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => "Gui mail that bai: " . $mail->ErrorInfo
        ];
    }
    }else{
        return [
            "success"=>false,
            "message"=>"0 co email nay"
        ];
    }
}
// ====================================
    public function checkOTP($otpInput,$confirmPassword,$password){
         session_start(); 
        if($password != $confirmPassword){
            return[
                "success"=>false,
                "message"=>"mật khẩu không khớp!"
            ];
        }
        if(!isset($_SESSION['otp'])){
            return[
                "success"=> false,
                "message"=>"otp bị hết hạn hoặc không xác định!"
            ];
        }
        if (time() - $_SESSION['otp_time'] > 300) {

            unset($_SESSION['otp']);
            unset($_SESSION['otp_time']);

            return[
                "success" => false,
                "message" => "OTP da het han"
            ];
        }
        if ($otpInput != $_SESSION['otp']) {
            return[
                "success" => false,
                "message" => "OTP sai"
            ];
        }
            $email = $_SESSION['email'];
            $newPassword = password_hash($password, PASSWORD_BCRYPT);
            $this->model->updatePassword($email,$newPassword);

            unset($_SESSION['otp']);
            unset($_SESSION['otp_time']);
            unset($_SESSION['email']);

            return[
                "success" => true,
                "message" => "Doi mat khau thanh cong"
            ];
    }
}

?>