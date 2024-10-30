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


            $old_password = sanitizeInput($_POST['old_password']);
            $new_password = sanitizeInput($_POST['new_password']);
            $confirm_new_password = sanitizeInput($_POST['confirm_new_password']);
            $verificationToken = generateRandomString(16);


            // Default profile image
            $profileimg = $currentUser->profileimg;


            // Check if the old password is correct

            if (!empty($old_password) && !password_verify($old_password, $currentUser->password)) {
                redirect('profile', 'Incorrect old password!');
            }

            // Check for password mismatch

            if (!empty($new_password) && !empty($confirm_new_password) && $new_password != $confirm_new_password) {
                redirect('profile', 'Password mismatch!');
            }

            // Check if password is already used
            if (!empty($new_password) && password_verify($new_password, $currentUser->password)) {
                redirect('profile', 'Password already used!');
            }

            $userData = [
                'password' => $new_password,
                'Confirm' => $confirm_new_password,
            ];

            // $msg = isEmpty($userData);
            // if ($msg != 1) {
            //     redirect('profile', $msg);
            // }


            if (!empty($currentUser)) {

                // Check if a new file was uploaded
                if (!empty($_FILES['profileimg']['name'])) {
                    $profileimgUpload = fileUpload($_FILES['profileimg']);
                    if (is_array($profileimgUpload)) {
                        // If there are errors during file upload, redirect with error
                        redirect('profile', implode(', ', $profileimgUpload));
                    } else {
                        $profileimg = $profileimgUpload;
                        $userData['ProfileImg'] = $profileimg;
                    }
                }

                echo "USER FORM DATA: " . print_r($userData);

                $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);

                // Update the user information
                $pdo->update(
                    "UPDATE users SET `password`=?, verification_token=?, profileimg=? WHERE id=?",
                    [$hashedPass, $verificationToken, $userData['ProfileImg'], Session::get('loggedin')]
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
    require_once 'view/loggedin/secured/customer_profile.php';
}


