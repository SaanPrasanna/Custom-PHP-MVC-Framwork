<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;

$routes = new RouteCollection();
//Routers for Auth
$routes->add('loginPage', new Route(constant('URL_SUBFOLDER') . '/login', array('controller' => 'UserController', 'method' => 'indexAction'), array()));
$routes->add('loginUser', new Route(constant('URL_SUBFOLDER') . '/loginUser', array('controller' => 'UserController', 'method' => 'userAuthentication'), array()));
$routes->add('registerPage', new Route(constant('URL_SUBFOLDER') . '/register', array('controller' => 'UserController', 'method' => 'userRegister'), array()));
$routes->add('registerUser', new Route(constant('URL_SUBFOLDER') . '/registerUesr', array('controller' => 'UserController', 'method' => 'registerUser'), array()));
$routes->add('verifyUser', new Route(constant('URL_SUBFOLDER') . '/verify', array('controller' => 'UserController', 'method' => 'verifyUser'), array()));
$routes->add('forgotPage', new Route(constant('URL_SUBFOLDER') . '/forgot', array('controller' => 'UserController', 'method' => 'forgotPassword'), array()));
$routes->add('forgot', new Route(constant('URL_SUBFOLDER') . '/forgotPassword', array('controller' => 'UserController', 'method' => 'forgot'), array()));
$routes->add('reest', new Route(constant('URL_SUBFOLDER') . '/reset', array('controller' => 'UserController', 'method' => 'reset'), array()));
$routes->add('resetPassword', new Route(constant('URL_SUBFOLDER') . '/resetPassword', array('controller' => 'UserController', 'method' => 'resetPassword'), array()));

$routes->add('usersPage', new Route(constant('URL_SUBFOLDER') . '/users', array('controller' => 'UserController', 'method' => 'usersPage'), array()));
$routes->add('getAllUsers', new Route(constant('URL_SUBFOLDER') . '/allUsers', array('controller' => 'UserController', 'method' => 'allUsers'), array()));

$routes->add('chatPage', new Route(constant('URL_SUBFOLDER') . '/chat', array('controller' => 'ChatController', 'method' => 'chat'), array()));
$routes->add('sendMessage', new Route(constant('URL_SUBFOLDER') . '/sendMessage', array('controller' => 'ChatController', 'method' => 'sendMessage'), array()));

$routes->add('404', new Route(constant('URL_SUBFOLDER') . '/notFound', array('controller' => 'UserController', 'method' => 'notFound'), array()));


$routes->add('product', new Route(constant('URL_SUBFOLDER') . '/product/{id}', array('controller' => 'ProductController', 'method' => 'showAction'), array('id' => '[0-9]+')));
$routes->add('test', new Route(constant('URL_SUBFOLDER') . '/test', array('controller' => 'HomeController', 'method' => 'getProductListAction'), array()));
$routes->add('login', new Route(constant('URL_SUBFOLDER') . '/', array('controller' => 'UserController', 'method' => 'indexAction'), array()));