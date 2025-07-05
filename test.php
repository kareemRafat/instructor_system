<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // or include files manually if not using Composer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yellowkareem@gmail.com';     // Your Gmail
    $mail->Password = '';        // App password from Google
    $mail->SMTPSecure = 'ssl';                    // or 'ssl'
    $mail->Port = 465;                            // 465 for SSL, 587 for TLS

    // Recipients
    $mail->setFrom('yellowkareem@gmail.com', 'kareem');
    $mail->addAddress('kareem.force@gmail.com', 'AHMED');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = 'This is a test <b>HTML</b> email sent using PHPMailer and Gmail SMTP.';
    $mail->AltBody = 'This is the plain text version of the email.';

    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
