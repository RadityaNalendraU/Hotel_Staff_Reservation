<?php

require __DIR__ . '/../pages/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data pembayaran dari input
    $payments = json_decode($_POST['payments'], true);

    $success = true;
    $errorMessages = [];

    foreach ($payments as $payment) {
        // Ambil total_pembayaran dari tabel pembayaran
        $totalPaymentStmt = $db->prepare("SELECT total_pembayaran FROM pembayaran WHERE id_pembayaran = ?");
        $totalPaymentStmt->bind_param("s", $payment['id_pembayaran']);
        $totalPaymentStmt->execute();
        $totalPaymentStmt->bind_result($total_pembayaran);
        $totalPaymentStmt->fetch();
        $totalPaymentStmt->close();

        // Cek apakah sudah ada entry di log_reservasi
        $checkLogStmt = $db->prepare("SELECT COUNT(*) FROM log_reservasi WHERE id_reservasi = ? AND id_pembayaran = ?");
        $checkLogStmt->bind_param("ss", $payment['id_reservasi'], $payment['id_pembayaran']);
        $checkLogStmt->execute();
        $checkLogStmt->bind_result($count);
        $checkLogStmt->fetch();
        $checkLogStmt->close();

        // Hanya insert ke log_reservasi jika belum ada
        if ($count == 0) {
            $insertLogStmt = $db->prepare("INSERT INTO log_reservasi (id_reservasi, id_pembayaran, tanggal_dihapus, total_pembayaran) VALUES (?, ?, NOW(), ?)");
            $insertLogStmt->bind_param("ssi", $payment['id_reservasi'], $payment['id_pembayaran'], $total_pembayaran);

            if (!$insertLogStmt->execute()) {
                $success = false;
                $errorMessages[] = "Error logging reservation for ID {$payment['id_reservasi']}: " . $insertLogStmt->error;
            }

            $insertLogStmt->close();
        }

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