<?php
// ini_set("include_path", '/home/preprcom/php:' . ini_get("include_path") );
// require_once 'qservers_mail.php';
use PHPMailer\PHPMailer\PHPMailer;

$title = 'Agent registration' . '|' . SITE_TITLE;


if (isset($_POST['register_agent'])) {
    $userName = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $_SESSION['user_email'] = $email;
    $fullname = sanitizeInput(ucwords($_POST['name']));
    $password = sanitizeInput($_POST['password']);
    $confirm = sanitizeInput($_POST['confirm-password']);
    $verificationToken = generateRandomString(16);
    $termsofuse = isset($_POST['terms-condition']) ? 1 : 0;

    // Validate the input data

    $userData = [
        'UserName' => $userName,
        'Email' => $email,
        'FullName' => $fullname,
        'password' => $password,
        'Confirm' => $confirm
    ];

    $msg = isEmpty($userData);

    if ($msg != 1) {
        redirect('signup', $msg);
    }

    if ($termsofuse != 1) {
        redirect('signup', "Please accept the terms and conditions.");
    } else {
        $userData['terms-condition'] = $_POST['terms-condition'];
    }

    if ($userData['password'] != $userData['Confirm']) {
        redirect('signup', "Password does not match.");
    }

    $res = $pdo->select("SELECT * FROM users WHERE username=? or email=?", [$userData['UserName'], $userData['Email']])->fetchAll(PDO::FETCH_BOTH);

    if (!empty($res)) {
        foreach ($res as $user) {
            if ($user['email'] == $userData['Email']) {
                redirect('signup', "Email already exists");
            } elseif ($user['username'] == $userData['UserName']) {
                redirect('signup', "Username already exists");
            }
        }
    }

    $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);

    // Insert the user with a verification token and `is_verified` field set to false (0)
    $pdo->insert('INSERT INTO users(username, email, fullname, `password`, verification_token, is_verified, is_agent, access, termsofuse) 
                  VALUES(?,?,?,?,?,?,?,?,?)',
        [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verificationToken, 0, 1, 'secured', $userData['terms-condition']]
    );

    if ($pdo->status) {
        // Construct the verification link
        $verificationLink = APP_URL . "/verify?token=" . $verificationToken;

        // Send the verification email
        $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Verify Your Email</title></head><body>
            <h1>Welcome ' . $userData["UserName"] . ' to EduPortal</h1>
            <p>Please click the link below to verify your email address:</p>
            <a href="' . $verificationLink . '">Verify Email</a>
            </body></html>';


        $to = $userData['Email'];
        $subject = 'Agent Email Account Verification';
        $from = "Eduportal<info@eduportal.com>";
        // $mail = new QserversMail($welcomeMsg, $subject, $from, $to);
        // $mail->sendMail(); 

        try {
            $mail->addAddress($to);
            $mail->Subject = $subject;
            $mail->Body = $welcomeMsg;

            // Try to send using port 465
            if (!$mail->send()) {
                // If it fails, try TLS on port 587
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->send();
            }

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect with success message
        redirect("signup", "Registration successful. Please check your email for the verification link.", "success");
    }
    exit;
} 
require_once 'view/guest/auth/agent_signup.php';

