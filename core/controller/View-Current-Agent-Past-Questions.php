<?php
$title = 'View Uploaded Past Questions' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=? AND `is_agent` = 1", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    $full_name = isset($_GET['fullname']) ? sanitizeInput(trim($_GET['fullname'])) : '';
    $subject = isset($_GET['subject']) ? sanitizeInput(trim($_GET['subject'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';
    $status = isset($_GET['status']) ? sanitizeInput(trim($_GET['status'])) : '';

    // Pagination
    $limit = 6; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the query
    $rows = ($limit * ($page - 1));

    $questions = toJson($pdo->select("SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = ? AND `id` = ?) AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit OFFSET $offset", [1, Session::get('loggedin')])->fetchAll(PDO::FETCH_ASSOC));

    $search = "SELECT `d`.*, `u`.fullname FROM `document` `d`, `users` `u` WHERE `d`.`user_id`= `u`.`id` AND `d`.`user_id` IN (SELECT `id` FROM `users` WHERE `is_agent` = 1 AND `id` = $currentUser->id)AND `u`.`fullname` LIKE '%$full_name%' AND `d`.`exam_body` LIKE '%$subject%' AND `d`.`year` LIKE '%$year%' AND `d`.`published` LIKE '%$status%' LIMIT $limit";


    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];

        $pdo->delete('DELETE FROM `document` WHERE id=?', [$questionId]);

        $successMessage = "You have successfully deleted question ID {$questionId}.";

        redirect('view-current-agent-past-questions', $successMessage, 'success');
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

            $resp = json_encode(['status' => 'success']);
            echo $resp;
            die;
        }
    }


}



require_once 'view/loggedin/agent/view-current-agent-past-questions.php';