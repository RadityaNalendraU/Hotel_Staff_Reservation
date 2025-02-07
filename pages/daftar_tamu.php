<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
    include 'koneksi.php';

    // Initialize success message variable
    $successMessage = "";

    // Handle update request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-no_telepon'])) {
        $no_telepon = $_POST['update-no_telepon'];
        $nama = $_POST['update-nama'];
        $alamat = $_POST['update-alamat'];
        $email = $_POST['update-email'];
        
        $updateQuery = "UPDATE tamu SET nama=?, alamat=?, email=? WHERE no_telepon=?";
        $stmt = $db->prepare($updateQuery);
        $stmt->bind_param("ssss", $nama, $alamat, $email, $no_telepon);
        
        if ($stmt->execute()) {
            $successMessage = "Data berhasil diperbarui.";
        } else {
            die("Update failed: " . $stmt->error);
        }
        
        $stmt->close();
    }

    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-no_telepon'])) {
        $no_telepon = $_POST['delete-no_telepon'];

        // Begin a transaction
        $db->begin_transaction();

        try {
            // Delete related records from pembayaran
            $deletePembayaranQuery = "DELETE FROM pembayaran WHERE no_telepon=?";
            $stmtPembayaran = $db->prepare($deletePembayaranQuery);
            $stmtPembayaran->bind_param("s", $no_telepon);
            $stmtPembayaran->execute();
            $stmtPembayaran->close();

            // Delete related records from reservasi
            $deleteReservasiQuery = "DELETE FROM reservasi WHERE no_telepon=?";
            $stmtReservasi = $db->prepare($deleteReservasiQuery);
            $stmtReservasi->bind_param("s", $no_telepon);
            $stmtReservasi->execute();
            $stmtReservasi->close();

            // Delete the guest from tamu
            $deleteTamuQuery = "DELETE FROM tamu WHERE no_telepon=?";
            $stmtTamu = $db->prepare($deleteTamuQuery);
            $stmtTamu->bind_param("s", $no_telepon);
            $stmtTamu->execute();
            $stmtTamu->close();

            // Commit the transaction
            $db->commit();

            $successMessage = "Data tamu dan semua catatan terkait berhasil dihapus.";
        } catch (Exception $e) {
            // Rollback the transaction if something failed
            $db->rollback();
            $successMessage = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
    $query = "SELECT no_telepon, nama, email, alamat, loyalitas FROM tamu";
    $result = mysqli_query($db, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search-input'])) {
        $searchTerm = $_POST['search-input'];
        $stmt = $db->prepare("CALL SearchTamu(?)");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.querySelector('tbody');
            const modal = document.getElementById('modal');
            const modalClose = document.getElementById('modal-close');
            const deleteModal = document.getElementById('delete-modal');
            const deleteModalClose = document.getElementById('delete-modal-close');
            const deleteConfirmButton = document.getElementById('delete-confirm');
            const updateForm = document.getElementById('update-form');
            const successModal = document.getElementById('updateSuccessModal');
            const successClose = document.getElementById('success-modal-close');
            let currentRow;
            let currentDeleteRow;

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
                    currentDeleteRow = target.closest('tr');
                    deleteModal.classList.remove('hidden');
                }
            });

            modalClose.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            deleteModalClose.addEventListener('click', function () {
                deleteModal.classList.add('hidden');
            });

            deleteConfirmButton.addEventListener('click', function () {
                const no_telepon = currentDeleteRow.dataset.no_telepon;
                // Create a form to submit delete request
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="delete-no_telepon" value="${no_telepon}">`;
                document.body.appendChild(form);
                form.submit();
            });

            updateForm.addEventListener('submit', function (e) {
                e.preventDefault();
                updateForm.submit();
            });

            // Show success modal if the success message is set
            <?php if ($successMessage): ?>
                successModal.classList.remove('hidden');
            <?php endif; ?>

            // Close success modal
            successClose.addEventListener('click', function () {
                successModal.classList.add('hidden');
            });
        });
    </script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-center w-full">Daftar Tamu</h2>
            <div class="flex items-center">
                <form method="POST">
                    <input id="search-input" name="search-input" type="text" placeholder="Cari Nama Tamu" class="border p-2 rounded-lg mr-2">
                    <button type="submit" id="search-button" class="bg-green-500 text-white px-4 py-2 rounded-lg">Search</button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto" style="max-height: 400px; overflow-y: auto;">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border">Nomor Telepon</th>
                        <th class="py-2 px-4 border">Nama Tamu</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Alamat</th>
                        <th class="py-2 px-4 border">Loyalitas</th>
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
                                    <div class="flex space-x-2">
                                        <button class="update-button bg-green-500 text-white px-3 py-1 rounded-lg">Update</button>
                                        <button class="delete-button bg-red-500 text-white px-3 py-1 rounded-lg">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="py-2 px-4 text-center text-gray-500">Tidak ada tamu yang ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Update -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4 text-center">Update Data</h2>
            <form id="update-form" method="POST">
                <input id="update-no_telepon" name="update-no_telepon" type="hidden" class="border p-2 w-full mb-2 rounded">

                <label class="block font-medium">Nama Tamu:</label>
                <input id="update-nama" name="update-nama" type="text" class="border p-2 w-full mb-2 rounded" required>

                <label class="block font-medium">Alamat:</label>
                <input id="update-alamat" name="update-alamat" type="text" class="border p-2 w-full mb-2 rounded" required>

                <label class="block font-medium">Email:</label>
                <input id="update-email" name="update-email" type="text" class="border p-2 w-full mb-2 rounded" required>

                <label class="block font-medium">Loyalitas:</label>
                <input id="update-loyalitas" type="text" class="border p-2 w-full mb-2 rounded" disabled>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                    <button type="button" id="modal-close" class="ml-2 bg-red-500 text-white px-4 py-2 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4 text-center">Konfirmasi Hapus</h2>
            <p class="mb-4 text-center">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end mt-4">
                <button id="delete-confirm" class="bg-red-500 text-white px-4 py-2 rounded-lg">Hapus</button>
                <button type="button" id="delete-modal-close" class="ml-2 bg-gray-300 text-black px-4 py-2 rounded-lg">Batal</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="updateSuccessModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-20 w-68">
            <h3 class="text-lg font-bold mb-2 text-center text-gray-800">Pemberitahuan</h3>
            <p class="mb-4 text-center text-gray-700"><?= htmlspecialchars($successMessage) ?></p>
            <div class="flex justify-center">
                <button class="bg-green-500 text-white font-bold py-2 px-4 rounded" id="success-modal-close">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</body>
</html>