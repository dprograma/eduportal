<?php

$title = 'Dashboard' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if(!empty(Session::get('loggedin'))){
    
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    $selectedExamBody = '';
    $selectedSubject = '';
    $selectedExamYear = '';

    require_once 'view/loggedin/secured/purchase-past-question.php';
}