<?php
session_start();

define('BASE_URL', 'http://localhost/MedLagbe/');
define('UPLOAD_PATH', 'public/uploads/');

// Email configuration
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('EMAIL_USERNAME', 'admin@medlagbe.com');
define('EMAIL_PASSWORD', 'password');

// Timezone
date_default_timezone_set('Asia/Dhaka');
?>
