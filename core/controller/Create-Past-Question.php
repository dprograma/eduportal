<?php

$title = 'Create Past Question' . '|' . SITE_TITLE;

if(isset($_POST['logout'])){
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if(!empty(Session::get('loggedin'))){
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    $selectedExamBody = '';
    $selectedSubject = '';
    $selectedExamYear = '';

    // Check if session data exists and set selected options accordingly
    if (isset($_SESSION['selectedOptions'])) {
        $selectedExamBody = $_SESSION['selectedOptions']['ExamBody'];
        $selectedSubject = $_SESSION['selectedOptions']['Subject'];
        $selectedExamYear = $_SESSION['selectedOptions']['ExamYear'];
    }
    
    if(isset($_POST['create-question'])){
        
        $examBody = sanitizeInput(strtoupper($_POST['examBody']));
        $subject = sanitizeInput(strtoupper($_POST['subject']));
        $examYear = sanitizeInput(strtoupper($_POST['examYear']));
        $question= sanitizeInput(ucwords($_POST['question']));
        $optionA = sanitizeInput($_POST['optionA']);
        $optionB =  sanitizeInput($_POST['optionB']);
        $optionC = sanitizeInput($_POST['optionC']);
        $optionD =  sanitizeInput($_POST['optionD']);
        $optionE =  sanitizeInput($_POST['optionE']);
        $correctAnswer = sanitizeInput($_POST['correctAnswer']);

      
        $questionData = [
            'ExamBody' => $examBody,
            'Subject' => $subject,
            'ExamYear' => $examYear,
            'Question' =>  $question,
            'OptionA' => $optionA,
            'OptionB' =>  $optionB,
            'OptionC' =>  $optionC,
            'OptionD' =>  $optionD,
            'CorrectAnswer' =>  $correctAnswer,

        ];

        $msg = isEmpty(  $questionData);

        if ($msg != 1) {
            redirect('create-past-question', $msg);
        }
   
        $pdo->insert('INSERT INTO past_question(exam_body,`subject`,exam_year,question,option_a, option_b, option_c, option_d, option_e, correct_answer, user_id) VALUES(?,?,?,?,?,?,?,?,?,?,?)', [$questionData['ExamBody'],$questionData['Subject'],$questionData['ExamYear'],$questionData['Question'],$questionData['OptionA'],$questionData['OptionB'],$questionData['OptionC'],$questionData['OptionD'],$optionE,$questionData['CorrectAnswer'],$currentUser->id]);

        if ($pdo->status) {
    redirect('create-past-question', "Past Question created Successfully", 'success');
}


    }
  

  
           
        


require_once 'view/loggedin/admin/create-past-question.php';

    }

  
  


