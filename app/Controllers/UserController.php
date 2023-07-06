<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;
use Symfony\Component\Routing\RouteCollection;

class UserController {
    public function indexAction(RouteCollection $routes) {
        $routeToHome =  $routes->get('homepage')->getPath();
        $routeToRegister =  $routes->get('registerPage')->getPath();

        $userModel = new User();

        require_once APP_ROOT . '/views/auth/login.php';
    }

    public function userRegister(RouteCollection $routes) {
        $routeToLogin =  $routes->get('loginPage')->getPath();
        $userModel = new User();

        require_once APP_ROOT . '/views/auth/register.php';
    }

    public function showUsers(RouteCollection $routes) {
        require_once APP_ROOT . '/views/users.php';
    }

    public function registerUser(RouteCollection $routes) {
        if (isset($_FILES['image'])) {
            $msg = "";

            $img_name = $_FILES['image']['name'];
            $img_type = $_FILES['image']['type'];
            $tmp_name = $_FILES['image']['tmp_name'];

            $img_explode = explode('.', $img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if (in_array($img_ext, $extensions) && in_array($img_type, ["image/jpeg", "image/jpg", "image/png"])) {
                $destinationDirectory = APP_ROOT . "/asserts/img/";
                $new_img_name = time() . $img_name;
                $destinationPath = $destinationDirectory . $new_img_name;

                if (move_uploaded_file($tmp_name, $destinationPath)) {

                    $user = new User();
                    $user->setUesrID(rand(time(), 100000000));
                    $user->setFname($_POST['fname']);
                    $user->setLname($_POST['lname']);
                    $user->setEmail($_POST['email']);
                    $user->setPassword(sha1($_POST['password']));
                    $user->setImage($new_img_name);
                    $user->setToken(bin2hex(random_bytes(16)));
                    $user->setActiveStatus("Disabled");
                    $user->setStatus("Offline");

                    if (!$user->emailAlreadyExist()) {
                        $user->saveUser();
                        $msg = "Success"; //Please check you mail inbox to verity Email
                    } else { //ykhqiqsptadzozbk
                        $msg = "Email already exist!";
                    }
                } else {
                    $msg = "Failed to move the uploaded image";
                }
            } else {
                $msg = "Please upload an image file - jpeg, png, jpg";
            }
            echo json_encode(["message" => $msg]);
        }
    }
}
