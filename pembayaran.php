<?php
session_start();
require('config/app.php');

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);

    return $data;
}

function normalize_school_name($name)
{
    $name = strtolower($name);
    $name = preg_replace('/[^a-z0-9]/', '', $name);
    $name = str_replace(['sklh', 's.k.l.h'], 'sekolah', $name);
    return $name;
}

function get_price($kategori_perlombaan)
{
    $current_date = date('Y-m-d');
    $harga = 0;

    if ($current_date >= '2024-12-18' && $current_date <= '2025-01-09') {
        if ($kategori_perlombaan == "materi") {
            $harga = 400000;
        } elseif ($kategori_perlombaan == "lkbb") {
            $harga = 500000;
        } elseif ($kategori_perlombaan == "gabungan") {
            $harga = 900000;
        }
    } elseif ($current_date >= '2025-01-10' && $current_date <= '2025-02-20') {
        if ($kategori_perlombaan == "materi") {
            $harga = 450000;
        } elseif ($kategori_perlombaan == "lkbb") {
            $harga = 550000;
        } elseif ($kategori_perlombaan == "gabungan") {
            $harga = 1000000;
        }
    } elseif ($current_date >= '2025-02-21' && $current_date <= '2025-03-26') {
        if ($kategori_perlombaan == "materi") {
            $harga = 500000;
        } elseif ($kategori_perlombaan == "lkbb") {
            $harga = 600000;
        } elseif ($kategori_perlombaan == "gabungan") {
            $harga = 1100000;
        }
    }

    return $harga;
}

$current_date = date('Y-m-d');
$registration_open = false;

$valid_periods = [
    ['start' => '2024-12-18', 'end' => '2025-01-09'],
    ['start' => '2025-01-10', 'end' => '2025-02-20'],
    ['start' => '2025-02-21', 'end' => '2025-03-26']
];

foreach ($valid_periods as $period) {
    if ($current_date >= $period['start'] && $current_date <= $period['end']) {
        $registration_open = true;
        break;
    }
}

