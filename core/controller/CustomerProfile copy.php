<?php
$title = 'User Profile' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    if ($currentUser->access == 'secured') {

        if (isset($_POST['profile'])) {

            $userName = sanitizeInput($_POST['username']);
            $email = sanitizeInput($_POST['email']);
            $_SESSION['user_email'] = $email;
            $fullname = sanitizeInput(ucwords($_POST['name']));
            $password = sanitizeInput($_POST['password']);
            $confirm = sanitizeInput($_POST['confirm-password']);
            $verificationToken = generateRandomString(16);
            $profileimg = 'assets/avatars/money-avatar.png';

            $userData = [
                'UserName' => $userName,
                'Email' => $email,
                'FullName' => $fullname,
                'password' => $password,
                'Confirm' => $confirm,
                'ProfileImg' => $profileimg,
            ];

            $msg = isEmpty($userData);

            if ($msg != 1) {
                redirect('profile', $msg);
            }


            $res = $pdo->select("SELECT * FROM users WHERE username=? or email=?", [$userData['UserName'], $userData['Email']])->fetchAll(PDO::FETCH_BOTH);

            if (!empty($res)) {
                $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);

                // Insert the user with a verification token and `is_verified` field set to false (0)
                $pdo->update(
                    "UPDATE users SET username=?, email=?, fullname=?, `password`=?, verification_token=?, profileimg=?)",
                    [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verificationToken, $userData['ProfileImg']]
                );

                if ($pdo->status) {
                    // Send the verification email
                    $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Profile Update</title></head><body>
                <h1>Hello ' . $userData["UserName"] . ', </h1>
                <p>Your profile has just been updated. If this was an error, please contact our <a href="mailto:<EMAIL>">customer support team.</a></p>
                <p>Thank you for using our services!</p>
                </body></html>';

                    $to = $userData['Email'];
                    $mail->AddAddress($to);
                    $mail->Subject = 'User Profile Update';
                    $mail->Body = $welcomeMsg;
                    $mail->isHTML(true);
                    $mail->send();

                    // Redirect with success message
                    redirect("profile", "Profile Update successful.", "success");
                }
            }
        }
    }
}

require_once 'view/loggedin/secured/customer_profile.php';
