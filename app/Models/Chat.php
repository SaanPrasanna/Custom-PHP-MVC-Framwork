<?php

namespace App\Models;

use PDOException;
use PDO;

class Chat {
    protected $chatID;
    protected $incomingID;
    protected $outgoingID;
    protected $msg;
    protected $createdOn;
    protected $isDeleted;
    protected $image;
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function getIncomingID() {
        return $this->incomingID;
    }

    public function getOutgoingID() {
        return $this->outgoingID;
    }

    public function getMsg() {
        return $this->msg;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getIsDeleted() {
        return $this->isDeleted;
    }

    public function getChatID() {
        return $this->chatID;
    }

    public function getImage() {
        return $this->image;
    }

    public function setIncomingID($incomingID) {
        $this->incomingID = $incomingID;
    }

    public function setOutgoingID($outgoingID) {
        $this->outgoingID = $outgoingID;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    public function setIsDeleted($isDeleted) {
        $this->isDeleted = $isDeleted;
    }

    public function setChatID($chatID) {
        $this->chatID = $chatID;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function insertSendMessage() {
        $query = "INSERT INTO chats (incomingID, outgoingID, message, createdOn, isDeleted) VALUES (:incomingID, :outgoingID, :message, :createdOn, :isDeleted);";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":incomingID", $this->incomingID, PDO::PARAM_INT);
        $stmt->bindParam(":outgoingID", $this->outgoingID, PDO::PARAM_INT);
        $stmt->bindParam(":message", $this->msg, PDO::PARAM_STR);
        $stmt->bindParam(":createdOn", $this->createdOn, PDO::PARAM_STR);
        $stmt->bindParam(":isDeleted", $this->isDeleted, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessages() {
        $chats = [];
        // $query = "SELECT DISTINCT chatID, incomingID, outgoingID, message, createdOn, isDeleted FROM chats AS c, users AS u WHERE c.outgoingID = u.userID AND c.outgoingID = :outgoingID AND c.incomingID = :incomingID 
        // OR c.outgoingID = :incomingID AND c.incomingID = :outgoingID ORDER BY c.chatID;";
        $query = "SELECT * FROM chats AS c LEFT JOIN users AS u ON u.userID = c.outgoingID WHERE c.outgoingID = :outgoingID AND c.incomingID = :incomingID
        OR c.outgoingID = :incomingID AND c.incomingId = :outgoingID ORDER BY c.chatID;";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":outgoingID", $this->outgoingID, PDO::PARAM_INT);
        $stmt->bindParam(":incomingID", $this->incomingID, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $chat = new Chat();
                $chat->setChatID($row['chatID']);
                $chat->setIncomingID($row['incomingID']);
                $chat->setOutgoingID($row['outgoingID']);
                $chat->setMsg($row['message']);
                $chat->setCreatedOn($row['createdOn']);
                $chat->setIsDeleted($row['isDeleted']);
                $chat->setImage($row['image']);
                $chats[] = $chat;
            }
        }
        return $chats;
    }

    // Convert Chat object into array
    public function objectToArray() {
        $array = [
            'chatID' => $this->chatID,
            'incomingID' => $this->incomingID,
            'outgoingID' => $this->outgoingID,
            'message' => $this->msg,
            'createdOn' => $this->createdOn,
            'isDeleted' => $this->isDeleted,
            'image' => $this->image
        ];

        return $array;
    }
}
