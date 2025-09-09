<?php
require_once 'core/Controller.php';
require_once 'models/User.php';
require_once 'models/Admin.php';

class AuthController extends Controller {
    private $userModel;
    private $adminModel;

    public function __construct() {
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $user = $this->userModel->findByEmail($email);
            
            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                $this->redirect('user/home');
            } else {
                $error = "Invalid email or password";
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            
            // Check if email already exists
            if ($this->userModel->findByEmail($data['email'])) {
                $error = "Email already exists";
                $this->view('auth/signup', ['error' => $error]);
                return;
            }
            
            $userId = $this->userModel->create($data);
            
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $data['name'];
                $_SESSION['user_email'] = $data['email'];
                
                $this->redirect('user/home');
            } else {
                $error = "Registration failed";
                $this->view('auth/signup', ['error' => $error]);
            }
        } else {
            $this->view('auth/signup');
        }
    }

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $admin = $this->adminModel->findByEmail($email);
            
            if ($admin && $this->adminModel->verifyPassword($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                
                $this->redirect('admin/dashboard');
            } else {
                $error = "Invalid email or password";
                $this->view('auth/admin_login', ['error' => $error]);
            }
        } else {
            $this->view('auth/admin_login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('');
    }

    public function adminLogout() {
        session_destroy();
        $this->redirect('auth/admin_login');
    }
}
?>
