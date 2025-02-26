<?php 

$title = 'News'.'|'.SITE_TITLE;

$posts = $pdo->select("SELECT * FROM posts WHERE publish=? ORDER BY date_edited DESC",[1])->fetchAll(PDO::FETCH_ASSOC);

require_once 'view/guest/news.php';

