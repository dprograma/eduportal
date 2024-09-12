<?php

$title = 'Create Post' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}

if (!empty(Session::get('loggedin'))) {

    $currentUser = $pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_OBJ);

    $title = isset($_GET['title']) ? sanitizeInput(trim($_GET['title'])) : '';
    $year = isset($_GET['year']) ? sanitizeInput(trim($_GET['year'])) : '';
    $status = isset($_GET['status']) ? sanitizeInput(trim($_GET['status'])) : '';
    $category = isset($_GET['category']) ? sanitizeInput(trim($_GET['category'])) : '';

    // Pagination
    $limit = 6; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the query
    $rows = ($limit * ($page - 1));

    $posts = $pdo->select("SELECT * FROM posts WHERE `title` LIKE '%$title%' AND `category` LIKE '%$category%' AND `date_created` LIKE '%$year%' AND `publish` LIKE '%$status%' LIMIT $limit OFFSET $offset")->fetchAll(PDO::FETCH_OBJ);

    $search = "SELECT COUNT(*) AS total FROM posts WHERE `title` LIKE '%$title%' AND `category` LIKE '%$category%' AND `date_created` LIKE '%$year%' AND `publish` LIKE '%$status%' LIMIT $limit";

    if (isset($_GET['id'])) {
        $postId = $_GET['id'];

        $pdo->delete('DELETE FROM posts WHERE id=?', [$postId]);

        $successMessage = "You have successfully deleted post ID {$postId}.";

        redirect('viewpost', $successMessage, 'success');
        exit();
    }

    if (isset($_POST['publish'])) {
        $id = $_POST['postId'];

        $currentPost = $pdo->select("SELECT * FROM posts WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC);

        $status = $currentPost['publish'] == 1 ? 0 : 1;

        $pdo->update('UPDATE posts SET publish =? WHERE id=?', [$status, $id]);

        if ($pdo->status) {
            $resp = json_encode(['status' => 'success']);
            echo $resp;
            die;
        }
    }

    if (isset($_POST['type'])) {
        $id = $_POST['id'];

        $currentPost = $pdo->select("SELECT * FROM posts WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC);

        if ($pdo->status) {
            $resp = json_encode(['status' => 'success', 'data' => $currentPost]);
            echo $resp;
            die;
        }
    }

    require_once 'view/loggedin/admin/view.viewpost.php';
}
