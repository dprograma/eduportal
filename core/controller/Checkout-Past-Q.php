<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';

$title = 'Checkout' . '|' . SITE_TITLE;


$priceTag = $pdo->select("SELECT * FROM users WHERE access = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$customer_email = Session::get('email') ?? '';
$document_price = Session::get('price') ?? '';
// echo $customer_email . '<br />' . $document_price;
// exit;
$reference = uniqid();
// $secretKey =  $priceTag['secretKey'];
$secretKey = $_ENV['PAYSTACK_SECRET_KEY'];
// $publicKey =  $priceTag['publicKey'];
$publicKey = $_ENV['PAYSTACK_PUBLIC_KEY'];

// Paystack payment URL
$payment_url = $_ENV['PAYSTACK_INITIALIZE_TRANSACTION_URL'];
$callback_url = $_ENV['PAYSTACK_CALLBACK_URL']; // URL to xredirect after payment

$postdata = array('email' => $customer_email, 'amount' => ($document_price * 100), 'callback_url' => $callback_url);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $payment_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); // encode the data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $secretKey",
  "Content-Type: application/json",
]);

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
  redirect($_SERVER['HTTP_REFERER'], "Could not resolve network issues.");
} else {

  $response = json_decode($response);
  header('Location: ' . $response->data->authorization_url); // Redirect to payment page
}
