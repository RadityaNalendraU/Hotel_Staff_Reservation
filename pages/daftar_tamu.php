<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    
                    document.getElementById('update-id').value = currentRow.dataset.id;
                    document.getElementById('update-notelepon').value = currentRow.dataset.notelepon;
                    document.getElementById('update-nomorkamar').value = currentRow.dataset.nomorkamar;
                    document.getElementById('update-nama').value = currentRow.dataset.nama;
                    document.getElementById('update-alamat').value = currentRow.dataset.alamat;
                    document.getElementById('update-email').value = currentRow.dataset.email;

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
                currentRow.cells[0].textContent = document.getElementById('update-id').value;
                currentRow.cells[1].textContent = document.getElementById('update-notelepon').value;
                currentRow.cells[2].textContent = document.getElementById('update-nomorkamar').value;
                currentRow.cells[3].textContent = document.getElementById('update-nama').value;
                currentRow.cells[4].textContent = document.getElementById('update-alamat').value;
                currentRow.cells[5].textContent = document.getElementById('update-email').value;
                modal.classList.add('hidden');
            });

            searchButton.addEventListener('click', function () {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr');
                let found = false;

                rows.forEach(row => {
                    const namaTamu = row.dataset.nama.toLowerCase();
                    if (namaTamu.includes(searchTerm)) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (!found) {
                    alert('Tidak ada tamu yang ditemukan.');
                }
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
                <input id="search-input" type="text" placeholder="Cari Nama Tamu" class="border p-2 rounded-lg mr-2">
                <button id="search-button" class="bg-green-500 text-white px-4 py-2 rounded-lg">Search</button>
            </div>
        </div>
        <div class="overflow-auto max-h-96 border border-gray-300 rounded-lg">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-2 px-4 border">ID Reservasi</th>
                        <th class="py-2 px-4 border">Nomor Telepon</th>
                        <th class="py-2 px-4 border">Nomor Kamar</th>
                        <th class="py-2 px-4 border">Nama Tamu</th>
                        <th class="py-2 px-4 border">Alamat</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center" data-id="1001" data-notelepon="08123456789" data-nomorkamar="101" data-nama="Roronoa Zoro" data-alamat="Shimotsuki Village" data-email="zoro@gmail.com">
                        <td class="py-2 px-4 border">1001</td>
                        <td class="py-2 px-4 border">08123456789</td>
                        <td class="py-2 px-4 border">101</td>
                        <td class="py-2 px-4 border">Roronoa Zoro</td>
                        <td class="py-2 px-4 border">Shimotsuki Village</td>
                        <td class="py-2 px-4 border">zoro@gmail.com</td>
                        <td class="py-2 px-4 border flex justify-center space-x-2">
                        <button class="update-button bg-green-500 text-white px-3 py-1 rounded-lg">Update</button>
                        <button class="delete-button bg-red-500 text-white px-3 py-1 rounded-lg">Delete</button>
                        </td>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </center>
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4 text-center">Update Data</h2>
            <form id="update-form">
                <label class="block font-medium">ID Reservasi:</label>
                <input id="update-id" type="text" class="border p-2 w-full mb-2 rounded" disabled>
                <label class="block font-medium">Nomor Telepon:</label>
                <input id="update-notelepon" type="text" class="border p-2 w-full mb-2 rounded">
                <label class="block font-medium">Nomor Kamar:</label>
                <input id="update-nomorkamar" type="text" class="border p-2 w-full mb-2 rounded">
                <label class="block font-medium">Nama Tamu:</label>
                <input id="update-nama" type="text" class="border p-2 w-full mb-2 rounded">
                <label class="block font-medium">Alamat:</label>
                <input id="update-alamat" type="text" class="border p-2 w-full mb-2 rounded">
                <label class="block font-medium">Email:</label>
                <input id="update-email" type="text" class="border p-2 w-full mb-2 rounded">
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                    <button type="button" id="modal-close" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>