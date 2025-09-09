<?php
require_once 'core/Controller.php';
require_once 'models/Product.php';

class ProductController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function search() {
        $term = $_GET['q'] ?? '';
        $products = $this->productModel->search($term);
        
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function suggestions() {
        $term = $_GET['term'] ?? '';
        $products = $this->productModel->search($term);
        $suggestions = array_map(function($product) {
            return $product['name'];
        }, $products);
        
        header('Content-Type: application/json');
        echo json_encode(array_unique($suggestions));
    }
}
?>
