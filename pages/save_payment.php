<?php

require __DIR__ . '/../pages/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Mengambil data pembayaran dari input

$payments = json_decode($_POST['payments'], true);

$success = true;

$errorMessages = [];

foreach ($payments as $payment) {

// Hapus dari tabel pembayaran

$deleteStmt = $db->prepare("DELETE FROM pembayaran WHERE id_pembayaran = ?");

$deleteStmt->bind_param("s", $payment['id_pembayaran']);


if (!$deleteStmt->execute()) {

$success = false;

$errorMessages[] = "Error deleting payment for ID {$payment['id_pembayaran']}: " . $deleteStmt->error;

}

$deleteStmt->close();

// Hapus dari tabel reservasi

$deleteReservasiStmt = $db->prepare("DELETE FROM reservasi WHERE id_reservasi = ?");

$deleteReservasiStmt->bind_param("s", $payment['id_reservasi']);


if (!$deleteReservasiStmt->execute()) {

$success = false;

$errorMessages[] = "Error deleting reservation for ID {$payment['id_reservasi']}: " . $deleteReservasiStmt->error;

}

$deleteReservasiStmt->close();

}

if ($success) {

echo json_encode(['status' => 'success']);

} else {

echo json_encode(['status' => 'error', 'message' => implode(', ', $errorMessages)]);

}

}

$db->close();

?>