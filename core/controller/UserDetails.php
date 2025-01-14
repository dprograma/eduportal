<?php
if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('login');
}

if(!empty(Session::get('loggedin'))){
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        $user = $pdo->select("SELECT fullname, email, profileimg, access, created_date FROM users WHERE id=?", [$userId])->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
}