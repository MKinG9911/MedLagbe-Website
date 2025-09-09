<?php
require_once 'core/Controller.php';
require_once 'models/Cart.php';

class CartController extends Controller {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    public function index() {
        $this->requireAuth();
        
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        $this->view('user/cart', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function add() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'] ?? 1;
            
            if ($this->cartModel->addItem($_SESSION['user_id'], $productId, $quantity)) {
                echo json_encode(['success' => true, 'message' => 'Item added to cart']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add item']);
            }
        }
    }

    public function update() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];
            
            if ($this->cartModel->updateQuantity($_SESSION['user_id'], $productId, $quantity)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function remove() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            
            if ($this->cartModel->removeItem($_SESSION['user_id'], $productId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }
}
?>
