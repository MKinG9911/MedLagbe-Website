<?php
require_once 'core/Controller.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'models/Order.php';

class UserController extends Controller {
    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->orderModel = new Order();
    }

    public function home() {
        $products = $this->productModel->findAll();
        $this->view('user/home', ['products' => $products]);
    }

    public function products() {
        $search = $_GET['search'] ?? '';
        $filters = [
            'category' => $_GET['category'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? ''
        ];
        
        if ($search) {
            $products = $this->productModel->search($search, $filters);
        } else {
            $products = $this->productModel->findAll();
        }
        
        $this->view('user/products', [
            'products' => $products,
            'search' => $search,
            'filters' => $filters
        ]);
    }

    public function productDetail() {
        $id = $_GET['id'] ?? 0;
        $product = $this->productModel->getWithReviews($id);
        $reviews = $this->productModel->getReviews($id);
        
        if (!$product) {
            $this->redirect('user/products');
        }
        
        $this->view('user/product_detail', [
            'product' => $product,
            'reviews' => $reviews
        ]);
    }

    public function profile() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            
            if ($this->userModel->update($_SESSION['user_id'], $data)) {
                $_SESSION['user_name'] = $data['name'];
                $success = "Profile updated successfully";
            } else {
                $error = "Failed to update profile";
            }
        }
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        $orders = $this->orderModel->getUserOrdersWithItems($_SESSION['user_id']);
        
        $this->view('user/profile', [
            'user' => $user,
            'orders' => $orders,
            'success' => $success ?? null,
            'error' => $error ?? null
        ]);
    }

    public function orders() {
        $this->requireAuth();
        
        $orders = $this->orderModel->getUserOrdersWithItems($_SESSION['user_id']);
        $flash = $this->getFlash();
        $this->view('user/orders', [
            'orders' => $orders,
            'flash' => $flash
        ]);
    }

    public function orderDetail() {
        $this->requireAuth();
        
        $orderId = $_GET['id'] ?? 0;
        $orderItems = $this->orderModel->getOrderWithItems($orderId);
        
        if (!$orderItems || $orderItems[0]['user_id'] != $_SESSION['user_id']) {
            $this->redirect('user/orders');
        }
        
        $this->view('user/order_detail', ['orderItems' => $orderItems]);
    }

    public function trackOrder() {
        $this->requireAuth();
        
        $orderId = $_GET['id'] ?? 0;
        $orderItems = $this->orderModel->getOrderWithItems($orderId);
        
        if (!$orderItems || $orderItems[0]['user_id'] != $_SESSION['user_id']) {
            $this->redirect('user/orders');
        }
        
        $this->view('user/track_order', ['orderItems' => $orderItems]);
    }

    public function downloadInvoice() {
        $this->requireAuth();
        
        $orderId = $_GET['id'] ?? 0;
        $orderItems = $this->orderModel->getOrderWithItems($orderId);
        
        if (!$orderItems || $orderItems[0]['user_id'] != $_SESSION['user_id']) {
            $this->redirect('user/profile');
        }
        
        // Generate simple HTML invoice
        $order = $orderItems[0];
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        $html = $this->generateInvoiceHTML($order, $orderItems, $user);
        
        // Set headers for PDF download
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="invoice-' . $orderId . '.html"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo $html;
        exit();
    }

    private function generateInvoiceHTML($order, $orderItems, $user) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice #' . $order['id'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .invoice-header { text-align: center; margin-bottom: 30px; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .customer-info, .invoice-info { flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .total { text-align: right; font-weight: bold; font-size: 18px; }
        .footer { text-align: center; margin-top: 50px; color: #666; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>MedLagbe - Online Pharmacy</h1>
        <h2>INVOICE</h2>
    </div>
    
    <div class="invoice-details">
        <div class="customer-info">
            <h3>Bill To:</h3>
            <p><strong>' . htmlspecialchars($user['name']) . '</strong><br>
            ' . htmlspecialchars($user['address']) . '<br>
            Phone: ' . htmlspecialchars($user['phone']) . '<br>
            Email: ' . htmlspecialchars($user['email']) . '</p>
        </div>
        <div class="invoice-info">
            <h3>Invoice Details:</h3>
            <p><strong>Invoice #:</strong> ' . $order['id'] . '<br>
            <strong>Date:</strong> ' . date('M d, Y', strtotime($order['created_at'])) . '<br>
            <strong>Status:</strong> ' . ucfirst($order['status']) . '</p>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($orderItems as $item) {
            $html .= '<tr>
                <td>' . htmlspecialchars($item['product_name']) . '</td>
                <td>' . $item['quantity'] . '</td>
                <td>৳' . number_format($item['item_price'], 2) . '</td>
                <td>৳' . number_format($item['item_price'] * $item['quantity'], 2) . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
    </table>
    
    <div class="total">
        <p>Total Amount: ৳' . number_format($order['total_amount'], 2) . '</p>
    </div>
    
    <div class="footer">
        <p>Thank you for your purchase!<br>
        For any queries, please contact our support team.</p>
    </div>
</body>
</html>';
        
        return $html;
    }
}
?>
