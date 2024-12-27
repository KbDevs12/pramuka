<?php
require('../config/app.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=transaksi_export.xls");

$result = $conn->query("
    SELECT t.*, k.nama as kategori_nama
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    ORDER BY t.tanggal_transaksi DESC
");

echo "Kode Transaksi\tSekolah\tKategori\tHarga\tDibayarkan\tSisa\tTanggal\tStatus\n";

while ($row = $result->fetch_assoc()) {
    $sisaPembayaran = $row['harga'] - $row['jumlahDibayarkan'];
    echo "{$row['kodeTransaksi']}\t{$row['namaSekolah']}\t{$row['kategori_nama']}\t";
    echo "Rp " . number_format($row['harga'], 0, ',', '.') . "\t";
    echo "Rp " . number_format($row['jumlahDibayarkan'], 0, ',', '.') . "\t";
    echo "Rp " . number_format($sisaPembayaran, 0, ',', '.') . "\t";
    echo date('d/m/Y', strtotime($row['tanggal_transaksi'])) . "\t{$row['status']}\n";
}
