<?php

if(isset($_GET['title'])){

    $title = sanitizeInput(str_replace('_',' ',$_GET['title']));
    $res = $pdo->select("SELECT * FROM posts WHERE title=? LIMIT 1", [$title])->fetch(PDO::FETCH_ASSOC);

    $post = toJson(($res));
   
}
$title = $post->title . '|' . SITE_TITLE;



require_once 'view/guest/view.blogdetails.php';
