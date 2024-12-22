<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Ambil data transaksi per kategori
$transaksiPerKategori = $conn->query("
    SELECT 
        k.nama as kategori, 
        COUNT(t.id) as jumlah,
        SUM(t.jumlahDibayarkan) as total_pendapatan
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    GROUP BY k.id
    ORDER BY jumlah DESC
");

$categories = [];
$counts = [];
$amounts = [];
while ($row = $transaksiPerKategori->fetch_assoc()) {
    $categories[] = $row['kategori'];
    $counts[] = (int) $row['jumlah'];
    $amounts[] = (int) $row['total_pendapatan'];
}

echo json_encode([
    'labels' => $categories,
    'data' => $counts,
    'amounts' => $amounts
]);
