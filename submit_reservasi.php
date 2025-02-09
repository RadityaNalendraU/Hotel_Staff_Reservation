<?php
require 'koneksi.php';

// Test database connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_telepon = htmlspecialchars($_POST['no_telepon']);
    $no_kamar = htmlspecialchars($_POST['no_kamar']);
    $tanggal_check_in = htmlspecialchars($_POST['tanggal_check_in']);
    $tanggal_check_out = htmlspecialchars($_POST['tanggal_check_out']);
    $total_cost = htmlspecialchars($_POST['total_cost']); // Retrieve total cost from POST data

    // Log input values for debugging
    error_log("Input values: no_telepon=$no_telepon, no_kamar=$no_kamar, check_in=$tanggal_check_in, check_out=$tanggal_check_out");

    // Check if no_telepon exists in the tamu table
    $checkQuery = "SELECT COUNT(*) FROM tamu WHERE no_telepon = ?";
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

    // Prepare SQL query
    $query = "INSERT INTO Reservasi (no_telepon, no_kamar, tanggal_check_in, tanggal_check_out, status_reservasi) VALUES (?, ?, ?, ?, 'Belum Lunas')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $no_telepon, $no_kamar, $tanggal_check_in, $tanggal_check_out);

    if ($stmt->execute()) {
        $id_reservasi = $conn->insert_id; // Retrieve the last inserted ID



        echo json_encode(['success' => true]);
    } else {
        // Log the error
        error_log("Database insertion error: " . $stmt->error);
        
        // Provide detailed error messages
        $errorMessage = "Terjadi kesalahan saat menyimpan data.";
        if ($stmt->errno == 1062) {
            $errorMessage = "Nomor telepon atau nomor kamar sudah terdaftar.";
        } elseif ($stmt->errno == 1048) {
            $errorMessage = "Semua field harus diisi.";
        }
        
        echo json_encode(['success' => false, 'error' => $errorMessage]);
    }

    $stmt->close();
}
?>
