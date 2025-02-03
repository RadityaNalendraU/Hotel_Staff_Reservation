<?php
require 'pages/koneksi.php';

if (isset($_GET['type'])) {
    $roomType = $_GET['type'];

    // Prepare SQL query to fetch available rooms
    $query = "SELECT no_kamar FROM Kamar WHERE tipe_kamar = ? AND status_kamar = 'Tersedia'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $result = $stmt->get_result();

    $availableRooms = [];
    while ($row = $result->fetch_assoc()) {
        $availableRooms[] = $row;
    }

    // Return available rooms as JSON
    echo json_encode($availableRooms);
}
?>
