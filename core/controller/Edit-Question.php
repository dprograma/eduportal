<?php
// Enable error reporting
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$title = 'Edit Question' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('home');
}
if (!empty(Session::get('loggedin'))) {
    try {
        $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        die("Error retrieving user data: " . $e->getMessage());
    }

    $id = $_GET['id'] ?? '';
    try {
        $questions = toJson($pdo->select("SELECT * FROM past_question WHERE id=?", [$id])->fetchAll(PDO::FETCH_ASSOC));
    } catch (Exception $e) {
        die("Error retrieving questions: " . $e->getMessage());
    }

    if (isset($_GET['publish'])) {
        dnd($_GET['publish']);
    }

    if (isset($_GET['id'])) {
        $questionId = $_GET['id'];
        if (isset($_POST['edit-question'])) {
            if (isset($_SESSION['initial_referer'])) {
                $referer = $_SESSION['initial_referer'];
            } else {
                $_SESSION['initial_referer'] = $_SERVER['HTTP_REFERER'];
            }
            $examBody = sanitizeInput($_POST['examBody']);
            $subject = sanitizeInput($_POST['subject']);
            $examYear = sanitizeInput($_POST['examYear']);
            $question = sanitizeInput($_POST['question']);
            $optionA = sanitizeInput($_POST['optionA']);
            $optionB = sanitizeInput($_POST['optionB']);
            $optionC = sanitizeInput($_POST['optionC']);
            $optionD = sanitizeInput($_POST['optionD']);
            $optionE = sanitizeInput($_POST['optionE']);
            $correctAnswer = sanitizeInput($_POST['correctAnswer']);

            try {
                // Update the question with the specified ID
                $pdo->update(
                    'UPDATE past_question SET exam_body=?, `subject`=?, exam_year=?, question=?, option_a=?, option_b=?, option_c=?, option_d=?, option_e=?, correct_answer=? WHERE id=?',
                    [$examBody, $subject, $examYear, $question, $optionA, $optionB, $optionC, $optionD, $optionE, $correctAnswer, $questionId]
                );

                if ($pdo->status) {
                    $_SESSION['referer'] = true;
                    $referer = isset($_SESSION['initial_referer']) ? $_SESSION['initial_referer'] : (isset($referer) ? $referer : '');
                    redirect($referer);
                } else {
                    redirect('view-past-questions', 'Question Failed to Update', 'danger');
                }
            } catch (Exception $e) {
                die("Error updating question: " . $e->getMessage());
            }
        }
    }

    require_once 'view/loggedin/admin/edit-question.php';
}

