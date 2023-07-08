<?php

namespace App\Models;

use PDOException;
use PDO;

class Chat {
    protected $incomingID;
    protected $outgoingID;
    protected $msg;
    protected $createdOn;
    protected $isDeleted;
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
        // $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
        // WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
        // OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = "SELECT * FROM chats AS c, users AS u WHERE c.outgoingID = u.userID AND c.outgoingID = :outgoingID AND c.incomingID = :incomingID 
        OR c.outgoingID = :incomingID AND c.incomingID = :outgoingID ORDER BY c.chatID;";
    }
}
