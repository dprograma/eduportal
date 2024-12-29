<?php
use PHPMailer\PHPMailer\PHPMailer;

$title = isset($_GET['ref']) == 'affiliate' ? 'Affiliate registration' . '|' . SITE_TITLE: 'User registration' . '|' . SITE_TITLE;


if (isset($_POST['register'])) {
    $userName = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $_SESSION['user_email'] = $email;
    $fullname = sanitizeInput(ucwords($_POST['name']));
    $password = sanitizeInput($_POST['password']);
    $confirm = sanitizeInput($_POST['confirm-password']);
    $verificationToken = generateRandomString(16);
    // Check if the affiliate checkbox was selected
    $affiliate = isset($_POST['affiliate']) ? 1 : 0;
    $termsofuse = isset($_POST['terms-condition']) ? 1 : 0;

    // Validate the input data

    $userData = [
        'UserName' => $userName,
        'Email' => $email,
        'FullName' => $fullname,
        'password' => $password,
        'Confirm' => $confirm,
        'affiliate' => $affiliate
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
    $pdo->insert('INSERT INTO users(username,email, fullname, `password`, verification_token, is_verified, access, affiliate, termsofuse) 
                  VALUES(?,?,?,?,?,?,?,?,?)',
        [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verificationToken, 0, 'secured', $userData['affiliate'], $userData['terms-condition']]
    );

    if ($pdo->status) {
        // Construct the verification link
        $verificationLink = APP_URL . "verify?token=" . $verificationToken;

        // Send the verification email
        $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Verify Your Email</title></head><body>
            <h1>Welcome ' . $userData["UserName"] . ' to EduPortal</h1>
            <p>Please click the link below to verify your email address:</p>
            <a href="' . $verificationLink . '">Verify Email</a>
            </body></html>';


        $to = $userData['Email'];

        try {
            $mail->addAddress($to);
            $mail->Subject = 'Email Account Verification';
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
} elseif (isset($_POST['register_affiliate'])) {
    $userName = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $_SESSION['user_email'] = $email;
    $fullname = sanitizeInput(ucwords($_POST['name']));
    $password = sanitizeInput($_POST['password']);
    $confirm = sanitizeInput($_POST['confirm-password']);
    $verificationToken = generateRandomString(16);
    // Check if the affiliate checkbox was selected
    $affiliate = isset($_POST['affiliate']) ? 1 : 0;
    $termsofuse = isset($_POST['terms-condition']) ? 1 : 0;

    // Validate the input data

    $userData = [
        'UserName' => $userName,
        'Email' => $email,
        'FullName' => $fullname,
        'password' => $password,
        'Confirm' => $confirm,
        'affiliate' => $affiliate
    ];

    $msg = isEmpty($userData);

    if ($msg != 1) {
        redirect('affiliate-signup', $msg);
    }

    if ($termsofuse != 1) {
        redirect('affiliate-signup', "Please accept the terms and conditions.");
    } else {
        $userData['terms-condition'] = $_POST['terms-condition'];
    }

    if ($userData['password'] != $userData['Confirm']) {
        redirect('affiliate-signup', "Password does not match.");
    }

    $res = $pdo->select("SELECT * FROM users WHERE username=? or email=?", [$userData['UserName'], $userData['Email']])->fetchAll(PDO::FETCH_BOTH);

    if (!empty($res)) {
        $user = $res[0];
        if ($user['email'] == $userData['Email']) {
            $pdo->update('UPDATE users SET affiliate = ' . $userData['affiliate'] . ', termsofuse = ' . $userData['terms-condition'] . ' WHERE email = ' . $userData['Email'] . '');
        }
    } else {
        $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Insert the user with a verification token and `is_verified` field set to false (0)
        $pdo->insert('INSERT INTO users(username,email, fullname, `password`, verification_token, is_verified, access, affiliate, termsofuse) 
              VALUES(?,?,?,?,?,?,?,?)',
            [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verificationToken, 0, 'secured', $userData['affiliate'], $userData['terms-condition']]
        );

        if ($pdo->status) {
            // Construct the verification link
            $verificationLink = APP_URL . "verify?token=" . $verificationToken;

            // Send the verification email
            $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Verify Your Email</title></head><body>
        <h1>Welcome ' . $userData["UserName"] . ' to EduPortal</h1>
        <p>Please click the link below to verify your email address:</p>
        <a href="' . $verificationLink . '">Verify Email</a>
        </body></html>';


            $to = $userData['Email'];

            try {
                $mail->addAddress($to);
                $mail->Subject = 'Affiliate Email Account Verification';
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
    }
}

if (isset($_GET['ref']) && $_GET['ref'] == 'affiliate') {
    require_once 'view/guest/auth/affiliate_signup.php';
} else {
    require_once 'view/guest/auth/signup.php';

}
