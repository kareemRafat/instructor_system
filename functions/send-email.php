<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yellowkareem@gmail.com';     // Your Gmail
    $mail->Password = 'xceq lrwg jryx hxjw';        // App password from Google
    $mail->SMTPSecure = 'TLS';                    // or 'ssl'
    $mail->Port = 587;                            // 465 for SSL, 587 for TLS

    // Recipients
    $mail->setFrom('yellowkareem@gmail.com', 'Createivo');
    $mail->addAddress($email, $username);

    // Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body = $emailBody;
    $mail->AltBody = 'This is the plain text version of the email.';

    $mail->send();
    $_SESSION['success'] = 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
