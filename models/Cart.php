<?php
require_once 'core/Model.php';

class Cart extends Model {
    protected $table = 'cart';

    public function addItem($userId, $productId, $quantity = 1) {
        // Check if item already exists in cart
        $existing = $this->getCartItem($userId, $productId);
        
        if ($existing) {
            // Update quantity
            $query = "UPDATE " . $this->table . " SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id";
        } else {
            // Insert new item
            $query = "INSERT INTO " . $this->table . " (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        }
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity
        ]);
    }

    public function getCartItems($userId) {
        $query = "SELECT c.*, p.name, p.price, p.image, p.prescription_required 
                  FROM " . $this->table . " c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartItem($userId, $productId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateQuantity($userId, $productId, $quantity) {
        $query = "UPDATE " . $this->table . " SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':quantity' => $quantity,
            ':user_id' => $userId,
            ':product_id' => $productId
        ]);
    }

    public function removeItem($userId, $productId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':user_id' => $userId, ':product_id' => $productId]);
    }

    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function getCartTotal($userId) {
        $query = "SELECT SUM(c.quantity * p.price) as total 
                  FROM " . $this->table . " c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
