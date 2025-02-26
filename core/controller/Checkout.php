<?php

$title = 'Checkout' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if (Session::exists('guest-purchase')) {
    unset($_SESSION['guest-purchase']);
}

if (! empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT *, DATE(created_date) as signup_date FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    $price       = isset($_POST['total']) ? $_POST['total'] : Session::get('price');
    $email       = $currentUser->email;
    if (empty(Session::get('email'))) {
        Session::put('email', $email);
    }
    if (empty(Session::get('price'))) {
        Session::put('price', $price);
    }

    redirect('checkout-past-q');

    // require_once 'view/loggedin/secured/checkout.php';
} else {
    $_SESSION['guest-purchase'] = true;
    $_SESSION['price']          = $_POST['total'];
    redirect('login');
}

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
            Notifications::create(
                $seller['user_id'],
                'sale',
                'New Purchase!',
                "Your {$item['title']} has been purchased by a customer.",
                "order-details?id=" . $orderId
            );
        }
    }
}
