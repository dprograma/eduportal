<?php

$url = "https://api.paystack.co/transaction/initialize";

$fields = [
   'email' => $email,
   'amount' => $priceTagKobo,
   'plan' => $plan_code,
];

$fields_string = http_build_query($fields);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   "Authorization: Bearer $secretKey",
   "Cache-Control: no-cache",
));


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

$intialize_data = json_decode($result);
$intialize_url = $intialize_data->data->authorization_url;

if ($result) {
    // Redirect to Paystack payment page
    header("Location: $intialize_url");
    $paymentAmount = $priceTagKobo /100;

  // Update your database with the payment amount
$pdo->update("UPDATE users SET access = 'secured', amount = ? WHERE email = ?", [$paymentAmount, $email]);

    exit;
}










































?>