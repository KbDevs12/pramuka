<?php
session_start();
require('config/app.php');
require('func/sanitize.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

if (!isset($_POST['trx_code']) || empty($_POST['trx_code'])) {
    echo "<p>Kode transaksi tidak valid.</p>";
    exit();
}

$trx_code = sanitize_input($_POST['trx_code']);

// Cek apakah file bukti pembayaran sudah diunggah
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    echo "<p>Error uploading file. Please try again.</p>";
    exit();
}

$file = $_FILES['payment_proof'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

// Validasi tipe file
if (!in_array($file['type'], $allowedTypes)) {
    echo "<p>Invalid file type. Please upload a JPG or PNG image.</p>";
    exit();
}

// Ambil id_transaksi berdasarkan kodeTransaksi
$query = "SELECT id FROM transaksi WHERE kodeTransaksi = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $trx_code);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    echo "<p>Kode transaksi tidak ditemukan.</p>";
    exit();
}

$id_transaksi = $transaction['id'];

// Konversi file gambar ke format base64
$imageData = base64_encode(file_get_contents($file['tmp_name']));
$base64Image = $imageData;

// Simpan bukti transaksi ke database
$query = "INSERT INTO bukti_transaksi (id_transaksi, images) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('is', $id_transaksi, $base64Image);

if ($stmt->execute()) {
    header('Location: payment_success.php?trx-code=' . $id_transaksi);
} else {
    echo "<p>Terjadi kesalahan saat menyimpan bukti transaksi. Silakan coba lagi.</p>";
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>