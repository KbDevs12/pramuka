<?php
require_once('../config/app.php');
ob_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['key'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (isset($_POST['update'])) {
        // Update peserta
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $idTransaksi = filter_input(INPUT_POST, 'idTransaksi', FILTER_SANITIZE_NUMBER_INT);
        $nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
        $tempat_lahir = filter_input(INPUT_POST, 'tempat_lahir', FILTER_SANITIZE_STRING);
        $tanggal_lahir = filter_input(INPUT_POST, 'tanggal_lahir', FILTER_SANITIZE_STRING);
        $jabatan = filter_input(INPUT_POST, 'jabatan', FILTER_SANITIZE_STRING);

        if (!$id || !$idTransaksi || !$nama || !$tempat_lahir || !$tanggal_lahir || !$jabatan) {
            echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
            exit;
        }

        // Jika ada gambar baru yang diunggah
        if (!empty($_FILES['image']['tmp_name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
                exit;
            }

            $image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
            $query = "UPDATE peserta SET idTransaksi = ?, nama = ?, tempat_lahir = ?, tanggal_lahir = ?, jabatan = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('isssssi', $idTransaksi, $nama, $tempat_lahir, $tanggal_lahir, $jabatan, $image, $id);
        } else {
            $query = "UPDATE peserta SET idTransaksi = ?, nama = ?, tempat_lahir = ?, tanggal_lahir = ?, jabatan = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('issssi', $idTransaksi, $nama, $tempat_lahir, $tanggal_lahir, $jabatan, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update data.']);
        }

        $stmt->close();
    }

    if (isset($_POST['delete'])) {
        // Delete peserta
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID.']);
            exit;
        }

        $query = "DELETE FROM peserta WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Data deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete data.']);
        }

        $stmt->close();
    }
}

$conn->close();
ob_end_flush();