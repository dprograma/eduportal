<?php
$title = 'Customer Publications' . '|' . SITE_TITLE;


if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    // Fetch questions for the selected exam body, subject, and exam year
    $publications = $pdo->select("SELECT `document`.* FROM `document` JOIN `transactionlogs` ON `document`.`sku` = `transactionlogs`.`sku`AND `document`.`user_id` = `transactionlogs`.`user_id` AND `document`.`document_type` = 'publications'")->fetchAll(PDO::FETCH_ASSOC);

}
require_once 'view/loggedin/secured/customer_publications.php';
