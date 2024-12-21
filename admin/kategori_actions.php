<?php
session_start();
require('../config/app.php');

if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}

// Create
if (isset($_POST['create'])) {
    $nama = $_POST['nama'];
    $stmt = $conn->prepare("INSERT INTO kategori (nama) VALUES (?)");
    $stmt->bind_param("s", $nama);
    if ($stmt->execute()) {
        header('Location: index.php?page=kategori');
    } else {
        echo "error";
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
        header('Location: index.php?page=kategori');
    } else {
        echo "error";
    }
    $stmt->close();
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: index.php?page=kategori');
    } else {
        echo "error";
    }
    $stmt->close();
}
?>