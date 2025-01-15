<?php

$title = 'Dashboard' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if(!empty(Session::get('loggedin'))){ 
    $id = sanitizeInput($_GET['id']);
    declineWithdrawal($id);
}