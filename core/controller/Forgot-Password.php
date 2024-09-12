<?php

$title = 'Forget Password' . '|' . SITE_TITLE;

if(isset($_POST['forgot-password'])){

    $email = sanitizeInput($_POST['email']);

    if (!$email) {
        redirect('reset-password', 'Invalid Email', 'error');    
    }else {
        $user = $pdo->select("SELECT email FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $token = rand(999999, 111111);
            $pdo->update("UPDATE users SET reset_code=? WHERE email = ?", [$token, $email]);
            $resetLink = "reset-password?token=$token";
            $to = $email;
                $subject = "Password Reset Request";
                $message = "To reset your password, click the following link: $resetLink";
                $headers = "From: owunnaizum@gmail.com";
                
                if (mail($to, $subject, $message, $headers)) {
                    echo "Password reset email sent. Please check your inbox.";
                } else {
                    echo "Error sending the reset email. Please try again.";
                }
            } else {
                redirect('auth-forgot-password', 'Email Does Not Exist', 'danger');
            

        }
        
       
    }

}


require_once 'view/guest/auth/forgot-password.php';

