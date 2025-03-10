<?php
require 'vendor/autoload.php';
require_once 'ENVLoader.php';
require_once 'core/utils.php';
// ini_set("include_path", '/home/preprcom/php:' . ini_get("include_path") );
// require_once 'qservers_mail.php';
use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;
require 'core/Mailer.php';

// Determine registration type and set appropriate title and view
$isAffiliate = isset($_GET['ref']) && $_GET['ref'] == 'affiliate';
$title = $isAffiliate ? 'Affiliate registration' . '|' . SITE_TITLE : 'User registration' . '|' . SITE_TITLE;
$registrationType = $isAffiliate ? 'affiliate-signup' : 'signup';

// Check if affiliate link was used to access the page
if (isset($_GET['ref']) && $_GET['ref'] != 'affiliate') {
    $affiliateId = $_GET['ref'];
    $referrerInstance = $pdo->select("SELECT id FROM users WHERE affiliate_code = ?", [$affiliateId])->fetchColumn();
    if ($referrerInstance) {
        $_SESSION['referrer_instance'] = $referrerInstance;
        $earningdata = ["ownerId" => $referrerInstance, "amount" => 3000];
        $_SESSION['affiliate_earnings'] = $earningdata;
    }
}

if (isset($_POST['register']) || isset($_POST['register_affiliate'])) {
    // Store registration data
    $userData = [
        'UserName' => sanitizeInput($_POST['username']),
        'Email' => sanitizeInput($_POST['email']),
        'FullName' => sanitizeInput(ucwords($_POST['name'])),
        'password' => sanitizeInput($_POST['password']),
        'Confirm' => sanitizeInput($_POST['confirm-password']),
        'termsofuse' => isset($_POST['terms-condition']) ? 1 : 0,
    ];

    // Validation with proper redirects
    $redirectUrl = $userData['affiliate'] == 1 ? 'affiliate-signup?ref=affiliate' : 'signup';

    // Check if email already exists - Do this first
    $existingUser = $pdo->select(
        "SELECT id, email, username FROM users WHERE email = ? OR username = ?",
        [$userData['Email'], $userData['UserName']]
    )->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        if ($existingUser['email'] === $userData['Email']) {
            redirect($redirectUrl, "Email address already exists.", 'error');
            exit;
        }
        if ($existingUser['username'] === $userData['UserName']) {
            redirect($redirectUrl, "Username already exists.", 'error');
            exit;
        }
    }

    // Validate the input data
    $msg = isEmpty($userData);
    if ($msg != 1) {
        $_SESSION['old_values'] = $userData;
        // If it is only affiliate field that is missing in $userData, do nothing
        redirect($redirectUrl, $msg, 'error');
        exit;
    }

    $userData['affiliate'] = isset($_POST['affiliate']) ? 1 : 0;

    if (!$userData['termsofuse']) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Please accept the terms and conditions.", 'error');
        exit;
    }

    if ($userData['password'] != $userData['Confirm']) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Password does not match.", 'error');
        exit;
    }

    if (!filter_var($userData['Email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Invalid email address.", 'error');
        exit;
    }

    if (!preg_match("/^[a-zA-Z0-9]*$/", $userData['UserName'])) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Invalid username. Only letters and numbers are allowed.", 'error');
        exit;
    }

    if (strlen($userData['UserName']) < 6) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Username must be at least 6 characters.", 'error');
        exit;
    }

    if (strlen($userData['password']) < 8) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Password must be at least 8 characters.", 'error');
        exit;
    }

    // Check if affiliate ID is set in the session
    if (isset($_SESSION['referrer_instance'])) {
        $userData['referred_by'] = $_SESSION['referrer_instance'];
        unset($_SESSION['referrer_instance']);
    }

    // Check if password contains at least one capital letter and one number character
    if (!preg_match('/[A-Z]/', $userData['password']) || !preg_match('/[0-9]/', $userData['password'])) {
        $_SESSION['old_values'] = $userData;
        redirect($redirectUrl, "Password must contain at least one capital letter and one number.", 'error');
        exit;
    }

    // Store registration data for after payment verification
    $_SESSION['pending_registration'] = $userData;
    // Handle affiliate registration (both checkbox and direct affiliate signup)
    if ($userData['affiliate']) {
        $paymentRef = 'AFF_REG_' . time() . '_' . rand(1000, 9999);
        $_SESSION['payment_ref'] = $paymentRef;

        // Store registration data with a more unique key
        $_SESSION['affiliate_registration'] = $userData; 

        try {
            $client = new Client();
            $response = $client->post($_ENV['PAYSTACK_INITIALIZE_TRANSACTION_URL'], [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_ENV['PAYSTACK_SECRET_KEY'],
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'email' => $userData['Email'],
                    'amount' => 300000,
                    'reference' => $paymentRef,
                    'callback_url' => $_ENV['PAYSTACK_CALLBACK_URL'],
                    'metadata' => [
                        'registration_type' => 'affiliate', 
                        'user_email' => $userData['Email'],
                        'payment_for' => 'affiliate_registration', 
                    ],
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            if ($result['status']) {
                header('Location: ' . $result['data']['authorization_url']);
                exit;
            }
        } catch (Exception $e) {
            error_log("Payment Error: " . $e->getMessage());
            redirect($redirectUrl, 'Payment initialization failed. Please try again.', 'error');
            exit;
        }
    } else {
        // Regular user registration
        completeRegistration($userData, $pdo);
    }
}

$url = parse_url($_SERVER['REQUEST_URI']);
$affiliate = strpos(isset($url['query']), 'ref=') != false;
// Determine which view to load based on URL and registration type
if ($isAffiliate || (isset($_SESSION['old_values']['affiliate']) && $_SESSION['old_values']['affiliate'] == 1)) {
    require_once 'view/guest/auth/affiliate_signup.php';
} elseif (isset($affiliate) && !empty($affiliate)) {
    require_once 'view/guest/auth/signup.php';
} else {
    require_once 'view/guest/auth/signup.php';
}
