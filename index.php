
<?php
require_once 'config/config.php';
require_once 'core/Router.php';

// Autoload controllers and models
spl_autoload_register(function ($class) {
    $directories = ['controllers/', 'models/', 'core/'];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize Router
$router = new Router();

// Define Routes
// Home and Authentication Routes
$router->get('/', ['UserController', 'home']);
$router->get('/auth/login', ['AuthController', 'login']);
$router->post('/auth/login', ['AuthController', 'login']);
$router->get('/auth/signup', ['AuthController', 'signup']);
$router->post('/auth/signup', ['AuthController', 'signup']);
$router->get('/auth/admin_login', ['AuthController', 'adminLogin']);
$router->post('/auth/admin_login', ['AuthController', 'adminLogin']);
$router->get('/auth/logout', ['AuthController', 'logout']);
$router->get('/auth/admin_logout', ['AuthController', 'adminLogout']);

// User Routes
$router->get('/user/home', ['UserController', 'home']);
$router->get('/user/products', ['UserController', 'products']);
$router->get('/user/product_detail', ['UserController', 'productDetail']);
$router->get('/user/profile', ['UserController', 'profile']);
$router->post('/user/profile', ['UserController', 'profile']);
$router->get('/user/orders', ['UserController', 'orders']);
$router->get('/user/order_detail', ['UserController', 'orderDetail']);
$router->get('/user/track_order', ['UserController', 'trackOrder']);
$router->get('/user/download_invoice', ['UserController', 'downloadInvoice']);
$router->get('/user/support', ['SupportController', 'index']);
$router->post('/user/support', ['SupportController', 'submit']);

// Reminder Routes
$router->post('/reminder/create', ['ReminderController', 'create']);
$router->post('/reminder/cancel', ['ReminderController', 'cancel']);

// Product Routes
$router->get('/product/search', ['ProductController', 'search']);
$router->get('/product/suggestions', ['ProductController', 'suggestions']);

// Cart Routes
$router->get('/cart', ['CartController', 'index']);
$router->post('/cart/add', ['CartController', 'add']);
$router->post('/cart/update', ['CartController', 'update']);
$router->post('/cart/remove', ['CartController', 'remove']);

// Order Routes
$router->get('/order/checkout', ['OrderController', 'checkout']);
$router->post('/order/checkout', ['OrderController', 'checkout']);
$router->get('/order/track', ['OrderController', 'track']);

// Wishlist Routes
$router->get('/wishlist', ['WishlistController', 'index']);
$router->post('/wishlist/add', ['WishlistController', 'add']);
$router->post('/wishlist/remove', ['WishlistController', 'remove']);

// Admin Routes
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);
$router->get('/admin/products', ['AdminController', 'products']);
$router->get('/admin/products/add', ['AdminController', 'addProduct']);
$router->post('/admin/products/add', ['AdminController', 'addProduct']);
$router->get('/admin/products/edit', ['AdminController', 'editProduct']);
$router->post('/admin/products/edit', ['AdminController', 'editProduct']);
$router->post('/admin/products/delete', ['AdminController', 'deleteProduct']);
$router->get('/admin/orders', ['AdminController', 'orders']);
$router->post('/admin/orders/update_status', ['AdminController', 'updateOrderStatus']);
$router->get('/admin/users', ['AdminController', 'users']);
$router->get('/admin/users/edit', ['AdminController', 'editUser']);
$router->post('/admin/users/edit', ['AdminController', 'editUser']);
$router->post('/admin/users/delete', ['AdminController', 'deleteUser']);
$router->get('/admin/prescriptions', ['AdminController', 'prescriptions']);
$router->post('/admin/prescriptions/verify', ['AdminController', 'verifyPrescription']);

// API Routes
$router->get('/api/cart/count', ['ApiController', 'getCartCount']);
$router->get('/api/notifications', ['ApiController', 'getNotifications']);

// Error handling
set_error_handler(function($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
});

set_exception_handler(function($exception) {
    error_log($exception->getMessage());
    
    if (isset($_SESSION['admin_id'])) {
        echo "<div style='background: #fee; border: 1px solid #fcc; padding: 1rem; margin: 1rem; border-radius: 4px;'>";
        echo "<strong>Error:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine();
        echo "</div>";
    } else {
        include 'views/errors/500.php';
    }
});

// Handle the request
try {
    $router->resolve();
} catch (Exception $e) {
    error_log("Router Error: " . $e->getMessage());
    http_response_code(500);
    include 'views/errors/500.php';
}
?>
