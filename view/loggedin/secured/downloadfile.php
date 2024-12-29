<?php
// URL of the PDF or Word document
$sku = $_GET['sku'];
$document = $pdo->select("SELECT * FROM `document` WHERE `sku`= ?", [$sku]);
$document = $document->fetch(PDO::FETCH_ASSOC);
$file_url = $document['filename'];
$file_ext = pathinfo($file_url, PATHINFO_EXTENSION);
$file_name = $document['subject'] . "_" . $document['exam_body'] . "_" . $document['year'] . "_Past_Question" . "." . $file_ext;

// Function to download the file
function downloadFile($url, $filename) {
    // Set headers to force download
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    // Output the file content to the browser
    readfile($url);
}

// Call the function to download the file
downloadFile($file_url, $file_name);