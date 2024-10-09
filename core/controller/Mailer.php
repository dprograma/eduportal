<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'vendor/autoload.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';

// Set the SMTP server and port
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->Host = 'smtp.gmail.com';
// $mail->Username = 'hiddentreaxure1@gmail.com';
$mail->Username = 'e4ddb8cd5d0f6e';
// $mail->Password = 'Aboki12345$';
$mail->Password = '7c789e78d8ef2a';

// Set the recipient's email address, subject, and message
$to = 'recipient@example.com'; // Replace with the recipient's email address
$mail->AddAddress($to);
$mail->Subject = 'Password Reset Request';
$mail->Body = 'To reset your password, click the following link: [resetLink]';

// Attempt to send the email
if ($mail->send()) {
    echo "Password reset email sent. Please check your inbox.";
} else {
    echo "Error sending the reset email. Please try again. Error: " . $mail->ErrorInfo;
}