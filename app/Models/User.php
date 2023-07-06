<?php

namespace App\Models;

use PDOException;
use PDO;

class User {
    protected $userID;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password;
    protected $image;
    protected $status;
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function getUserID() {
        return $this->userID;
    }

    public function getFname() {
        return $this->fname;
    }

    public function getLname() {
        return $this->lname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getImage() {
        return $this->image;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setUesrID($userID) {
        $this->userID = $userID;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setLname($lname) {
        $this->lname = $lname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function emailAlreadyExist($email) {
        $query = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertUser() {
        $query = "INSERT INTO users(userID, fname, lanme, email, password, image, status) 
        VALUES (:userID, :fname, :lname, :email, :password, :image, :status)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        $stmt->bindParam(":fname",  $this->fname, PDO::PARAM_STR);
        $stmt->bindParam(":lname", $this->lname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->bindParam(":image", $this->image, PDO::PARAM_STR);
        $stmt->bindParam(":status", "Offline", PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
