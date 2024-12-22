<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}
// Create
if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $peserta = $_POST['peserta'];
    $deskripsi = $_POST['deskripsi'];
    $waktu = $_POST['waktu'];
    $kategori_id = $_POST['kategori_id'];

    $stmt = $conn->prepare("INSERT INTO perlombaan (nama, peserta, deskripsi, waktu, kategori_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisii", $nama, $peserta, $deskripsi, $waktu, $kategori_id);
    if ($stmt->execute()) {
        header('Location: index.php?page=perlombaan');
    }
    $stmt->close();
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $peserta = $_POST['peserta'];
    $deskripsi = $_POST['deskripsi'];
    $waktu = $_POST['waktu'];
    $kategori_id = $_POST['kategori_id'];

    $stmt = $conn->prepare("UPDATE perlombaan SET nama = ?, peserta = ?, deskripsi = ?, waktu = ?, kategori_id = ? WHERE id = ?");
    $stmt->bind_param("sisiii", $nama, $peserta, $deskripsi, $waktu, $kategori_id, $id);
    if ($stmt->execute()) {
        header('Location: index.php?page=perlombaan');
    }
    $stmt->close();
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM perlombaan WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: index.php?page=perlombaan');
    }
    $stmt->close();
}