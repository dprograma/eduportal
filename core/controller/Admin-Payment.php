<?php

$title = 'payment gateway' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_OBJ));
    
    if (isset($_POST['payment-gateway'])) {
        $amount = sanitizeInput($_POST['amount']);
        $secretKey = sanitizeInput($_POST['secretKey']);
        $publicKey = sanitizeInput($_POST['publicKey']);
        
        if (empty($currentUser)) {
            // Handle the case where the user data is not found
            redirect('payment-gateway', 'User data not found', 'danger');
        }

        // Use the current values if they are not provided in the form
        if (empty($secretKey)) {
            $secretKey = $currentUser->secretKey;
        }
        if (empty($publicKey)) {
            $publicKey = $currentUser->publicKey;
        }

        // Preserve the amount if it's not provided in the form
        if (empty($amount)) {
            $amount = $currentUser->amount;
        }

        $pdo->update(
            'UPDATE users SET amount = ?, secretKey = ?, publicKey = ? WHERE id = ?',
            [$amount, $secretKey, $publicKey, $currentUser->id]
        );

        if ($pdo->status) {
            redirect('payment-gateway', 'Updated Successfully', 'success');
        } else {
            redirect('payment-gateway', 'Please check your input', 'danger');
        }
    }
    require_once 'view/loggedin/admin/payment-gateway.php';
}


