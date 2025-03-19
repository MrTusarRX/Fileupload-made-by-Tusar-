<?php
session_start();

$secret_key = "mysecretkey";
if (
    !isset($_SERVER['HTTP_X_API_KEY']) || 
    $_SERVER['HTTP_X_API_KEY'] !== $secret_key
) {
    http_response_code(403);
    die("Access Denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = __DIR__ . '/../uploads/';
    $file = $_FILES['file'];
    $fileName = uniqid() . '-' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo $fileName; 
        exit;
    } else {
        http_response_code(500);
        die("Failed to upload file.");
    }
}
