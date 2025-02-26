<?php
require 'vendor/autoload.php';
require_once 'ENVLoader.php';

if (! isset($_GET['reference']) || ! isset($_SESSION['pending_registration'])) {
    error_log("Missing reference or pending registration data");
    redirect('signup', 'Invalid payment verification attempt', 'error');
    exit;
}

$reference = $_GET['reference'];
$userData  = $_SESSION['pending_registration'];

// Verify payment with PayStack
$client = new GuzzleHttp\Client();
try {
    $response = $client->get($_ENV['PAYSTACK_VERIFY_URL'] . $reference, [
        'headers' => [
            'Authorization' => 'Bearer ' . $_ENV['PAYSTACK_SECRET_KEY'],
        ],
    ]);

    $result = json_decode($response->getBody(), true);
    error_log("PayStack Response: " . print_r($result, true));

    if ($result['status'] && $result['data']['status'] === 'success') {
        // Payment successful, complete registration
        try {
            completeRegistration($userData, $pdo);
        } catch (Exception $e) {
            error_log("Registration Error after payment: " . $e->getMessage());
            redirect('affiliate-signup?ref=affiliate', 'Registration failed after payment: ' . $e->getMessage(), 'error');
            exit;
        }

        // Clear session data
        unset($_SESSION['pending_registration']);
        unset($_SESSION['payment_ref']);

        exit; // completeRegistration handles the redirect
    } else {
        error_log("PayStack payment verification failed: " . print_r($result, true));
        redirect('affiliate-signup?ref=affiliate', 'Payment verification failed. Please try again.', 'error');
    }
} catch (Exception $e) {
    error_log("PayStack API Error: " . $e->getMessage());
    redirect('affiliate-signup?ref=affiliate', 'Payment verification failed: ' . $e->getMessage(), 'error');
}
