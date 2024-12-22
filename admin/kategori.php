<?php
session_start();
require('../config/app.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Create
if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $stmt = $conn->prepare("INSERT INTO kategori (nama) VALUES (?)");
    $stmt->bind_param("s", $nama);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data kategori berhasil ditambahkan', 'success');
        </script>";
    }
    $stmt->close();
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $stmt = $conn->prepare("UPDATE kategori SET nama = ? WHERE id = ?");
    $stmt->bind_param("si", $nama, $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data kategori berhasil diupdate', 'success');
        </script>";
    }
    $stmt->close();
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data kategori berhasil dihapus', 'success');
        </script>";
    }
    $stmt->close();
}

// Read
$result = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Kategori</h2>
        <button onclick="openModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Tambah Kategori
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?= $row['id'] ?></td>
                        <td class="px-6 py-4"><?= $row['nama'] ?></td>
                        <td class="px-6 py-4">
                            <button onclick="editKategori(<?= $row['id'] ?>, '<?= $row['nama'] ?>')"
                                class="bg-yellow-500 text-white px-3 py-1 rounded mr-2 hover:bg-yellow-600 transition">
                                Edit
                            </button>
                            <button onclick="deleteKategori(<?= $row['id'] ?>)"
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
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/3 mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4" id="modalTitle">Tambah Kategori</h3>
        <form id="kategoriForm" method="POST" action="kategori_actions.php">
            <input type="hidden" name="id" id="kategoriId">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                <input type="text" name="nama" id="kategoriNama" required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
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
        document.getElementById('modalTitle').textContent = 'Tambah Kategori';
        document.getElementById('kategoriForm').reset();
        document.getElementById('submitBtn').name = 'create';
    }

    function closeModal() {
        document.getElementById('formModal').classList.add('hidden');
    }

    function editKategori(id, nama) {
        document.getElementById('formModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Kategori';
        document.getElementById('kategoriId').value = id;
        document.getElementById('kategoriNama').value = nama;
        document.getElementById('submitBtn').name = 'update';
    }

    function deleteKategori(id) {
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
                form.action = 'kategori_actions.php';
                form.innerHTML = `<input type="hidden" name="id" value="${id}">
                            <input type="hidden" name="delete">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>