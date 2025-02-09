<?php require 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Reservasi Hotel</h2>

    <form id="reservation-form">        <!-- Nomor Telepon + Tombol Search -->
        <label class="block mb-2 font-semibold">Nomor Telepon</label>
        <div class="flex">
            <input id="phone-input" name="no_telepon" type="tel" class="w-full p-2 border rounded-md" placeholder="Masukkan nomor telepon">
            <button onclick="searchPhone()" type="button" class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Search
            </button>
        </div>

        <!-- Tipe Kamar -->
        <label class="block mt-4 mb-2 font-semibold">Tipe Kamar</label>
        <select id="room-type" name="tipe_kamar" class="w-full p-2 border rounded-md" onchange="fetchAvailableRooms()">
            <option value="">Pilih Tipe Kamar</option>
        </select>

        <!-- Nomor Kamar -->
        <label class="block mt-4 mb-2 font-semibold">Nomor Kamar</label>
        <select id="room-number" name="no_kamar" class="w-full p-2 border rounded-md">
            <option value="">Pilih Nomor Kamar</option>
        </select>

        <!-- Check-in -->
        <label class="block mt-4 mb-2 font-semibold">Check-in</label>
        <input id="checkin" name="tanggal_check_in" type="date" class="w-full p-2 border rounded-md" onchange="calculateTotalCost()">

        <!-- Check-out -->
        <label class="block mt-4 mb-2 font-semibold">Check-out</label>
        <input id="checkout" name="tanggal_check_out" type="date" class="w-full p-2 border rounded-md" onchange="calculateTotalCost()">

        <!-- Total Biaya -->
        <label class="block mt-4 mb-2 font-semibold">Total Biaya</label>
        <input id="total-cost" name="total_cost" class="w-full p-2 border rounded-md bg-gray-100" placeholder="Rp 0" readonly>

        <!-- Tombol Simpan & Hapus -->
        <div class="flex justify-between mt-4">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Simpan
            </button>
            <button onclick="clearData()" type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Hapus Data
            </button>
        </div>
    </form>
</div>

<!-- Popup -->
<div id="popup" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
        <p id="popup-message" class="text-lg font-semibold"></p>
        <button onclick="closePopup()" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            OK
        </button>
    </div>
</div>

<!-- JavaScript -->
<script>
    document.getElementById("reservation-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah reload halaman

        const formData = new FormData(this); // Mengambil data dari form

        fetch('submit_reservasi.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup("✅ Reservasi berhasil!");
            } else {
                showPopup("❌ " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showPopup("❌ Terjadi kesalahan saat mengirim data.");
        });
    });

    // Fetch room types on page load
    window.onload = function() {
        fetch('fetch_rooms.php?get_types=true')
            .then(response => response.json())
            .then(data => {
                let roomTypeSelect = document.getElementById("room-type");
                roomTypeSelect.innerHTML = '<option value="">Pilih Tipe Kamar</option>'; // Clear previous options
                data.forEach(type => {
                    let option = document.createElement("option");
                    option.value = type;
                    option.textContent = type;
                    roomTypeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching room types:', error));
    };

    function searchPhone() {
        let phone = document.getElementById("phone-input").value;
        // AJAX call to check_phone.php
        fetch('check_phone.php?phone=' + phone)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    showPopup("✅ Nomor telepon tersedia!");
                } else {
                    showPopup("❌ Nomor telepon belum tersedia.");
                    document.getElementById("popup").onclick = function() {
                        window.location.href = 'index.php?page=pendaftaran';
                    };
                }
            });
    }

    function fetchAvailableRooms() {
        let roomType = document.getElementById("room-type").value;
        if (roomType) {
            fetch('fetch_rooms.php?type=' + roomType)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    let roomNumberSelect = document.getElementById("room-number");
                    roomNumberSelect.innerHTML = '<option value="">Pilih Nomor Kamar</option>'; // Clear previous options
                    data.forEach(room => {
                        let option = document.createElement("option");
                        option.value = room.no_kamar;
                        option.textContent = room.no_kamar;
                        roomNumberSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching rooms:', error);
                });
        } else {
            document.getElementById("room-number").innerHTML = '<option value="">Pilih Nomor Kamar</option>'; // Reset if no room type selected
        }
    }

    function calculateTotalCost() {
        const checkinDate = new Date(document.getElementById("checkin").value);
        const checkoutDate = new Date(document.getElementById("checkout").value);
        const roomType = document.getElementById("room-type").value;

        if (checkinDate && checkoutDate && roomType) {
            const timeDifference = checkoutDate - checkinDate;
            const numberOfNights = Math.max(1, timeDifference / (1000 * 3600 * 24)); // Ensure at least 1 night

            fetch('fetch_rooms.php?type=' + roomType)
                .then(response => response.json())
                .then(data => {
                    const pricePerNight = data[0].harga_per_malam; // Assuming the first result contains the price
                    const totalCost = numberOfNights * pricePerNight;
                    document.getElementById("total-cost").value = totalCost; // Set the total cost value
                })
                .catch(error => console.error('Error fetching room price:', error));
        } else {
            document.getElementById("total-cost").value = "0"; // Reset if no valid dates or room type
        }
    }

    function clearData() {
        if (confirm("⚠️ Apakah Anda yakin ingin menghapus data?")) {
            document.getElementById("phone-input").value = "";
            document.getElementById("room-type").value = "";
            document.getElementById("room-number").value = "";
            document.getElementById("checkin").value = "";
            document.getElementById("checkout").value = "";
            document.getElementById("total-cost").value = "0";

            showPopup("🗑️ Data telah dihapus.");
        }
    }

    function showPopup(message) {
        let popup = document.getElementById("popup");
        document.getElementById("popup-message").innerText = message;
        popup.classList.remove("hidden");
    }

    function closePopup() {
        document.getElementById("popup").classList.add("hidden");
    }
</script>

</body>
</html>
<?php
require __DIR__ . '/../pages/koneksi.php';

if (isset($_GET['type'])) {
    $roomType = $_GET['type'];

    // Prepare SQL query to fetch available rooms and their prices
    $query = "SELECT no_kamar, harga_per_malam FROM Kamar WHERE tipe_kamar = ? AND status_kamar = 'Tersedia'";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $result = $stmt->get_result();

    $availableRooms = [];
    while ($row = $result->fetch_assoc()) {
        $availableRooms[] = $row;
    }

    // Return available rooms as JSON
    echo json_encode($availableRooms);
}

// New endpoint to fetch room types
if (isset($_GET['get_types'])) {
    $query = "SELECT DISTINCT tipe_kamar FROM Kamar";
    $result = $db->query($query);
    
    if (!$result) {
        die("Query failed: " . $db->error); // Error handling
    }

    $roomTypes = [];
    while ($row = $result->fetch_assoc()) {
        $roomTypes[] = $row['tipe_kamar'];
    }
    echo json_encode($roomTypes);
    exit;
}
?>
