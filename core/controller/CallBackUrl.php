<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse the incoming JSON data from Paystack
    $input = file_get_contents('php://input');
    $event = json_decode($input, true);

    if ($event['event'] === 'charge.success') {
        // Payment was successful, perform your success handling here
        // For example, update your database, mark the payment as successful, etc.
        
        // Your code to update the user's access to "secured"
        $email = $event['data']['customer']['email'];
        // Make sure to sanitize $email and perform proper validation here.
        
        $paymentAmount = $priceTagKobo /100;

        // Update your database with the payment amount
      $pdo->update("UPDATE users SET access = 'secured', amount = ? WHERE email = ?", [$paymentAmount, $email]);

        // Redirect the user to the home page
        header('Location: dashboard');
        exit;
    }
}

// If the script reaches this point, it means there was no valid event
http_response_code(400);
?>
