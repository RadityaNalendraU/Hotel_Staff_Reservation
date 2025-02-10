<?php
include 'koneksi.php';

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

$reservations = [];

if (!empty($startDate) && !empty($endDate)) {
    // Ambil data berdasarkan filter tanggal dan hanya status lunas
    $stmt = $db->prepare("SELECT id_reservasi, no_telepon, no_kamar, tanggal_check_in, tanggal_check_out, status_reservasi, total_pembayaran 
                          FROM reservasi 
                          WHERE (tanggal_check_in BETWEEN ? AND ? OR tanggal_check_out BETWEEN ? AND ?) 
                          AND status_reservasi = 'Lunas'");
    $stmt->bind_param("ssss", $startDate, $endDate, $startDate, $endDate);
} else {
    // Ambil semua data dengan status lunas tanpa filter tanggal
    $stmt = $db->prepare("SELECT id_reservasi, no_telepon, no_kamar, tanggal_check_in, tanggal_check_out, status_reservasi, total_pembayaran 
                          FROM reservasi 
                          WHERE status_reservasi = 'Lunas'");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
}

// Tutup koneksi
$stmt->close();
$db->close();

// Jika permintaan berasal dari AJAX, kirimkan JSON
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($reservations);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchReservations(); // Ambil data saat halaman pertama kali dimuat
        });

        async function fetchReservations(startDate = "", endDate = "") {
            let url = `log_reservasi.php?ajax=1`;
            if (startDate && endDate) {
                url += `&start_date=${encodeURIComponent(startDate)}&end_date=${encodeURIComponent(endDate)}`;
            }

            const response = await fetch(url);
            const reservations = await response.json();
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            if (reservations.length === 0) {
                showModal('Peringatan', 'Data tidak ditemukan.');
            } else {
                reservations.forEach(reservation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">${reservation.id_reservasi}</td>
                        <td class="py-2 px-4 border-b">${reservation.no_telepon}</td>
                        <td class="py-2 px-4 border-b">${reservation.no_kamar}</td>
                        <td class="py-2 px-4 border-b">${reservation.tanggal_check_in}</td>
                        <td class="py-2 px-4 border-b">${reservation.tanggal_check_out}</td>
                        <td class="py-2 px-4 border-b">${reservation.status_reservasi}</td>
                        <td class="py-2 px-4 border-b">${reservation.total_pembayaran}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        }

        function searchReservations() {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            fetchReservations(startDate, endDate);
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
        <form action="" method="POST">
        <div class="flex mb-4">
            <input id="start-date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="date">
            <input id="end-date" name="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ml-2" type="date">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">Cari</button>
        </div>
        </form>
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID Reservasi</th>
                        <th class="py-2 px-4 border-b">No Telepon</th>
                        <th class="py-2 px-4 border-b">No Kamar</th>
                        <th class="py-2 px-4 border-b">Tanggal Check-In</th>
                        <th class="py-2 px-4 border-b">Tanggal Check-Out</th>
                        <th class="py-2 px-4 border-b">Status Reservasi</th>
                        <th class="py-2 px-4 border-b">Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['id_reservasi']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['no_telepon']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['no_kamar']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['tanggal_check_in']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['tanggal_check_out']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['status_reservasi']); ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($reservation['total_pembayaran']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="py-2 px-4 text-center text-gray-500">Tidak Ada Data di Tanggal Yang di Maksud</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Peringatan -->
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
