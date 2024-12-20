<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzillen | Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            scroll-behavior: smooth !important;
        }
    </style>
</head>

<body>

    <?php
    session_start();
    require('config/app.php');

    function sanitize_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        header('Location: index.php');
        exit();
    }

    $trxcode = sanitize_input($_GET['trx-code']);

    // Get transaction details
    $query = 'SELECT t.*, k.nama, k.id 
          FROM transaksi t 
          JOIN kategori k ON t.id_kategori = k.id 
          WHERE t.kodeTransaksi = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $trxcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();

    // Check if proof of payment exists
    $query_proof = 'SELECT * FROM bukti_transaksi WHERE id_transaksi = ?';
    $stmt_proof = $conn->prepare($query_proof);
    $stmt_proof->bind_param('i', $transaction['id']);
    $stmt_proof->execute();
    $proof_result = $stmt_proof->get_result();
    $has_proof = $proof_result->num_rows > 0;

    // Get competition details
    $query_competitions = 'SELECT * FROM perlombaan WHERE kategori_id = ?';
    $stmt_competitions = $conn->prepare($query_competitions);
    $stmt_competitions->bind_param('i', $transaction['id_kategori']);
    $stmt_competitions->execute();
    $competitions_result = $stmt_competitions->get_result();
    $competitions = [];
    while ($row = $competitions_result->fetch_assoc()) {
        $competitions[$row['id']] = $row;
    }
    if ($transaction['status'] === 'pending' && !$has_proof) {
        ?>
        <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg text-center">
            <h1 class="text-3xl font-bold text-blue-600">Transaksi Sedang Diproses</h1>
            <p class="text-lg mt-4">Bukti pembayaran Anda telah diterima.</p>
            <p class="text-lg mt-2">Kami sedang memverifikasi pembayaran Anda.</p>
            <p class="text-gray-700 mt-6 text-sm">
                Silakan cek kembali nanti untuk informasi lebih lanjut.
            </p>
        </div>
        <?php
    } elseif ($transaction['status'] === 'pending' && $has_proof) {
        // Display payment form
        ?>
        <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">Silahkan Lakukan Pembayaran</h1>
                <p class="font-bold text-2xl">
                    <?php echo "Rp" . number_format($transaction['jumlahDibayarkan'], 0, ',', '.') ?>
                </p>
                <p class="text-lg mt-2">Unggah bukti pembayaran Anda untuk melanjutkan proses pendaftaran.</p>
                <p class="text-gray-700 text-lg">Kode Pembayaran Anda:</p>
                <p class="text-2xl text-yellow-200 font-bold"><?php echo $transaction['kodeTransaksi']; ?></p>
            </div>

            <div class="text-center mb-6">
                <?php if ($transaction['metode_pembayaran'] === 'qris') { ?>
                    <img src="images/qris.jpeg" class="w-64 mx-auto" alt="QRIS Payment">
                    <p class="mt-4 text-sm text-gray-500">Scan QRIS di atas untuk melakukan pembayaran.</p>
                <?php } else { ?>
                    <p class="text-xl font-medium">Transfer ke rekening BSI a/n YYS EDUCATIONAL ZILLENIAL</p>
                    <p class="text-lg font-semibold text-blue-600">Nomor Rekening: 2320230031</p>
                <?php } ?>
            </div>

            <form action="submit_payment_proof.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="space-y-4">
                    <input type="hidden" name="trx_code" value="<?php echo $transaction['kodeTransaksi']; ?>">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700">Bukti Pembayaran
                        (Foto)</label>
                    <input type="file" id="payment_proof" name="payment_proof" accept=".jpg, .jpeg, .png"
                        class="w-full py-3 px-4 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500">Harap unggah foto bukti pembayaran dengan format JPG atau PNG.</p>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
        <?php
    } elseif ($transaction['status'] === 'success' && $transaction['sisaPembayaran'] > 0) {
        // Display remaining payment form
        ?>
        <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">Pelunasan Pembayaran</h1>
                <p class="font-bold text-2xl">
                    <?php echo "Rp" . number_format($transaction['sisaPembayaran'], 0, ',', '.') ?>
                </p>
                <p class="text-lg mt-2">Silahkan lunasi sisa pembayaran Anda.</p>
                <p class="text-gray-700 text-lg">Kode Pembayaran:</p>
                <p class="text-2xl text-yellow-200 font-bold"><?php echo $transaction['kodeTransaksi']; ?></p>
            </div>

            <div class="text-center mb-6">
                <?php if ($transaction['metode_pembayaran'] === 'qris') { ?>
                    <img src="images/qris.jpeg" class="w-64 mx-auto" alt="QRIS Payment">
                    <p class="mt-4 text-sm text-gray-500">Scan QRIS di atas untuk melakukan pembayaran.</p>
                <?php } else { ?>
                    <p class="text-xl font-medium">Transfer ke rekening BSI a/n YYS EDUCATIONAL ZILLENIAL</p>
                    <p class="text-lg font-semibold text-blue-600">Nomor Rekening: 2320230031</p>
                <?php } ?>
            </div>

            <!-- Similar payment form as above but for remaining payment -->
            <!-- ... Payment form code ... -->
            <form action="submit_payment_proof.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="space-y-4">
                    <input type="hidden" name="trx_code" value="<?php echo $transaction['kodeTransaksi']; ?>">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700">Bukti Pembayaran
                        (Foto)</label>
                    <input type="file" id="payment_proof" name="payment_proof" accept=".jpg, .jpeg, .png"
                        class="w-full py-3 px-4 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500">Harap unggah foto bukti pembayaran dengan format JPG atau PNG.</p>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
        <?php
    }

    // Participant management section
    if ($transaction['status'] === 'success') {
        ?>
        <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Pendaftaran & Manajemen Peserta</h2>

            <!-- Competition Selection -->
            <div class="mb-8">
                <label for="lomba" class="block text-sm font-medium text-gray-700">Pilih Perlombaan</label>
                <select id="lomba" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md"
                    onchange="handleCompetitionChange(this.value)">
                    <option value="">Pilih Perlombaan</option>
                    <?php foreach ($competitions as $competition) { ?>
                        <option value="<?php echo $competition['id']; ?>" data-jumlah="<?php echo $competition['peserta']; ?>">
                            <?php echo $competition['nama']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Participant Forms Container -->
            <div id="participant-container">
                <!-- Forms will be loaded here dynamically -->
            </div>
        </div>

        <!-- Participant Form Template (Hidden) -->
        <template id="participant-form-template">
            <div class="participant-form border-t pt-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Peserta {index}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="participants[{index}][nama]" required
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="participants[{index}][tempat_lahir]" required
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="participants[{index}][tanggal_lahir]" required
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <input type="text" name="participants[{index}][jabatan]" required
                            class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Foto Peserta</label>
                    <input type="file" name="participants[{index}][image]" accept="image/*"
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau JPEG (Max 2MB)</p>
                </div>

                <div class="mt-4 preview-image-container" style="display: none;">
                    <img src="" alt="Preview" class="preview-image max-w-xs">
                    <button type="button" class="text-red-600 mt-2" onclick="removeImage({index})">
                        Hapus Foto
                    </button>
                </div>
            </div>
        </template>

        <script>
            async function handleCompetitionChange(competitionId) {
                if (!competitionId) return;

                const container = document.getElementById('participant-container');
                container.innerHTML = '<div class="text-center"><p>Loading...</p></div>';

                try {
                    // Fetch existing participants if any
                    const response = await fetch(`get_participants.php?competition_id=${competitionId}&trx_code=<?php echo $trxcode; ?>`);
                    const data = await response.json();
                    console.log(data)
                    if (data.exists) {

                        showEditForm(data.participants, competitionId);
                    } else {
                        // Show new registration form
                        showNewRegistrationForm(competitionId);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    container.innerHTML = '<div class="text-red-500">Terjadi kesalahan. Silakan coba lagi.</div>';
                }
            }

            function showEditForm(participants, competitionId) {
                const container = document.getElementById('participant-container');
                container.innerHTML = '';
                const formHtml = `
                                                                                                                                                                        <form action="update_participants.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                                                                                                                                                                            <input type="hidden" name="competition_id" value="${competitionId}">
                                                                                                                                                                            <input type="hidden" name="trx_code" value="<?php echo $trxcode; ?>">
                                                                                                                                                                            ${generateParticipantForms(participants)}
                                                                                                                                                                            <div class="text-center">
                                                                                                                                                                                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                                                                                                                                                    Update Data Peserta
                                                                                                                                                                                </button>
                                                                                                                                                                            </div>
                                                                                                                                                                        </form>
                                                                                                                                                                    `;

                container.innerHTML = formHtml;
                initializeImagePreviews();
            }

            function showNewRegistrationForm(competitionId) {
                const select = document.querySelector('#lomba');
                const participantCount = parseInt(select.options[select.selectedIndex].getAttribute('data-jumlah'));
                const container = document.getElementById('participant-container');

                const formHtml = `
                                                                                                                                                                        <form action="submit_participants.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                                                                                                                                                                            <input type="hidden" name="competition_id" value="${competitionId}">
                                                                                                                                                                            <input type="hidden" name="trx_code" value="<?php echo $trxcode; ?>">
                                                                                                                                                                            ${generateEmptyParticipantForms(participantCount)}
                                                                                                                                                                            <div class="text-center">
                                                                                                                                                                                <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                                                                                                                                                    Daftar Peserta
                                                                                                                                                                                </button>
                                                                                                                                                                            </div>
                                                                                                                                                                        </form>
                                                                                                                                                                    `;

                container.innerHTML = formHtml;
                initializeImagePreviews();
            }

            function generateParticipantForms(participants) {
                // Pastikan participants selalu dalam bentuk array
                if (!Array.isArray(participants)) {
                    participants = [participants];
                }

                return participants.map((participant, index) => {
                    return `
                <div class="participant-form border-t pt-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Peserta ${index + 1}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" 
                                   name="participants[${index + 1}][nama]" 
                                   value="${participant.nama || ''}"
                                   required 
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" 
                                   name="participants[${index + 1}][tempat_lahir]" 
                                   value="${participant.tempat_lahir || ''}"
                                   required 
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" 
                                   name="participants[${index + 1}][tanggal_lahir]" 
                                   value="${participant.tanggal_lahir || ''}"
                                   required 
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input type="text" 
                                   name="participants[${index + 1}][jabatan]" 
                                   value="${participant.jabatan || ''}"
                                   required 
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Foto Peserta</label>
                        <input type="file" 
                               name="participants[${index + 1}][image]" 
                               accept="image/*"
                               class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau JPEG (Max 2MB)</p>
                    </div>

                    <div class="mt-4 preview-image-container ${participant.image ? '' : 'hidden'}">
                        <img src="${participant.image ? 'data:image/jpeg;base64,' + participant.image : ''}" 
                             alt="Preview" 
                             class="preview-image max-w-xs h-auto object-contain rounded-lg">
                        <button type="button" 
                                class="text-red-600 mt-2" 
                                onclick="removeImage(${index + 1})">
                            Hapus Foto
                        </button>
                    </div>
                </div>
            `;
                }).join('');
            }
            function generateEmptyParticipantForms(count) {
                const template = document.getElementById('participant-form-template').innerHTML;
                return Array.from({ length: count }, (_, i) =>
                    template.replaceAll('{index}', i + 1)
                ).join('');
            }

            function initializeImagePreviews() {
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.addEventListener('change', function (e) {
                        const container = this.closest('.participant-form').querySelector('.preview-image-container');
                        const preview = container.querySelector('.preview-image');

                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.src = e.target.result;
                                container.style.display = 'block';
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                });
            }

            function removeImage(index) {
                const participantForm = document.querySelector(`[name="participants[${index}][image]"]`).closest('.participant-form');
                const previewContainer = participantForm.querySelector('.preview-image-container');
                const fileInput = participantForm.querySelector('input[type="file"]');

                fileInput.value = '';
                previewContainer.style.display = 'none';
                previewContainer.querySelector('.preview-image').src = '';
            }
        </script>
        <?php
    }
    ?>
</body>

</html>