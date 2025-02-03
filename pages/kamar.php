<?php
require __DIR__ . '/../pages/koneksi.php'; // Include the database connection file

// Initialize success flag
$updateSuccess = false;

// Handle the form submission when updating the data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_kamar'])) {
    // Get data from the POST request
    $no_kamar = $_POST['no_kamar'];
    $status_kamar = $_POST['status_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga_per_malam = $_POST['harga_per_malam'];

    // Prepare the SQL query to update the room data
    $query = "UPDATE kamar SET status_kamar = ?, tipe_kamar = ?, harga_per_malam = ? WHERE no_kamar = ?";

    // Prepare the statement
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bind_param("ssss", $status_kamar, $tipe_kamar, $harga_per_malam, $no_kamar);

    // Execute the statement
    if ($stmt->execute()) {
        // Set success flag to true if the update is successful
        $updateSuccess = true;
    } else {
        // If the update fails, display an error
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Initialize search variable and avoid undefined index warning
$search = isset($_POST['search']) ? $_POST['search'] : '';
$noDataFound = false; // Flag to check if no data is found

// Prepare SQL query
$query = "SELECT * FROM kamar";
if (!empty($search)) {
    $query .= " WHERE no_kamar LIKE ? OR status_kamar LIKE ? OR tipe_kamar LIKE ? OR harga_per_malam LIKE ?";
    $searchParam = "%" . $search . "%";
}

// Prepare the statement
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
        $rows[] = $row;
    }
} else {
    $noDataFound = true; // Set flag if no data found
}

// Close the statement and connection after all queries are done
$stmt->close();
$db->close();
?>

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
        body {
            margin: 0; /* Remove default margin */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-start min-h-screen pt-10">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Kamar</h2>
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
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if data was found and render table rows
                        if (!empty($rows)) {
                            foreach ($rows as $row) {
                                echo "<tr>
                                        <td class='py-2 px-4 border-b'>{$row['no_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['status_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['tipe_kamar']}</td>
                                        <td class='py-2 px-4 border-b'>Rp {$row['harga_per_malam']}</td>
                                        <td class='py-2 px-4 border-b'>
                                            <button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' onclick='openModal(\"{$row['no_kamar']}\", \"{$row['status_kamar']}\", \"{$row['tipe_kamar']}\", \"{$row['harga_per_malam']}\")'>
                                                Update
                                            </button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='py-2 px-4 border-b text-center text-gray-700'>Tidak ada data ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Update -->
    <div id="updateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h3 class="text-lg font-bold mb-4 text-center text-gray-800">Update Kamar</h3>
            <form id="updateForm" action="" method="POST">
                <input type="hidden" name="no_kamar" id="modal_no_kamar">
                <div class="mb-4">
                    <label for="modal_no_kamar_display" class="block text-gray-700">Nomor Kamar</label>
                    <input type="text" id="modal_no_kamar_display" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
                </div>
                <div class="mb-4">
                    <label for="modal_status_kamar" class="block text-gray-700">Status Kamar</label>
                    <input type="text" name="status_kamar" id="modal_status_kamar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="modal_tipe_kamar" class="block text-gray-700">Tipe Kamar</label>
                    <select name="tipe_kamar" id="modal_tipe_kamar" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="Standar">Standar</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="modal_harga_per_malam" class="block text-gray-700">Harga Permalam</label>
                    <input type="number" name="harga_per_malam" id="modal_harga_per_malam" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                    <button type="button" class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="closeModal()">
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <?php if ($updateSuccess): ?>
    <div id="updateSuccessModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h3 class="text-lg font-bold mb-4 text-center text-gray-800">Pemberitahuan</h3>
            <p class="mb-4 text-center text-gray-700">Data kamar berhasil diperbarui!</p>
            <div class="flex justify-center">
                <button class="bg-green-500 text-white font-bold py-2 px-4 rounded" onclick="closeSuccessModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        function openModal(no_kamar, status_kamar, tipe_kamar, harga_per_malam) {
            document.getElementById('modal_no_kamar').value = no_kamar;
            document.getElementById('modal_no_kamar_display').value = no_kamar; // Display the room number but make it readonly
            document.getElementById('modal_status_kamar').value = status_kamar;
            document.getElementById('modal_tipe_kamar').value = tipe_kamar;
            document.getElementById('modal_harga_per_malam').value = harga_per_malam;
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('updateModal').style.display = 'none'; // Hide update modal
        }

        function closeSuccessModal() {
            document.getElementById('updateSuccessModal').style.display = 'none'; // Hide success modal
        }
    </script>
</body>
</html>
