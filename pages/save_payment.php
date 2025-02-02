<?php
require __DIR__ . '/../pages/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payments = json_decode($_POST['payments'], true);
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    $success = true;
    $errorMessages = [];

    foreach ($payments as $payment) {
        // Check if payment already exists in log_pembayaran
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM log_pembayaran WHERE id_pembayaran = ?");
        $checkStmt->bind_param("s", $payment['id_pembayaran']);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        // Skip if the payment is already logged
        if ($count > 0) {
            continue;
        }

        // Proceed to log the payment
        $stmt = $db->prepare("INSERT INTO log_pembayaran (id_pembayaran, id_reservasi, jumlah_bayar, metode_pembayaran) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $payment['id_pembayaran'], $payment['id_reservasi'], $jumlah_bayar, $metode_pembayaran);
        if (!$stmt->execute()) {
            $success = false;
            $errorMessages[] = "Error inserting payment for ID {$payment['id_pembayaran']}: " . $stmt->error;
        }
        $stmt->close();
        
        // Delete from pembayaran
        $deleteStmt = $db->prepare("DELETE FROM pembayaran WHERE id_pembayaran = ?");
        $deleteStmt->bind_param("s", $payment['id_pembayaran']);
        if (!$deleteStmt->execute()) {
            $success = false;
            $errorMessages[] = "Error deleting payment for ID {$payment['id_pembayaran']}: " . $deleteStmt->error;
        }
        $deleteStmt->close();
    }

    if ($success) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => implode(', ', $errorMessages)]);
    }
}

$db->close();
?>