<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $trxRef = $_GET['trxref'];
    // get authorization key
    $priceTag = $pdo->select("SELECT * FROM users WHERE access = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    // $secretKey =  $priceTag['secretKey'];
    $secretKey = "sk_test_0342e36d66f55580bb0ddad382062eed99f41608";
    // $publicKey =  $priceTag['publicKey'];
    $publicKey = "pk_test_dd190b038dc1059a96638e2f718bf40bdb127e70";

    // verify transaction status
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/$trxRef",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $secretKey",
            "Cache-Control: no-cache",
        ),
    )
    );

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        // echo $response;
        $response = json_decode($response);
        if ($response->data->status == "success") {

            $user_id = $currentUser->id;
            // echo "user id: " . $user_id;
            $trxid = $response->data->id;
            // echo "tran id: " . $trxid;
            $domain = $response->data->domain;
            // echo "domain: " . $domain;
            $reference = $response->data->reference;
            // echo "reference: " . $reference;
            $receipt_no = $response->data->receipt_number;
            // echo "receipt no: " . $receipt_no;
            $amount = $response->data->amount / 100;
            // echo "amount: " . $amount;
            $channel = $response->data->channel;
            // echo "channel: " . $channel;
            $currency = $response->data->currency;
            // echo "currency: " . $currency;
            $ipaddress = $response->data->ip_address;
            // echo "ipaddress: " . $ipaddress;
            $paid_at = $response->data->paid_at;
            // echo "paid at: " . $paid_at;
            $created_at = $response->data->created_at;
            // echo "created at: " . $created_at;
            $log = json_encode($response->data->log);
            // echo "log: " . $log;


            // Purchased item datax
            // $subject = $_SESSION['subject'] . " Past Question";
            // // echo $subject;
            // $exam_body = $_SESSION['exam_body'];
            // // echo $exam_body;
            // $year = $_SESSION['year'];
            // // echo $year;
            // $sku = $_SESSION['sku'];

            $carts = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : [];

            foreach ($carts as $sku => $cart) {
                // Insert the transaction details into the database
                $pdo->insert("INSERT INTO `transactionlogs` (`user_id`, `trxid`, `sku`, `item`, `exambody`, `year`, `domain`, `reference`, `receipt_number`, `amount`, `channel`, `currency`, `ip_address`, `paid_at`, `created_at`, `log`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$user_id, $trxid, $sku, $cart['subject'], $cart['exam_body'], $cart['year'], $domain, $reference, $receipt_no, $cart['price'], $channel, $currency, $ipaddress, $paid_at, $created_at, $log]);
            }

            if ($pdo->status) {
                setcookie('cart', '', time() - 3600, '/');
                unset($_COOKIE['cart']);
                redirect('purchases');
            }

        }
    }
}

http_response_code(400);
