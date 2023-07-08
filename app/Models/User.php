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
    protected $token;
    protected $activeStatus;
    protected $msg;
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

    public function getToken() {
        return $this->token;
    }

    public function getActiveStatus() {
        return $this->activeStatus;
    }

    public function getMsg() {
        return $this->msg;
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

    public function setToken($token) {
        $this->token = $token;
    }

    public function setActiveStatus($activeStatus) {
        $this->activeStatus = $activeStatus;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function emailAlreadyExist() {
        $query = "SELECT * FROM users WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_ASSOC) == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function saveUser() {
        $query = "INSERT INTO users(userID, fname, lname, email, password, image, token, activeStatus, status) 
        VALUES (:userID, :fname, :lname, :email, :password, :image, :token, :activeStatus, :status)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        $stmt->bindParam(":fname",  $this->fname, PDO::PARAM_STR);
        $stmt->bindParam(":lname", $this->lname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->bindParam(":image", $this->image, PDO::PARAM_STR);
        $stmt->bindParam(":token", $this->token, PDO::PARAM_STR);
        $stmt->bindParam(":activeStatus", $this->activeStatus, PDO::PARAM_STR);
        $stmt->bindParam(":status", $this->status, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserDetails() {
        $user = new User();
        $query = "SELECT * FROM users WHERE userID = :userID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user->setUesrID($row['userID']);
            $user->setFname($row['fname']);
            $user->setLname($row['lname']);
            $user->setEmail($row['email']);
            $user->setImage($row['image']);
            $user->setStatus($row['status']);
        }
        return $user;
    }

    public function allUsers() {
        $users = [];
        $msg = "";

        $query = "SELECT * FROM users WHERE NOT userID = :userID AND activeStatus = :activeStatus ORDER BY userID ASC;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $this->userID);
        $stmt->bindParam(":activeStatus", $this->activeStatus);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User();
                $user->setUesrID($row['userID']);
                $user->setFname($row['fname']);
                $user->setLname($row['lname']);
                $user->setEmail($row['email']);
                $user->setImage($row['image']);
                $user->setStatus($row['status']);

                $innerQuery = "SELECT * FROM chats AS c LEFT JOIN users AS u ON u.userID = c.outgoingID WHERE (c.outgoingID = :outgoingID AND c.incomingID = :incomingID)
                OR (c.outgoingID = :incomingID AND c.incomingId = :outgoingID) ORDER BY c.chatID DESC LIMIT 1;";
                $innerStmt = $this->conn->prepare($innerQuery);
                $innerStmt->bindParam(":outgoingID", $this->userID);
                $innerStmt->bindParam(":incomingID", $row['userID']);
                $innerStmt->execute();
                if ($innerStmt->rowCount() > 0) {
                    $chat = $innerStmt->fetch(PDO::FETCH_ASSOC);
                    $msg = $chat['message'];
                } else {
                    $msg = "Not chat Yet, say Hello😊!";
                }
                (strlen($msg) > 30) ? $user->setMsg(substr($msg, 0, 30) . '...') : $user->setMsg($msg);


                $users[] = $user;
            }
        }
        return $users;
    }

    public function searchUsers() {
        $users = [];
        $msg = "";

        $query = "SELECT * FROM users WHERE NOT userID = :userID AND activeStatus = :activeStatus AND (fname LIKE CONCAT('%', :fname, '%') OR lname LIKE CONCAT('%', :fname, '%'))";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $this->userID);
        $stmt->bindParam(":fname", $this->fname);
        $stmt->bindParam(":activeStatus", $this->activeStatus);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = new User();
                $user->setUesrID($row['userID']);
                $user->setFname($row['fname']);
                $user->setLname($row['lname']);
                $user->setEmail($row['email']);
                $user->setImage($row['image']);
                $user->setStatus($row['status']);

                $innerQuery = "SELECT * FROM chats AS c LEFT JOIN users AS u ON u.userID = c.outgoingID WHERE (c.outgoingID = :outgoingID AND c.incomingID = :incomingID)
                OR (c.outgoingID = :incomingID AND c.incomingId = :outgoingID) ORDER BY c.chatID DESC LIMIT 1;";
                $innerStmt = $this->conn->prepare($innerQuery);
                $innerStmt->bindParam(":outgoingID", $this->userID);
                $innerStmt->bindParam(":incomingID", $row['userID']);
                $innerStmt->execute();
                if ($innerStmt->rowCount() > 0) {
                    $chat = $innerStmt->fetch(PDO::FETCH_ASSOC);
                    $msg = $chat['message'];
                } else {
                    $msg = "Not chat Yet, say Hello😊!";
                }
                (strlen($msg) > 30) ? $user->setMsg(substr($msg, 0, 30) . '...') : $user->setMsg($msg);


                $users[] = $user;
            }
        }
        return $users;
    }

    public function objectToArray() {
        $array = [
            'userID' => $this->userID,
            'fname' => $this->fname,
            'lname' => $this->lname,
            'email' => $this->email,
            'image' => $this->image,
            'status' => $this->status,
            'message' => $this->msg
        ];

        return $array;
    }

    public function changeStatus() {
        $query = "UPDATE users SET status = :status WHERE userID = :userID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $this->status, PDO::PARAM_STR);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyEmail() {
        $query = "UPDATE users SET token = :token, activeStatus = :activeStatus WHERE userID = :userID";

        $stmt = $this->conn->prepare($query);
        $this->setToken("");
        $stmt->bindParam(":token", $this->token, PDO::PARAM_STR);
        $stmt->bindParam(":activeStatus", $this->activeStatus, PDO::PARAM_STR);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function isValidToken() {
        $query = "SELECT * FROM users WHERE token = :token AND userID = :userID";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $this->token, PDO::PARAM_STR);
        $stmt->bindParam(":userID", $this->userID, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function authentication() {
        $query = "SELECT * FROM users WHERE email = :email AND password = :password;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isVerifiedUser() {
        $query = "SELECT * FROM users WHERE email = :email AND activeStatus = :activeStatus;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->bindParam(":activeStatus", $this->activeStatus, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getNewToken() {
        $query = "UPDATE users SET token = :token WHERE email = :email;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $this->token, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function findUserID() {
        $query = "SELECT * FROM users  WHERE email = :email;";
        $user = new User();

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user->setUesrID($row['userID']);
            $user->setFname($row['fname']);
            $user->setLname($row['lname']);
            $user->setEmail($row['email']);
            $user->setImage($row['image']);
        }
        return $user;
    }

    public function resetPassword() {
        $query = "UPDATE users SET password = :password WHERE email = :email;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
        $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
