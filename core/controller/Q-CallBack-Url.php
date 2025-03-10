<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';
require_once 'core/utils.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $trxRef    = $_GET['trxref'];
    $secretKey = $_ENV['PAYSTACK_SECRET_KEY']; 

    // verify transaction status
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $_ENV['PAYSTACK_TRANSACTION_VERIFICATION_URL'] . $trxRef,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => "",
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST  => "GET",
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer $secretKey",
            "Cache-Control: no-cache",
        ],
    ]);

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) {
        error_log("PayStack Error: " . $err);
        redirect('affiliate-signup?ref=affiliate', 'Payment verification failed. Please try again.', 'error');
        exit;
    }

        $response = json_decode($response);
    if ($response->data->status === "success") {
        $metadata = $response->data->metadata;

        // Check if this is an affiliate registration payment
        if (isset($metadata->payment_for) && $metadata->payment_for === 'affiliate_registration') {
            if (isset($_SESSION['affiliate_registration'])) {
                $userData = $_SESSION['affiliate_registration'];

                try {
                    // Complete the registration
                    completeRegistration($userData, $pdo);
                    // Clear session data
                    unset($_SESSION['affiliate_registration']);
                    unset($_SESSION['payment_ref']);
                    unset($_SESSION['affiliate_earnings']);
                    redirect('login', 'Registration successful! Please check your email to verify your account.', 'success');
                    exit;
                } catch (Exception $e) {
                    error_log("Registration Error: " . $e->getMessage());
                    redirect('affiliate-signup?ref=affiliate', 'Registration failed. Please try again.', 'error');
                    exit;
                }
            } else {
                error_log("No affiliate registration data found in session");
                redirect('affiliate-signup?ref=affiliate', 'Registration data not found. Please try again.', 'error');
                exit;
            }
        }

        // Handle regular purchase payment
        if (! Session::exists('loggedin')) {
            redirect('login', 'Please login to complete your purchase.', 'error');
            exit;
        }

        $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
        if (! $currentUser) {
            redirect('login', 'User session expired. Please login again.', 'error');
            exit;
        }

        // Process the purchase
        $paymentData = $response->data;
        $carts       = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : [];

        foreach ($carts as $sku => $cart) {
            // Insert the transaction details
            $pdo->insert(
                "INSERT INTO `transactionlogs` (
                    user_id, trxid, sku, item, exambody, year, domain,
                    reference, receipt_number, amount, channel, currency,
                    ip_address, paid_at, created_at, log
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $currentUser->id,
                    $paymentData->id,
                    $sku,
                    $cart['subject'],
                    $cart['exam_body'],
                    $cart['year'],
                    $paymentData->domain,
                    $paymentData->reference,
                    $paymentData->receipt_number,
                    $cart['price'],
                    $paymentData->channel,
                    $paymentData->currency,
                    $paymentData->ip_address,
                    $paymentData->paid_at,
                    $paymentData->created_at,
                    json_encode($paymentData->log),
                ]
            );

            // Update agent balance if successful
            if ($pdo->status) {
                saveEarnedCommisions($cart, $pdo);
            }
        }

        if ($pdo->status) {
            // Clear cart and redirect
            setcookie('cart', '', time() - 3600, '/');
            unset($_COOKIE['cart']);
            redirect('purchases', 'Purchase successful!', 'success');
            exit;
        }
    }
}

// If we get here, something went wrong
redirect('affiliate-signup?ref=affiliate', 'Payment verification failed. Please try again.', 'error');
