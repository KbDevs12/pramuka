<?php
require('../config/app.php');
require('../func/sanitize.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = sanitize_input($_POST['id']);
    $status = sanitize_input($_POST['status']);

    $stmt = $conn->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // header('Location: index.php?page=transaksi');
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update status']);
    }

    $stmt->close();
    exit();
}
?>