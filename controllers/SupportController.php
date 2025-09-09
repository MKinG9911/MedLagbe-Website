<?php
require_once 'core/Controller.php';
require_once 'models/Support.php';

class SupportController extends Controller {
    private $supportModel;
    
    public function __construct() {
        $this->supportModel = new Support();
    }
    
    public function index() {
        $this->view('user/support');
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'] ?? null,
                'subject' => $_POST['subject'],
                'message' => $_POST['message'],
                'email' => $_POST['email'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $result = $this->supportModel->create($data);
            
            if ($result) {
                $success = "Your message has been sent successfully. We'll get back to you soon.";
                $this->view('user/support', ['success' => $success]);
            } else {
                $error = "Failed to send message. Please try again.";
                $this->view('user/support', ['error' => $error]);
            }
        } else {
            $this->redirect('user/support');
        }
    }
}
