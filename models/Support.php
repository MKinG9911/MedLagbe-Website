<?php
require_once 'core/Model.php';

class Support extends Model {
    protected $table = 'support_tickets';
    
    public function create($data) {
        $sql = "INSERT INTO support_tickets (user_id, subject, message, email, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['user_id'],
            $data['subject'],
            $data['message'],
            $data['email'],
            $data['created_at']
        ]);
    }
    
    public function findByUserId($userId) {
        $sql = "SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $sql = "SELECT st.*, u.name as user_name FROM support_tickets st 
                LEFT JOIN users u ON st.user_id = u.id 
                ORDER BY st.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
