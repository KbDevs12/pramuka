<?php
session_start();
require('config/app.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    echo "<p>Error uploading file. Please try again.</p>";
    exit();
}

$file = $_FILES['payment_proof'];
$trx_code = sanitize_input($_POST('trx_code'));
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($file['type'], $allowedTypes)) {
    echo "<p>Invalid file type. Please upload a JPG or PNG image.</p>";
    exit();
}

$imageData = base64_encode(file_get_contents($file['tmp_name']));
$base64Image = 'data:' . $file['type'] . ';base64,' . $imageData;