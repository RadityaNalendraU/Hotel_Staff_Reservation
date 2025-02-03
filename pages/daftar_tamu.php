<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
    include 'koneksi.php';

    $query = "SELECT  no_telepon, nama, email, alamat, loyalitas FROM tamu";
    $result = mysqli_query($db, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search-input'])) {
        $searchTerm = $_POST['search-input'];
    
        // Panggil stored procedure
        $stmt = $db->prepare("CALL SearchTamu(?)");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        
        $result = $stmt->get_result();
    } else {
        // Jika tidak ada pencarian, tampilkan semua data
        $query = "SELECT no_telepon, nama, email, alamat, loyalitas FROM tamu";
        $result = mysqli_query($db, $query);
    }
    
    if (!$result) {
        die("Query gagal: " . mysqli_error($db));
    }
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.querySelector('tbody');
            const modal = document.getElementById('modal');
            const modalClose = document.getElementById('modal-close');
            const updateForm = document.getElementById('update-form');
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');
            let currentRow;

            tableBody.addEventListener('click', function (event) {
                const target = event.target;
                if (target.classList.contains('update-button')) {
                    currentRow = target.closest('tr');

                    document.getElementById('update-no_telepon').value = currentRow.dataset.no_telepon;
                    document.getElementById('update-nama').value = currentRow.dataset.nama;
                    document.getElementById('update-alamat').value = currentRow.dataset.alamat;
                    document.getElementById('update-email').value = currentRow.dataset.email;
                    document.getElementById('update-loyalitas').value = currentRow.dataset.loyalitas;

                    modal.classList.remove('hidden');
                }

                if (target.classList.contains('delete-button')) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        target.closest('tr').remove();
                    }
                }
            });

            modalClose.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            updateForm.addEventListener('submit', function (e) {
                e.preventDefault();
                currentRow.cells[1].textContent = document.getElementById('update-no_telepon').value;
                currentRow.cells[3].textContent = document.getElementById('update-nama').value;
                currentRow.cells[4].textContent = document.getElementById('update-alamat').value;
                currentRow.cells[5].textContent = document.getElementById('update-email').value;
                currentRow.cells[5].textContent = document.getElementById('update-loyalitas').value;
                modal.classList.add('hidden');
            });
            });
    </script>
</head>
<center>
<body class="bg-gray-100 flex justify-center items-center min-h-screen p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Daftar Tamu</h2>
            <div class="flex items-center">
                <form method="POST">
                <input id="search-input" name="search-input" type="text" placeholder="Cari Nama Tamu" class="border p-2 rounded-lg mr-2">
                <button type="submit" id="search-button" class="bg-green-500 text-white px-4 py-2 rounded-lg">Search</button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border">Nomor Telepon</th>
                        <th class="py-2 px-4 border">Nama Tamu</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Alamat</th>
                        <th class="py-2 px-4 border">loyalitas</th>
                        <th class="py-2 px-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr data-no_telepon="<?= $row['no_telepon'] ?>" 
                                data-nama="<?= $row['nama'] ?>" 
                                data-email="<?= $row['email'] ?>" 
                                data-alamat="<?= $row['alamat'] ?>" 
                                data-loyalitas="<?= $row['loyalitas'] ?>">
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['alamat']) ?></td>
                                <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['loyalitas']) ?></td>

                                <td class="py-2 px-4 border-b">
                                    <button class="update-button bg-green-500 text-white px-3 py-1 rounded-lg">Update</button>
                                    <button class="delete-button bg-red-500 text-white px-3 py-1 rounded-lg">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="py-2 px-4 text-center text-gray-500">Tidak ada tamu yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
    </center>
    <!-- Modal Update -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4 text-center">Update Data</h2>
            <form id="update-form">
                <label class="block font-medium">Nomor Telepon:</label>
                <input id="update-no_telepon" name="update-no_telepon" type="text" class="border p-2 w-full mb-2 rounded">

                <label class="block font-medium">Nama Tamu:</label>
                <input id="update-nama" name="update-nama" type="text" class="border p-2 w-full mb-2 rounded">

                <label class="block font-medium">Alamat:</label>
                <input id="update-alamat" name="update-alamat" type="text" class="border p-2 w-full mb-2 rounded">

                <label class="block font-medium">Email:</label>
                <input id="update-email" name="update-email" type="text" class="border p-2 w-full mb-2 rounded">

                <label class="block font-medium">loyalitas:</label>
                <input id="update-loyalitas" type="text" class="border p-2 w-full mb-2 rounded" disabled>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                    <button type="button" id="modal-close" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>