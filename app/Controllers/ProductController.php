<?php

namespace App\Controllers;

use App\Models\Product;
use Symfony\Component\Routing\RouteCollection;

class ProductController {
    // Show the product attributes based on the id.
    public function showAction(int $id, RouteCollection $routes) {

        $routeToHome =  $routes->get('homepage')->getPath();

        $product = new Product();
        if ($product->read($id) == null) {
            http_response_code(404);
            require_once APP_ROOT . '/views/404.php';
        } else {
            $product->read($id);
            require_once APP_ROOT . '/views/product.php';
        }

        // if ($product->read($id) == "Product not found.") {
        //     http_response_code(404);
        //     require_once APP_ROOT . '/views/404.php';
        // }

    }
}
