<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        async function searchReservations() {
            const search = document.getElementById('search').value;
            const response = await fetch(`search_reservations.php?search=${search}`);
            const reservations = await response.json();
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';

            reservations.forEach(reservation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-2 px-4 border-b">${reservation.id_log}</td>
                    <td class="py-2 px-4 border-b">${reservation.id_reservasi}</td>
                    <td class="py-2 px-4 border-b">${reservation.no_telepon}</td>
                    <td class="py-2 px-4 border-b">${reservation.no_kamar}</td>
                    <td class="py-2 px-4 border-b">${reservation.tanggal_check_in}</td>
                    <td class="py-2 px-4 border-b">${reservation.tanggal_waktu}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-start min-h-screen pt-10">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6">Log Reservasi</h2>
            <div class="flex mb-4">
                <input id="search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Cari Reservasi">
                <button onclick="searchReservations()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">Search</button>
            </div>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID Log</th>
                            <th class="py-2 px-4 border-b">ID Reservasi</th>
                            <th class="py-2 px-4 border-b">Nomor Telepon</th>
                            <th class="py-2 px-4 border-b">Nomor Kamar</th>
                            <th class="py-2 px-4 border-b">Tanggal Check In</th>
                            <th class="py-2 px-4 border-b">Tanggal Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <!-- Data will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>