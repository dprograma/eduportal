<?php
$title = 'Dashboard' . '|' . SITE_TITLE;


if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));

    // if ($currentUser->access == 'secured' && $currentUser->amount >= 1000) {
    if ($currentUser->access == 'secured') {
        $data = json_decode(file_get_contents("php://input"));

        if ($data) {
            // Assuming you receive the selected parameters from the user
            $examBody = $data->examBody;
            $subject = $data->subject;
            $examYear = $data->examYear;

            // Fetch questions for the selected exam body, subject, and exam year
            $questions = $pdo->select("SELECT * FROM past_question
            WHERE examBody = :exam_body
            AND subject = :`subject`
            AND examYear = :exam_year
            AND publish = 1", [
                'examBody' => $examBody,
                'subject' => $subject,
                'examYear' => $examYear
            ])->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // If no parameters are selected, fetch all published questions
            $questions = $pdo->select("SELECT * FROM past_question
            WHERE publish = 1")->fetchAll(PDO::FETCH_ASSOC);
        }

        if (isset($_GET['questionsDetails'])) {
            $examBody = isset($_GET['examBody']) ? sanitizeInput($_GET['examBody']) : '';
            $examYear = isset($_GET['examYear']) ? sanitizeInput($_GET['examYear']) : '';
            $subject = isset($_GET['subject']) ? sanitizeInput($_GET['subject']) : '';

            // Now use $examBody, $examYear, $subject in a new query to fetch questions
            $questions = $pdo->select("SELECT * FROM past_question WHERE exam_body = ? AND exam_year = ? AND subject = ? AND publish = 1", [$examBody, $examYear, $subject])->fetchAll(PDO::FETCH_ASSOC);

            if ($questions) {

                // Include assessment.php with questions data
                require_once 'view/loggedin/secured/assessment.php';


                exit();
            } else {
                $error = "Questions not found. Please check the details and try again.";
            }
        }

    }
    // else {
    //     redirect('first-sub');
    // }
    require_once 'view/loggedin/secured/cbt-test.php';
}