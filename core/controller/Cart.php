<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the current cart from the cookie
    $cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : [];

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case '+':
                $sku = $_POST['sku'];
                if (isset($cart[$sku])) {
                    $cart[$sku]['quantity']++;
                }
                break;
            case '-':
                $sku = $_POST['sku'];
                if (isset($cart[$sku]) && $cart[$sku]['quantity'] > 1) {
                    $cart[$sku]['quantity']--;
                }
                break;
            case 'delete':
                $sku = $_POST['sku'];
                if (isset($cart[$sku])) {
                    unset($cart[$sku]);
                }
                break;
            case 'clear':
                $cart = [];
                break;
        }
    }

    // Update the cart cookie
    setcookie('cart', serialize($cart), time() + (86400 * 30), '/');
    
    // Redirect back to the cart page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}


require_once 'view/guest/view.cart.php';