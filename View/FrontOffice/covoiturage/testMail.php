<?php
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/Exception.php';
require_once __DIR__ . '/assets/PHPMailer/PHPMailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP server configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Use Gmail's SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'emna.garbaa@gmail.com'; // Replace with your email
    $mail->Password   = 'gthf zqyi zzqq uyfr';   // Replace with your app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Email settings
    $mail->setFrom('emna.garbaa@gmail.com', 'emna'); // Replace with your email and name
    $mail->addAddress('ines.bouzid@esprit.tn');      // Replace with the recipient's email
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer.';

    // Send the email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
}