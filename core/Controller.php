<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require_once "views/{$view}.php";
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin() {
        return isset($_SESSION['admin_id']);
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('auth/admin_login');
        }
    }

    protected function setFlash($message, $type = 'success') {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    protected function getFlash() {
        if (!isset($_SESSION['flash'])) {
            return null;
        }
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
}
?>
