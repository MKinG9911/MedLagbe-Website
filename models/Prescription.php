<?php
require_once 'core/Model.php';

class Prescription extends Model {
    protected $table = 'prescriptions';
    
    public function create($data) {
        $sql = "INSERT INTO prescriptions (user_id, order_id, image_path, status, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['order_id'],
            $data['image_path'],
            $data['status'],
            $data['created_at']
        ]);
    }
    
    public function findByOrderId($orderId) {
        $sql = "SELECT * FROM prescriptions WHERE order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE prescriptions SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
    
    public function getAllPending() {
        $sql = "SELECT p.*, u.name as user_name, o.order_number 
                FROM prescriptions p 
                LEFT JOIN users u ON p.user_id = u.id 
                LEFT JOIN orders o ON p.order_id = o.id 
                WHERE p.status = 'pending' 
                ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
