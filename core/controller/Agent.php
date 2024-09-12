<?php

if (Session::exists('new-agent')) {
    unset($_SESSION['new-agent']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new-agent']) && $_POST['new-agent'] == 'yes') {
        $_SESSION['new-agent'] = true;
        // redirect('auth-register');
    }
}

require_once 'view/guest/auth/auth-agent.php';

