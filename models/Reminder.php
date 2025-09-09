<?php
require_once 'core/Model.php';

class Reminder extends Model {
    protected $table = 'reminders';

    public function ensureTable(): void {
        $sql = "CREATE TABLE IF NOT EXISTS reminders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            order_id INT NULL,
            order_item_id INT NULL,
            product_id INT NOT NULL,
            times_per_day INT DEFAULT 1,
            specific_times VARCHAR(255) NULL,
            start_date DATE NOT NULL,
            end_date DATE NULL,
            timezone VARCHAR(64) DEFAULT 'Asia/Dhaka',
            next_run_at DATETIME NOT NULL,
            last_sent_at DATETIME NULL,
            channel ENUM('email') DEFAULT 'email',
            active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX(user_id),
            INDEX(product_id),
            INDEX(next_run_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->db->exec($sql);
    }

    public function createReminder(array $data): bool {
        $sql = "INSERT INTO reminders (user_id, order_id, order_item_id, product_id, times_per_day, specific_times, start_date, end_date, timezone, next_run_at, channel, active)
                VALUES (:user_id, :order_id, :order_item_id, :product_id, :times_per_day, :specific_times, :start_date, :end_date, :timezone, :next_run_at, :channel, :active)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':order_id' => $data['order_id'] ?? null,
            ':order_item_id' => $data['order_item_id'] ?? null,
            ':product_id' => $data['product_id'],
            ':times_per_day' => $data['times_per_day'],
            ':specific_times' => $data['specific_times'] ?? null,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'] ?? null,
            ':timezone' => $data['timezone'] ?? 'Asia/Dhaka',
            ':next_run_at' => $data['next_run_at'],
            ':channel' => 'email',
            ':active' => 1,
        ]);
    }

    public function cancelReminder(int $id, int $userId): bool {
        $stmt = $this->db->prepare("UPDATE reminders SET active = 0 WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function dueReminders(int $limit = 100): array {
        $stmt = $this->db->prepare("SELECT r.*, u.email, u.name, p.name AS product_name
            FROM reminders r
            JOIN users u ON r.user_id = u.id
            JOIN products p ON r.product_id = p.id
            WHERE r.active = 1 AND r.channel = 'email' AND r.next_run_at <= NOW()
            ORDER BY r.next_run_at ASC
            LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateNextRun(int $id, string $nextRunAt): bool {
        $stmt = $this->db->prepare("UPDATE reminders SET next_run_at = :next_run_at, last_sent_at = NOW() WHERE id = :id");
        return $stmt->execute([':next_run_at' => $nextRunAt, ':id' => $id]);
    }
}
?>


