<?php
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/Exception.php';
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($recipientEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emna.garbaa@gmail.com';  // Your Gmail address
        $mail->Password   = 'gthf zqyi zzqq uyfr';    // App-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email settings
        $mail->setFrom('emna.garbaa@gmail.com', 'TransitX');  // Sender's email
        $mail->addAddress($recipientEmail);  // Recipient's email
        $mail->isHTML(true);  // Enable HTML content
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>