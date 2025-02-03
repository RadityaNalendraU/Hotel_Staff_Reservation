<?php
require __DIR__ . '/../pages/koneksi.php';

header('Content-Type: application/json');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$reservations = [];

$query = "SELECT * FROM log_reservasi";
$params = [];
$types = '';

if (!empty($search)) {
    $query .= " WHERE id_log LIKE ? OR id_reservasi LIKE ? OR no_telepon LIKE ? OR no_kamar LIKE ?";
    $searchParam = "%$search%";
    $params = [$searchParam, $searchParam, $searchParam, $searchParam];
    $types = "ssss";
}

$stmt = $db->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

$stmt->close();
$db->close();

echo json_encode($reservations);
?>
