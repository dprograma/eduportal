<?php
$title = 'Checkout' . '|' . SITE_TITLE;


$priceTag = $pdo->select("SELECT * FROM users WHERE access = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);

$customer_email = Session::get('email') ?? '';
$document_price = Session::get('price') ?? '';
// echo $customer_email . '<br />' . $document_price;
// exit;
$reference = uniqid();
// $secretKey =  $priceTag['secretKey'];
$secretKey = "sk_test_0342e36d66f55580bb0ddad382062eed99f41608";
// $publicKey =  $priceTag['publicKey'];
$publicKey = "pk_test_dd190b038dc1059a96638e2f718bf40bdb127e70";

// Paystack payment URL
$payment_url = 'https://api.paystack.co/transaction/initialize';
$callback_url = 'http://localhost/preprdotcom/q-callback-url'; // URL to xredirect after payment

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
