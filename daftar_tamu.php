<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
    include 'koneksi.php';

    $query = "SELECT  no_telepon, nama, email, alamat FROM tamu";
    $result = mysqli_query($db, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($db));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $nama = $_POST['nama'];

        $query = "UPDATE tamu SET nama='$nama' WHERE no_telepon='$id'";

        if (mysqli_query($db, $query)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($db)]);
        }
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
    
        $query = "DELETE FROM tamu WHERE no_telepon='$id'";
    
        if (mysqli_query($db, $query)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => mysqli_error($db)]);
        }
    }
    
    ?>


    <script>
       document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.querySelector('tbody');
        const modal = document.getElementById('modal');
        const modalClose = document.getElementById('modal-close');
        const updateForm = document.getElementById('update-form');
        let currentRow;

        tableBody.addEventListener('click', function (event) {
            const target = event.target;

            if (target.classList.contains('update-button')) {
                currentRow = target.closest('tr');

                // Ambil data dari atribut data-id dan dataset lainnya
                const noTelepon = currentRow.dataset.id;
                const nama = currentRow.dataset.nama;
                const email = currentRow.dataset.email;
                const alamat = currentRow.dataset.alamat;

                // Masukkan nilai ke dalam form modal
                document.getElementById('update-id').value = noTelepon;
                document.getElementById('update-nama').value = nama;
                document.getElementById('update-email').value = email;
                document.getElementById('update-alamat').value = alamat;

                // Tampilkan modal
                modal.classList.remove('hidden');
            }
        });

        // Menutup modal saat tombol "Batal" diklik
        modalClose.addEventListener('click', function () {
            modal.classList.add('hidden');
        });

        // Form submit untuk update data
        updateForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const noTelepon = document.getElementById('update-id').value;
            const nama = document.getElementById('update-nama').value;
            const email = document.getElementById('update-email').value;
            const alamat = document.getElementById('update-alamat').value;

            fetch('update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `no_telepon=${noTelepon}&nama=${nama}&email=${email}&alamat=${alamat}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Update tampilan tabel setelah update berhasil
                    currentRow.cells[1].textContent = nama;
                    currentRow.cells[2].textContent = email;
                    currentRow.cells[3].textContent = alamat;
                    modal.classList.add('hidden');
                } else {
                    alert("Gagal mengupdate: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Daftar Tamu</h2>
            <div class="overflow-x-auto max-h-60 overflow-y-auto mb-4 border rounded">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">no_telepon</th>
                            <th class="py-2 px-4 border-b">nama</th>
                            <th class="py-2 px-4 border-b">email</th>
                            <th class="py-2 px-4 border-b">alamat</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr data-id="<?= $row['no_telepon'] ?>" data-nama="<?= $row['nama'] ?>">
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['no_telepon']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['alamat']) ?></td>
                            <td class="py-2 px-4 border-b">
                                <button class="update-button bg-green-500 text-white px-4 py-1 rounded">Update</button>
                                <button class="delete-button bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Update Data</h2>
            <form id="update-form">
                <label class="block">Nomor Telepon:</label>
                <input id="update-id" type="text" class="border p-2 w-full mb-2" disabled>
                <label class="block">Nama:</label>
                <input id="update-nama" type="text" class="border p-2 w-full mb-4">
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                    <button type="button" id="modal-close" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($db);
?>