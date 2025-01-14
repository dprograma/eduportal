<?php
$title = 'Dashboard' . '|' . SITE_TITLE;


if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    $full_name = isset($_GET['fullname']) ? sanitizeInput(trim($_GET['fullname'])) : '';
    $email = isset($_GET['email']) ? sanitizeInput(trim($_GET['email'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';

    // Pagination
    $limit = 6; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the query
    $rows = ($limit * ($page-1));

    // Fetch all withdrawals requests
    $requests = toJson($pdo->select("SELECT u.fullname, u.email, u.is_agent, u.is_affiliate, u.access, w.amount, w.status, w.created_at FROM users u JOIN withdrawals w ON u.id=w.user_id WHERE u.fullname LIKE '%$full_name%' AND u.email LIKE '%$email%' AND w.created_at LIKE '%$year%' AND u.access != 'Admin' LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC));

    $search = "SELECT COUNT(*) AS total FROM users u JOIN withdrawals w ON u.id=w.user_id WHERE u.fullname LIKE '%$full_name%' AND u.email LIKE '%$email%' AND w.created_at LIKE '%$year%' AND u.access != 'Admin' LIMIT $limit";

    require_once 'view/loggedin/admin/view-request-withdrawals.php';
}
