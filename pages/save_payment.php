<?php
require __DIR__ . '/../pages/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $payments = json_decode($_POST['payments'], true);
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Initialize total payment and extract reservation IDs
    $totalPembayaran = 0;
    $idReservasiList = [];

    // Calculate total payment and extract reservation IDs
    foreach ($payments as $payment) {
        $totalPembayaran += floatval(preg_replace('/[^0-9.-]+/', '', $payment['total']));
        $idReservasiList[] = $payment['id_reservasi'];
    }

    $statusReservasi = 'Lunas'; // Status indicating payment completion
    $idReservasiList = implode(',', array_map('intval', $idReservasiList)); // Prepare for SQL IN clause

    // Update each reservation status and set total payment to the calculated amount
    $query = "UPDATE reservasi SET status_reservasi = ?, total_pembayaran = ? WHERE id_reservasi IN ($idReservasiList)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sd", $statusReservasi, $totalPembayaran); // No longer adding to total_pembayaran

    if ($stmt->execute()) {
        // Payment was successful
        echo json_encode(['status' => 'success']);
    } else {
        // Error while updating
        error_log("Error updating reservasi: " . $stmt->error);
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $db->close();
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>