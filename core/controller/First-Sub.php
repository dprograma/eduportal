<?php

$title = 'Subscription' . '|' . SITE_TITLE;


$priceTag = $pdo->select("SELECT * FROM users WHERE access = 'admin'")->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['payment-process'])) {

  $email = $_SESSION['user_email'];
  $priceTagKobo = $priceTag['amount'] * 100;
  $reference = uniqid();
  $secretKey =  $priceTag['secretKey'];
  $publicKey =  $priceTag['publicKey'];


  $paymentData = [
    'Email' => $email,
  ];

  $msg = isEmpty($paymentData);

  if ($msg != 1) {
    redirect('first-sub', $msg);
  }

  $res = $pdo->select("SELECT email FROM users WHERE id=?", [$paymentData['Email']])->fetchColumn();


  $curl = curl_init();

  curl_setopt_array(
    $curl,
    array(
      CURLOPT_URL => "https://api.paystack.co/plan",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array(
        "name" => "Prepr Membership Plan",
        "interval" => "annually",
        "amount" => $priceTagKobo

      ),

      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $secretKey",
        "Cache-Control: no-cache"
      ),
    )
  );

  $response = curl_exec($curl);

  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    //   echo $response;
    $data = json_decode($response);
    $plan_code = $data->data->plan_code;
    include_once("Intialize.php");
  }
  $data = [
    'amount' => $amount,
    'reference' => $reference,
    'callback_url' => 'auth-login', // Set your callback URL
  ];
}



























require_once 'view/guest/subscription/first-sub.php';
