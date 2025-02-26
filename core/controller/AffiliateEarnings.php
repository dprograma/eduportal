<?php

require_once 'core/utils.php';

if (! Session::exists('loggedin')) {
    redirect('login');
    exit;
}

$currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

// Get total earnings (both product and referral commissions)
$totalEarnings = $pdo->select(
    "SELECT
        COALESCE(SUM(commission_amount), 0) as total,
        commission_type,
        COUNT(*) as count
     FROM commissions
     WHERE affiliate_id = ?
     GROUP BY commission_type",
    [$currentUser->id]
)->fetchAll(PDO::FETCH_ASSOC);

$productCommissions  = 0;
$referralCommissions = 0;

foreach ($totalEarnings as $earning) {
    if ($earning['commission_type'] === 'product') {
        $productCommissions = $earning['total'];
    } else {
        $referralCommissions = $earning['total'];
    }
}

$totalCommissions = $productCommissions + $referralCommissions;

// Get available balance (total earnings minus withdrawn amount)
$withdrawnAmount = $pdo->select(
    "SELECT COALESCE(SUM(amount), 0) as total
     FROM withdrawals
     WHERE user_id = ? AND status IN ('paid', 'pending')",
    [$currentUser->id]
)->fetch(PDO::FETCH_ASSOC)['total'];

$availableBalance = $totalCommissions - $withdrawnAmount;

// Get total referrals
$totalReferrals = $pdo->select(
    "SELECT COUNT(DISTINCT referred_user_id) as total
     FROM commissions
     WHERE affiliate_id = ? AND commission_type = 'referral'",
    [$currentUser->id]
)->fetch(PDO::FETCH_ASSOC)['total'];

// Get recent commissions with referred user details
$recentCommissions = toJson($pdo->select(
    "SELECT
        c.*,
        u.fullname as referred_name,
        d.title as product_name,
        c.commission_type,
        DATE_FORMAT(c.created_at, '%M %d, %Y') as formatted_date
     FROM commissions c
     LEFT JOIN users u ON c.referred_user_id = u.id
     LEFT JOIN document d ON d.sku = c.sku
     WHERE c.affiliate_id = ?
     ORDER BY c.created_at DESC
     LIMIT 10",
    [$currentUser->id]
)->fetchAll(PDO::FETCH_ASSOC));

require_once 'view/loggedin/agent/affiliate_earnings.php';
