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
        $postId = $_GET['id'];
        $post = $pdo->select("SELECT * FROM posts WHERE id=?", [$postId])->fetch(PDO::FETCH_OBJ);

        if (isset($_POST['edit-post'])) {
            $title = sanitizeInput($_POST['title']);
            $category = sanitizeInput($_POST['category']);
            $body = sanitizeInput($_POST['body']);
            $img = $_FILES['upload'];
            $path = fileUpload($img);

            if (is_array($path)) {
                // error happened
            }

            $pdo->update(
                'UPDATE posts SET title=?, category=?, body=?, img=? WHERE id=?',
                [$title, $category, $body, $path, $postId]
            );

            if ($pdo->status) {
                redirect('viewpost', 'Post updated successfully', 'success');
            } else {
                redirect('viewpost', 'Post Failed to Update', 'danger');
            }
        } else {
            // display the edit post form with the specific post data
            require_once 'view/loggedin/admin/edit-post.php';
        }
    } else {
        // display the edit post form with an empty form
        require_once 'view/loggedin/admin/edit-post.php';
    }
}