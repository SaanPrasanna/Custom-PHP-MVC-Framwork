<?php

namespace App\Models;

use PDOException;
use PDO;

class Product {
	protected $id;
	protected $title;
	protected $description;
	protected $price;
	protected $sku;
	protected $image;
	protected $msg;
	protected $conn;

	public function __construct() {
		try {
			$this->conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
		} catch (PDOException $e) {
			die('Connection failed: ' . $e->getMessage());
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getSku() {
		return $this->sku;
	}

	public function getImage() {
		return $this->image;
	}
	public function getMsg() {
		return $this->msg;
	}

	public function setMsg(string $msg) {
		$this->msg = $msg;
	}

	public function setId(string $id) {
		$this->id = $id;
	}

	public function setTitle(string $title) {
		$this->title = $title;
	}

	public function setDescription(string $description) {
		$this->description = $description;
	}

	public function setPrice(string $price) {
		$this->price = $price;
	}

	public function setSku(string $sku) {
		$this->sku = $sku;
	}

	public function setImage(string $image) {
		$this->image = $image;
	}

	public function read(int $id) {
		$query = "SELECT * FROM products WHERE id = :id";

		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			$this->id = $row['id'];
			$this->title = $row['title'];
			$this->description = $row['description'];
			$this->price = $row['price'];
			$this->sku = $row['sku'];
			$this->image = $row['image'];
			return $this;
		} else {
			return null;
		}
	}


	public function allProduct() {
		$products = [];
		$query = "SELECT * FROM products";

		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$product = new Product();
				$product->setID($row['id']);
				$product->setTitle($row['title']);
				$product->setDescription($row['description']);
				$product->setPrice($row['price']);
				$product->setSku($row['sku']);
				$product->setImage($row['image']);

				$products[] = $product;
			}
		}
		return $products;
	}

	public function objectToArray() {
		$array = [
			'id' => $this->id,
			'title' => $this->title,
			'description' => $this->description,
			'price' => $this->price,
			'sku' => $this->sku,
			'image' => $this->image,
		];

		return $array;
	}

	public function create(array $data) {
	}

	public function update(int $id, array $data) {
	}

	public function delete(int $id) {
	}
}
