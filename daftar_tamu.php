<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.querySelector('tbody');
            const modal = document.getElementById('modal');
            const modalClose = document.getElementById('modal-close');
            const updateForm = document.getElementById('update-form');
            let currentRow;

            // Event listener untuk tombol update dan delete
            tableBody.addEventListener('click', function (event) {
                const target = event.target;

                // Jika tombol update diklik
                if (target.classList.contains('update-button')) {
                    currentRow = target.closest('tr');

                    // Mengambil data dari row menggunakan data-attribute
                    const idReservasi = currentRow.dataset.id;
                    const namaTamu = currentRow.dataset.nama;

                    // Memasukkan nilai ke dalam form update
                    document.getElementById('update-id').value = idReservasi;
                    document.getElementById('update-nama').value = namaTamu;

                    // Tampilkan modal
                    modal.classList.remove('hidden');
                }

                // Jika tombol delete diklik
                if (target.classList.contains('delete-button')) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        target.closest('tr').remove();
                    }
                }
            });

            // Menutup modal saat tombol "Batal" diklik
            modalClose.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            // Menyimpan perubahan di modal update
            updateForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Perbarui data pada row yang sedang diedit
                currentRow.cells[0].textContent = document.getElementById('update-id').value;
                currentRow.cells[3].textContent = document.getElementById('update-nama').value;

                // Sembunyikan modal
                modal.classList.add('hidden');
            });
        });
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Daftar Tamu</h2>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID Reservasi</th>
                            <th class="py-2 px-4 border-b">Nomor Telepon</th>
                            <th class="py-2 px-4 border-b">Nomor Kamar</th>
                            <th class="py-2 px-4 border-b">Nama Tamu</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-id="12345" data-nama="Monkey D Luffy">
                            <td class="py-2 px-4 border-b">12345</td>
                            <td class="py-2 px-4 border-b">08123456789</td>
                            <td class="py-2 px-4 border-b">101</td>
                            <td class="py-2 px-4 border-b">Monkey D Luffy</td>
                            <td class="py-2 px-4 border-b">
                                <button class="update-button bg-green-500 text-white px-4 py-1 rounded">Update</button>
                                <button class="delete-button bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                            </td>
                        </tr>
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
                <label class="block">ID Reservasi:</label>
                <input id="update-id" type="text" class="border p-2 w-full mb-2" disabled>
                <label class="block">Nama Tamu:</label>
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
