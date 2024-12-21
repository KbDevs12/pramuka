<?php
require_once('../config/app.php');

// Create
if (isset($_POST['create'])) {
    $idTransaksi = $_POST['idTransaksi'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jabatan = $_POST['jabatan'];

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/peserta/";
        $image = uniqid() . "-" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO peserta (idTransaksi, nama, tempat_lahir, tanggal_lahir, jabatan, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $idTransaksi, $nama, $tempat_lahir, $tanggal_lahir, $jabatan, $image);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data peserta berhasil ditambahkan', 'success');
        </script>";
    }
    $stmt->close();
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $idTransaksi = $_POST['idTransaksi'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jabatan = $_POST['jabatan'];

    // Handle image update
    $image = $_POST['old_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "uploads/peserta/";
        $image = uniqid() . "-" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Delete old image if exists
            if ($_POST['old_image'] && file_exists($_POST['old_image'])) {
                unlink($_POST['old_image']);
            }
            $image = $target_file;
        }
    }

    $stmt = $conn->prepare("UPDATE peserta SET idTransaksi = ?, nama = ?, tempat_lahir = ?, tanggal_lahir = ?, jabatan = ?, image = ? WHERE id = ?");
    $stmt->bind_param("isssssi", $idTransaksi, $nama, $tempat_lahir, $tanggal_lahir, $jabatan, $image, $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data peserta berhasil diupdate', 'success');
        </script>";
    }
    $stmt->close();
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Get image path before delete
    $stmt = $conn->prepare("SELECT image FROM peserta WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['image'] && file_exists($row['image'])) {
            unlink($row['image']);
        }
    }

    $stmt = $conn->prepare("DELETE FROM peserta WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>
            Swal.fire('Berhasil!', 'Data peserta berhasil dihapus', 'success');
        </script>";
    }
    $stmt->close();
}

// Read with pagination and search
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClause = $search ? "WHERE p.nama LIKE '%$search%' OR t.namaSekolah LIKE '%$search%'" : "";

$totalRows = $conn->query("
    SELECT COUNT(*) as count 
    FROM peserta p
    JOIN transaksi t ON p.idTransaksi = t.id 
    $whereClause
")->fetch_assoc()['count'];

$totalPages = ceil($totalRows / $limit);

$result = $conn->query("
    SELECT p.*, t.namaSekolah, t.kodeTransaksi
    FROM peserta p
    JOIN transaksi t ON p.idTransaksi = t.id
    $whereClause
    ORDER BY p.id DESC
    LIMIT $offset, $limit
");

// Get transactions for dropdown
$transactions = $conn->query("SELECT id, namaSekolah, kodeTransaksi FROM transaksi ORDER BY tanggal_transaksi DESC");
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Data Peserta</h2>
        <div class="flex gap-4">
            <form class="flex gap-2">
                <input type="text" name="search" value="<?= $search ?>" placeholder="Cari peserta..."
                    class="px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Cari
                </button>
            </form>
            <button onclick="openModal()"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                Tambah Peserta
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sekolah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tempat Lahir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Lahir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4"><?= $row['nama'] ?></td>
                        <td class="px-6 py-4">
                            <?= $row['namaSekolah'] ?><br>
                            <span class="text-sm text-gray-500"><?= $row['kodeTransaksi'] ?></span>
                        </td>
                        <td class="px-6 py-4"><?= $row['tempat_lahir'] ?></td>
                        <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal_lahir'])) ?></td>
                        <td class="px-6 py-4"><?= $row['jabatan'] ?></td>
                        <td class="px-6 py-4">
                            <?php if ($row['image']): ?>
                                <img src="data:image/jpeg;base64,<?= $row['image'] ?>" alt="Foto <?= $row['nama'] ?>"
                                    class="h-12 w-12 object-cover rounded">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="editPeserta(<?= json_encode($row) ?>)"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                    Edit
                                </button>
                                <button onclick="deletePeserta(<?= $row['id'] ?>)"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center">
        <div class="text-gray-600">
            Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRows) ?> of <?= $totalRows ?> entries
        </div>
        <div class="flex gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="px-3 py-1 rounded <?= $i === $page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="pesertaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-auto mt-20">
        <h3 class="text-xl font-semibold mb-4" id="modalTitle">Tambah Peserta</h3>
        <form id="pesertaForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="peserta_id">
            <input type="hidden" name="old_image" id="old_image">

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
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500"
                        onchange="previewImage(this)">
                    <div id="imagePreview" class="mt-2"></div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" name="create" id="submitBtn"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

    function editPeserta(data) {
        document.getElementById('pesertaModal').classList.remove('hidden');
        document.getElementById('modalTitle').textContent = 'Edit Peserta';
        document.getElementById('peserta_id').value = data.id;
        document.getElementById('idTransaksi').value = data.idTransaksi;
        document.getElementById('nama').value = data.nama;
        document.getElementById('tempat_lahir').value = data.tempat_lahir;
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
        document.getElementById('jabatan').value = data.jabatan;
        document.getElementById('old_image').value = data.image;
        document.getElementById('submitBtn').name = 'update';

        if (data.image) {
            document.getElementById('imagePreview').innerHTML =
                `<img src="data:image/jpeg;base64,${data.image}" class="h-32 w-32 object-cover rounded">`;
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
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                <input type="hidden" name="id" value="${id}">
                <input type="hidden" name="delete" value="1">
            `;
                document.body.appendChild(form);
                form.submit();
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