<?php 

$title = 'News'.'|'.SITE_TITLE;

$posts = toJson($pdo->select("SELECT * FROM posts WHERE publish=? ",[1])->fetchAll(PDO::FETCH_ASSOC));


  





require_once 'view/guest/news.php';
