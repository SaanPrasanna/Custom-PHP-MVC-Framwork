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
}
