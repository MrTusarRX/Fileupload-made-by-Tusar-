<?php
session_start();

$secret_key = "xtusar";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(" Method Not Allowed");
}

$input = json_decode(file_get_contents('php://input'), true);

if (
    !isset($input['api_key']) || 
    !isset($input['file_id']) || 
    $input['api_key'] !== $secret_key
) {
    http_response_code(403);
    die("Access Denied");
}

$fileId = basename($input['file_id']);
$filePath = __DIR__ . '/../uploads/' . $fileId;

if (!file_exists($filePath)) {
    http_response_code(404);
    die("File not found");
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileId . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: public');

readfile($filePath);
exit;
