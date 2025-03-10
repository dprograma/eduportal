<?php

require 'vendor/autoload.php';
require_once 'ENVLoader.php';
use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;
require 'core/Mailer.php';

$title = 'Update User' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    if (!empty($currentUser)) {
        $userId = sanitizeInput($_POST['id']);
        $name = sanitizeInput($_POST['fullname']);
        $email = sanitizeInput($_POST['email']);
        $access = sanitizeInput($_POST['access']);

        try {
            $pdo->update(
                "UPDATE users SET `fullname`=?, email=?, access=? WHERE id=?",
                [$name, $email, $access, $userId]
            );

            if ($pdo->status) {
                $updatedUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [$userId])->fetch(PDO::FETCH_ASSOC));

                // Send the verification email
                $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Profile Update</title></head><body>
                        <h1>Hello ' . $name . ', </h1>
                        <p>Admin has just updated your record in response to your request. If this was an error, please contact our <a href="mailto:<EMAIL>">customer support team.</a></p>
                        <p>Thank you for using our services!</p>
                        </body></html>';

                $mail->addAddress($email);
                $mail->Subject = 'User Profile Update';
                $mail->Body = $welcomeMsg;

                // $to = $userData['Email'];
                // $subject = 'Agent Email Account Verification';
                // $from = "Eduportal<info@eduportal.com>";
                // $mail = new QserversMail($welcomeMsg, $subject, $from, $to);
                // $mail->sendMail(); 

                // Try to send the email
                // if (!$mail->send()) {
                //     // If it fails, try TLS on port 587
                //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                //     $mail->Port = 587;
                //     $mail->send();
                // }

                echo json_encode(["success" => 1, "data" => $updatedUser]);
            } else {
                echo json_encode(["success" => 0, "message" => "Failed to update user details."]);
            }
        } catch (Exception $e) {
            echo json_encode(["success" => 0, "message" => "An error occurred: " . $e->getMessage()]);
        }
    }
}

