<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Chat;
use Symfony\Component\Routing\RouteCollection;

class ChatController {

    public function chat(RouteCollection $routes) {
        $routeToLogin = $routes->get('usersPage')->getPath();
        session_start();
        $user = new User();
        $chat = new Chat();

        if (isset($_SESSION['id'])) {
            if (isset($_GET['id'])) {

                $user->setUesrID(base64_decode($_GET['id']));
                $user = $user->getUserDetails();

                if ($user->emailAlreadyExist($user->getEmail())) {
                    require_once APP_ROOT . '/views/chat.php';
                } else {
                    header("Location: notFound?invalid_userID");
                }
            } else {
                header("Location: notFound?invalid_chatID");
            }
        } else {
            header("Location: login?session_expire");
        }
    }

    public function sendMessage(RouteCollection $routes) {
        session_start();
        $chat = new Chat();

        if (isset($_SESSION['id'])) {
            $chat->setOutgoingID($_SESSION['id']);
            $chat->setIncomingID($_POST['incomingID']);
            $chat->setMsg($_POST['message']);
            $chat->setCreatedOn(date('Y-m-d H:i:s'));
            $chat->setIsDeleted(0);

            if ($chat->insertSendMessage()) {
                echo "Inserted";
            } else {
                echo "Failed";
            }
        } else {
            header("Location: login"); //TODO: logout
        }
    }

    public function getMessages(RouteCollection $routes) {
        session_start();
        $chatModel = new Chat();

        if (isset($_SESSION['id'])) {
            $chatModel->setOutgoingID($_SESSION['id']);
            $chatModel->setIncomingID($_POST['incomingID']);

            $chats = $chatModel->getMessages();

            header('Content-Type: application/json');
            $data = [];
            foreach ($chats as $chat) {
                $data[] = $chat->objectToArray();
            }
            echo json_encode($data);
        } else {
            header("Location: login"); //TODO: logout
        }
    }
}
