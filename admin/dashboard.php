<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}

// Get total transactions
$totalTransaksi = $conn->query("SELECT COUNT(*) as total FROM transaksi")->fetch_assoc()['total'];

// Get total income (dari jumlahDibayarkan yang status success atau lunas)
$totalPendapatan = $conn->query("
    SELECT SUM(jumlahDibayarkan) as total 
    FROM transaksi 
    WHERE status IN ('success', 'lunas')"
)->fetch_assoc()['total'];

// Get total pangkalan count
$totalPangkalan = $conn->query("
SELECT SUM(total) AS total
FROM (
    SELECT COUNT(DISTINCT pangkalan) AS total
    FROM transaksi
    GROUP BY namaSekolah
) AS subquery;
"
)->fetch_assoc()['total'];

// Get transactions by category with percentage
$transaksiPerKategori = $conn->query("
    SELECT 
        k.nama as kategori, 
        COUNT(t.id) as jumlah,
        COUNT(t.id) * 100.0 / (SELECT COUNT(*) FROM transaksi) as percentage,
        SUM(t.jumlahDibayarkan) as total_pendapatan
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    GROUP BY k.id
    ORDER BY jumlah DESC
");

// Get latest transactions
$latestTransaksi = $conn->query("
    SELECT 
        t.*,
        k.nama as kategori_nama
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    ORDER BY t.tanggal_transaksi DESC
    LIMIT 5
");
?>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Transactions Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">
                        <?= number_format($totalTransaksi) ?>
                    </p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Income Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-2">
                        Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                    </p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pangkalan Card -->
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pangkalan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">
                        <?= number_format($totalPangkalan) ?>
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Transactions by Category -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Transaksi per Kategori</h3>
            <div class="text-sm text-gray-500">Total: <?= $totalTransaksi ?></div>
        </div>

        <!-- Chart Canvas -->
        <div class="mb-6">
            <canvas id="kategoriChart" class="w-full" height="300"></canvas>
        </div>

        <!-- Category List -->
        <div class="space-y-4">
            <?php
            $colors = ['bg-blue-100', 'bg-green-100', 'bg-yellow-100', 'bg-purple-100', 'bg-pink-100', 'bg-indigo-100'];
            $textColors = ['text-blue-800', 'text-green-800', 'text-yellow-800', 'text-purple-800', 'text-pink-800', 'text-indigo-800'];
            $i = 0;
            mysqli_data_seek($transaksiPerKategori, 0);
            while ($row = $transaksiPerKategori->fetch_assoc()):
                ?>
                <div class="flex items-center justify-between p-3 rounded-lg <?= $colors[$i % count($colors)] ?>">
                    <span class="font-medium <?= $textColors[$i % count($textColors)] ?>"><?= $row['kategori'] ?></span>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold"><?= $row['jumlah'] ?> transaksi</span>
                        <span class="text-sm text-gray-500">Rp
                            <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></span>
                    </div>
                </div>
                <?php
                $i++;
            endwhile;
            ?>
        </div>
    </div>

    <!-- Latest Transactions -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Transaksi Terbaru</h3>
        <div class="space-y-6">
            <?php while ($row = $latestTransaksi->fetch_assoc()): ?>
                <div
                    class="flex items-start justify-between p-4 rounded-lg hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span
                                        class="text-gray-600 font-medium"><?= strtoupper(substr($row['namaSekolah'], 0, 1)) ?></span>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?= $row['namaSekolah'] ?></p>
                                <p class="text-sm text-gray-500"><?= $row['pangkalan'] ?> - <?= $row['kwaran'] ?></p>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full <?=
                                $row['status'] === 'success' || $row['status'] === 'lunas' ? 'bg-green-100 text-green-800' :
                                ($row['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                <?= ucfirst($row['metode_pembayaran']) ?>
                            </span>
                            <span
                                class="text-sm text-gray-500"><?= date('d M Y', strtotime($row['tanggal_transaksi'])) ?></span>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="font-semibold text-gray-900">Rp
                            <?= number_format($row['jumlahDibayarkan'], 0, ',', '.') ?>
                        </p>
                        <p class="text-sm text-gray-500"><?= $row['kategori_nama'] ?></p>
                        <?php if ($row['sisaPembayaran'] > 0): ?>
                            <p class="text-sm text-red-500 mt-1">Sisa: Rp
                                <?= number_format($row['sisaPembayaran'], 0, ',', '.') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('kategoriChart').getContext('2d');

        // Ambil data melalui AJAX
        fetch('getKategoriData.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data');
                }
                return response.json();
            })
            .then(kategoriData => {
                // Render Chart
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: kategoriData.labels,
                        datasets: [{
                            label: 'Jumlah Transaksi',
                            data: kategoriData.data,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.5)',  // blue
                                'rgba(16, 185, 129, 0.5)',  // green
                                'rgba(245, 158, 11, 0.5)',  // yellow
                                'rgba(139, 92, 246, 0.5)',  // purple
                                'rgba(236, 72, 153, 0.5)',  // pink
                                'rgba(99, 102, 241, 0.5)'   // indigo
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(139, 92, 246)',
                                'rgb(236, 72, 153)',
                                'rgb(99, 102, 241)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const value = context.raw;
                                        const amount = kategoriData.amounts[context.dataIndex];
                                        return [
                                            `${value} transaksi`,
                                            `Rp ${amount.toLocaleString('id-ID')}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data chart.');
            });
    });
</script>