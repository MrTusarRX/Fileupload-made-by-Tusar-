<?php
if (!isset($_GET['file'])) {
    die(" File ID is required.");
}

$file_id = basename($_GET['file']);
$api_url = "https://tkserver.serv00.net/DownloadHub/api/publicaccess.php";
$api_key = "xtusar";

$data = json_encode([
    'api_key' => $api_key,
    'file_id' => $file_id
]);

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($http_code == 200) {
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $content_type);
    header('Content-Disposition: attachment; filename="' . $file_id . '"');
    header('Content-Length: ' . strlen($response));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: public');

    echo $response;
    exit;
} else {
    echo "Failed to download file. HTTP Code: $http_code";
}
