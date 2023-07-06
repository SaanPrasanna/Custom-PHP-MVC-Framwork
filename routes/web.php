<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;


// Routes system
$routes = new RouteCollection();
$routes->add('loginPage', new Route(constant('URL_SUBFOLDER') . '/', array('controller' => 'UserController', 'method' => 'indexAction'), array()));
$routes->add('registerPage', new Route(constant('URL_SUBFOLDER') . '/register', array('controller' => 'UserController', 'method' => 'userRegister'), array()));
$routes->add('homepage', new Route(constant('URL_SUBFOLDER') . '/temp', array('controller' => 'HomeController', 'method' => 'indexAction'), array()));
$routes->add('product', new Route(constant('URL_SUBFOLDER') . '/product/{id}', array('controller' => 'ProductController', 'method' => 'showAction'), array('id' => '[0-9]+')));
$routes->add('test', new Route(constant('URL_SUBFOLDER') . '/test', array('controller' => 'HomeController', 'method' => 'getProductListAction'), array()));