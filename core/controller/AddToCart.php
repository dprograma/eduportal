<?php
require_once __DIR__ . '/Notifications.php';
// Initialize the cart array
$cart = [];

// Check if the cart cookie already exists
if (isset($_COOKIE['cart'])) {
    // Retrieve the cart data from the cookie and unserialize it
    $cart = unserialize($_COOKIE['cart']);
}

// Retrieve item details from the form
$sku = $_POST['sku'];
$price = $_POST['price'];
$subject = $_POST['subject'] ?? '';
$exam_body = $_POST['exam_body'] ?? '';
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$year = $_POST['year'];
$coverpage = $_POST['coverpage'];
$quantity = $_POST['quantity'];

// Create an array to store item details
$cart[$sku] = [
    'price' => $price,
    'subject' => $subject,
    'exam_body' => $exam_body,
    'title' => $title,
    'author' => $author,
    'year' => $year,
    'coverpage' => $coverpage,
    'quantity' => $quantity,
];


setCookie('cart', serialize($cart), time() + (86400 * 30), '/');
// print_r($cart);
// exit;
// $pdo->insert('INSERT INTO cart(`user_id`,`sku`,`price`,`subject`,`exam_body`, `year`, `quantity`, `coverage`, `created_at`) VALUES(?,?,?,?,?,?,?,?,?)', [$currentUser->id,$sku,$price,$subject,$exam_body,$year,$coverage,$quantity]);

// After creating cart item, before redirect
if (Session::get('loggedin')) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    
    // Get document owner
    $itemId = explode('_', $sku)[1];
    $documentOwner = $pdo->select(
        "SELECT user_id FROM document WHERE id = ?", 
        [$itemId]
    )->fetch(PDO::FETCH_ASSOC);

    if ($documentOwner) {
        Notifications::create(
            $documentOwner['user_id'],
            'cart_add',
            'Item Added to Cart',
            "{$title} has been added to a customer's cart.",
            null
        );
    }
}

// Redirect back to the previous page or to the cart page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
