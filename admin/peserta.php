<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$query = "
    SELECT 
        p.*, t.namaSekolah, t.kodeTransaksi, l.id as perlombaan_id, l.kategori_id, k.nama as kategori_nama
    FROM peserta p
    JOIN transaksi t ON p.idTransaksi = t.id
    LEFT JOIN perlombaan l ON p.id_perlombaan = l.id
    JOIN kategori k ON l.kategori_id = k.id
    ORDER BY p.id DESC
";
$allPeserta = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

$transactions = $conn->query("SELECT id, namaSekolah, kodeTransaksi FROM transaksi ORDER BY tanggal_transaksi DESC");

$kategoriQuery = $conn->query("SELECT id, nama FROM kategori");
$kategoriList = $kategoriQuery->fetch_all(MYSQLI_ASSOC);
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Peserta Berdasarkan Kategori</h2>
        <div class="flex gap-4">
            <input type="text" id="searchInput" placeholder="Cari peserta..."
                class="px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
        </div>
    </div>

    <?php foreach ($kategoriList as $kategori): ?>
        <div class="mb-8">
            <h3 class="text-xl font-semibold mt-6 mb-4"><?= $kategori['nama'] ?></h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tempat Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $pesertaKategori = array_filter($allPeserta, function ($peserta) use ($kategori) {
                            return $peserta['kategori_id'] == $kategori['id'];
                        });

                        if (empty($pesertaKategori)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada peserta dalam kategori ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pesertaKategori as $peserta): ?>
                                <tr class="transaction-row" data-kategori="<?= htmlspecialchars($kategori['nama']) ?>" data-search="<?= strtolower($peserta['nama'] . ' ' .
                                      $peserta['namaSekolah'] . ' ' .
                                      $peserta['tempat_lahir'] . ' ' .
                                      $peserta['jabatan']) ?>">
                                    <td class="px-6 py-4"><?= htmlspecialchars($peserta['nama']) ?></td>
                                    <td class="px-6 py-4">
                                        <?= htmlspecialchars($peserta['namaSekolah']) ?><br>
                                        <span class="text-sm text-gray-500">
                                            <?= htmlspecialchars($peserta['kodeTransaksi']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($peserta['tempat_lahir']) ?></td>
                                    <td class="px-6 py-4">
                                        <?= date('d/m/Y', strtotime($peserta['tanggal_lahir'])) ?>
                                    </td>
                                    <td class="px-6 py-4"><img src="data:image/jpeg;base64,<?php echo $peserta['image'] ?>" alt="">
                                    </td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($peserta['jabatan']) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button onclick="editPeserta(<?= htmlspecialchars(json_encode($peserta)) ?>)"
                                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                                Edit
                                            </button>
                                            <button onclick="deletePeserta(<?= $peserta['id'] ?>)"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="pagination-<?= $kategori['id'] ?>" class="flex justify-center mt-4 gap-2"></div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Form -->
<div id="pesertaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4" id="modalTitle">Tambah Peserta</h3>
        <form id="pesertaForm" method="POST">
            <input type="hidden" name="id" id="peserta_id">
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Transaksi</label>
                    <select name="idTransaksi" id="idTransaksi" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Transaksi</option>
                        <?php while ($trans = $transactions->fetch_assoc()): ?>
                            <option value="<?= $trans['id'] ?>">
                                <?= $trans['namaSekolah'] ?> (<?= $trans['kodeTransaksi'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nama</label>
                    <input type="text" name="nama" id="nama" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Jabatan</label>
                    <input type="text" name="jabatan" id="jabatan" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Foto</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" name="submit" id="submitBtn"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4">Konfirmasi Penghapusan</h3>
        <form id="deleteForm" method="POST" action="peserta_actions.php">
            <input type="hidden" name="id" id="delete_id">
            <p class="mb-4">Apakah Anda yakin ingin menghapus peserta ini? Data yang dihapus tidak dapat dikembalikan.
            </p>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" name="submit" value="delete"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.getElementsByClassName('transaction-row');

        Array.from(rows).forEach(row => {
            const searchText = row.getAttribute('data-search').toLowerCase();
            console.log('ketemu')
            const matchesSearch = searchText.includes(searchTerm);

            if (matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        })
    }
    document.getElementById('searchInput').addEventListener('input', filterTable);
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = `<img src="${e.target.result}" class="h-32 w-32 object-cover rounded">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openDeleteModal(id) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('delete_id').value = id;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function openModal() {
        document.getElementById('pesertaModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Tambah Peserta';
        document.getElementById('pesertaForm').reset();
        document.getElementById('submitBtn').name = 'create';
        document.getElementById('imagePreview').innerHTML = '';
    }

    function closeModal() {
        document.getElementById('pesertaModal').classList.add('hidden');
    }

    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    function editPeserta(data) {
        document.getElementById('pesertaModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Peserta';
        document.getElementById('peserta_id').value = data.id;
        document.getElementById('idTransaksi').value = data.idTransaksi;
        document.getElementById('nama').value = data.nama;
        document.getElementById('tempat_lahir').value = data.tempat_lahir;
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('jabatan').value = data.jabatan;

        document.getElementById('submitBtn').name = 'update';
        const form = document.getElementById('pesertaForm');
        form.onsubmit = async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            formData.append('update', 'true');
            const id = document.getElementById('peserta_id').value;

            try {
                const response = await fetch('peserta_actions.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire('Berhasil!', result.message, 'success').then(() => {
                        window.location.href = 'index.php';
                    });
                    closeModal();
                } else {
                    Swal.fire('Gagal!', result.message, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error!', 'Terjadi kesalahan dalam proses pembaruan.', 'error');
            }

        }
    }

    function deletePeserta(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('delete', true);  // Changed from 'deletes' to 'delete'

                fetch('peserta_actions.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan dalam penghapusan data.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan dalam penghapusan data.', 'error');
                    });
            }
        });
    }
    // Close modal when clicking outside
    document.getElementById('pesertaModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>