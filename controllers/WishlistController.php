<?php
require_once 'core/Controller.php';

class WishlistController extends Controller {
    
    public function index() {
        // For now, redirect to products page
        $this->redirect('user/products');
    }
    
    public function add() {
        // For now, redirect to products page
        $this->redirect('user/products');
    }
    
    public function remove() {
        // For now, redirect to products page
        $this->redirect('user/products');
    }
}
