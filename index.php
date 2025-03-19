<?php
session_start();

$secret_key = "mysecretkey";
$fileId = '';

//  Auto-detect Base URL
$baseUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$uploadUrl = rtrim($baseUrl, '/') . "/api/upload.php";
$downloadUrl = rtrim($baseUrl, '/') . "/api/download.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $curlFile = curl_file_create($file['tmp_name'], $file['type'], $file['name']);

        $postFields = [
            'file' => $curlFile
        ];

        $ch = curl_init($uploadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $secret_key
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $fileId = $response;
        } else {
            $fileId = "❌ Failed to upload file.";
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download'])) {
    $fileId = basename($_POST['search_id']);
    $downloadUrl = rtrim($baseUrl, '/') . "/api/download.php?file_id=" . urlencode($fileId);

    $ch = curl_init($downloadUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-KEY: ' . $secret_key
    ]);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200 && $result !== false) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileId . '"');
        header('Content-Length: ' . strlen($result));
        echo $result;
        exit;
    } else {
        $fileId = "❌ Failed to download file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Secure File Upload & Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        h2 {
            color: #4f46e5;
            font-size: 24px;
            margin-bottom: 16px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        input[type="file"],
        input[type="text"] {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            background-color: #f9fafb;
            transition: border-color 0.3s ease;
        }
        input[type="file"]:focus,
        input[type="text"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 8px rgba(99, 102, 241, 0.4);
        }
        button {
            padding: 12px;
            font-size: 16px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
        hr {
            margin: 20px 0;
            border: 0;
            height: 1px;
            background-color: #e5e7eb;
        }
        .file-id {
            margin-top: 16px;
            padding: 12px;
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #38bdf8;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Upload File</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required />
        <button type="submit" name="upload">Upload</button>
    </form>

    <?php if (!empty($fileId)): ?>
        <div class="file-id">
            File ID: <strong><?= htmlspecialchars($fileId) ?></strong>
        </div>
    <?php endif; ?>

    <hr>

    <h2>Download File</h2>
    <form action="" method="POST">
        <input type="text" name="search_id" placeholder="Enter File ID" required />
        <button type="submit" name="download">Download</button>
    </form>
</div>

</body>
</html>
