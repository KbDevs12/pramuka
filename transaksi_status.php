<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduzillen | Transaksi</title>
    <link rel="icon" type="image/png" href="images/logo.PNG">
    <link rel="apple-touch-icon" href="images/logo.PNG">
    <link rel="shortcut icon" type="image/png" href="images/logo.PNG">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            scroll-behavior: smooth !important;
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
        }

        .payment-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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

    if (empty($trxcode) || $result->num_rows == 0) {
        ?>
        <div class="container mx-auto my-12 p-8 bg-white rounded-lg shadow-md">
            <div class="text-center">
                <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold text-red-500 mb-4">Data Tidak Lengkap</h1>
                <p class="text-lg text-gray-700 mb-6">Kode transaksi tidak ditemukan. Pastikan Anda memasukkan kode yang
                    benar.</p>
                <button
                    class="bg-blue-600 text-white px-10 py-3 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-transform transform hover:scale-105"
                    onclick="window.history.back()">
                    Kembali
                </button>
            </div>
        </div>

        <?php
        exit();
    }


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
    if ($transaction['status'] === 'pending') {
        ?>
        <div class="container mx-auto my-12 p-8 payment-card">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold gradient-text mb-4">Transaksi Sedang Diproses</h1>
                <p class="text-lg text-gray-600 mb-2">Bukti pembayaran Anda telah diterima.</p>
                <p class="text-lg text-gray-600 mb-6">Kami sedang memverifikasi pembayaran Anda.</p>
                <p class="text-gray-500 mb-6">Silakan cek kembali nanti untuk informasi lebih lanjut.</p>
                <button class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-colors duration-300"
                    onclick="window.history.back()">
                    Kembali
                </button>
            </div>
        </div>
        <?php
    } elseif ($transaction['status'] === 'success' && $transaction['sisaPembayaran'] > 0) {
        // Display remaining payment form
        ?>
        <div class="container mx-auto my-12 p-8 payment-card">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold gradient-text mb-6">Pelunasan Pembayaran</h1>
                <div class="bg-blue-600 text-white py-4 px-6 rounded-xl inline-block mb-4">
                    <p class="text-2xl font-bold">
                        <?php echo "Rp" . number_format($transaction['sisaPembayaran'], 0, ',', '.') ?>
                    </p>
                </div>
                <p class="text-lg text-gray-600 mb-4">Silakan lunasi sisa pembayaran Anda.</p>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-600 mb-2">Kode Pembayaran:</p>
                    <p class="text-2xl font-bold text-blue-600"><?php echo $transaction['kodeTransaksi']; ?></p>
                </div>
            </div>

            <div class="text-center mb-8 bg-white p-6 rounded-xl shadow-sm">
                <?php if ($transaction['metode_pembayaran'] === 'qris'): ?>
                    <img src="images/qris.jpeg" class="w-64 mx-auto rounded-lg" alt="QRIS Payment">
                    <p class="mt-4 text-gray-600">Scan QRIS di atas untuk melakukan pembayaran</p>
                <?php else: ?>
                    <p class="text-xl font-medium text-gray-800 mb-2">Transfer ke rekening BSI a/n YYS EDUCATIONAL ZILLENIAL</p>
                    <p class="text-2xl font-bold text-blue-600">2320230031</p>
                <?php endif; ?>
            </div>

            <form action="submit_payment_proof.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="trx_code" value="<?php echo $transaction['kodeTransaksi']; ?>">

                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran (Foto)</label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                        <input type="file" id="payment_proof" name="payment_proof" accept=".jpg, .jpeg, .png"
                            class="w-full py-3 px-4 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-2">Format: JPG, JPEG, atau PNG (Maks. 2MB)</p>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
        <?php
    }

    // Participant management section
    if ($transaction['status'] === 'lunas' || $transaction['status'] === 'success') {
        ?>
        <div class="container mx-auto my-12">
            <div class="payment-card p-8">
                <h2 class="text-2xl font-bold gradient-text mb-8 text-center">Pendaftaran & Manajemen Peserta</h2>

                <div class="mb-8">
                    <label for="lomba" class="block text-sm font-medium text-gray-700 mb-2">Pilih Perlombaan</label>
                    <select id="lomba"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        onchange="handleCompetitionChange(this.value)">
                        <option value="">Pilih Perlombaan</option>
                        <?php foreach ($competitions as $competition): ?>
                            <option id="idLomba" value="<?php echo $competition['id']; ?>"
                                data-jumlah="<?php echo $competition['peserta']; ?>">
                                <?php echo $competition['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="competition-description" class="bg-gray-100 p-4 rounded-lg text-gray-700">
                    Pilih perlombaan untuk melihat deskripsi.
                </div>


                <div id="participant-container" class="space-y-8">
                    <!-- Forms will be loaded here dynamically -->
                </div>
            </div>
        </div>
        <template id="participant-form-template">
            <div class="participant-form border-t border-gray-200 pt-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Peserta {index}</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="participants[{index}][nama]" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="participants[{index}][tempat_lahir]" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                        <input type="date" name="participants[{index}][tanggal_lahir]" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="participants[{index}][jabatan]" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Peserta</label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors">
                        <input type="file" name="participants[{index}][image]" accept="image/*" class="w-full">
                        <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG, atau JPEG (Max 2MB)</p>
                    </div>
                    <div class="mt-4 preview-image-container" style="display: none;">
                        <img src="" alt="Preview" class="preview-image max-w-xs rounded-lg shadow-sm">
                        <button type="button" class="text-red-600 mt-2 text-sm hover:text-red-700"
                            onclick="removeImage({index})">
                            Hapus Foto
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <script>
            const competitions = document.getElementById('idLomba').value

            function handleCompetitionChange(selectedId) {
                const descriptionElement = document.getElementById('competition-description');

                if (!selectedId) {
                    descriptionElement.textContent = 'Pilih perlombaan untuk melihat deskripsi.';
                    return;
                }

                const competition = competitions[selectedId];
                if (competition) {
                    descriptionElement.textContent = competition.deskripsi || 'Deskripsi tidak tersedia untuk perlombaan ini.';
                } else {
                    descriptionElement.textContent = 'Perlombaan tidak ditemukan.';
                }
            }

            async function handleCompetitionChange(competitionId) {
                if (!competitionId) return;

                const container = document.getElementById('participant-container');
                container.innerHTML = '<div class="text-center"><p>Loading...</p></div>';

                try {
                    // Fetch existing participants if any
                    const response = await fetch(`get_participants.php?competition_id=${competitionId}&trx_code=<?php echo $trxcode; ?>`);
                    const data = await response.json();
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
                                                                                                                                                                                                                                                    <img src="${participant.image ? 'data:image/*;base64,' + participant.image : ''}" 
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