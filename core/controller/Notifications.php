<?php

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('login');
}

if (!empty(Session::get('loggedin'))) {

    $currentUser = toJson($pdo->select(
        "SELECT * FROM users WHERE id=?",
        [Session::get('loggedin')]
    )->fetch(PDO::FETCH_ASSOC));

    require_once 'view/loggedin/secured/notifications.php';
}


