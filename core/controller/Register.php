<?php

$title = 'User registration' . '|' . SITE_TITLE;

if (isset($_POST['register'])) {

    $userName = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $_SESSION['user_email'] = $email;
    $fullname = sanitizeInput(ucwords($_POST['name']));
    $password = sanitizeInput($_POST['password']);
    $confirm = sanitizeInput($_POST['confirm-password']);
    $verification = generateUniqueCode(6);
    $profileimg = 'assets/avatars/money-avatar.png
';


    $userData = [
        'UserName' => $userName,
        'Email' => $email,
        'FullName' => $fullname,
        'password' => $password,
        'Confirm' => $confirm,
        'ProfileImg' => $profileimg,
    ];

    $msg = isEmpty($userData);

    if ($msg != 1) {
        // redirect('auth-register', $msg);
        redirect('signup', $msg);
    }

    if ($userData['password'] != $userData['Confirm']) {

        // redirect('auth-register', "Password does not match.");
        redirect('signup', "Password does not match.");

    }


    $res = $pdo->select("SELECT * FROM users WHERE username=? or email=?", [$userData['UserName'], $userData['Email']])->fetchAll(PDO::FETCH_BOTH);

    if (!empty($res)) {
        foreach ($res as $user) {
            if ($user['email'] == $userData['Email']) {
                // redirect('auth-register', "Email already exists");
                redirect('signup', "Email already exists");
            } elseif ($user['username'] == $userData['UserName']) {
                // redirect('auth-register', "Username already exists");
                redirect('signup', "Username already exists");
            }
        }
    }

    $hashedPass = password_hash($userData['password'], PASSWORD_DEFAULT);

    if (Session::exists('new-agent')) {
        $pdo->insert('INSERT INTO users(username,email, fullname, `password`, verification, profileimg, is_agent, access) VALUES(?,?,?,?,?,?,?,?)', [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verification, $userData['ProfileImg'], true, 'secured']);

        // $date = date('d-m-Y H:i:s');

        // $pdo->insert('INSERT INTO `agents`(`agent_id`, `user_id`, `wallet_balance`, `created_at`, `updated_at`) VALUES (?,?,?,?)', [$currentUser->id, $currentUser->id, '', $date, $date]);
        
    } else {
        $pdo->insert('INSERT INTO users(username,email, fullname, `password`, verification, profileimg, access) VALUES(?,?,?,?,?,?,?)', [$userData['UserName'], $userData['Email'], $userData['FullName'], $hashedPass, $verification, $userData['ProfileImg'], 'secured']);
    }




    //     $_SESSION['email_verification_required'] = true;
// header('Location: auth-two-steps'); 
// exit;
    if ($pdo->status) {
        // $pdo->update("UPDATE users SET access = 'secured' WHERE email = ?", [ $userData['Email']]);
        // Session::exists('new-agent') ? redirect('auth-login', "registration Successful", 'success') : redirect('first-sub', 'Please Make Payment');

        //send a welcome email to the user
        $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Document</title>
</head><body>
    <h1 style="color:skyblue;">Welcome' . $userData["UserName"] . ' to Trifty</h1>
    <p style="line-height:1.6; text-align:justify">
        Were Gossip and backbitting trends, Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eius ea beatae nulla consectetur veritatis ut est minus, voluptate ullam sequi nam officiis! Atque voluptates recusandae qui! Dolor vero maxime labore?
    </p>
</body>
</html>';


        // try {

        //     //Recipients
        //     $mail->setFrom('owunnaizum@gmail.com', 'Trifty Support');
        //     $mail->addAddress($userData['Email']); //Add a recipient
        //     // $mail->addAddress('ellen@example.com'); //Name is optional
        //     $mail->addReplyTo('owunnaizum@gmail.com', );
        //     // $mail->addCC('cc@example.com');
        //     // $mail->addBCC('bcc@example.com');

        //     //Attachments
        //     // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //     //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //     //Content
        //     $mail->isHTML(true); //Set email format to HTML
        //     $mail->Subject = 'Welcome';
        //     // $mail->Body = "Welcome <b>".$userData['UserName']."</b> <br> You are welcomed to Trifty Blog were gossip and Back-biting are the order of the day";
        //     $mail->Body = $welcomeMsg;


        //     // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //     $mail->send();
        //     echo 'Message has been sent';
        // } catch (Exception $e) {
        //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // }

        redirect('auth-login', "registration Successful", 'success');
    }



    exit;

}

require_once 'view/guest/auth/signup.php';
