<?php
// Assuming $data has the decoded JSON data from the request body
$userId = $data['user_id'];
$score = $data['score'];
$subject = $data['subject'];

// Insert the score into the database
$savescore = $pdo->insert('cbt_test', [
    'user_id' => $userId,
    'score' => $score,
    'subject' => $subject
]);

// Check if the score was saved successfully
if ($savescore) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving score']);
}
