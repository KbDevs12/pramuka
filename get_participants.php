<?php
// get_participants.php
session_start();
require('config/app.php');

header('Content-Type: application/json');

$competition_id = $_GET['competition_id'] ?? null;
$trx_code = $_GET['trx_code'] ?? null;

if (!$competition_id || !$trx_code) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$query = "SELECT p.* FROM peserta p 
          JOIN transaksi t ON p.idTransaksi = t.id 
          WHERE p.id = ? AND t.kodeTransaksi = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('is', $competition_id, $trx_code);
$stmt->execute();
$result = $stmt->get_result();

$participants = [];
while ($row = $result->fetch_assoc()) {
    $participants[] = [
        'id' => $row['id'],
        'nama' => $row['nama'],
        'tempat_lahir' => $row['tempat_lahir'],
        'tanggal_lahir' => $row['tanggal_lahir'],
        'jabatan' => $row['jabatan'],
        'image' => $row['image']
    ];
}

echo json_encode([
    'exists' => count($participants) > 0,
    'participants' => $participants
]);