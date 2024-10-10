const express = require('express');
const multer = require('multer');
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;
const upload = multer({ dest: 'uploads/' });

app.use(express.static('public'));
app.use(express.urlencoded({ extended: true }));

// Password protection and file upload
app.post('/upload', upload.single('file'), (req, res) => {
    const password = req.body.password;
    const filePath = path.join(__dirname, '../uploads', req.file.filename);
    
    // Save password in a simple way (this is not secure for production)
    fs.writeFileSync(`${filePath}.txt`, password);
    
    res.send(`<h1>File uploaded successfully!</h1><p>Your file is saved with password: ${password}</p>`);
});

// Download file with password
app.post('/download', (req, res) => {
    const { filename, password } = req.body;
    const filePath = path.join(__dirname, '../uploads', filename);

    // Check password
    const savedPassword = fs.readFileSync(`${filePath}.txt`, 'utf-8');
    if (savedPassword === password) {
        res.download(filePath);
    } else {
        res.status(403).send('Incorrect password');
    }
});

app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
