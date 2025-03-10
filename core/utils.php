<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';
// ini_set("include_path", '/home/preprcom/php:' . ini_get("include_path") );
// require_once 'controller/qservers_mail.php';
use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;
require 'core/Mailer.php';

function fetchBankCodes()
{
    // Now you can access the environment variables
    $secretKey = $_ENV['PAYSTACK_SECRET_KEY'];
    $bankListUrl = $_ENV['PAYSTACK_BANKLIST_URL'];
    $client = new Client();
    $response = $client->get($bankListUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $secretKey,
        ],
    ]);

    $bankData = json_decode($response->getBody(), true);

    if ($bankData['status']) {
        return $bankData['data'];
    }

    return [];
}

/**
 * Check if a user's referral is still within the commission period
 */
function isWithinReferralPeriod($referralDate)
{
    $referralDateTime = new DateTime($referralDate);
    $currentDateTime = new DateTime();
    $interval = $referralDateTime->diff($currentDateTime);

    // Check if within 3 months (90 days)
    return $interval->days <= 90;
}

/**
 * Calculate commission based on type
 */
function calculateCommission($amount, $type = 'product')
{
    $productCommissionRate = 0.70; // 70% for product owner
    $referralCommissionRate = 0.65; // 65% for referrer

    return $amount * ($type === 'product' ? $productCommissionRate : $referralCommissionRate);
}

/**
 * Record agent and affiliate earnings
 */
function saveEarnedCommisions($cartItems, $pdo)
{
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    // Create an order Id
    $orderId = generateRandomString(16);
    // After successful payment processing
    if ($cartItems) {
        foreach ($cartItems as $item) {
            // 1. Process product owner commission (70%)
            $productOwnerCommission = calculateCommission($item['price'], 'product');

            $pdo->insert(
                "INSERT INTO commissions (
                affiliate_id,
                referred_user_id,
                order_id,
                sku,
                amount,
                commission_amount,
                commission_type,
                product_owner_id,
                status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
                [
                    $item['owner_id'], // Product owner gets commission
                    $currentUser->id,  // Buyer
                    $orderId,
                    $item['sku'],
                    $item['price'],
                    $productOwnerCommission,
                    'product',
                    $item['owner_id'],
                ]
            );

            // 2. Process referral commission if applicable (65%)
            if ($currentUser->referred_by && isWithinReferralPeriod($currentUser->signup_date)) {
                $referralCommission = calculateCommission($item['price'], 'referral');

                $pdo->insert(
                    "INSERT INTO commissions (
                    affiliate_id,
                    referred_user_id,
                    order_id,
                    sku,
                    amount,
                    commission_amount,
                    commission_type,
                    product_owner_id,
                    status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
                    [
                        $currentUser->referred_by, // Referrer gets commission
                        $currentUser->id,          // Buyer
                        $orderId,
                        $item['sku'],
                        $item['price'],
                        $referralCommission,
                        'referral',
                        $item['owner_id'],
                    ]
                );
            }

            // Get the seller/owner of the item
            $seller = $pdo->select("SELECT user_id FROM document WHERE id = ?", [$item['id']])->fetch(PDO::FETCH_ASSOC);

            if ($seller) {
                create(
                    $seller['user_id'],
                    'sale',
                    'New Purchase!',
                    "Your {$item['title']} has been purchased by a customer.",
                    "order-details?id=" . $orderId
                );
            }
        }
    }

}

/**
 * Save Earned Commission from Affilate's N3,000 registrations
 */
function saveEarnedCommisionsonAffiliateReg($item, $pdo)
{
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    // Create an order Id
    $orderId = generateRandomString(16);
    $productOwnerCommission = calculateCommission($item['amount'], 'referral');

    $pdo->insert(
        "INSERT INTO commissions (
        affiliate_id,
        referred_user_id,
        order_id,
        sku,
        amount,
        commission_amount,
        commission_type,
        product_owner_id,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')",
        [
            $item['ownerId'], // Product owner gets commission
            $currentUser->id,  // Buyer
            $orderId,
            '',
            $item['amount'],
            $productOwnerCommission,
            'referral',
            $item['ownerId'],
        ]
    );

}

/**
 * Add to notifications
 */
function create($userId, $type, $title, $message, $link = null)
    {
        global $pdo;
        $pdo->insert(
            "INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)",
            [$userId, $type, $title, $message, $link]
        );
    }

/**
 * Complete user registration process
 */
