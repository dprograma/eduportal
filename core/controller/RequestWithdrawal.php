<?php

$title = 'Dashboard' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if(!empty(Session::get('loggedin'))){
    $user_id = Session::get('loggedin');
    $res = file_get_contents('php://input');
    $data = json_decode($res, true);
    $amount = $data['amount'];
    requestWithdrawal($user_id, $amount);
}