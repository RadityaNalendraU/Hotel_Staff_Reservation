<?php
require 'koneksi.php';

$search = '';
$noResults = false; // Variabel untuk menandai apakah hasil pencarian kosong

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
}

$query = "SELECT * FROM kamar";
if (!empty($search)) {
    $query .= " WHERE no_kamar LIKE ? OR status_kamar LIKE ? OR tipe_kamar LIKE ? OR harga_per_malam LIKE ?";
    $searchParam = "%" . $search . "%";
}

$stmt = $db->prepare($query);
if (!empty($search)) {
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $noResults = true; // Set bahwa tidak ada hasil
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-scroll {
            max-height: 300px;
            overflow-y: auto;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Kamar</h2>
            <form action="index.php" method="POST">
                <div class="mb-4">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="search" name="search" type="text" placeholder="Cari Kamar">
                </div>
                <div class="mb-4">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Search
                    </button>
                </div>
            </form>
            <div class="overflow-x-auto table-scroll">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Nomor Kamar</th>
                            <th class="py-2 px-4 border-b">Status Kamar</th>
                            <th class="py-2 px-4 border-b">Tipe Kamar</th>
                            <th class="py-2 px-4 border-b">Harga Permalam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$noResults): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class='py-2 px-4 border-b'><?= $row['no_kamar'] ?></td>
                                    <td class='py-2 px-4 border-b'><?= $row['status_kamar'] ?></td>
                                    <td class='py-2 px-4 border-b'><?= $row['tipe_kamar'] ?></td>
                                    <td class='py-2 px-4 border-b'>Rp <?= $row['harga_per_malam'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan='4' class='py-2 px-4 border-b text-center'>Tidak ada data ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <p>Data kamar tidak ditemukan.</p>
            <button id="close-modal" class="bg-red-500 text-white px-4 py-2 rounded mt-2">Tutup</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('modal');
            const closeModal = document.getElementById('close-modal');

            // Jika tidak ada hasil, tampilkan modal
            <?php if ($noResults): ?>
                modal.style.display = 'flex';
            <?php endif; ?>

            // Event listener untuk menutup modal
            closeModal.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });
    </script>
</body>
</html>
