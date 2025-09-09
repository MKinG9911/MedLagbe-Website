<?php
// CLI script: php scripts/send_reminders.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Reminder.php';
require_once __DIR__ . '/../core/Mailer.php';
require_once __DIR__ . '/../config/database.php';

// Instantiate model using Model autoload via index isn't present; load Model base
require_once __DIR__ . '/../core/Model.php';

$reminderModel = new Reminder();
$reminderModel->ensureTable();

$due = $reminderModel->dueReminders(200);
if (empty($due)) {
    echo "No due reminders\n";
    exit(0);
}

foreach ($due as $r) {
    $email = $r['email'] ?? null;
    if (!$email) { continue; }
    $productName = $r['product_name'] ?? 'your medicine';
    $subject = 'Medicine Reminder: ' . $productName;
    $html = '<h2>Time to take your medicine</h2>' .
            '<p>Hi ' . htmlspecialchars($r['name'] ?? 'Customer') . ',</p>' .
            '<p>This is a reminder to take <strong>' . htmlspecialchars($productName) . '</strong>.</p>' .
            '<p><span class="muted">If you wish to stop these reminders, you can cancel the reminder from your order details page.</span></p>';
    Mailer::sendHtml($email, $subject, $html);

    // Compute next_run_at
    $timezone = $r['timezone'] ?: 'Asia/Dhaka';
    $now = new DateTime('now', new DateTimeZone($timezone));
    $next = clone $now;
    if (!empty($r['specific_times'])) {
        $times = array_filter(array_map('trim', explode(',', $r['specific_times'])));
        $scheduledToday = [];
        foreach ($times as $t) {
            $candidate = DateTime::createFromFormat('H:i', $t, new DateTimeZone($timezone));
            if ($candidate) {
                $candidate->setDate((int)$now->format('Y'), (int)$now->format('m'), (int)$now->format('d'));
                if ($candidate > $now) {
                    $scheduledToday[] = $candidate;
                }
            }
        }
        if (!empty($scheduledToday)) {
            usort($scheduledToday, function($a, $b){ return $a <=> $b; });
            $next = $scheduledToday[0];
        } else {
            // earliest time next day
            $first = isset($times[0]) ? $times[0] : '08:00';
            $next = DateTime::createFromFormat('Y-m-d H:i', $now->modify('+1 day')->format('Y-m-d') . ' ' . $first, new DateTimeZone($timezone));
        }
    } else {
        $intervalHours = max(1, (int)floor(24 / max(1, (int)$r['times_per_day'])));
        $next->modify('+' . $intervalHours . ' hours');
    }

    $reminderModel->updateNextRun((int)$r['id'], $next->format('Y-m-d H:i:s'));
}

echo 'Processed ' . count($due) . " reminders\n";


