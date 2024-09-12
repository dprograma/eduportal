<?php

$title = 'Create Post' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}
if (!empty(Session::get('loggedin'))) {

    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    $posts = $pdo->select("SELECT * FROM posts", [])->fetchAll(PDO::FETCH_OBJ);




    if (isset($_GET['publish'])) {

     dnd($_GET['publish']);

    }

    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        if (isset($_POST['edit-post'])) {
            $title = sanitizeInput($_POST['title']);
            $category = sanitizeInput($_POST['category']);
            $body = sanitizeInput($_POST['body']);
            $img = $_FILES['upload'];

        $path = fileUpload($img);

        if(is_array($path)){
            //error happend
        }
    
           
$pdo->update(
    'UPDATE posts SET title=?, category=?, body=?, img=? WHERE id=?',
    [$title, $category, $body, $path, $userId]
);

    
            if ($pdo->status) {
                redirect('viewpost', 'Post updated successfully', 'success');
            } else {
                redirect('viewpost', 'Post Failed to Update', 'danger');
            }
        }
    }
    

    require_once 'view/loggedin/admin/edit-post.php';

}
