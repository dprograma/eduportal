<?php

$title = 'Forget Password' . '|' . SITE_TITLE;

if (isset($_POST['forgot-password'])) {

    $email = sanitizeInput($_POST['email']);

    if (!$email) {
        redirect('reset-password', 'Invalid Email', 'error');
    } else {
        $user = $pdo->select("SELECT email FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $token = rand(999999, 111111);
            $pdo->update("UPDATE users SET reset_code=? WHERE email = ?", [$token, $email]);
            $resetLink = APP_URL . "reset?token=" . $token;
            $to = $email;
            $mail->AddAddress($to);
            $mail->Subject = "Password Reset Request";
            $mail->Body = "To reset your password, click the following link: <a href='$resetLink'>Reset Password</a>";
            $mail->isHTML(true);

            if ($mail->send()) {
                redirect('forgot-password', 'Password reset email sent. Please check your inbox.', 'success');
            } else {
                redirect('forgot-password', 'Error sending the reset email. Please try again.');

            }
        } else {
            redirect('forgot-password', 'Email Does Not Exist', 'danger');


        }


    }

}


require_once 'view/guest/auth/forgot-password.php';

