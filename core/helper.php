<?php
// ini_set("include_path", '/home/preprcom/php:' . ini_get("include_path") );
// require_once 'qservers_mail.php';

function dnd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    die;
}

function sanitizeInput($data)
{
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function isEmpty($data)
{
    foreach ($data as $key => $value) {
        if (empty($value)) {
            return $key . " field is empty";
        }
    }
    return 1;
}

function redirect($where, $info = '', $type = 'danger')
{
    if (empty($info)) {
        header("location: $where");
    }

    if (!empty($info)) {
        header("location: $where?error=$info&type=$type");
    }
    exit;

}

function abort($code)
{
    http_response_code($code);
    require "controller/$code.php";
    die;
}

function toJson($res)
{
    return json_decode(json_encode($res));

}

function fileUpload($upload)
{
    $target_dir = 'uploads/';
    $allowed_size = 1000000; // 1MB
    $allowed_type = ['jpg', 'jpeg', 'png', 'gif'];
    $error = [];

    // Ensure directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }

    $targetFile = $target_dir . time() . basename($upload['name']);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $fileSize = $upload['size'];
    $getImgSize = getimagesize($upload['tmp_name']);

    if (!$getImgSize) {
        $error['invalid'] = "File is not an image.";
    }

    if ($fileSize > $allowed_size) {
        $error['size'] = "File size should not exceed 1MB.";
    }

    if (!in_array($fileType, $allowed_type)) {
        $error['type'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    if (file_exists($targetFile)) {
        $error['exists'] = "File already exists.";
    }

    if (empty($error)) {
        if (move_uploaded_file($upload['tmp_name'], $targetFile)) {
            return $targetFile; // Return the file path if upload is successful
        } else {
            $error['move_error'] = "Sorry, there was an error uploading your file.";
        }
    }

    return $error; // Return errors if any
}


function generateUniqueCode($length)
{
    $characters = '0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}
function formatNumber($number, $decimalPlaces = 2)
{
    return number_format($number, $decimalPlaces, '.', ',');
}

function computeAgentCommission($amount): float
{
    $amount = (int) ($amount);
    return 0.70 * $amount;
}

function generateRandomString($length = 10)
{
    $bytes = random_bytes(ceil($length / 2));
    $randomString = strtoupper(substr(bin2hex($bytes), 0, $length));
    return $randomString;
}

function updateAgentBalance($userId, $amount)
{
    global $pdo;

    // Query to retrieve the agent's current balance
    $sqlSelect = "SELECT balance FROM deposit WHERE id = :user_id";
    $stmt = $pdo->select($sqlSelect, ['user_id' => $userId]);
    $agent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($agent) {
        // Calculate new balance, fee, and payable balance
        $newBalance = $agent['balance'] + $amount;
        $fee = $newBalance * 0.3;
        $payableBalance = $newBalance * 0.7;

        // Update the agent's balance, fee, and payable balance
        $sqlUpdate = "UPDATE deposit SET balance = :balance, fee = :fee, payable = :payable WHERE id = :agentId";
        $pdo->update($sqlUpdate, [
            'balance' => $newBalance,
            'fee' => $fee,
            'payable' => $payableBalance,
            'user_id' => $userId
        ]);
    }
}


function requestWithdrawal($userId, $amount)
{
    global $pdo;

    header('Content-Type: application/json'); 

    try {
        // Start transaction
        $pdo->conn->beginTransaction();

        // Fetch agent data
        $agentData = $pdo->select("SELECT * FROM deposit WHERE user_id=?", [$userId])->fetch(PDO::FETCH_ASSOC);

        if (!$agentData) {
            echo json_encode(["success" => false, "message" => "Invalid Agent ID"]);
            exit; 
        }

        $agent = json_decode(json_encode($agentData)); 

        if ($amount <= 0) {
            echo json_encode(["success" => false, "message" => "Amount must be greater than zero"]);
            exit; 
        }

        if ($agent->payable <= 0) {
            echo json_encode(["success" => false, "message" => "No funds available for withdrawal"]);
            exit; 
        }

        if ($amount > $agent->payable) {
            echo json_encode(["success" => false, "message" => "Insufficient funds"]);
            exit; 
        }

        // Deduct the amount from the agent's payable balance
        $newPayableBalance = $agent->payable - $amount;
        $sqlUpdateAgent = "UPDATE deposit SET payable = :payable WHERE user_id = :user_id";
        $pdo->update($sqlUpdateAgent, [
            'payable' => $newPayableBalance,
            'user_id' => $userId
        ]);

        // Insert the withdrawal request
        $sqlInsertWithdrawal = "INSERT INTO withdrawals (user_id, amount, status) VALUES (:user_id, :amount, 'Pending')";
        $pdo->insert($sqlInsertWithdrawal, [
            'user_id' => $userId,
            'amount' => $amount
        ]);

        $agentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [$agent->user_id])->fetch(PDO::FETCH_ASSOC));
        $admin = toJson($pdo->select("SELECT * FROM users WHERE access = 'admin' ORDER BY id ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC));

        // Commit transaction
        $pdo->conn->commit();
        
        // Send email notification to agent
        $message = "Dear " . $agentUser->fullname . ",\n\nYour withdrawal request of ₦ " . $amount . " has been sent. Your new payable balance when is ₦ " . $newPayableBalance . ".\n\nThank you for using Eduportal.";
        $subject = 'Withdrawal Request Notification';
        $from = "Eduportal<info@eduportal.com>";
        // $mail = new QserversMail($message, $subject, $from, $agentUser->email);
        // $mail->sendMail();   
        
        // Send email notification to admin
        $message = "Dear " . $admin->fullname . ",\n\n" . $agentUser->fullname . " has requested a withdrawal of ₦ " . $amount . ". The new payable balance for the agent is ₦ " . $newPayableBalance . ".\n\nThank you for using Eduportal.";
        $subject = 'Withdrawal Request Notification';
        $from = "Eduportal<info@eduportal.com>";
        // $mail = new QserversMail($message, $subject, $from, $admin->email);
        // $mail->sendMail(); 

        echo json_encode(['success' => true, "payable" => $newPayableBalance, "message" => "Transaction successful."]);
        exit; 
    } catch (Exception $e) {
        $pdo->conn->rollBack();
        echo json_encode(['success' => false, "message" => "Transaction failed!"]);
        exit; 
    }
}



function approveWithdrawal($userId)
{
    global $pdo;

    try {
        // Start transaction
        $pdo->conn->beginTransaction();

        // Fetch the withdrawal request
        $sqlSelectRequest = "SELECT * FROM withdrawals WHERE user_id = :user_id FOR UPDATE";
        $stmt = $pdo->select($sqlSelectRequest, ['user_id' => $userId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            throw new Exception("Withdrawal request not found.");
        }

        if ($request['status'] !== 'Pending') {
            throw new Exception("This request is not pending.");
        }

        // Update the withdrawal request status to 'Approved'
        $sqlUpdateRequest = "UPDATE withdrawals SET status = 'Approved' WHERE user_id = :user_id";
        $pdo->update($sqlUpdateRequest, ['user_id' => $userId]);

        // Commit transaction
        $pdo->conn->commit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->conn->rollBack();
        echo "Error approving withdrawal: " . $e->getMessage();
    }
}


function declineWithdrawal($userId)
{
    global $pdo;

    try {
        // Start transaction
        $pdo->conn->beginTransaction();

        // Fetch the withdrawal request
        $sqlSelectRequest = "SELECT * FROM withdrawals WHERE user_id = :user_id FOR UPDATE";
        $stmt = $pdo->select($sqlSelectRequest, ['user_id' => $userId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) {
            throw new Exception("Withdrawal request not found.");
        }

        if ($request['status'] !== 'Pending') {
            throw new Exception("This request is not pending.");
        }

        // Fetch the associated agent
        $sqlSelectAgent = "SELECT * FROM deposit WHERE user_id = :user_id FOR UPDATE";
        $stmt = $pdo->select($sqlSelectAgent, ['user_id' => $request['user_id']]);
        $agent = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$agent) {
            throw new Exception("Agent not found.");
        }

        // Refund the amount to the agent's payable balance
        $newPayableBalance = $agent['payable_balance'] + $request['amount'];
        $sqlUpdateAgent = "UPDATE deposit SET payable = :payable WHERE user_id = :user_id";
        $pdo->update($sqlUpdateAgent, [
            'payable' => $newPayableBalance,
            'user_id' => $request['user_id']
        ]);

        // Update the withdrawal request status to 'Declined'
        $sqlUpdateRequest = "UPDATE withdrawals SET status = 'Declined' WHERE user_id = :user_id";
        $pdo->update($sqlUpdateRequest, ['user_id' => $userId]);

        // Commit transaction
        $pdo->conn->commit();
    } catch (Exception $e) {
        $pdo->conn->rollBack();
    }
}