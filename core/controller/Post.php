<?php
require_once 'core/utils.php';

$title = 'Create Post' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}
if(!empty(Session::get('loggedin'))){
    
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    if(file_exists('public/txt/category.txt')){
        $file = explode(',',trim(file_get_contents('public/txt/category.txt'),' '));
    }

    if(isset($_POST['create-post'])){
        
        $title = sanitizeInput($_POST['title']);
        $body = sanitizeInput($_POST['body']);
        $category = sanitizeInput($_POST['category']);
        $img = $_FILES['upload'];

        $path = fileUpload($img);

        if(is_array($path)){
            //error happend
        }

       

        $postData = [
            'Title' =>$title,
            'Body' => $body,
            'Category' =>$category
        ];

        $msg = isEmpty($postData);

        if ($msg != 1) {
            redirect('create-post', $msg);
        }

   
        $pdo->insert('INSERT INTO posts(title,body,author,category,img) VALUES(?,?,?,?,?)', [$postData['Title'],$postData['Body'],$currentUser->id,$postData['Category'],$path]);
        $lastInsertId = $pdo->lastInsertId(); 
        
        if ($pdo->status) {
            // Notify all users about new post
            $users = $pdo->select("SELECT id FROM users WHERE is_verified = '1'")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($users as $user) {
                create(
                    $user['id'],
                    'news',
                    'New Post Published',
                    "New post: {$title}",
                    "viewpost?id=" . $lastInsertId
                );
            }
            redirect('create-post', "Post created Successfully", 'success');
        }


    }

    if(isset($_POST['publish'])){
        $id = $_POST['id'];

        $currentPost = toJson($pdo->select("SELECT * FROM posts WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC));

     
        $status = $currentPost->publish == 1 ? 0 : 1;

        $pdo->update('UPDATE posts SET publish =? WHERE id=?', [$status,$id]);

        if($pdo->status){
            // Notify admin and post author
            create(
                $currentPost->author,
                'post_status',
                $status ? 'Post Published' : 'Post Unpublished',
                "Your post '{$currentPost->title}' has been " . ($status ? 'published' : 'unpublished'),
                "viewpost?id=" . $id
            );

            $resp = json_encode(['status'=>'success']);
            echo $resp; die;


        }
           
        



    }

    if(isset($_POST['type'])){
      
        $id = $_POST['id'];
    
        $currentPost = toJson($pdo->select("SELECT * FROM posts WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC));

       

        if ($pdo->status) {
            $resp = json_encode(['status'=>'success','data'=>$currentPost]);
            echo $resp;die;

        }

       
    }

    if(isset($_POST['edit-post'])){
        
    }
    
    require_once 'view/loggedin/admin/create-post.php';
}


