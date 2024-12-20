<?php
session_start();
require('config/app.php');
require('func/sanitize.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$competition_id = $_POST['competition_id'] ?? null;
$trx_code = $_POST['trx_code'] ?? null;

if (!$competition_id || !$trx_code) {
    $_SESSION['error'] = 'Data tidak lengkap';
    header('Location: pendaftaran.php?trx-code=' . $trx_code);
    exit;
}

$query = "SELECT id FROM transaksi WHERE kodeTransaksi = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $trx_code);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    $_SESSION['error'] = 'Transaksi tidak ditemukan';
    header('Location: pendaftaran.php?trx-code=' . $trx_code);
    exit;
}

foreach ($_POST['participants'] as $index => $participant) {
    $nama = sanitize_input($participant['nama']);
    $tempat_lahir = sanitize_input($participant['tempat_lahir']);
    $tanggal_lahir = sanitize_input($participant['tanggal_lahir']);
    $jabatan = sanitize_input($participant['jabatan']);

    $image = $_FILES['participants']['tmp_name'][$index]['image'] ?? null;

    if ($image) {
        $imageData = file_get_contents($image);
        $imageBase64 = base64_encode($imageData);
    } else {
        $imageBase64 = null;
    }
    $query = "INSERT INTO peserta (id_perlombaan, idTransaksi, nama, tempat_lahir, tanggal_lahir, jabatan, image) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'iisssss',
        $competition_id,
        $transaction['id'],
        $nama,
        $tempat_lahir,
        $tanggal_lahir,
        $jabatan,
        $imageBase64
    );

    if (!$stmt->execute()) {
        $_SESSION['error'] = 'Gagal menyimpan peserta: ' . $stmt->error;
        header('Location: pendaftaran.php?trx-code=' . $trx_code);
        exit;
    }
}