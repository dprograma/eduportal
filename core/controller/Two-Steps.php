<?php

$title = 'two steps' . '|' . SITE_TITLE;

if (!isset($_SESSION['email_verification_required']) || !$_SESSION['email_verification_required']) {
    header('Location: auth-login'); 
    exit;
}
$access = 'guest'; 

if (isset($_POST['verifyCode'])) {
    $verification = sanitizeInput($_POST['verification']);
  

    $storedOtp = $pdo->select("SELECT verification FROM users WHERE id=?", [ $verification])->fetch(PDO::FETCH_ASSOC);

    if ( $verification ===  $storedOtp ) {
        $pdo->update("UPDATE users SET access = 'secured' WHERE email = ?",Session::get('loggedin'));
 echo $storedOtp;
//         if($pdo->status){
//             $resp = json_encode(['access'=>'secured']);
//  //send a welcome email to the user
//  $welcomeMsg = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Document</title>
//  </head><body>
//      <h1 style="color:skyblue;">Welcome' . $userData["UserName"] . ' to Trifty</h1>
//      <p style="line-height:1.6; text-align:justify">
//          Were Gossip and backbitting trends, Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eius ea beatae nulla consectetur veritatis ut est minus, voluptate ullam sequi nam officiis! Atque voluptates recusandae qui! Dolor vero maxime labore?
//      </p>
//  </body>
//  </html>';
 
 
//          try {
            
//              //Recipients
//              $mail->setFrom('uzkat.au@gmail.com', 'Trifty Support');
//              $mail->addAddress($userData['Email']); //Add a recipient
//              // $mail->addAddress('ellen@example.com'); //Name is optional
//              $mail->addReplyTo('uzkat.au@gmail.com', );
//              // $mail->addCC('cc@example.com');
//              // $mail->addBCC('bcc@example.com');
 
//              //Attachments
//              // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//              //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
 
//              //Content
//              $mail->isHTML(true); //Set email format to HTML
//              $mail->Subject = 'Welcome';
//              // $mail->Body = "Welcome <b>".$userData['UserName']."</b> <br> You are welcomed to Trifty Blog were gossip and Back-biting are the order of the day";
//              $mail->Body = $welcomeMsg;
 
 
//              // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
 
//              $mail->send();
//              echo 'Message has been sent';
//          } catch (Exception $e) {
//              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//          }
 
//          redirect('auth-login', "Registeration Successful", 'success');
            


//         }

    } else {
        // Incorrect OTP, display an error message
        $error = "Invalid OTP. Please try again.";
    }

   
}


























require_once 'view/guest/auth/auth-two-steps.php';
