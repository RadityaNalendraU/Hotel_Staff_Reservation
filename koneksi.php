<?php
// db.php
$servername = "localhost";
$username = "root";
$password = "";
$database = "reservasihoteldb";

$db = new mysqli($servername, $username, $password, $database);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
?>