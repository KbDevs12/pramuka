<?php
session_start();
require('../config/app.php');
require('../func/sanitize.php');
if (!isset($_SESSION['key'])) {
    header('Location: login.php');
    exit();
}

// Create
if (isset($_POST['create'])) {
    $nama = sanitize_input($_POST['nama']);
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
    $id = sanitize_input($_POST['id']);
    $nama = sanitize_input($_POST['nama']);
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
    $id = sanitize_input($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: index.php?page=kategori');
    } else {
        echo "error";
    }
    $stmt->close();
}
