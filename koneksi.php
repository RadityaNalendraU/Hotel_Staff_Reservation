<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservasihoteldb";



// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>