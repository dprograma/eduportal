<?php

// Define constants
const FILE_SIZE_LIMIT = 2000000; // 2MB
const ALLOWED_FILE_TYPES = ['pdf', 'doc', 'docx'];

// Initialize variables
$title = 'Upload Questions' . '|' . SITE_TITLE;
$currentUser = null;
$msg = '';

// Check if user is logged in
if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC));
}

// Handle logout request
if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('auth-login');
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileToUpload = $_FILES["fileToUpload"];
    $pageCover = $_FILES["page_cover"];

    // Validate file upload
    if ($fileToUpload['size'] > FILE_SIZE_LIMIT || $pageCover['size'] > FILE_SIZE_LIMIT) {
        $msg = "File size shouldnt be more than 2mb";
        redirect('upload-past-question.php', $msg);
    }

    if (!in_array(pathinfo($fileToUpload['name'], PATHINFO_EXTENSION), ALLOWED_FILE_TYPES)) {
        $msg = 'File type not allowed';
        redirect('upload-past-question.php', $msg);
    }

    // Move uploaded file to new location
    $targetDir = "uploads/past-questions/";
    $pageCoverDir = "uploads/page-cover/";
    $newFileName = generateRandomString(10) . "." . pathinfo($fileToUpload['name'], PATHINFO_EXTENSION);
    $pageCoverName = generateRandomString(10) . "_page_cover." . pathinfo($pageCover['name'], PATHINFO_EXTENSION);
    $targetFile = $targetDir . $newFileName;
    $pageCoverFile = $pageCoverDir . $pageCoverName;

    if (!move_uploaded_file($fileToUpload["tmp_name"], $targetFile)) {
        $msg = "Error uploading file to " . $targetFile;
        redirect('upload-past-question.php', $msg);
    }

    if (!move_uploaded_file($pageCover["tmp_name"], $pageCoverFile)) {
        $msg = "Error uploading page cover to " . $pageCoverFile;
        redirect('upload-past-question.php', $msg);
    }
    // Insert data into database
    try {
        $data = [
            'user_id' => $currentUser->id,
            'sku' => generateRandomString(10),
            'title' => sanitizeInput($_POST["title"] ?? ''),
            'author' => sanitizeInput($_POST["author"] ?? ''),
            'page_cover' => $pageCoverFile,
            'isbn' => sanitizeInput($_POST["isbn"] ?? ''),
            'subject' => sanitizeInput($_POST["subject"] ?? ''),
            'filename' => $targetFile,
            'document_type' => sanitizeInput($_POST["documentType"]),
            'exam_body' => sanitizeInput($_POST["examBody"] ?? ''),
            'year' => sanitizeInput($_POST["examYear"] ?? ''),
            'price' => sanitizeInput($_POST["price"]),
            'published' => $currentUser->is_agent ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $pdo->insert(
            "INSERT INTO document (user_id, sku, `title`, `author`, `page_cover`, `isbn`, `subject`, `filename`, `document_type`, exam_body, `year`, price, published, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$data['user_id'],
            $data['sku'],
            $data['title'],
            $data['author'],
            $data['page_cover'],
            $data['isbn'],
            $data['subject'],
            $data['filename'],
            $data['document_type'],
            $data['exam_body'],
            $data['year'],
            $data['price'],
            $data['published'],
            $data['created_at']]
        );

        $msg = $currentUser->is_agent ? "Your upload is under review. It normally takes 1 hour to 24hours for approval." : "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        redirect('upload-past-question.php', $msg, 'success');
    } catch (\PDOException $e) {
        $msg = "Error: " . $e->getMessage();
        redirect('upload-past-question.php', $msg);
    }
}

// Require view file
if ($currentUser->is_affiliate) {
    require_once 'view/loggedin/agent/affiliate-upload-past-question.php';
} elseif ($currentUser->is_agent) {
    require_once 'view/loggedin/agent/agent-upload-past-question.php';
} else {
    require_once 'view/loggedin/admin/upload-past-question.php';
}