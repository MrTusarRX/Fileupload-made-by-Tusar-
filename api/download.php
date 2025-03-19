<?php
$uploadDir = '../uploads/';
$secret_key = "mysecretkey";

// Check API key
if (!isset($_SERVER['HTTP_X_API_KEY']) || $_SERVER['HTTP_X_API_KEY'] !== $secret_key) {
    echo json_encode(["message" => "Access Denied"]);
    exit;
}

if (isset($_GET['file_id'])) {
    $fileId = basename($_GET['file_id']);
    $filePath = $uploadDir . $fileId;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileId . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo json_encode(["message" => "File not found"]);
        exit;
    }
} else {
    echo json_encode(["message" => "Invalid request"]);
}
