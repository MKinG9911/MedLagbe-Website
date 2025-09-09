<?php
require_once 'core/Controller.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/User.php';
require_once 'models/Prescription.php';
require_once 'core/Mailer.php';

class AdminController extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;
    private $prescriptionModel;

    public function __construct() {
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->prescriptionModel = new Prescription();
    }

    public function dashboard() {
        $this->requireAdmin();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        $recentOrders = $this->orderModel->getAllOrders();
        $lowStockProducts = $this->getLowStockProducts();
        
        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts
        ]);
    }

    public function prescriptions() {
        $this->requireAdmin();
        // Show pending prescriptions; view exists as views/admin/prescription.php
        $pending = $this->prescriptionModel->getAllPending();
        $this->view('admin/prescription', ['prescriptions' => $pending]);
    }

    public function verifyPrescription() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/prescriptions');
        }

        $prescriptionId = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null; // 'approved' or 'rejected'
        if (!$prescriptionId || !$status) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            return;
        }

        $db = (new Database())->getConnection();
        // Get prescription + user info for email
        $stmt = $db->prepare("SELECT p.*, u.email, u.name, o.order_number FROM prescriptions p JOIN users u ON p.user_id = u.id LEFT JOIN orders o ON p.order_id = o.id WHERE p.id = ?");
        $stmt->execute([$prescriptionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $ok = $this->prescriptionModel->updateStatus($prescriptionId, $status);
        if ($ok && $row && !empty($row['email'])) {
            $email = $row['email'];
            $name = $row['name'] ?? 'Customer';
            $orderNumber = $row['order_number'] ?? '';
            if ($status === 'approved') {
                $subject = 'Your prescription has been approved';
                $html = '<h2>Prescription Approved</h2>' .
                        '<p>Hi ' . htmlspecialchars($name) . ',</p>' .
                        '<p>Your prescription for order <strong>' . htmlspecialchars($orderNumber) . '</strong> has been approved. We will proceed with your order.</p>';
                Mailer::sendHtml($email, $subject, $html);
            } elseif ($status === 'rejected') {
                $subject = 'Your prescription could not be approved';
                $html = '<h2>Prescription Not Approved</h2>' .
                        '<p>Hi ' . htmlspecialchars($name) . ',</p>' .
                        '<p>We could not approve your prescription for order <strong>' . htmlspecialchars($orderNumber) . '</strong>. Please contact support or upload a valid prescription.</p>';
                Mailer::sendHtml($email, $subject, $html);
            }
        }

        echo json_encode(['success' => (bool)$ok]);
    }

    public function products() {
        $this->requireAdmin();
        
        $products = $this->productModel->findAll();
        $this->view('admin/products', ['products' => $products]);
    }

    public function addProduct() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'brand' => $_POST['brand'] ?? '',
                'category_id' => $_POST['category_id'] ?? null,
                'dosage' => $_POST['dosage'] ?? '',
                'ingredients' => $_POST['ingredients'] ?? '',
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'prescription_required' => isset($_POST['prescription_required']) ? 1 : 0,
                'stock_quantity' => $_POST['stock_quantity'] ?? 0,
                'image' => 'placeholder.jpg',
                'status' => 'active'
            ];
            
            // Handle image upload (store where views expect: public/images/products)
            if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === 0) {
                $uploadDir = 'public/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $originalName = basename($_FILES['image']['name']);
                $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
                $safeExt = strtolower(preg_replace('/[^a-z0-9]/i', '', $fileExt));
                $fileName = uniqid('prod_') . ($safeExt ? ('.' . $safeExt) : '');
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $data['image'] = $fileName;
                }
            }
            
            try {
                if ($this->productModel->create($data)) {
                    $this->redirect('admin/products');
                } else {
                    $error = "Failed to add product";
                }
            } catch (Exception $e) {
                $error = 'Error creating product: ' . $e->getMessage();
            }
        }
        
        $this->view('admin/add_product', ['error' => $error ?? null]);
    }

    public function orders() {
        $this->requireAdmin();
        
        $orders = $this->orderModel->getAllOrders();
        $this->view('admin/orders', ['orders' => $orders]);
    }

    public function updateOrderStatus() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            
            if ($this->orderModel->updateStatus($orderId, $status)) {
                // Fetch user email for the order and send notification
                $db = (new Database())->getConnection();
                $stmt = $db->prepare("SELECT u.email, u.name, o.order_number FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
                $stmt->execute([$orderId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && !empty($row['email'])) {
                    $email = $row['email'];
                    $name = $row['name'] ?? 'Customer';
                    $orderNumber = $row['order_number'] ?? ('#' . $orderId);
                    $subject = 'Your order status was updated: ' . ucfirst($status);
                    $html = '<h2>Order Status Update</h2>' .
                            '<p>Hi ' . htmlspecialchars($name) . ',</p>' .
                            '<p>Your order <strong>' . htmlspecialchars($orderNumber) . '</strong> status is now <strong>' . htmlspecialchars(ucfirst($status)) . '</strong>.</p>' .
                            '<p>You can track this order from your account.</p>';
                    Mailer::sendHtml($email, $subject, $html);
                }
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function users() {
        $this->requireAdmin();
        
        $users = $this->userModel->findAll();
        $this->view('admin/users', ['users' => $users]);
    }

    public function deleteUser() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id && $this->userModel->delete($id)) {
                echo json_encode(['success' => true]);
                return;
            }
            echo json_encode(['success' => false]);
            return;
        }
        $this->redirect('admin/users');
    }

    public function editUser() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/users');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
            ];

            $success = $this->userModel->update($id, $data);

            if ($success && !empty($_POST['password'])) {
                $success = $this->userModel->updatePassword($id, $_POST['password']);
            }

            if ($success) {
                $this->redirect('admin/users');
            } else {
                $error = 'Failed to update user';
            }
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->redirect('admin/users');
        }

        $this->view('admin/edit_user', [
            'user' => $user,
            'error' => $error ?? null
        ]);
    }

    public function editProduct() {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('admin/products');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'brand' => $_POST['brand'] ?? '',
                'category_id' => $_POST['category_id'] ?? null,
                'dosage' => $_POST['dosage'] ?? '',
                'ingredients' => $_POST['ingredients'] ?? '',
                'expiry_date' => $_POST['expiry_date'] ?? '',
                'prescription_required' => isset($_POST['prescription_required']) ? 1 : 0,
                'stock_quantity' => $_POST['stock_quantity'] ?? 0,
                'image' => $_POST['current_image'] ?? 'placeholder.jpg',
                'status' => $_POST['status'] ?? 'active'
            ];

            // Handle optional image upload
            if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === 0) {
                $uploadDir = 'public/images/products/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $originalName = basename($_FILES['image']['name']);
                $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
                $safeExt = strtolower(preg_replace('/[^a-z0-9]/i', '', $fileExt));
                $fileName = uniqid('prod_') . ($safeExt ? ('.' . $safeExt) : '');
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $data['image'] = $fileName;
                }
            }

            try {
                if ($this->productModel->update($id, $data)) {
                    $this->redirect('admin/products');
                } else {
                    $error = "Failed to update product";
                }
            } catch (Exception $e) {
                $error = 'Error updating product: ' . $e->getMessage();
            }
        }

        $product = $this->productModel->findById($id);
        if (!$product) {
            $this->redirect('admin/products');
        }

        $this->view('admin/edit_product', [
            'product' => $product,
            'error' => $error ?? null
        ]);
    }

    public function deleteProduct() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id && $this->productModel->delete($id)) {
                echo json_encode(['success' => true]);
                return;
            }
            echo json_encode(['success' => false]);
            return;
        }

        $this->redirect('admin/products');
    }

    private function getDashboardStats() {
        $db = (new Database())->getConnection();
        
        $stats = [];
        
        // Total products
        $stmt = $db->query("SELECT COUNT(*) as count FROM products");
        $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total orders
        $stmt = $db->query("SELECT COUNT(*) as count FROM orders");
        $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total users
        $stmt = $db->query("SELECT COUNT(*) as count FROM users");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total revenue
        $stmt = $db->query("SELECT SUM(total_amount) as revenue FROM orders WHERE status != 'cancelled'");
        $stats['total_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;
        
        return $stats;
    }

    private function getLowStockProducts() {
        $db = (new Database())->getConnection();
        $stmt = $db->query("SELECT * FROM products WHERE stock_quantity < 10 AND status = 'active' ORDER BY stock_quantity ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
