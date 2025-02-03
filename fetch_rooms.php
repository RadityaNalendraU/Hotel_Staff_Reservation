<?php
require 'pages/koneksi.php';

if (isset($_GET['type'])) {
    $roomType = $_GET['type'];

    // Prepare SQL query to fetch available rooms
    $query = "SELECT no_kamar FROM Kamar WHERE tipe_kamar = ? AND status_kamar = 'Tersedia'";
    $stmt = $db->prepare($query);
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

// New endpoint to fetch room types
if (isset($_GET['get_types'])) {
    $query = "SELECT DISTINCT tipe_kamar FROM Kamar";
    $result = $db->query($query);
    
    if (!$result) {
        die("Query failed: " . $db->error); // Error handling
    }

    $roomTypes = [];
    while ($row = $result->fetch_assoc()) {
        $roomTypes[] = $row['tipe_kamar'];
    }
    echo json_encode($roomTypes);
    exit;
}
?>
