<?php
    class UserController{
        private $model;
        
        public function __construct($model)
        {
            $this->model = $model;
        }

        public function getUsers() {
            return $this->model->getAllUsers();
        }

        public function changePassword($user,$data){
            if (!password_verify($data['password'], $user['password'])) {
            return ["status" => false, "message" => "Sai mật khẩu cũ"];
        }

        if ($data['newPassword'] != $data['ConfirmNewpassword']) {
            return ["status" => false, "message" => "Mật khẩu xác nhận không khớp"];
        }
            $hash = password_hash($data['newPassword'], PASSWORD_DEFAULT);
            $this->model->updatePassword($user['id'], $hash);

            return ["status" => true, "message" => "Đổi mật khẩu thành công"];
        }
        public function updateProfile($user,$data){
                $name = $data['name'] ?? $user['name'];
                $email = $data['email'] ?? $user['email'];
                $hostline = $data['hostline'] ?? $user['hostline'];
                $this->model->updateProfile($user['id'], $name, $email, $hostline);

            return ["status" => true, "message" => "Cập nhật thành công"];
        }

    }
?>