if (!$registration_open) {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrasi Ditutup</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="flex items-center justify-center h-screen bg-gray-100">
        <div class="bg-white p-8 rounded shadow-lg text-center">
            <svg class="mx-auto mb-4 w-12 h-12 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm0 21.75A9.75 9.75 0 1121.75 12 9.761 9.761 0 0112 21.75zM12 5.25a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-3 0v-6a1.5 1.5 0 011.5-1.5zm0 10.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
            </svg>
            <h1 class="text-2xl font-bold text-red-500">Pendaftaran Ditutup</h1>
            <p class="text-gray-600 mt-2">Saat ini pendaftaran tidak tersedia. Silakan kembali di periode berikutnya.</p>
        </div>
    </body>
    </html>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sekolah = sanitize_input($_POST["nama_sekolah"]);
    $pangkalan = sanitize_input($_POST["pangkalan"]);
    $kwaran = sanitize_input($_POST["kwaran"]);
    $kwarlab = sanitize_input($_POST["kwarlab"]);
    $pembina = sanitize_input($_POST["pembina"]);
    $alamat_sekolah = sanitize_input($_POST["alamat_sekolah"]);
    $no_gugus = sanitize_input($_POST["no_gugus"]);
    $no_telp = sanitize_input($_POST["no_telp"]);
    $metode_pembayaran = sanitize_input($_POST["metode_pembayaran"]);
    $kategori_perlombaan = sanitize_input($_POST["kategori_perlombaan"]);
    $jenis_pembayaran = sanitize_input($_POST["jenis_pembayaran"]);
    $regu = sanitize_input($_POST["regu"]);

    $harga = get_price($kategori_perlombaan);

    $normalized_name = normalize_school_name($nama_sekolah);
    $stmt = $conn->prepare("SELECT namaSekolah FROM transaksi");
    $stmt->execute();
    $result = $stmt->get_result();

    $transaction_count = 0;
    while ($row = $result->fetch_assoc()) {
        $db_name = normalize_school_name($row['namaSekolah']);
        if ($normalized_name == $db_name) {
            $transaction_count++;
        }
    }

    if ($transaction_count >= 2) {
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Transaksi Ditolak</title>
            <link rel="icon" type="image/png" href="images/logo.PNG">
            <link rel="apple-touch-icon" href="images/logo.PNG">
            <link rel="shortcut icon" type="image/png" href="images/logo.PNG">
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="flex items-center justify-center h-screen bg-gray-100">
            <div class="bg-white p-8 rounded shadow-lg text-center">
                <svg class="mx-auto mb-4 w-12 h-12 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm0 21.75A9.75 9.75 0 1121.75 12 9.761 9.761 0 0112 21.75zM12 5.25a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-3 0v-6a1.5 1.5 0 011.5-1.5zm0 10.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                </svg>
                <h1 class="text-2xl font-bold text-yellow-500">Transaksi Ditolak</h1>
                <p class="text-gray-600 mt-2">Sekolah Anda telah mencapai batas maksimum 2 transaksi.</p>
            </div>
        </body>
        </html>';
        exit();
    }

    $id_kategori = 0;
    if ($kategori_perlombaan == 'lkbb') {
        $id_kategori = 2;
    } elseif ($kategori_perlombaan == 'materi') {
        $id_kategori = 1;
    } elseif ($kategori_perlombaan == 'gabungan') {
        $id_kategori = 3;
    }

    if (
        empty($nama_sekolah) || empty($pangkalan) || empty($kwaran) || empty($kwarlab) ||
        empty($pembina) || empty($alamat_sekolah) || empty($no_gugus) || empty($no_telp) ||
        empty($metode_pembayaran) || empty($jenis_pembayaran) || empty($regu)
    ) {
        header('Location: index.php');
        exit();
    }

    // Check session date for Sesi 1
    if ($current_date >= '2024-18-12' && $current_date <= '2025-01-09') {
        $stmt = $conn->prepare("SELECT COUNT(*) AS participant_count FROM transaksi WHERE tanggal_transaksi BETWEEN ? AND ? AND kategori_perlombaan = ?");
        $stmt->bind_param('sss', $start_date, $end_date, $kategori_perlombaan);

        $start_date = '2025-01-06';
        $end_date = '2025-01-09';

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $participant_count = $data['participant_count'];

        if ($participant_count >= 10) {
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Transaksi Ditolak</title>
                <link rel="icon" type="image/png" href="images/logo.PNG">
                <link rel="apple-touch-icon" href="images/logo.PNG">
                <link rel="shortcut icon" type="image/png" href="images/logo.PNG">
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="flex items-center justify-center h-screen bg-gray-100">
                <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-red-600">Pendaftaran Ditutup untuk Sesi 1</h1>
                    <p class="text-lg text-gray-700 mt-4">Kuota peserta untuk sesi 1 telah penuh. Silakan coba lagi pada sesi berikutnya.</p>
                    <a href="index.php" class="mt-6 inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                        Kembali ke Halaman Utama
                    </a>
                </div>
              </div>
            </body>
            </html>';
            exit();
        }

        $stmt->close();
    }

    $kodeUnik = rand(100, 999);
    $tanggalTransaksi = date('Y-m-d');
    $kodePembayaran = 'TRX-' . rand(1000, 9999) . '-' . rand(1, 99) . '-' . date('YY-mm-dd');
    $jumlah_dibayarkan = ($jenis_pembayaran == "dp") ? $harga * 0.5 + $kodeUnik : $harga + $kodeUnik;
    $sisa_pembayaran = ($jenis_pembayaran == "dp") ? $harga * 0.5 : 0;

    $status = 'pending';
    if ($registration_open) {
        $query = 'INSERT INTO transaksi (id_kategori, namaSekolah, pangkalan, kwaran, kwarlab, alamatSekolah, pembina, noGuDep, noTelp, jumlahDibayarkan, sisaPembayaran, regu, tanggal_transaksi, kodeUnik, kodeTransaksi, status, harga, metode_pembayaran)
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $conn->prepare($query);

        $stmt->bind_param('issssssssiississis', $id_kategori, $nama_sekolah, $pangkalan, $kwaran, $kwarlab, $alamat_sekolah, $pembina, $no_gugus, $no_telp, $jumlah_dibayarkan, $sisa_pembayaran, $regu, $tanggalTransaksi, $kodeUnik, $kodePembayaran, $status, $harga, $metode_pembayaran);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzillen | Pembayaran</title>
    <link rel="icon" type="image/png" href="images/logo.PNG">
    <link rel="apple-touch-icon" href="images/logo.PNG">
    <link rel="shortcut icon" type="image/png" href="images/logo.PNG">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            scroll-behavior: smooth !important;
        }
    </style>
</head>

<body class="font-poppins bg-gray-100 text-gray-800">

    <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">Silahkan Lakukan Pembayaran</h1>
            <p class="font-bold text-2xl"><?php echo "Rp" . number_format($jumlah_dibayarkan, 0, ',', '.'); ?></p>
            <p class="text-lg mt-2">Unggah bukti pembayaran Anda untuk melanjutkan proses pendaftaran.</p>
            <p class="text-gray-700 text-lg">Kode Pembayaran Anda:</p>
            <p id="kodePembayaran" class="text-2xl text-yellow-200 font-bold"><?php echo $kodePembayaran; ?></p>
            <button onclick="salinKode()"
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                Salin Kode
            </button>
            <p id="notifikasi" class="text-green-600 font-medium mt-2 hidden">Kode berhasil disalin!</p>
        </div>

        <!-- Payment Info -->
        <div class="text-center mb-6">
            <?php if ($metode_pembayaran === 'qris') { ?>
                <img src="images/qris.jpeg" class="w-64 mx-auto" alt="QRIS Payment">
                <p class="mt-4 text-sm text-gray-500">Scan QRIS di atas untuk melakukan pembayaran.</p>
            <?php } else { ?>
                <p class="text-xl font-medium">Transfer ke rekening BSI a/n YYS EDUCATIONAL ZILLENIAL</p>
                <p class="text-lg font-semibold text-blue-600">Nomor Rekening: 2320230031</p>
            <?php } ?>
        </div>

        <!-- Payment Proof Form -->
        <form action="submit_payment_proof.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div class="space-y-4">
                <input type="hidden" name="trx_code" id="trx_code" value="<?php echo $kodePembayaran ?>">
                <label for="payment_proof" class="block text-sm font-medium text-gray-700">Bukti Pembayaran
                    (Foto)</label>
                <input type="file" id="payment_proof" name="payment_proof" accept=".jpg, .jpeg, .png"
                    class="w-full py-3 px-4 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500">Harap unggah foto bukti pembayaran dengan format JPG atau PNG.
                </p>
            </div>

            <!-- Submit Button Section -->
            <div class="text-center">
                <button type="submit"
                    class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Kirim Bukti Pembayaran
                </button>
            </div>
        </form>

        <!-- Footer Section -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Jika ada pertanyaan, hubungi kami di <a href="mailto:support@eduzillen.com"
                    class="text-blue-500 hover:text-blue-600">support@eduzillen.com</a></p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/menu.js"></script>
    <script>
        function salinKode() {
            const kode = document.getElementById('kodePembayaran').textContent;

            navigator.clipboard.writeText(kode).then(() => {
                const notifikasi = document.getElementById('notifikasi');
                notifikasi.classList.remove('hidden');

                setTimeout(() => {
                    notifikasi.classList.add('hidden');
                }, 300);
            }).catch(err => {
                console.error('gagal menyalin kode', err);
            });
        }
    </script>
    <script src="js/main.js"></script>
</body>

</html>