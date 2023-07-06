<?php

namespace App\Controllers;

use App\Models\Product;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController {
	// Homepage action
	public function indexAction(RouteCollection $routes) {
		$routeToProduct = str_replace('{id}', 2, $routes->get('product')->getPath());
		$routeToHome =  $routes->get('homepage')->getPath();

		$productModel = new Product();
		$products = $productModel->allProduct();

		$data = [];
		foreach ($products as $product) {
			$data[] = $product->objectToArray();
		}
		// var_dump($data);
		require_once APP_ROOT . '/views/home.php';
	}

	public function getProductListAction(RouteCollection $routes) {
		$productModel = new Product();
		$products = $productModel->allProduct();

		header('Content-Type: application/json');
		$data = [];
		foreach ($products as $product) {
			$data[] = $product->objectToArray();
		}
		echo json_encode($data);
		exit;
	}

	public function testingPost(RouteCollection $routes) {
		$name = $_POST['name'];

		header('Content-Type: application/json');
		echo json_encode($name);
		exit;
	}
}
