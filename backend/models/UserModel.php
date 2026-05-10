<?php

use LDAP\Result;

    class UserModel{
        private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }
    public function getAllUsers() {
        $result = mysqli_query($this->conn, "SELECT * FROM users");

        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        return $users;
    }
    public function findByToken($token) {
        $sql = "SELECT * FROM users WHERE remember_token = '$token'";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }
    
    public function updatePassword($id,$has){
        $sql = "update users set password = '$has' where id = '$id'";
        $result = mysqli_query($this->conn,$sql);
        return $result;
    }
    public function updateProfile($id, $name, $email, $hostline){
        $sql = "update users set
        username = '$name',
        email = '$email',
        hostline = '$hostline'
        where id = '$id'";
        $query = mysqli_query($this->conn,$sql);
        return $query;
    }
    }
    
?>