<?php
if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);

    // Check if the token exists in the database
    $user = $pdo->select("SELECT * FROM users WHERE verification_token = ?", [$token])->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update user status to verified
        $pdo->update("UPDATE users SET is_verified = 1 WHERE verification_token = ?", [$token]);

        // Redirect to the login page with a success message
        redirect('login', "Your account has been verified. Please log in.", "success");
    } else {
        // Invalid token, redirect with an error message
        redirect('signup', "Invalid or expired verification token.");
    }
} else {
    // No token provided, redirect with an error
    redirect('signup', "No verification token provided.");
}

