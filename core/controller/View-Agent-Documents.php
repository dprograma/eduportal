<?php

$title = 'View Uploaded Past Questions' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    $full_name = isset($_GET['fullname']) ? sanitizeInput(trim($_GET['fullname'])) : '';
    $subject = isset($_GET['subject']) ? sanitizeInput(trim($_GET['subject'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';
    $status = isset($_GET['status']) ? sanitizeInput(trim($_GET['status'])) : '';

    // Pagination
    $limit = 6; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the query
    $rows = ($limit * ($page - 1));

    $questions = toJson($pdo->select("SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`document_type` = 'past question' AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = ?) AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit OFFSET $offset", [1])->fetchAll(PDO::FETCH_ASSOC));

    $ebooks = toJson($pdo->select("SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`document_type` = 'ebook' AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = ?) AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit OFFSET $offset", [1])->fetchAll(PDO::FETCH_ASSOC));

    $publications = toJson($pdo->select("SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`document_type` = 'publication' AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = ?) AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit OFFSET $offset", [1])->fetchAll(PDO::FETCH_ASSOC));

    $search = "SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = 1) AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit";


    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];

        $pdo->delete('DELETE FROM `document` WHERE id=?', [$questionId]);

        $successMessage = "You have successfully deleted question ID {$questionId}.";

        redirect('view-agent-past-questions', $successMessage, 'success');
        exit();
    }

    if (isset($_POST['publish'])) {
        $id = $_POST['questionId'];
        // echo $id;
        $currentQuestion = $pdo->select("SELECT * FROM `document` WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC);
        // var_dump($currentQuestion);
        $status = $currentQuestion['published'] == 1 ? 0 : 1;

        $pdo->update('UPDATE `document` SET published =? WHERE id=?', [$status, $id]);

        if ($pdo->status) {
            require_once __DIR__ . '/Notifications.php';
            // Create notification for document owner
            Notifications::create(
                $currentQuestion['user_id'],
                'document_status',
                $status ? 'Document Published' : 'Document Unpublished',
                "Your document '{$currentQuestion['title']}' has been " . ($status ? 'published' : 'unpublished'),
                "view-document?id=" . $id
            );
            $resp = json_encode(['status' => 'success']);
            echo $resp;
            die;
        }
    }


}



require_once 'view/loggedin/agent/view-agent-documents.php';