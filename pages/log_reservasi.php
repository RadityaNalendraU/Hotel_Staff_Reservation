<?php
require __DIR__ . '/../pages/koneksi.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$reservations = [];

// Prepare SQL query
$query = "SELECT * FROM log_reservasi"; // Adjust 'log_reservasi' to your actual table name

if (!empty($search)) {
    $query .= " WHERE id_log LIKE ? OR id_reservasi LIKE ? OR no_telepon LIKE ? OR no_kamar LIKE ?";
    $searchParam = "%" . $search . "%";
}

// Prepare statement
$stmt = $db->prepare($query);
if (!empty($search)) {
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        async function searchReservations() {
            const search = document.getElementById('search').value;
            const response = await fetch(`log_reservasi.php?search=${encodeURIComponent(search)}`);
            const reservations = await response.json();
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (reservations.length === 0) {
                showModal('Peringatan', 'Data yang dicari tidak ditemukan.');
            } else {
                reservations.forEach(reservation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">${reservation.id_log}</td>
                        <td class="py-2 px-4 border-b">${reservation.id_reservasi}</td>
                        <td class="py-2 px-4 border-b">${reservation.no_telepon}</td>
                        <td class="py-2 px-4 border-b">${reservation.no_kamar}</td>
                        <td class="py-2 px-4 border-b">${reservation.tanggal_check_in}</td>
                        <td class="py-2 px-4 border-b">${reservation.tanggal_waktu}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        }

        function showModal(title, message) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').textContent = message;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100">

<div class="flex justify-center items-start min-h-screen pt-10">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
        <h2 class="text-2xl font-bold mb-6">Log Reservasi</h2>
        <div class="flex mb-4">
            <input id="search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Cari Reservasi">
            <button onclick="searchReservations()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">Cari</button>
        </div>
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID Log</th>
                        <th class="py-2 px-4 border-b">ID Reservasi</th>
                        <th class="py-2 px-4 border-b">No Telepon</th>
                        <th class="py-2 px-4 border-b">No Kamar</th>
                        <th class="py-2 px-4 border-b">Tanggal Check-in</th>
                        <th class="py-2 px-4 border-b">Tanggal Waktu</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['id_log']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['id_reservasi']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['no_telepon']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['no_kamar']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['tanggal_check_in']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['tanggal_waktu']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for No Results -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h2 id="modal-title" class="text-xl font-bold mb-4">Peringatan</h2>
        <p id="modal-message" class="mb-4">Data yang dicari tidak ditemukan.</p>
        <div class="flex justify-end">
            <button onclick="closeModal()" class="bg-green-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Tutup
            </button>
        </div>
    </div>
</div>

</body>
</html>