<?php
$host = "localhost"; // Sesuaikan dengan server MySQL Anda
$user = "root"; // Username default XAMPP adalah "root"
$password = ""; // Default XAMPP password kosong
$database = "reservasihoteldb"; // Ganti dengan nama database Anda

$db = new mysqli($host, $user, $password, $database); // Ubah dari $conn ke $db

// Cek koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
?>
