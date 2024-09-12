<?php

$title = 'Create Post' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}

if (!empty(Session::get('loggedin'))) {

    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    $subject = isset($_GET['subject']) ? sanitizeInput(trim($_GET['subject'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';
    $status = isset($_GET['status']) ? sanitizeInput(trim($_GET['status'])) : '';
    $exam_body = isset($_GET['exam_body']) ? sanitizeInput(trim($_GET['exam_body'])) : '';


    // Pagination
    $limit = 6; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the query
    $rows = ($limit * ($page - 1));
    $user_id = Session::get('loggedin');

    if ($currentUser->is_agent) {
        $questions = $pdo->select("SELECT * FROM document WHERE user_id=? AND `subject` LIKE '%$subject%' AND `year` LIKE '%$year%' AND `published` LIKE '%$status%' AND `exam_body` LIKE '%$exam_body%' LIMIT $limit OFFSET $offset", [Session::get('loggedin')])->fetchAll(PDO::FETCH_OBJ);

        $search = "SELECT COUNT(*) AS total FROM document WHERE user_id='$user_id' AND `subject` LIKE '%$subject%' AND `year` LIKE '%$year%' AND `published` LIKE '%$status%' AND `exam_body` LIKE '%$exam_body%' LIMIT $limit";
    }else{
        $questions = $pdo->select("SELECT * FROM past_question WHERE `subject` LIKE '%$subject%' AND `exam_year` LIKE '%$year%' AND `publish` LIKE '%$status%' AND `exam_body` LIKE '%$exam_body%' LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_OBJ);

        $search = "SELECT COUNT(*) AS total FROM past_question WHERE `subject` LIKE '%$subject%' AND `exam_year` LIKE '%$year%' AND `publish` LIKE '%$status%' AND `exam_body` LIKE '%$exam_body%' LIMIT $limit";
    }


    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];

        $pdo->delete('DELETE FROM past_question WHERE id=?', [$questionId]);

        $successMessage = "You have successfully deleted question ID {$questionId}.";

        redirect('view-past-questions', $successMessage, 'success');
        exit();
    }

    if (isset($_POST['publish'])) {
        $id = $_POST['questionId'];
        $currentQuestion = $pdo->select("SELECT * FROM past_question WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC);

        $status = $currentQuestion['publish'] == 1 ? 0 : 1;

        $pdo->update('UPDATE past_question SET publish =? WHERE id=?', [$status, $id]);

        if ($pdo->status) {
            $resp = json_encode(['status' => 'success']);
            echo $resp;
            die;
        }
    }

    if (isset($_POST['type'])) {
        $id = $_POST['id'];
        $currentQuestion = $pdo->select("SELECT * FROM past_question WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC);

        if ($pdo->status) {
            $resp = json_encode(['status' => 'success', 'data' => $currentQuestion]);
            echo $resp;
            die;
        }
    }

    require_once 'view/loggedin/admin/view-past-questions.php';
}

