<?php

$title = 'Checkout' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if (Session::exists('guest-purchase')) {
    unset($_SESSION['guest-purchase']);
}

if (! empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT *, DATE(created_date) as signup_date FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    $price       = isset($_POST['total']) ? $_POST['total'] : Session::get('price');
    $email       = $currentUser->email;
    if (empty(Session::get('email'))) {
        Session::put('email', $email);
    }
    if (empty(Session::get('price'))) {
        Session::put('price', $price);
    }

    redirect('checkout-past-q');

    // require_once 'view/loggedin/secured/checkout.php';
} else {
    $_SESSION['guest-purchase'] = true;
    $_SESSION['price']          = $_POST['total'];
    redirect('login');
}


