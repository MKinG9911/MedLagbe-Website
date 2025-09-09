<?php
require_once 'core/Controller.php';
require_once 'models/Reminder.php';

class ReminderController extends Controller {
    private $reminderModel;

    public function __construct() {
        $this->reminderModel = new Reminder();
        $this->reminderModel->ensureTable();
    }

    public function create() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $orderId = (int)($_POST['order_id'] ?? 0);
        $orderItemId = (int)($_POST['order_item_id'] ?? 0);
        $timesPerDay = (int)($_POST['times_per_day'] ?? 1);
        $specificTimes = trim($_POST['specific_times'] ?? '');
        $startDate = $_POST['start_date'] ?? date('Y-m-d');
        $endDate = $_POST['end_date'] ?? null;
        $timezone = $_POST['timezone'] ?? date_default_timezone_get();

        if ($productId <= 0 || $timesPerDay <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid input']);
            return;
        }

        // Compute next_run_at: start today at now if within schedule, else next interval
        $now = new DateTime('now', new DateTimeZone($timezone));
        $nextRunAt = clone $now;
        if (!empty($specificTimes)) {
            $times = array_filter(array_map('trim', explode(',', $specificTimes)));
            $nextRunAt = null;
            foreach ($times as $t) {
                $candidate = DateTime::createFromFormat('Y-m-d H:i', $startDate . ' ' . $t, new DateTimeZone($timezone));
                if ($candidate && $candidate >= $now) {
                    $nextRunAt = $candidate;
                    break;
                }
            }
            if ($nextRunAt === null && isset($times[0])) {
                $nextRunAt = DateTime::createFromFormat('Y-m-d H:i', (new DateTime($startDate . ' +1 day'))->format('Y-m-d') . ' ' . $times[0], new DateTimeZone($timezone));
            }
        } else {
            $intervalHours = max(1, floor(24 / $timesPerDay));
            $nextRunAt->modify('+' . $intervalHours . ' hours');
        }

        $data = [
            'user_id' => $_SESSION['user_id'],
            'order_id' => $orderId ?: null,
            'order_item_id' => $orderItemId ?: null,
            'product_id' => $productId,
            'times_per_day' => $timesPerDay,
            'specific_times' => $specificTimes ?: null,
            'start_date' => $startDate,
            'end_date' => $endDate ?: null,
            'timezone' => $timezone,
            'next_run_at' => $nextRunAt->format('Y-m-d H:i:s'),
        ];

        $ok = $this->reminderModel->createReminder($data);
        echo json_encode(['success' => (bool)$ok]);
    }

    public function cancel() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid id']);
            return;
        }
        $ok = $this->reminderModel->cancelReminder($id, (int)$_SESSION['user_id']);
        echo json_encode(['success' => (bool)$ok]);
    }
}
?>


