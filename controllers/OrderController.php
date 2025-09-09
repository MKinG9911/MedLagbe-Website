<?php
require_once 'core/Controller.php';
require_once 'models/Order.php';
require_once 'models/Cart.php';
require_once 'core/Mailer.php';

class OrderController extends Controller {
    private $orderModel;
    private $cartModel;

    public function __construct() {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    public function checkout() {
        $this->requireAuth();
        
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        $total = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        if (empty($cartItems)) {
            $this->redirect('cart');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prescriptionFile = null;
            
            // Handle prescription upload
            if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] === 0) {
                $uploadDir = UPLOAD_PATH . 'prescriptions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = uniqid() . '_' . $_FILES['prescription']['name'];
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['prescription']['tmp_name'], $uploadPath)) {
                    $prescriptionFile = $fileName;
                }
            }
            
            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'total_amount' => $total,
                'delivery_address' => $_POST['address'],
                'payment_method' => $_POST['payment_method'],
                'prescription_file' => $prescriptionFile,
                'discount_amount' => $_POST['discount_amount'] ?? 0
            ];
            
            $orderId = $this->orderModel->create($orderData);
            
            if ($orderId) {
                // Add order items
                $orderItems = [];
                foreach ($cartItems as $item) {
                    $orderItems[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ];
                }
                
                $this->orderModel->addOrderItems($orderId, $orderItems);
                
                // Clear cart
                $this->cartModel->clearCart($_SESSION['user_id']);
                
                // Send notification email and set flash message
                $this->sendOrderNotification($_SESSION['user_email'], $orderId, $total);
                $this->setFlash('Order has been confirmed. Please check your email for the details.', 'success');
                $this->redirect('user/orders');
            } else {
                $error = "Failed to place order";
            }
        }
        
        $this->view('user/checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'error' => $error ?? null
        ]);
    }

    private function sendOrderNotification($email, $orderId, $total) {
        if (!$email) return;
        $subject = 'Your MedLagbe Order Confirmation';
        $html = '<h2>Thank you for your purchase!</h2>' .
                '<p>Your order has been confirmed.</p>' .
                '<p><strong>Order ID:</strong> #' . htmlspecialchars((string)$orderId) . '</p>' .
                '<p><strong>Total amount:</strong> ৳' . number_format((float)$total, 2) . '</p>' .
                '<p>We will notify you when your order ships.</p>' .
                '<p class="muted">You can view your order anytime from your account.</p>';
        Mailer::sendHtml($email, $subject, $html);
    }
}
?>
