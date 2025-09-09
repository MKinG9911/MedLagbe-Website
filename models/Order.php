<?php
require_once 'core/Model.php';

class Order extends Model {
    protected $table = 'orders';

    public function create($data) {
        $orderNumber = 'MED' . date('Ymd') . rand(1000, 9999);
        
        $query = "INSERT INTO " . $this->table . " (user_id, order_number, total_amount, delivery_address, payment_method, prescription_file, discount_amount) 
                  VALUES (:user_id, :order_number, :total_amount, :delivery_address, :payment_method, :prescription_file, :discount_amount)";
        
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':user_id' => $data['user_id'],
            ':order_number' => $orderNumber,
            ':total_amount' => $data['total_amount'],
            ':delivery_address' => $data['delivery_address'],
            ':payment_method' => $data['payment_method'],
            ':prescription_file' => $data['prescription_file'] ?? null,
            ':discount_amount' => $data['discount_amount'] ?? 0
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function addOrderItems($orderId, $items) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->db->prepare($query);
        
        foreach ($items as $item) {
            $stmt->execute([
                ':order_id' => $orderId,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':price' => $item['price']
            ]);
        }
        
        return true;
    }

    public function getUserOrders($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrdersWithItems($userId) {
        $query = "SELECT o.*, GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names
                  FROM " . $this->table . " o
                  LEFT JOIN order_items oi ON oi.order_id = o.id
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE o.user_id = :user_id
                  GROUP BY o.id
                  ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderWithItems($orderId) {
        $query = "SELECT o.*, 
                  oi.product_id, oi.quantity, oi.price as item_price,
                  p.name as product_name, p.image as product_image
                  FROM " . $this->table . " o
                  LEFT JOIN order_items oi ON o.id = oi.order_id
                  LEFT JOIN products p ON oi.product_id = p.id
                  WHERE o.id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($orderId, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':status' => $status, ':id' => $orderId]);
    }

    public function getAllOrders() {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                  FROM " . $this->table . " o
                  JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