function completeRegistration($userData, $pdo)
{
    try {
        error_log("User Data: " . print_r($userData, true));
        $verificationToken = generateRandomString(16);
        $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);
        $affiliateCode = $userData['affiliate'] ? generateAffiliateCode($userData['UserName']) : null;

        // Check for referral
        // $referredBy = null;
        // if (isset($_GET['ref'])) {
        //     $referrerCode = $_GET['ref'];
        //     $affiliate    = $pdo->select("SELECT id FROM users WHERE affiliate_code = ?", [$referrerCode])->fetch(PDO::FETCH_ASSOC);
        //     if ($affiliate) {
        //         $referredBy = $affiliate['id'];
        //     }
        // }

        // Insert the user
        $pdo->insert(
            'INSERT INTO users (
                username,
                email,
                fullname,
                password,
                verification_token,
                is_verified,
                access,
                is_affiliate,
                termsofuse,
                affiliate_code,
                referred_by
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $userData['UserName'],
                $userData['Email'],
                $userData['FullName'],
                $hashedPass,
                $verificationToken,
                0,
                'secured',
                $userData['affiliate'],
                $userData['termsofuse'],
                $affiliateCode,
                $userData['referred_by'],
            ]
        );

        // Send verification email
        sendVerificationEmail($userData, $verificationToken);

        $successMessage = "Registration successful! Please check your email to verify your account.";
        if ($affiliateCode) {
            $successMessage .= " Your affiliate link is: " . APP_URL . "signup?ref=" . $affiliateCode;
        }

        // Verify user was created by checking the database
        $newUser = $pdo->select(
            "SELECT id FROM users WHERE email = ? AND username = ?",
            [$userData['Email'], $userData['UserName']]
        )->fetch(PDO::FETCH_ASSOC);

        if (!$newUser) {
            throw new Exception("User creation verification failed");
        }
        $earnings = $_SESSION['affiliate_earnings'];
        saveEarnedCommisionsonAffiliateReg($earnings, $pdo);
        unset($_SESSION['affiliate_earnings']);

        // Corrected redirect URL
        SessionRedirect(APP_URL . ($userData['affiliate'] ? "affiliate-signup?ref=affiliate" : "signup"), $successMessage, "success");
        exit;

    } catch (Exception $e) {
        error_log("Registration Error: " . $e->getMessage());
        error_log("User Data: " . print_r($userData, true));

        redirect(
            $userData['affiliate'] ? "affiliate-signup?ref=affiliate" : "signup",
            "Registration failed: " . $e->getMessage(),
            "error"
        );
        exit;
    }
}

/**
 * Generate unique affiliate code
 */
function generateAffiliateCode($username)
{
    $code = substr(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $username)), 0, 5) . rand(1000, 9999);
    return $code;
}

/**
 * Generate ebook isbn
 */
function generateEbookISBN()
{
    $isbn = '978' . rand(1000000000, 9999999999);
    return $isbn;
}
/**
 * Send verification email to new user
 */
function sendVerificationEmail($userData, $verificationToken)
{
    global $mail;

    try {
        if (!isset($mail) || !($mail instanceof PHPMailer\PHPMailer\PHPMailer)) {
            error_log("Mailer not properly initialized");
            return false;
        }

        // Reset any previous settings
        $mail->clearAddresses();
        $mail->clearAttachments();

        $verificationLink = APP_URL . "verify?token=" . $verificationToken;

        // Create HTML message
        $welcomeMsg = <<<HTML
        <html>
        <body style="font-family: Arial, sans-serif;">
            <h1>Welcome {$userData["UserName"]} to EduPortal</h1>
            <p>Thank you for registering! Please click the link below to verify your email address:</p>
            <p><a href="{$verificationLink}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Verify Email</a></p>
            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p>{$verificationLink}</p>
        </body>
        </html>
HTML;

        // Set email parameters
        $mail->addAddress($userData['Email']);
        $mail->Subject = 'EduPortal - Email Verification';
        $mail->Body = $welcomeMsg;
        $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n\n"], $welcomeMsg));

        error_log("Attempting to send email to: " . $userData['Email']);

        // Send the email
        if (!$mail->send()) {
            error_log("Email sending failed. Mailer Error: " . $mail->ErrorInfo);

            // Try with SSL as fallback
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            if (!$mail->send()) {
                error_log("Email sending failed again with SSL. Mailer Error: " . $mail->ErrorInfo);
                return false;
            }
        }

        error_log("Email sent successfully to: " . $userData['Email']);
        return true;

    } catch (Exception $e) {
        error_log("Email Error: " . $e->getMessage());
        error_log("Debug Info: " . print_r($mail->Debugoutput, true));
        return false;
    }
}
