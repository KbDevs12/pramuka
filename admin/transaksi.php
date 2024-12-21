<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$totalRows = $conn->query("SELECT COUNT(*) as count FROM transaksi")->fetch_assoc()['count'];
$totalPages = ceil($totalRows / $limit);

$result = $conn->query("
    SELECT t.*, k.nama as kategori_nama, p.nama as nama_perlombaan, bt.images as bukti_transaksi
    FROM transaksi t
    JOIN kategori k ON t.id_kategori = k.id
    JOIN perlombaan p ON p.kategori_id = k.id
    LEFT JOIN bukti_transaksi bt ON bt.id_transaksi = t.id
    ORDER BY t.tanggal_transaksi DESC
    LIMIT $offset, $limit
");

if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Status transaksi berhasil diupdate', 'success');
        </script>";
    }
    $stmt->close();
}
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Transaksi</h2>
        <div class="flex gap-2">
            <button onclick="exportData()"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perlombaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibayarkan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()):

                    ?>
                    <tr>
                        <td class="px-6 py-4"><?= $row['kodeTransaksi'] ?></td>
                        <td class="px-6 py-4"><?= $row['namaSekolah'] ?></td>
                        <td class="px-6 py-4"><?= $row['kategori_nama'] ?></td>
                        <td class="px-6 py-4"><?= $row['nama_perlombaan'] ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($row['jumlahDibayarkan'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4">Rp <?= number_format($row["sisaPembayaran"], 0, ',', '.') ?></td>
                        <td class="px-6 py-4"> <?php if (!empty($row['bukti_transaksi'])): ?>
                                <img src="data:image/png;base64,<?= htmlspecialchars($row['bukti_transaksi']) ?>"
                                    onclick="openImageModal('data:image/png;base64,<?= htmlspecialchars($row['bukti_transaksi']) ?>')"
                                    alt="Bukti Transaksi" />
                            <?php else: ?>
                                <span class="text-gray-500">Tidak ada bukti transaksi</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal_transaksi'])) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full <?=
                                $row['status'] === 'success' ? 'bg-green-100 text-green-800' :
                                ($row['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')
                                ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 flex items-center">
                            <button onclick="viewDetail(<?= $row['id'] ?>)"
                                class="bg-blue-500 text-white px-3 py-1 rounded mr-2 hover:bg-blue-600 transition">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </button>
                            <button onclick="updateStatus(<?= $row['id'] ?>, '<?= $row['status'] ?>')"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                <i class="fas fa-edit mr-1"></i>Update
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?pages=<?= $i ?>"
                    class="relative inline-flex items-center px-4 py-2 border <?= $i === $page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'border-gray-300' ?> text-sm font-medium">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    </div>
</div>

<!-- Modal for Image Preview -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <img id="imagePreview" src="" alt="Bukti Transaksi" class="max-w-full max-h-96">
        <div class="mt-4 flex justify-end">
            <button onclick="closeImageModal()"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-2/3 mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4">Detail Transaksi</h3>
        <div id="detailContent" class="space-y-4">
            <!-- Content will be loaded here -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeDetailModal()"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4">Update Status Transaksi</h3>
        <form id="statusForm" method="POST">
            <input type="hidden" name="id" id="statusTransaksiId">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" id="statusSelect" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                    <option value="pending">Pending</option>
                    <option value="success">Success</option>
                    <option value="failed">Failed</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeStatusModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" name="update_status"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    async function viewDetail(id) {
        const response = await fetch(`get_transaksi_detail.php?id=${id}`);
        const data = await response.json();

        let content = `
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <h4 class="font-semibold mb-2">Informasi Sekolah</h4>
                    <p class="mb-1"><span class="font-medium">Nama:</span> ${data.namaSekolah}</p>
                    <p class="mb-1"><span class="font-medium">Pangkalan:</span> ${data.pangkalan}</p>
                    <p class="mb-1"><span class="font-medium">Kwaran:</span> ${data.kwaran}</p>
                    <p class="mb-1"><span class="font-medium">Kwarlab:</span> ${data.kwarlab}</p>
                    <p class="mb-1"><span class="font-medium">No Gudep:</span> ${data.noGuDep}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Informasi Pembayaran</h4>
                    <p class="mb-1"><span class="font-medium">Kode:</span> ${data.kodeTransaksi}</p>
                    <p class="mb-1"><span class="font-medium">Total Harga:</span> Rp ${new Intl.NumberFormat('id-ID').format(data.harga)}</p>
                    <p class="mb-1"><span class="font-medium">Dibayarkan:</span> Rp ${new Intl.NumberFormat('id-ID').format(data.jumlahDibayarkan)}</p>
                    <p class="mb-1"><span class="font-medium">Sisa:</span> Rp ${new Intl.NumberFormat('id-ID').format(data.harga - data.jumlahDibayarkan)}</p>
                    <p class="mb-1"><span class="font-medium">Status:</span> ${data.status}</p>
                    <p class="mb-1"><span class="font-medium">Metode:</span> ${data.metode_pembayaran}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2">Informasi Perlombaan</h4>
                    <p class="mb-1"><span class="font-medium">Kategori:</span> ${data.kategori_nama}</p>
                    <p class="mb-1"><span class="font-medium">Perlombaan:</span> ${data.nama_perlombaan}</p>
                    <p class="mb-1"><span class="font-medium">Regu:</span> ${data.regu || '-'}</p>
                    <p class="mb-1"><span class="font-medium">Tanggal Daftar:</span> ${new Date(data.tanggal_transaksi).toLocaleDateString('id-ID')}</p>
                </div>
            </div>
        `;

        document.getElementById('detailContent').innerHTML = content;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function updateStatus(id, currentStatus) {
        document.getElementById('statusModal').classList.remove('hidden');
        document.getElementById('statusTransaksiId').value = id;
        document.getElementById('statusSelect').value = currentStatus;

        const form = document.getElementById('statusForm');
        form.onsubmit = async (e) => {
            e.preventDefault();
            const status = document.getElementById('statusSelect').value;

            try {
                const response = await fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ id, status })
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire('Berhasil!', result.message, 'success');
                    closeStatusModal();
                    // Refresh data on the table (optional)
                    location.reload();
                } else {
                    Swal.fire('Gagal!', result.message, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error!', 'Terjadi kesalahan dalam proses pembaruan.', 'error');
            }
        };
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }


    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    function exportData() {
        window.location.href = 'export_transaksi.php';
    }
    function openImageModal(imageSrc) {
        const imageModal = document.getElementById('imageModal');
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = imageSrc;
        imageModal.classList.remove('hidden');
    }

    function closeImageModal() {
        const imageModal = document.getElementById('imageModal');
        imageModal.classList.add('hidden');
    }
</script>