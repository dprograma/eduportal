<?php


$title = $post->title . '|' . SITE_TITLE;

if ($questions) {
    // Include the code to display questions
    require_once 'view/loggedin/secured/past-question.php';
    header('Content-Type: application/json');

    // Return questions as JSON
    echo json_encode($questions);
    exit();
} else {
    // Handle case when there are no questions
    echo "No questions found.";
}

require_once 'view/loggedin/secured/assessment.php';
