<?php
session_start();
require('config/app.php');

if (!isset($_GET['trx-code'])) {
    header('Location: index.php');
    exit();
}

$id_transaksi = $_GET['trx-code'];
$query = "SELECT t.*, bt.images FROM transaksi t 
          LEFT JOIN bukti_transaksi bt ON t.id = bt.id_transaksi 
          WHERE t.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_transaksi);
$stmt->execute();
$result = $stmt->get_result();
$transaction = $result->fetch_assoc();

if (!$transaction) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil | Eduzillen</title>
    <link rel="icon" type="image/png" href="images/logo.PNG">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-poppins">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-xl p-8">
            <div class="text-center">
                <svg class="w-20 h-20 text-green-500 mx-auto mb-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mb-8">Bukti pembayaran Anda telah berhasil diunggah dan sedang diverifikasi oleh
                    tim kami.</p>

                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Detail Transaksi</h2>
                    <div class="text-left space-y-2">
                        <p><span class="font-medium">Kode Transaksi:</span> <?php echo $transaction['kodeTransaksi']; ?>
                        </p>
                        <p><span class="font-medium">Nama Sekolah:</span> <?php echo $transaction['namaSekolah']; ?></p>
                        <p><span class="font-medium">Total Pembayaran:</span>
                            Rp<?php echo number_format($transaction['jumlahDibayarkan'], 0, ',', '.'); ?></p>
                        <?php if ($transaction['sisaPembayaran'] > 0): ?>
                            <p><span class="font-medium">Sisa Pembayaran:</span>
                                Rp<?php echo number_format($transaction['sisaPembayaran'], 0, ',', '.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Silakan bergabung dengan grup WhatsApp untuk informasi lebih
                        lanjut:</p>
                    <a href=" https://chat.whatsapp.com/H4ZI6OFa9F25ynreZMpaSl" target="_blank"
                        class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition-colors">
                        Gabung Grup WhatsApp
                    </a>
                </div>

                <div class="mt-8">
                    <a href="index.php" class="text-blue-600 hover:text-blue-800 font-medium">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>