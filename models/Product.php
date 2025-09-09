<?php
require_once 'core/Model.php';

class Product extends Model {
    protected $table = 'products';

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (name, description, price, brand, category_id, dosage, ingredients, expiry_date, prescription_required, stock_quantity, image, status) 
                  VALUES (:name, :description, :price, :brand, :category_id, :dosage, :ingredients, :expiry_date, :prescription_required, :stock_quantity, :image, :status)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':brand' => $data['brand'],
            ':category_id' => $data['category_id'],
            ':dosage' => $data['dosage'],
            ':ingredients' => $data['ingredients'],
            ':expiry_date' => $data['expiry_date'],
            ':prescription_required' => $data['prescription_required'],
            ':stock_quantity' => $data['stock_quantity'],
            ':image' => $data['image'],
            ':status' => $data['status'] ?? 'active'
        ]);
    }

    public function search($term, $filters = []) {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.status = 'active' AND (p.name LIKE :term OR p.description LIKE :term OR p.brand LIKE :term)";
        
        $params = [':term' => '%' . $term . '%'];
        
        if (!empty($filters['category'])) {
            $query .= " AND p.category_id = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['min_price'])) {
            $query .= " AND p.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $query .= " AND p.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryId) {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  WHERE p.category_id = :category_id AND p.status = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWithReviews($id) {
        $query = "SELECT p.*, c.name as category_name,
                  AVG(r.rating) as avg_rating,
                  COUNT(r.id) as review_count
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN reviews r ON p.id = r.product_id
                  WHERE p.id = :id AND p.status = 'active'
                  GROUP BY p.id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getReviews($productId) {
        $query = "SELECT r.*, u.name as user_name FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.product_id = :product_id 
                  ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET 
                    name = :name,
                    description = :description,
                    price = :price,
                    brand = :brand,
                    category_id = :category_id,
                    dosage = :dosage,
                    ingredients = :ingredients,
                    expiry_date = :expiry_date,
                    prescription_required = :prescription_required,
                    stock_quantity = :stock_quantity,
                    image = :image,
                    status = :status
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':brand' => $data['brand'],
            ':category_id' => $data['category_id'],
            ':dosage' => $data['dosage'],
            ':ingredients' => $data['ingredients'],
            ':expiry_date' => $data['expiry_date'],
            ':prescription_required' => $data['prescription_required'],
            ':stock_quantity' => $data['stock_quantity'],
            ':image' => $data['image'],
            ':status' => $data['status'] ?? 'active'
        ]);
    }
}
?>
