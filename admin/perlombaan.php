<?php
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}

// Read
$result = $conn->query("SELECT p.*, k.nama as kategori_nama FROM perlombaan p 
                       LEFT JOIN kategori k ON p.kategori_id = k.id 
                       ORDER BY p.id DESC");

// Get categories for dropdown
$categories = $conn->query("SELECT * FROM kategori ORDER BY nama");
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Perlombaan</h2>
        <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Tambah Perlombaan
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Peserta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu (menit)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?= $row['id'] ?></td>
                        <td class="px-6 py-4"><?= $row['nama'] ?></td>
                        <td class="px-6 py-4"><?= $row['peserta'] ?></td>
                        <td class="px-6 py-4"><?= $row['deskripsi'] ?></td>
                        <td class="px-6 py-4"><?= $row['waktu'] ?></td>
                        <td class="px-6 py-4"><?= $row['kategori_nama'] ?></td>
                        <td class="px-6 py-4">
                            <button onclick='editPerlombaan(<?= json_encode($row) ?>)'
                                class="bg-yellow-500 text-white px-3 py-1 rounded mr-2 hover:bg-yellow-600 transition">
                                Edit
                            </button>
                            <button onclick="deletePerlombaan(<?= $row['id'] ?>)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div id="formModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/2 mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4" id="modalTitle">Tambah Perlombaan</h3>
        <form id="perlombaanForm" method="POST" action="perlombaan_actions.php">
            <input type="hidden" name="id" id="perlombaanId">
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Perlombaan</label>
                    <input type="text" name="nama" id="perlombaanNama" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Peserta</label>
                    <input type="number" name="peserta" id="perlombaanPeserta" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="perlombaanDeskripsi" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Waktu (menit)</label>
                    <input type="number" name="waktu" id="perlombaanWaktu" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="kategori_id" id="perlombaanKategori" required
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['nama'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" name="create" id="submitBtn"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Tambah Perlombaan';
        document.getElementById('perlombaanForm').reset();
        document.getElementById('submitBtn').name = 'create';
    }

    function closeModal() {
        document.getElementById('formModal').classList.add('hidden');
    }

    function editPerlombaan(data) {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Perlombaan';
        document.getElementById('perlombaanId').value = data.id;
        document.getElementById('perlombaanNama').value = data.nama;
        document.getElementById('perlombaanPeserta').value = data.peserta;
        document.getElementById('perlombaanDeskripsi').value = data.deskripsi;
        document.getElementById('perlombaanWaktu').value = data.waktu;
        document.getElementById('perlombaanKategori').value = data.kategori_id;
        document.getElementById('submitBtn').name = 'update';
    }

    function deletePerlombaan(id) {
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="id" value="${id}">
                            <input type="hidden" name="delete">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>