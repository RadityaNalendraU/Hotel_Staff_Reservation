<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-scroll {
            max-height: 300px; /* Set a max height for the table */
            overflow-y: auto; /* Enable vertical scrolling */
        }
        .fixed {
            display: flex;
        }
        body {
            margin: 0; /* Remove default margin */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-start min-h-screen pt-10">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Kamar</h2>
            
            <!-- Form untuk mengubah harga -->
            <form action="" method="POST" class="mb-6">
                <div class="mb-4">
                    <select name="tipe_kamar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Tipe Kamar</option>
                        <option value="Standar">Standar</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                    </select>
                </div>
                <div class="mb-4">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="harga_baru" type="number" placeholder="Harga Baru" required>
                </div>
                <div class="mb-4">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Ubah Harga
                    </button>
                </div>
            </form>

            <form action="" method="POST">
                <div class="mb-4">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="search" name="search" type="text" placeholder="Cari Kamar" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                </div>
                <div class="mb-4">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Cari
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
                        <?php
                        require __DIR__ . '/../pages/koneksi.php';

                        // Initialize search variable
                        $search = '';
                        $noDataFound = false; // Flag to check if no data is found
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST['tipe_kamar']) && isset($_POST['harga_baru'])) {
                                $tipe_kamar = $_POST['tipe_kamar'];
                                $harga_baru = $_POST['harga_baru'];

                                // Update query
                                $update_query = "UPDATE kamar SET harga_per_malam = ? WHERE tipe_kamar = ?";
                                $update_stmt = $db->prepare($update_query);
                                $update_stmt->bind_param("is", $harga_baru, $tipe_kamar);
                                $update_stmt->execute();
                                $update_stmt->close();
                            }

                            // Check if search is set
                            if (isset($_POST['search'])) {
                                $search = $_POST['search'];
                            }
                        }

                        // Prepare SQL query
                        $query = "SELECT * FROM kamar";
                        if (!empty($search)) {
                            $query .= " WHERE no_kamar LIKE ? OR status_kamar LIKE ? OR tipe_kamar LIKE ? OR harga_per_malam LIKE ?";
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

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td class='py-2 px-4 border-b'>{$row['no_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['status_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['tipe_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>Rp {$row['harga_per_malam']}</td>
                                      </tr>";
                            }
                        } else {
                            $noDataFound = true; // Set flag if no data found
                        }

                        // Close statement and connection
                        $stmt->close();
                        $db->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for No Data Found -->
    <?php if ($noDataFound): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h3 class="text-lg font-bold mb-4 text-center text-gray-800">Pemberitahuan</h3>
            <p class="mb-4 text-center text-gray-700">Tidak ada data ditemukan untuk pencarian Anda.</p>
            <div class="flex justify-center">
                <button class="bg-green-500 text-white font-bold py-2 px-4 rounded" onclick="document.querySelector('.fixed').style.display='none'">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>
