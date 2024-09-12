<?php
$title = 'Upload Questions' . '|' . SITE_TITLE;

if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target_dir = "uploads/past-questions/";
        $price = sanitizeInput($_POST["price"]);
        $subject = sanitizeInput($_POST["subject"]);
        $currentTime = date('Y-m-d H:i:s');
        $exambody = sanitizeInput($_POST["examBody"]);
        $year = sanitizeInput($_POST["examYear"]);
        $sku = generateRandomString(10);
        $allowed_size = 2000000; //2mb
        $allowed_type = array('pdf', 'doc', 'docx');
        $fileToUpload = $_FILES["fileToUpload"];
        $fileExtension = pathinfo($fileToUpload['name'], PATHINFO_EXTENSION);
        $fileSize = $fileToUpload['size'];
        $newFileName = $sku . "." . $fileExtension;
        $target_file = $target_dir . $newFileName;
        // File upload process
        if ($fileSize > $allowed_size) {
            $msg = "File size shouldnt be more than 2mb";
            redirect('upload-past-question.php', $msg);
        }

        if (!in_array($fileExtension, $allowed_type)) {
            $msg = 'File type not allowed';
            redirect('upload-past-question.php', $msg);
        }

        if (move_uploaded_file($fileToUpload["tmp_name"], $target_file)) {
            $msg = $currentUser->is_agent ? "Your upload is under review. It normally takes 1 hour to 24hours for approval.": "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";

            try {
                if ($currentUser->is_agent) {
                    $stmt = $pdo->insert("INSERT INTO document (user_id, sku, `subject`, `filename`, exam_body, year, price, published, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$currentUser->id, $sku, $subject, $target_file, $exambody, $year, $price, false, $currentTime]);
                } else {
                    $stmt = $pdo->insert("INSERT INTO document (user_id, sku, `subject`, `filename`, exam_body, year, price, published, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$currentUser->id, $sku, $subject, $target_file, $exambody, $year, $price, true, $currentTime]);
                }

            } catch (\PDOException $e) {
                $msg = "Error: " . $e->getMessage();
                redirect('upload-past-question.php', $msg);
                exit;
            }
            $currentUser->is_agent ? redirect('upload-past-question.php', $msg) : redirect('upload-past-question.php', $msg, 'success');
        } else {
            $msg = "Sorry, there was an error uploading your file.";
            redirect('upload-past-question.php', $msg);
        }
    }

    if ($currentUser->is_agent) {
        require_once 'view/loggedin/agent/agent-upload-past-question.php';
    } else {
        require_once 'view/loggedin/admin/upload-past-question.php';
    }

}



