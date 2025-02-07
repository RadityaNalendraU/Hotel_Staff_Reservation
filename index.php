<?php
// Pastikan halaman yang diminta ada
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$file = "pages/$page.php";

if (!file_exists($file)) {
    $file = "pages/home.php"; // Default jika halaman tidak ditemukan
}
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Hotel Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const laporanButton = document.getElementById('laporan-button');
            const laporanDropdown = document.getElementById('laporan-dropdown');

            laporanButton.addEventListener('click', function () {
                laporanDropdown.classList.toggle('hidden');
            });

            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function () {
                    let target = this.getAttribute('data-target');
                    if (target) {
                        // Menghapus ekstensi .php jika ada
                        target = target.replace('.php', '');
                        window.location.href = `index.php?page=${target}`;
                    }
                });
            });
        });
    </script>
    <style>
        .sidebar-item:hover {
            background-color: #2f855a;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-green-700 text-white min-h-screen">
        <div class="p-4">
            <div class="text-2xl font-bold mb-4">
                Hotel Reservasi
            </div>
            <div class="mb-4 sidebar-item" data-target="home">
                <div class="font-bold">
                    <i class="fas fa-hotel mr-2"></i> Hotel XYZ
                </div>
                <div class="text-sm">
                    <i class="fas fa-home mr-2"></i> Home
                </div>
            </div>
            <div class="mb-4 sidebar-item" data-target="pendaftaran">
                <div class="font-bold">
                    <i class="fas fa-user-plus mr-2"></i> Pendaftaran
                </div>
            </div>
            <div class="mb-4 sidebar-item">
                <div class="font-bold cursor-pointer" id="laporan-button">
                    <i class="fas fa-file-alt mr-2"></i> Laporan
                </div>
                <ul class="text-sm hidden" id="laporan-dropdown">
                    <li class="py-1 sidebar-item" data-target="log_reservasi">
                        <i class="fas fa-book mr-2"></i> Log Reservasi
                    </li>
                    <li class="py-1 sidebar-item" data-target="daftar_tamu">
                        <i class="fas fa-users mr-2"></i> Daftar Tamu
                    </li>
                </ul>
            </div>
            <div class="mb-4 sidebar-item" data-target="reservasi">
                <div class="font-bold">
                    <i class="fas fa-calendar-check mr-2"></i> Reservasi
                </div>
            </div>
            <div class="mb-4 sidebar-item" data-target="pembayaran">
                <div class="font-bold">
                    <i class="fas fa-credit-card mr-2"></i> Pembayaran
                </div>
            </div>
            <div class="mb-4 sidebar-item" data-target="kamar">
                <div class="font-bold">
                    <i class="fas fa-bed mr-2"></i> Kamar
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="flex-1 p-6" id="main-content">
        <?php include $file; ?>
    </div>
</div>
</body>
</html>
