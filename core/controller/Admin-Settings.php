<?php

$title = 'reset' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if(!empty(Session::get('loggedin'))){
    
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    if (isset($_POST['reset-password'])) {

        $cPassword = sanitizeInput($_POST['currentPassword']);
        $newPassword = sanitizeInput($_POST['newPassword']);
        $confirmPassword = sanitizeInput($_POST['confirmPassword']);
        
        $hashedPassword = $pdo->select("SELECT password FROM users WHERE id=?", [Session::get('loggedin')])->fetchColumn();
    
        if ($cPassword === $newPassword) {
            redirect('admin-settings', 'Current Password and New Password Cannot be the same', 'danger');
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
                        redirect('logout', 'Password Changed Successfully', 'success');
                    } else {
                        redirect('admin-settings', 'Please check your password input', 'danger');
                    }
                } catch (PDOException $e) {
                    redirect('admin-settings', 'Please check your password input', 'danger');
                }
            } else {
                redirect('admin-settings', 'Please Check your new password and confirm input', 'danger');
            }
        } else {
            redirect('admin-settings', "The Current Password doesn't match any entry", 'danger');
        }
    }


    if (isset($_POST['reset-details'])) {
        $fullname = sanitizeInput(ucwords($_POST['name']));

        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
    
        $userData = $pdo->select("SELECT username, email FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC);
        $currentUsername = $userData['username'];
        $currentEmail = $userData['email'];
    
        if ($username === $currentUsername && $email === $currentEmail && $fullname === $userData['fullname']) {
            redirect('admin-settings', 'No changes made to the username, email, or fullname', 'info');
        } else {
            if ($username !== $currentUsername) {
                $existingUsername = $pdo->select("SELECT username FROM users WHERE username=? AND id != ?", [$username, Session::get('loggedin')])->fetchColumn();
                if ($existingUsername) {
                    redirect('admin-settings', 'Username already exists, please choose another username', 'error');
                }
            }
            
            if ($email !== $currentEmail) {
                $existingEmail = $pdo->select("SELECT email FROM users WHERE email=? AND id != ?", [$email, Session::get('loggedin')])->fetchColumn();
                if ($existingEmail) {
                    redirect('admin-settings', 'Email already exists, please choose another email', 'error');
                }
            }
    
            try {
                // Update the user's username, fullname, and email in the database
                $pdo->update('UPDATE users SET username=?, fullname=?, email=? WHERE id=?', [$username, $fullname, $email, Session::get('loggedin')]);
    
                if ($pdo->status) {
                    redirect('admin-settings', 'Username, email, and fullname updated successfully. Please log in again.', 'success');
                } else {
                    redirect('admin-settings', 'Failed to update user details. Please try again.', 'error');
                }
            } catch (PDOException $e) {
                redirect('admin-settings', 'An error occurred while updating user details. Please try again.', 'error');
            }
        }
    }
    
    
    require_once 'view/loggedin/admin/admin-settings.php';

}
