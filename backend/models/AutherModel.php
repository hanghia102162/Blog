<?php
    class AutherModel{
        private $conn;

    public function __construct($conn){
            return $this->conn = $conn;
    }
    public function findUserByEmail($email){
        $sql = "select * from users where email = '$email'";
        $query = mysqli_query($this->conn,$sql);
        if (!$query || mysqli_num_rows($query) === 0) {
            return null;
        }
        return mysqli_fetch_assoc($query);
    }
    public function updateToken($id, $token){
        $sql = "update users set remember_token = '$token' where id = '$id'";
        $query = mysqli_query($this->conn,$sql);
        return $query;
    }
    public function createUser($username,$email,$hash){
        $sql = "INSERT INTO users(username,email,password)
            VALUES('$username','$email','$hash')";
        $query = mysqli_query($this->conn,$sql);
        return $query;
    }   
    public function updatePassword($email,$newPassword){
        $sql ="update users set password = '$newPassword' where email = '$email'";
        $query = mysqli_query($this->conn,$sql);
        return $query;
    }
    }
?>