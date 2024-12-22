<?php
require('../config/app.php');
require('../func/sanitize.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize_input($_POST['id']);
    $status = sanitize_input($_POST['status']);

    if ($status === 'lunas') {

        $stmt = $conn->prepare("SELECT jumlahDibayarkan, sisaPembayaran FROM transaksi WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($jumlahDibayarkan, $sisaPembayaran);
        $stmt->fetch();
        $stmt->close();
        $newJumlahDibayarkan = $jumlahDibayarkan + $sisaPembayaran;

        $stmt = $conn->prepare("UPDATE transaksi SET status = ?, jumlahDibayarkan = ?, sisaPembayaran = 0 WHERE id = ?");
        $stmt->bind_param("sii", $status, $newJumlahDibayarkan, $id);
    } else {
        $stmt = $conn->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update status']);
    }

    $stmt->close();
    exit();
}
