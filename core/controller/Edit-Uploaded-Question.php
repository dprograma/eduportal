<?php

$title = 'Edit Uploaded Question' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}
if (!empty(Session::get('loggedin'))) {

    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    
    $id = $_GET['id'] ?? '';
    $questions = toJson($pdo->select("SELECT * FROM document WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC));

    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];
        if (isset($_POST['edit-uploaded-past-question'])) {
            $examBody = sanitizeInput($_POST['examBody']) ?? '';
            $subject = sanitizeInput($_POST['subject']) ?? '';
            $examYear = sanitizeInput($_POST['examYear']) ?? '';
            $fileToUpload = sanitizeInput($_POST['fileToUpload']) ?? '';
            $price = sanitizeInput($_POST['price']) ?? '';
    
            // Update the question with the specified ID
            $pdo->update(
                'UPDATE `document` SET exam_body=?, `subject`=?, `year`=?, `filename`=?, `price`=? WHERE id=?',
                [$examBody, $subject, $examYear, $fileToUpload, $price, $questionId]
            );
    
            if ($pdo->status) {
                redirect('view-past-questions', 'Question updated successfully', 'success');
            } else {
                redirect('view-past-questions', 'Question Failed to Update', 'danger');
            }
        }
    }
    

    require_once 'view/loggedin/admin/edit-uploaded-past-question.php';

}
