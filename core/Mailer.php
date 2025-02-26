<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
require 'vendor/autoload.php';

try {
    //Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();
    $mail->Host     = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // Use environment variables for sensitive data
    $mail->Username = $_ENV['SMTP_USERNAME'] ?? 'ketuojoken@gmail.com';
    $mail->Password = $_ENV['SMTP_PASSWORD'] ?? 'oyuisxthlkunofrk'; // Make sure this is an App Password

    // Enable verbose debug output
    $mail->SMTPDebug   = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function ($str, $level) {
        error_log("PHPMailer Debug: $str");
    };

    // Use TLS encryption
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Set timeout
    $mail->Timeout       = 30;
    $mail->SMTPKeepAlive = true;

    // Set charset
    $mail->CharSet  = 'UTF-8';
    $mail->Encoding = 'base64';

    // Default settings
    $mail->isHTML(true);
    $mail->setFrom('eduportal@prepr.com.ng', 'EduPortal');

} catch (Exception $e) {
    error_log("Mailer Configuration Error: " . $e->getMessage());
    error_log("Mailer Debug Info: " . print_r($mail->Debugoutput, true));
}
