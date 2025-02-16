<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let selectedPayments = []; // Store selected payment details for processing later

        document.addEventListener('DOMContentLoaded', function () {
            const addButton = document.getElementById('add-button');
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');
            const payButton = document.getElementById('pay-button');
            const totalPaymentDisplay = document.getElementById('total-payment-display');

            addButton.addEventListener('click', function () {
                const searchInput = document.querySelector('#input-container input').value.trim();
                if (!searchInput) {
                    showModal('Peringatan', 'Silakan masukkan ID Pembayaran, ID Reservasi, atau Nomor Telepon.', false);
                    return;
                }

                const tableBody = document.querySelector('tbody');
                tableBody.innerHTML = ''; // Clear previous results

                fetch(`fetch_pembayaran.php?search=${encodeURIComponent(searchInput)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            data.forEach(item => {
                                tableBody.innerHTML += `
                                    <tr>
                                        <td class='py-2 px-4 border-b'>${item.id_pembayaran}</td>
                                        <td class='py-2 px-4 border-b'>${item.id_reservasi}</td>
                                        <td class='py-2 px-4 border-b'>${item.no_telepon}</td>
                                        <td class='py-2 px-4 border-b'>${item.tanggal_pembayaran}</td>
                                        <td class='py-2 px-4 border-b'>Rp ${item.total_pembayaran}</td>
                                        <td class='py-2 px-4 border-b'>
                                            <input type="checkbox" name="selected-payments" value="${item.id_pembayaran}" class="payment-checkbox" data-reservasi="${item.id_reservasi}" data-no-telepon="${item.no_telepon}" data-tanggal="${item.tanggal_pembayaran}" data-total="${item.total_pembayaran}">
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            showModal('Peringatan', 'Data tidak ditemukan.', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        showModal('Error', 'Terjadi kesalahan saat mengambil data.', false);
                    });
            });

            // Update total payment display when a payment option is selected
            document.addEventListener('change', function () {
                const selectedPaymentCheckboxes = document.querySelectorAll('input[name="selected-payments"]:checked');
                let total = 0;

                selectedPayments = Array.from(selectedPaymentCheckboxes).map(checkbox => {
                    total += parseFloat(checkbox.dataset.total.replace(/[^0-9.-]+/g,"")); // Extract numeric value
                    return {
                        id_pembayaran: checkbox.value,
                        id_reservasi: checkbox.dataset.reservasi, // Ensure this is included
                        no_telepon: checkbox.dataset.no_telepon,
                        tanggal: checkbox.dataset.tanggal,
                        total: checkbox.dataset.total
                    };
                });

                totalPaymentDisplay.textContent = `Rp ${total}`;
            });

            // Pay button click event
            payButton.addEventListener('click', function () {
                const paymentMethod = document.querySelector('input[name="payment-method"]:checked');
                const paymentAmount = parseFloat(document.getElementById('payment-input').value.trim());

                if (!paymentMethod) {
                    showModal('Peringatan', 'Silakan pilih metode pembayaran (Debit atau Tunai).', false);
                    return;
                }

                if (selectedPayments.length === 0) {
                    showModal('Peringatan', 'Silakan pilih pembayaran yang ingin diproses.', false);
                    return;
                }

                const totalPembayaran = selectedPayments.reduce((sum, payment) => sum + parseFloat(payment.total.replace(/[^0-9.-]+/g,"")), 0);

                if (paymentAmount < totalPembayaran) {
                    showModal('Peringatan', `Jumlah bayar Rp ${paymentAmount} kurang dari total pembayaran Rp ${totalPembayaran}.`, false);
                    return;
                }

                // Show confirmation modal
                modalTitle.textContent = 'Konfirmasi Pembayaran';
                document.getElementById('modal-amount').textContent = paymentAmount; // Set payment amount
                document.getElementById('modal-ids').textContent = selectedPayments.map(p => p.id_pembayaran).join(', '); // Set IDs
                modal.classList.remove('hidden');
            });

            // Confirm button in modal
            document.getElementById('modal-confirm-button').addEventListener('click', function () {
                if (selectedPayments.length > 0) {
                    // Send data to server
                    fetch('pages/save_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            payments: JSON.stringify(selectedPayments),
                            jumlah_bayar: document.getElementById('payment-input').value.trim(),
                            metode_pembayaran: document.querySelector('input[name="payment-method"]:checked').value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Remove the selected rows from the table
                            selectedPayments.forEach(payment => {
                                const selectedRow = document.querySelector(`input[value="${payment.id_pembayaran}"]`).closest('tr');
                                selectedRow.remove();
                            });

                            showModal('Sukses', 'Pembayaran berhasil diproses.', true);
                        } else {
                            showModal('Error', data.message || 'Terjadi kesalahan saat menyimpan data.', false);
                        }
                    })
                    .catch(error => {
                        console.error('Error saving payment:', error);
                        showModal('Error', 'Terjadi kesalahan saat menyimpan data.', false);
                    });
                }
                modal.classList.add('hidden');
            });

            // Close modal functionality
            document.getElementById('modal-close-button').addEventListener('click', function () {
                modal.classList.add('hidden');
            });

            function showModal(title, message, showConfirmButton) {
                modalTitle.textContent = title;
                modalMessage.textContent = message;
                document.getElementById('modal-confirm-button').style.display = showConfirmButton ? 'inline-block' : 'none';
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
    <div class="flex justify-center items-center min-h-screen pt-100"> <!-- Adjusted padding-top to move it up -->
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>
            <div class="flex mb-4">
                <div class="w-full" id="input-container">
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="ID Pembayaran, ID Reservasi, atau Nomor Telepon" hidden>
                </div>
                <button id="add-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4" hidden>
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
                            <th class="py-2 px-4 border-b">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require __DIR__ . '/../pages/koneksi.php';

                        $search = '';
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $search = $_POST['search'];
                        }

                        $query = "SELECT id_pembayaran, id_reservasi, no_telepon, tanggal_pembayaran, total_pembayaran FROM pembayaran";
                        if (!empty($search)) {
                            $query .= " WHERE id_pembayaran LIKE ? OR id_reservasi LIKE ? OR no_telepon LIKE ?";
                            $searchParam = "%" . $search . "%";
                        }

                        $stmt = $db->prepare($query);
                        if (!empty($search)) {
                            $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
                        }

                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td class='py-2 px-4 border-b'>{$row['id_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['id_reservasi']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['no_telepon']}</td>
                                        <td class='py-2 px-4 border-b'>{$row['tanggal_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>Rp {$row['total_pembayaran']}</td>
                                        <td class='py-2 px-4 border-b'>
                                            <input type='checkbox' name='selected-payments' value='{$row['id_pembayaran']}' class='payment-checkbox' data-reservasi='{$row['id_reservasi']}' data-no-telepon='{$row['no_telepon']}' data-tanggal='{$row['tanggal_pembayaran']}' data-total='{$row['total_pembayaran']}'>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='py-2 px-4 border-b text-center'>Tidak ada data ditemukan.</td></tr>";
                        }

                        $stmt->close();
                        $db->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="flex items-center mb-4">
                <label class="mr-2">Total yang harus dibayar:</label>
                <span id="total-payment-display" class="font-bold">Rp 0</span>
            </div>
            <div class="flex items-center mb-4">
                <label class="mr-2">Pembayaran:</label>
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="payment-method" value="Debit" class="form-radio text-green-500">
                    <span class="ml-2">Debit</span>
                </label>
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="payment-method" value="Tunai" class="form-radio text-green-500">
                    <span class="ml-2">Tunai</span>
                </label>
            </div>
            <div class="flex justify-start items-center mb-4">
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
            <h2 id="modal-title" class="text-xl font-bold mb-4">Konfirmasi Pembayaran</h2>
            <p id="modal-message" class="mb-4">Anda yakin ingin memproses pembayaran sebesar Rp <span id="modal-amount"></span> untuk ID Pembayaran: <span id="modal-ids"></span>?</p>
            <div class="flex justify-end">
                <button id="modal-close-button" class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                    Tutup
                </button>
                <button id="modal-confirm-button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Ya
                </button>
            </div>
        </div>
    </div>
</body>
</html>