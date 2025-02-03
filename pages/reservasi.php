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

    <!-- Nomor Telepon + Tombol Search -->
    <label class="block mb-2 font-semibold">Nomor Telepon</label>
    <div class="flex">
        <input id="phone-input" type="tel" class="w-full p-2 border rounded-md" placeholder="Masukkan nomor telepon">
        <button onclick="searchPhone()" class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Search
        </button>
    </div>

    <!-- Tipe Kamar -->
    <label class="block mt-4 mb-2 font-semibold">Tipe Kamar</label>
    <select id="room-type" class="w-full p-2 border rounded-md" onchange="fetchAvailableRooms()">
        <option value="">Pilih Tipe Kamar</option>
        <option>Standar</option>
        <option>Deluxe</option>
        <option>Suite</option>
    </select>

    <!-- Nomor Kamar -->
    <label class="block mt-4 mb-2 font-semibold">Nomor Kamar</label>
    <select id="room-number" class="w-full p-2 border rounded-md">
        <option value="">Pilih Nomor Kamar</option>
    </select>

    <!-- Check-in -->
    <label class="block mt-4 mb-2 font-semibold">Check-in</label>
    <input id="checkin" type="date" class="w-full p-2 border rounded-md">

    <!-- Check-out -->
    <label class="block mt-4 mb-2 font-semibold">Check-out</label>
    <input id="checkout" type="date" class="w-full p-2 border rounded-md">

    <!-- Total Biaya -->
    <label class="block mt-4 mb-2 font-semibold">Total Biaya</label>
    <input id="total-cost" type="text" class="w-full p-2 border rounded-md bg-gray-100" placeholder="Rp 0" readonly>

    <!-- Tombol Simpan & Hapus -->
    <div class="flex justify-between mt-4">
        <button onclick="saveData()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Simpan
        </button>
        <button onclick="clearData()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Hapus Data
        </button>
    </div>
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
    function searchPhone() {
        let phone = document.getElementById("phone-input").value;

        fetch('check_phone.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'no_telepon=' + encodeURIComponent(phone)
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                showPopup("✅ Nomor telepon tersedia!");
            } else {
                showPopup("❌ Nomor telepon belum tersedia.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function fetchAvailableRooms() {
        let roomType = document.getElementById("room-type").value;
        if (roomType) {
            fetch('fetch_rooms.php?type=' + roomType)
                .then(response => response.json())
                .then(data => {
                    let roomNumberSelect = document.getElementById("room-number");
                    roomNumberSelect.innerHTML = '<option value="">Pilih Nomor Kamar</option>'; // Clear previous options
                    data.forEach(room => {
                        let option = document.createElement("option");
                        option.value = room.no_kamar;
                        option.textContent = room.no_kamar;
                        roomNumberSelect.appendChild(option);
                    });
                });
        } else {
            document.getElementById("room-number").innerHTML = '<option value="">Pilih Nomor Kamar</option>'; // Reset if no room type selected
        }
    }

    function saveData() {
        showPopup("✅ Data telah tersimpan!");
    }

    function clearData() {
        if (confirm("⚠️ Apakah Anda yakin ingin menghapus data?")) {
            document.getElementById("phone-input").value = "";
            document.getElementById("room-type").value = "";
            document.getElementById("room-number").value = "";
            document.getElementById("checkin").value = "";
            document.getElementById("checkout").value = "";
            document.getElementById("total-cost").value = "Rp 0";

            showPopup("🗑️ Data telah dihapus.");
        }
    }

    function showPopup(message) {
        let popup = document.getElementById("popup");
        document.getElementById("popup-message").innerText = message;
        popup.classList.remove("hidden"); // Pastikan popup tidak tersembunyi
    }

    function closePopup() {
        let message = document.getElementById("popup-message").innerText;
        if (message.includes("❌")) {
            window.location.href = "pendaftaran.php"; // Redirect to pendaftaran.php
        }
        document.getElementById("popup").classList.add("hidden");
    }
</script>

</body>
</html>
