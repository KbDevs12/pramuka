<?php
require('../config/app.php');

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=transaksi_export.xls");

$result = $conn->query("
    SELECT t.*, k.nama as kategori_nama, p.nama as nama_perlombaan
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    JOIN perlombaan p ON p.kategori_id = k.id
    ORDER BY t.tanggal_transaksi DESC
");

echo "Kode Transaksi\tSekolah\tKategori\tPerlombaan\tHarga\tDibayarkan\tSisa\tTanggal\tStatus\n";

while ($row = $result->fetch_assoc()) {
    $sisaPembayaran = $row['harga'] - $row['jumlahDibayarkan'];
    echo "{$row['kodeTransaksi']}\t{$row['namaSekolah']}\t{$row['kategori_nama']}\t{$row['nama_perlombaan']}\t";
    echo "Rp " . number_format($row['harga'], 0, ',', '.') . "\t";
    echo "Rp " . number_format($row['jumlahDibayarkan'], 0, ',', '.') . "\t";
    echo "Rp " . number_format($sisaPembayaran, 0, ',', '.') . "\t";
    echo date('d/m/Y', strtotime($row['tanggal_transaksi'])) . "\t{$row['status']}\n";
}
