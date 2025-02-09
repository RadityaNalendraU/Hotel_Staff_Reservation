<?php
require 'koneksi.php';

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_telepon = htmlspecialchars($_POST['no_telepon']);
    $no_kamar = htmlspecialchars($_POST['no_kamar']);
    $tanggal_check_in = htmlspecialchars($_POST['tanggal_check_in']);
    $tanggal_check_out = htmlspecialchars($_POST['tanggal_check_out']);
    $total_cost = htmlspecialchars($_POST['total_cost']);

    // Memeriksa apakah nomor telepon terdaftar
    $checkQuery = "SELECT COUNT(*) FROM Tamu WHERE no_telepon = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $no_telepon);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count == 0) {
        echo json_encode(['success' => false, 'error' => "Nomor telepon tidak terdaftar."]);
        exit;
    }

    // Insert ke tabel Reservasi
    $query = "INSERT INTO Reservasi (no_telepon, no_kamar, tanggal_check_in, tanggal_check_out, total_pembayaran) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssd", $no_telepon, $no_kamar, $tanggal_check_in, $tanggal_check_out, $total_cost);

    if ($stmt->execute()) {
        $id_reservasi = $conn->insert_id; // Ambil ID reservasi yang baru dimasukkan

        // Insert ke tabel Pembayaran
        $paymentQuery = "INSERT INTO Pembayaran (id_reservasi, no_telepon, total_pembayaran) VALUES (?, ?, ?)";
        $paymentStmt = $conn->prepare($paymentQuery);
        $paymentStmt->bind_param("isd", $id_reservasi, $no_telepon, $total_cost);

        if ($paymentStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            error_log("Database insertion error (Pembayaran): " . $paymentStmt->error);
            echo json_encode(['success' => false, 'error' => "Terjadi kesalahan saat menyimpan data pembayaran."]);
        }

        $paymentStmt->close();
    } else {
        error_log("Database insertion error (Reservasi): " . $stmt->error);
        echo json_encode(['success' => false, 'error' => "Terjadi kesalahan saat menyimpan data reservasi."]);
    }

    $stmt->close();
}
?>