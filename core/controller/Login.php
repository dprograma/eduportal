<?php

$title = 'User Login' . '|' . SITE_TITLE;

// Check for remember me cookie first
if (!isset($_POST['login']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $pdo->select(
        "SELECT u.* FROM users u 
         INNER JOIN remember_tokens rt ON u.id = rt.user_id 
         WHERE rt.token = ? AND rt.expires_at > NOW() 
         LIMIT 1",
        [$token]
    );
    $remembered_user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($remembered_user) {
        Session::put('loggedin', $remembered_user->id);
        Session::put('user_email', $remembered_user->email);

        if ($remembered_user->access === 'admin') {
            redirect('admin-dashboard');
        } else if ($remembered_user->access === 'secured') {
            redirect($remembered_user->is_agent ? 'agent-dashboard' : 'dashboard');
        }
    }
}

if (isset($_POST['login'])) {
    $userEmail = sanitizeInput($_POST['email-username']);
    $password = sanitizeInput($_POST['password']);
    $remember = isset($_POST['remember']) ? true : false;

    $userData = [
        'userEmail' => $userEmail,
        'password' => $password
    ];
    $msg = isEmpty($userData);

    if ($msg != 1) {
        redirect('login', $msg);
    }

    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $res = $pdo->select("SELECT * FROM users WHERE email=? LIMIT 1", [$userEmail])->fetch(PDO::FETCH_ASSOC);
    } else {
        $res = $pdo->select("SELECT * FROM users WHERE username=? LIMIT 1", [$userEmail])->fetch(PDO::FETCH_ASSOC);
    }

    if (empty($res)) {
        redirect('login', "Email, Username or password incorrect!!!");
    }

    $res = json_decode(json_encode($res));

    if (!password_verify($userData['password'], $res->password)) {
        redirect('login', 'Email, Username, or password incorrect!!!');
    }
    if ($res->email_verification_required) {

        $_SESSION['email_verification_required'] = true;

        header('Location: verify-email');
        exit;
    }

    if (!empty($res)) {
        Session::put('loggedin', $res->id);
        Session::put('user_email', $res->email);

        // Handle remember me
        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

            // Delete any existing remember tokens for this user
            $pdo->delete("DELETE FROM remember_tokens WHERE user_id = ?", [$res->id]);

            // Save new remember token
            $pdo->insert(
                "INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)",
                [$res->id, $token, $expires]
            );

            // Set cookie
            setcookie(
                'remember_me',
                $token,
                [
                    'expires' => strtotime('+30 days'),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );
        }

        if ($res->access === 'admin') {
            if ($_SESSION['guest-purchase']) {
                unset($_SESSION['guest-purchase']);
                redirect('checkout');
            }

            redirect('admin-dashboard');
        } else if ($res->access === 'secured') {
            if ($_SESSION['guest-purchase']) {
                unset($_SESSION['guest-purchase']);
                redirect('checkout');

            }
            if ($_SESSION['pre-cbt-test']) {
                unset($_SESSION['pre-cbt-test']);
                redirect('cbt-test');
            }
            if ($res->is_agent) {
                redirect('agent-dashboard');
            }
            redirect('dashboard');
        } else {
            redirect('first-sub', 'Please make a payment before you continue', 'danger');
        }

    } else {
        redirect('login', 'Email, Username, or password incorrect!!!');
    }
}

require_once 'view/guest/auth/signin.php';

