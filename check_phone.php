<?php
require 'koneksi.php'; // Include database connection

$phone = $_GET['phone'];
$query = "SELECT * FROM tamu WHERE no_telepon = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $phone);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['exists' => $result->num_rows > 0]);
?>
