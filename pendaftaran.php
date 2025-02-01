<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6">Pendaftaran</h2>
            <form action="index.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama" name="nama" type="text" placeholder="Nama" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nomor-telepon">Nomor Telepon</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nomor-telepon" name="no_telepon" type="text" placeholder="Nomor Telepon" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" placeholder="Email" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="alamat">Alamat</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="alamat" name="alamat" type="text" placeholder="Alamat" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Daftar
                    </button>
                </div>
            </form>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require 'koneksi.php';

                // Ambil data dari form
                $nama = $_POST['nama'];
                $no_telepon = $_POST['no_telepon'];
                $email = $_POST['email'];
                $alamat = $_POST['alamat'];

                // Siapkan dan jalankan query
                $stmt = $db->prepare("INSERT INTO tamu (no_telepon, nama, alamat, email) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $no_telepon, $nama, $alamat, $email);

                if ($stmt->execute()) {
                    echo "<p class='text-green-500'>Pendaftaran berhasil!</p>";
                } else {
                    echo "<p class='text-red-500'>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
                $db->close();
            }
            ?>
        </div>
    </div>
</body>
</html>