<?php
$host = "localhost"; // Sesuaikan dengan server MySQL Anda
$user = "root"; // Username default XAMPP adalah "root"
$password = ""; // Default XAMPP password kosong
$database = "reservasihoteldb"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
