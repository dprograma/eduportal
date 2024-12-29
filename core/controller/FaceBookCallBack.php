<?php
require 'FacebookAuth.php';

if (isset($_GET['code'])) {
    $facebookAuth = new FacebookAuth();
    $token = $facebookAuth->getAccessToken($_GET['code']);
    $userData = $facebookAuth->getUserData($token);

    // Process user data (e.g., store in your database or log them in)
    $res = $pdo->select("SELECT * FROM users WHERE email=?", [$userData['Email']])->fetchAll(PDO::FETCH_BOTH);

    if (!empty($res)) {
        foreach ($res as $user) {
            if ($user['email'] == $userData['Email']) {
                redirect('signup', "User already exists");
            } 
        }
    }

    // Insert the user into the database
    $pdo->insert('INSERT INTO users(email, fullname, is_verified, access) 
                  VALUES(?,?,?,?)',
        [$userData['Email'], $userData['FullName'], 0, 'secured']
    );
    
    Session::put('loggedin', $userData['FullName']);
    Session::put('user_email', $userData['Email']);

    //chek if cbt test was selected
    if ($_SESSION['pre-cbt-test']) {
        redirect('cbt-test');
    }
    // Redirect to dashboard or desired page
    redirect('dashboard', 'You have successfully logged in.', 'success');
    exit;
}
