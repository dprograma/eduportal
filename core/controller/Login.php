<?php

$title = 'User Login' . '|' . SITE_TITLE;

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

        if ($res->access === 'admin') {
            if ($_SESSION['guest-purchase']) {
                redirect('checkout');
            }

            redirect('admin-dashboard');
        } else if ($res->access === 'secured') {
            if ($_SESSION['guest-purchase']) {
                redirect('checkout');
            }
            if ($_SESSION['pre-cbt-test']) {
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

