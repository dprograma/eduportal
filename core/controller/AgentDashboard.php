<?php

$title = 'Agent Dashboard' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if(!empty(Session::get('loggedin'))){
    
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    
    $totalQuestionsUploaded = $pdo->select("SELECT COUNT(*) AS `total_questions` FROM `document` WHERE `user_id` = (SELECT DISTINCT `id` FROM `users` WHERE `id` = '$currentUser->id' AND `is_agent` = 1); ")->fetchColumn();

    // $totalAmount = $pdo->select("SELECT SUM(amount) AS total_amounts FROM users")->fetchColumn();
    $totalAmount = $pdo->select("SELECT SUM(price) FROM `document` WHERE user_id = '$currentUser->id' AND `published` = ?", [1])->fetchColumn();
    $totalAmount = computeAgentCommission($totalAmount);
    $usersData = $pdo->select("SELECT id, username,fullname, email, created_date, access FROM users")->fetchAll(PDO::FETCH_ASSOC);

    $pastQuestionsApproved = toJson($pdo->select("SELECT COUNT(*) as `approved` FROM `document` WHERE `user_id` = '$currentUser->id' AND `published` = ?", [1])->fetch(PDO::FETCH_ASSOC));

    echo $pastQuestionsApproved->approved;

    $pastQuestionsUploaded = tojson($pdo->select("SELECT COUNT(*) as `total` FROM `document` WHERE `user_id` = '$currentUser->id'")->fetch(PDO::FETCH_ASSOC));

    echo $pastQuestionsUploaded->total;

    $pastQuestionsPending = toJson($pdo->select("SELECT COUNT(*) `pending` FROM `document` WHERE `user_id` = '$currentUser->id' AND `published` = ?", [0])->fetch(PDO::FETCH_ASSOC));

    echo $pastQuestionsPending->pending;

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];

       
        $pdo->delete('DELETE FROM users  WHERE id=?', [$userId]);
    
        $successMessage = "You have successfully deleted user ID {$userId}.";

        redirect('agent-dashboard', $successMessage, 'success');
        exit();
    }

    
    require_once 'view/loggedin/agent/agent-dashboard.php';


}


