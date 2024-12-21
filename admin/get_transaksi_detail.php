<?php
require('../config/app.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing ID']);
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT t.*, k.nama as kategori_nama, p.nama as nama_perlombaan
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    JOIN perlombaan p ON p.kategori_id = k.id
    WHERE t.id = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'No transaction found']);
}
$stmt->close();
