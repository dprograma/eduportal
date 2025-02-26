<?php

$title = 'reset' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {

    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    if (isset($_POST['reset-password'])) {
        $cPassword = sanitizeInput($_POST['currentPassword']);
        $newPassword = sanitizeInput($_POST['newPassword']);
        $confirmPassword = sanitizeInput($_POST['confirmPassword']);

        $hashedPassword = $pdo->select("SELECT password FROM users WHERE id=?", [Session::get('loggedin')])->fetchColumn();

        if ($cPassword === $newPassword) {
            redirect('reset-password', 'Current Password and New Password Cannot be the same', 'danger');
        }

        if (password_verify($cPassword, $hashedPassword)) {
            if ($newPassword === $confirmPassword) {
                $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);

                try {
                    $pdo->update(
                        'UPDATE users SET password=? WHERE id=?',
                        [$hashedPass, Session::get('loggedin')]
                    );

                    if ($pdo->status) {
                        // Create security notification
                        Notifications::create(
                            Session::get('loggedin'),
                            'security',
                            'Password Changed',
                            "Your account password was changed successfully. If you didn't make this change, please contact support immediately.",
                            "settings"
                        );

                        redirect('logout', 'Password Changed Successfully', 'success');
                    } else {
                        redirect('reset-password', 'Please check your password input', 'danger');
                    }
                } catch (PDOException $e) {
                    redirect('reset-password', 'Please check your password input', 'danger');
                }
            } else {
                redirect('reset-password', 'Please Check your new password and confirm input', 'danger');
            }
        } else {
            redirect('reset-password', "The Current Password doesn't match any entry", 'danger');
        }
    }

} else {
    if (isset($_POST['reset-password'])) {
        $newPassword = sanitizeInput($_POST['newPassword']);
        $confirmPassword = sanitizeInput($_POST['confirmPassword']);
        if (Session::exists('reset-token')) {
            $token = Session::get('reset-token');
            unset($_SESSION['reset-token']);
        }

        $hashedPass = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            $pdo->update(
                'UPDATE users SET password=? WHERE reset_code=?',
                [$hashedPass, $token]
            );

            if ($pdo->status) {
                redirect('login', 'Password Changed Successfully', 'success');
            } else {
                redirect('reset-password', 'Please check your password input', 'danger');
            }
        } catch (PDOException $e) {
            redirect('reset-password', 'Please check your password input', 'danger');
        }

    }
}
require_once 'view/loggedin/secured/set-new-password.php';
