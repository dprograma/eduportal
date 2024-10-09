<?php
if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);

    // Check if the token exists in the database
    $user = $pdo->select("SELECT * FROM users WHERE reset_code = ?", [$token])->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['reset-token'] = $token;
        // Redirect to the login page with a success message
        redirect('reset-password', "Please enter a new password.", "success");
    } else {
        // Invalid token, redirect with an error message
        redirect('forgot-password', "Invalid or expired verification token.");
    }
} else {
    // No token provided, redirect with an error
    redirect('login', "No reset token provided.");
}

