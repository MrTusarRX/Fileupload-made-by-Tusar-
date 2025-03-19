# 🚀 Secure File Upload & Download System  

This project allows secure file uploading and downloading using PHP and cURL with API key-based authentication.  
It automatically detects the base URL and works with any file type.  

---

## 🛠️ Features  
✅ Secure file upload using cURL  
✅ Secure file download with API key authentication  
✅ Auto-detecting base URL for flexibility  
✅ Works with any file type  
✅ Simple and clean interface  

---

## 📂 Project Structure  
├── api/ # Contains the backend API files │ ├── upload.php # Handles file uploads │ ├── download.php # Handles file downloads ├── uploads/ # Uploaded files are stored here ├── index.php # Main frontend file ├── README.md # Project documentation


---

## 🏗️ Installation  
### 1. Clone the repository:  
```bash
git clone https://github.com/MrTusarRX/secure-file-upload.git
```
### 1. Clone the repository:  
```bash
git clone https://github.com/MrTusarRX/secure-file-upload.git
cd secure-file-upload
```
SECRET_KEY="mysecretkey"

⚙️ Configuration
✅ Auto-Detect Base URL

The base URL is automatically detected using PHP's $_SERVER variables:


$baseUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$uploadUrl = rtrim($baseUrl, '/') . "/api/upload.php";
$downloadUrl = rtrim($baseUrl, '/') . "/api/download.php";


🚀 Usage
✅ 1. Upload a File

    Open index.php in your browser
    Choose a file and hit "Upload"
    The file ID will be shown after a successful upload

✅ 2. Download a File

    Enter the file ID in the download form
    Hit "Download"
    The file will be downloaded using secure cURL


# 💻 Example Code

## 📤 Upload Example:
```php
$file = $_FILES['file'];  
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
    echo "❌ Failed to download file.";  
}

```
## 🧠 Notes  
- ✅ **Make sure the `uploads/` folder has write permissions.**  
- ✅ **Files are secured with an API key-based authentication.**  
- ✅ **For better security, store the secret key in an `.env` file.**  


