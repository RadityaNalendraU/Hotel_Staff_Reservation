<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addButton = document.getElementById('add-button');
            const paymentMethods = document.getElementsByName('payment-method');
            const paymentInput = document.getElementById('payment-input');
            const payButton = document.getElementById('pay-button');
            const totalInput = document.getElementById('total-input');
            const inputContainer = document.getElementById('input-container');
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const modalConfirmButton = document.getElementById('modal-confirm-button');
            const modalCloseButton = document.getElementById('modal-close-button');

            addButton.addEventListener('click', function () {
                const searchInput = document.querySelector('#input-container input').value.trim();
                if (!searchInput) {
                    showModal('Peringatan', 'Silakan masukkan ID Reservasi.', false);
                    return;
                }

                // Assume we fetch data based on search input here
                // For demonstration, we'll just simulate
                const tableBody = document.querySelector('tbody');
                tableBody.innerHTML = ''; // Clear previous results

                // Simulate a search result
                // Replace this with actual database query result
                const found = Math.random() > 0.5; // Randomly simulate found or not

                if (found) {
                    // Simulate adding a row
                    tableBody.innerHTML = `
                        <tr>
                            <td class='py-2 px-4 border-b'>1</td>
                            <td class='py-2 px-4 border-b'>RES123</td>
                            <td class='py-2 px-4 border-b'>08123456789</td>
                            <td class='py-2 px-4 border-b'>2025-02-01</td>
                            <td class='py-2 px-4 border-b'>Rp 1.400.000</td>
                            <td class='py-2 px-4 border-b'>
                                <button class='pay-button bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'>
                                    Bayar
                                </button>
                            </td>
                        </tr>
                    `;
                } else {
                    showModal('Peringatan', 'Data tidak ditemukan.', false);
                }
            });

            // Event listener for pay buttons to update total input
            document.querySelectorAll('.pay-button').forEach(button => {
                button.addEventListener('click', function () {
                    const totalPaymentText = this.closest('tr').querySelector('td:nth-child(5)').textContent; // Get total payment text
                    const totalPayment = totalPaymentText.replace('Rp ', '').replace(/\./g, '').trim(); // Remove 'Rp ' and format
                    totalInput.value = `Rp ${parseInt(totalPayment).toLocaleString()}`; // Update total input with formatted value
                    paymentInput.value = totalPayment; // Set the payment input to the numeric value
                });
            });

            payButton.addEventListener('click', function () {
                let selectedMethod = '';
                paymentMethods.forEach(method => {
                    if (method.checked) {
                        selectedMethod = method.value;
                    }
                });

                if (!selectedMethod) {
                    showModal('Peringatan', 'Silakan pilih metode pembayaran.', false);
                } else if (!paymentInput.value) {
                    showModal('Peringatan', 'Silakan masukkan jumlah pembayaran.', false);
                } else {
                    showModal('Konfirmasi', `Anda akan membayar sebesar Rp ${parseInt(paymentInput.value).toLocaleString()} menggunakan metode ${selectedMethod}. Apakah Anda yakin?`, true);
                }
            });

            modalConfirmButton.addEventListener('click', function () {
                showModal('Pemberitahuan', 'Pembayaran berhasil dilakukan.', false);
            });

            modalCloseButton.addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            function showModal(title, message, showConfirmButton) {
                modalTitle.textContent = title;
                modalMessage.textContent = message;
                modalConfirmButton.style.display = showConfirmButton ? 'inline-block' : 'none';
                modal.classList.remove('hidden');
            }
        });
    </script>
    <style>
        .table-scroll {
            max-height: 300px; /* Set a max height for the table */
            overflow-y: auto; /* Enable vertical scrolling */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>
            <div class="flex mb-4">
                <div class="w-full" id="input-container">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="ID Reservasi">
                </div>
                <button id="add-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                    Cari
                </button>
            </div>
            <div class="overflow-x-auto mb-4 table-scroll">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID Pembayaran</th>
                            <th class="py-2 px-4 border-b">ID Reservasi</th>
                            <th class="py-2 px-4 border-b">Nomor Telepon</th>
                            <th class="py-2 px-4 border-b">Tanggal Pembayaran</th>
                            <th class="py-2 px-4 border-b">Total Pembayaran</th>
                            <th class="py-2 px-4 border-b"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require 'koneksi.php'; // Include database connection

                        // Prepare SQL query to fetch data from pembayaran table
                        $query = "SELECT id_pembayaran, id_reservasi, no_telepon, tanggal_pembayaran, total_pembayaran FROM pembayaran";
                        $result = $db->query($query);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td class='py-2 px-4 border-b'>{$row['id_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['id_reservasi']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['no_telepon']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['tanggal_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>Rp {$row['total_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>
                                            <button class='pay-button bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'>
                                                Bayar
                                            </button>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-2 px-4 border-b text-center'>Tidak ada data ditemukan.</td></tr>";
                        }

                        // Close connection
                        $result->close();
                        $db->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <label class="mr-2">Pembayaran:</label>
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" name="payment-method" value="Debit" class="form-radio text-green-500">
                        <span class="ml-2">Debit</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment-method" value="Tunai" class="form-radio text-green-500">
                        <span class="ml-2">Tunai</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <span class="mr-2">Total:</span>
                    <input id="total-input" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="Rp 0" readonly>
                </div>
            </div>
            <div class="flex justify-end items-center mb-4">
                <input id="payment-input" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-4" type="text" placeholder="Jumlah Pembayaran">
                <button id="pay-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Bayar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
            <h2 id="modal-title" class="text-xl font-bold mb-4">Modal Title</h2>
            <p id="modal-message" class="mb-4">Modal message goes here.</p>
            <div class="flex justify-end">
                <button id="modal-close-button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                    Tutup
                </button>
                <button id="modal-confirm-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</body>
</html>