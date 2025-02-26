<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';

use GuzzleHttp\Client;

$secretKey            = $_ENV['PAYSTACK_SECRET_KEY'];
$transferrecipientUrl = $_ENV['PAYSTACK_TRANSFER_RECIPIENT_URL'];
$transferUrl          = $_ENV['PAYSTACK_TRANSFER_URL'];

$title = 'Request Withdrawal' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if (! Session::exists('loggedin')) {
    redirect('login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    // Validate amount
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    if (! $amount || $amount < 1000) {
        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Minimum withdrawal amount is ₦1,000', 'error');
        exit;
    }

    // Check available balance based on user type
    if ($currentUser->is_agent) {
        // For agents, get their total earnings from sales (70% of sales)
        $totalEarnings = $pdo->select(
            "SELECT COALESCE(SUM(amount * 0.70), 0) as total
             FROM transactionlogs
             WHERE user_id = ?",
            [$currentUser->id]
        )->fetch(PDO::FETCH_ASSOC)['total'];
    } else {
        // For affiliates, get their commission earnings
        $totalEarnings = $pdo->select(
            "SELECT COALESCE(SUM(commission_amount), 0) as total
             FROM commissions
             WHERE affiliate_id = ?",
            [$currentUser->id]
        )->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Get withdrawn amount
    $withdrawnAmount = $pdo->select(
        "SELECT COALESCE(SUM(amount), 0) as total
         FROM withdrawals
         WHERE user_id = ? AND status IN ('paid', 'pending')",
        [$currentUser->id]
    )->fetch(PDO::FETCH_ASSOC)['total'];

    $availableBalance = $totalEarnings - $withdrawnAmount;

    if ($amount > $availableBalance) {
        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Insufficient balance', 'error');
        exit;
    }

    // Validate bank details
    $bankCode      = sanitizeInput($_POST['bank_code']);
    $accountNumber = sanitizeInput($_POST['account_number']);
    $accountName   = sanitizeInput($_POST['account_name']);

    if (strlen($accountNumber) !== 10 || ! ctype_digit($accountNumber)) {
        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Invalid account number', 'error');
        exit;
    }

    // Create a transfer recipient
    $client   = new Client();
    $response = $client->post($transferrecipientUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $secretKey,
            'Content-Type'  => 'application/json',
        ],
        'json'    => [
            'type'           => 'nuban',
            'name'           => $accountName,
            'account_number' => $accountNumber,
            'bank_code'      => $bankCode,
            'currency'       => 'NGN',
        ],
    ]);

    $recipientData = json_decode($response->getBody(), true);

    if (! $recipientData['status']) {
        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Error creating transfer recipient', 'error');
        exit;
    }

    $recipientCode = $recipientData['data']['recipient_code'];

    // Initiate the transfer
    $transferResponse = $client->post($transferUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $secretKey,
            'Content-Type'  => 'application/json',
        ],
        'json'    => [
            'source'    => 'balance',
            'amount'    => $amount * 100,
            'recipient' => $recipientCode,
            'reason'    => $currentUser->is_agent ? 'Agent earnings withdrawal' : 'Affiliate commission withdrawal',
        ],
    ]);

    $transferData = json_decode($transferResponse->getBody(), true);

    if ($transferData['status']) {
        // Record the withdrawal
        $pdo->insert(
            "INSERT INTO withdrawals (
                user_id,
                amount,
                bank_name,
                account_number,
                account_name,
                status,
                user_type
            ) VALUES (?, ?, ?, ?, ?, 'paid', ?)",
            [
                $currentUser->id,
                $amount,
                $bankCode,
                $accountNumber,
                $accountName,
                $currentUser->is_agent ? 'agent' : 'affiliate',
            ]
        );

        // Create notification for user
        Notifications::create(
            $currentUser->id,
            'withdrawal',
            'Withdrawal Successful',
            "Your withdrawal of ₦" . number_format($amount, 2) . " has been processed successfully.",
            "withdrawal-history"
        );

        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Withdrawal successful', 'success');
    } else {
        $redirectPage = $currentUser->is_agent ? 'dashboard' : 'affiliate-earnings';
        redirect($redirectPage, 'Error processing withdrawal', 'error');
    }
    exit;
}

require_once 'view/loggedin/agent/affiliate_earnings.php';
