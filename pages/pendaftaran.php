<?php
require __DIR__ . '/../pages/koneksi.php';

$pesan = ""; // Message variable
$modalVisible = false; // Flag for modal visibility

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];

    // Validate input
    if (empty($nama) || empty($no_telepon) || empty($email) || empty($alamat)) {
        $pesan = "Semua field harus diisi!";
        $modalVisible = true;
    } else {
        // Check if phone number or email already exists
        $stmt = $db->prepare("SELECT * FROM tamu WHERE no_telepon = ? OR email = ?");
        $stmt->bind_param("ss", $no_telepon, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            if ($result->fetch_assoc()['no_telepon'] === $no_telepon) {
                $pesan = "Nomor telepon sudah terdaftar!";
            } else {
                $pesan = "Email sudah terdaftar!";
            }
            $modalVisible = true;
        } else {
            // Insert data if not already present
            $stmt = $db->prepare("INSERT INTO tamu (no_telepon, nama, alamat, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $no_telepon, $nama, $alamat, $email);

            if ($stmt->execute()) {
                $pesan = "Pendaftaran berhasil untuk: $nama";
            } else {
                $pesan = "Pendaftaran gagal: " . $stmt->error;
            }
            $modalVisible = true;
            $stmt->close();
        }
    }
}
?>

<div class="flex flex-col items-center min-h-screen p-4">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Pendaftaran</h2>
        
        <form id="pendaftaran-form" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama">Nama</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700" id="nama" name="nama" type="text" placeholder="Nama">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nomor-telepon">Nomor Telepon</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700" id="nomor-telepon" name="no_telepon" type="number" placeholder="Nomor Telepon">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700" id="email" name="email" type="email" placeholder="Email">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="alamat">Alamat</label>
                <input class="shadow border rounded w-full py-2 px-3 text-gray-700" id="alamat" name="alamat" type="text" placeholder="Alamat">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Daftar
                </button>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <?php if ($modalVisible): ?>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h3 class="text-lg font-bold mb-4 text-center text-gray-800">Pesan</h3>
            <p class="mb-4 text-center text-gray-700"><?php echo $pesan; ?></p>
            <div class="flex justify-center">
                <button class="bg-green-500 text-white font-bold py-2 px-4 rounded" onclick="document.querySelector('.fixed').style.display='none'">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .fixed {
        display: flex;
    }
</style>