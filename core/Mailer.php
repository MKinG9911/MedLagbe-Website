<?php

class Mailer {
    public static function sendHtml(string $toEmail, string $subject, string $htmlBody, ?string $textFallback = null): bool {
        if (empty($toEmail)) {
            return false;
        }

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $from = defined('EMAIL_USERNAME') ? EMAIL_USERNAME : 'no-reply@localhost';
        $headers[] = 'From: ' . $from;
        $headers[] = 'Reply-To: ' . $from;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        $wrappedBody = self::wrapTemplate($htmlBody, $subject);

        // Attempt to send using mail()
        return @mail($toEmail, $subject, $wrappedBody, implode("\r\n", $headers));
    }

    private static function wrapTemplate(string $content, string $title): string {
        $brand = 'MedLagbe';
        $styles = 'body{font-family:Arial,Helvetica,sans-serif;background:#f6f8fa;margin:0;padding:0;color:#333} .container{max-width:640px;margin:24px auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden} .header{background:#0ea5e9;color:#fff;padding:16px 20px;font-size:18px;font-weight:600} .content{padding:20px} .footer{padding:16px 20px;color:#6b7280;font-size:12px;background:#f9fafb;border-top:1px solid #f1f5f9} h1,h2,h3{color:#111827} .button{display:inline-block;padding:10px 16px;background:#0ea5e9;color:#fff;text-decoration:none;border-radius:6px} .muted{color:#6b7280;font-size:14px}';
        return "<!DOCTYPE html><html><head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><title>" . htmlspecialchars($title) . "</title><style>" . $styles . "</style></head><body><div class=\"container\"><div class=\"header\">" . $brand . "</div><div class=\"content\">" . $content . "</div><div class=\"footer\">This is an automated message from " . $brand . ". Please do not reply.</div></div></body></html>";
    }
}

?>


