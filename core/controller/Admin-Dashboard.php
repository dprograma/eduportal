<?php

$title = 'Dashboard' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if(!empty(Session::get('loggedin'))){
    
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));


    $totalVerifiedUsers = $pdo->select("SELECT COUNT(*) AS total_signups FROM users WHERE access = 'secured'")->fetchColumn();


    $totalUnverifiedUsers = $pdo->select("SELECT COUNT(*) AS total_signups FROM users WHERE access = 'guest'")->fetchColumn();

    
    $totalQuestionsUploaded = $pdo->select("SELECT COUNT(*) AS total_questions FROM past_question")->fetchColumn();

    $totalAmount = $pdo->select("SELECT SUM(amount) AS total_amounts FROM users")->fetchColumn();


    $full_name = isset($_GET['fullname']) ? sanitizeInput(trim($_GET['fullname'])) : '';
    $email = isset($_GET['email']) ? sanitizeInput(trim($_GET['email'])) : '';
    $access = isset($_GET['access']) ? sanitizeInput(trim($_GET['access'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';
    

     // Pagination
     $limit = 6; // Number of items per page
     $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
     $offset = ($page - 1) * $limit; // Offset for the query
     $rows = ($limit * ($page-1));

    // $usersData = $pdo->select("SELECT id, username,fullname, email, created_date, access FROM users")->fetchAll(PDO::FETCH_ASSOC);

    $usersData = $pdo->select("SELECT id, username, fullname, email, created_date, access FROM users WHERE fullname LIKE '%$full_name%' AND email LIKE '%$email%' AND access LIKE '%$access' AND created_date LIKE '%$year%' AND access != 'Admin' LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_ASSOC);

    $search = "SELECT COUNT(*) AS total FROM users WHERE fullname LIKE '%$full_name%' AND email LIKE '%$email%' AND access LIKE '%$access' AND created_date LIKE '%$year%' AND access != 'Admin' LIMIT $limit";
    


    if (isset($_GET['id'])) {
        $userId = $_GET['id'];

       
        $pdo->delete('DELETE FROM users  WHERE id=?', [$userId]);
    
        $successMessage = "You have successfully deleted user ID {$userId}.";

        redirect('admin-dashboard', $successMessage, 'success');
        exit();
    }

    
    require_once 'view/loggedin/admin/admin-dashboard.php';


}


