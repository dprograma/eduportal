<?php
session_start();

require_once '../model/DB.php';

// Decode the JSON data from the request body
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['user_id']) && !empty($data['score']) && !empty($data['subject'])) {
    // Insert the score into the database using PDO
    $savescore = $pdo->insert('INSERT INTO `cbt_test` (`user_id`, `score`, `subject`, `created_at`) VALUES (?, ?, ?, current_timestamp());', [
        $data['user_id'],
        $data['score'],
        $data['subject'],
    ]);
    // Return a JSON response indicating success
    echo json_encode(['success' => true, 'message' => 'Score saved successfully', 'data' => $data]);
    exit;
} else {
    // Return an error message for missing data
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}
