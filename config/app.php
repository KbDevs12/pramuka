<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db = 'eduzille_pramuka';

$conn = new mysqli($host, $username, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}