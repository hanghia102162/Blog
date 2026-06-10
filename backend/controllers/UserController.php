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

        // public function changePassword($user,$data){
        //     if (!password_verify($data['password'], $user['password'])) {
        //     return ["status" => false, "message" => "Sai mật khẩu cũ"];
        // }

        // if ($data['newPassword'] != $data['ConfirmNewpassword']) {
        //     return ["status" => false, "message" => "Mật khẩu xác nhận không khớp"];
        // }
        //     $hash = password_hash($data['newPassword'], PASSWORD_DEFAULT);
        //     $this->model->updatePassword($user['id'], $hash);

        //     return ["status" => true, "message" => "Đổi mật khẩu thành công"];
        // }
        public function changePassword($user, $data)
        {
        $errors = [];
        // if($data['newPassword'][0] < 'A' || $data['newPassword'][0] > 'Z'){
        //         $errors['newPassword'] = "Chữ đầu phải viết hoa";
        // }
        
        // if (!preg_match('/[\W]/', $data['newPassword'])) {
        //     $errors['newPassword'] = "Mật khẩu mới phải có ít nhất 1 ký tự đặc biệt";
        // }
        // if ($data['newPassword'] != $data['ConfirmNewpassword']) {
        //     $errors['confirmPassword'] = "Mật khẩu xác nhận không khớp";
        // }

        if (!preg_match('/[A-Z]/', $data['newPassword'])) {
            $errors['newPassword'][] = "Mật khẩu phải có ít nhất 1 chữ hoa";
        }

        if (!preg_match('/[a-z]/', $data['newPassword'])) {
            $errors['newPassword'][] = "Mật khẩu phải có ít nhất 1 chữ thường";
        }

        if (!preg_match('/[0-9]/', $data['newPassword'])) {
            $errors['newPassword'][] = "Mật khẩu phải có ít nhất 1 chữ số";
        }

        if (!preg_match('/[\W]/', $data['newPassword'])) {
            $errors['newPassword'][] = "Mật khẩu phải có ít nhất 1 ký tự đặc biệt";
        }
        if ($data['newPassword'] != $data['ConfirmNewpassword']) {
            $errors['confirmPassword'][] = "Mật khẩu xác nhận không khớp";
        }
        if (strlen($data['newPassword']) < 6) {
            $errors['newPassword'][] = "Mật khẩu mới phải ít nhất 6 ký tự";
        }
        if (!password_verify($data['password'], $user['password'])) {
            $errors['password'][] = "sai mat khẩu cũ nhé mày!";
        }       
        if (strlen($data['newPassword']) == 0) {
            $errors['newPassword'] = "Nhập dữ liệu vào newPassword";
        }
        if (strlen($data['password']) == 0) {
            $errors['password'] = "Nhập dữ liệu vào password";
        }
        if (strlen($data['ConfirmNewpassword']) == 0) {
            $errors['confirmPassword'] = "Nhập dữ liệu vào ConfirmNewpassword";
        }
        
        if (!empty($errors)) {
            return [
                "status" => false,
                "errors" => $errors
            ];
        }
        
        $hash = password_hash($data['newPassword'], PASSWORD_DEFAULT);
        $this->model->updatePassword($user['id'], $hash);

        return [
            "status" => true,
            "message" => "Đổi mật khẩu thành công"
        ];
    }

// ==================================================================
        public function updateProfile($user,$data){
                $name = $data['name'] ?? $user['name'];
                $email = $data['email'] ?? $user['email'];
                $hostline = $data['hostline'] ?? $user['hostline'];
                $this->model->updateProfile($user['id'], $name, $email, $hostline);

            return ["status" => true, "message" => "Cập nhật thành công"];
        }

        public function deleteUser($data){
            $id = $data['id'] ?? '';
            $user = $this->model->getUserById($id);
            if(!$user){
                return [
                "status" => false,
                "message" => "User không tồn tại!"
                    ];
            }

            if($user['role'] === 'admin'){
                return [
                    "status" => false,
                    "message" => "Không thể xóa admin!"
                    ];
            }
            $this->model->deleteUser($data['id']);
            return ["status" => true, 
            "message" => "Xóa thành công",
            "user"=>$user];
        }

    }
?>