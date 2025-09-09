<?php
require_once 'core/Controller.php';
require_once 'models/Cart.php';

class ApiController extends Controller {
    private $cartModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
    }
    
    public function getCartCount() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['count' => 0]);
            return;
        }
        
        $count = $this->cartModel->getCartItemCount($_SESSION['user_id']);
        echo json_encode(['count' => $count]);
    }
    
    public function getNotifications() {
        // For now, return empty notifications
        echo json_encode(['notifications' => []]);
    }
}
