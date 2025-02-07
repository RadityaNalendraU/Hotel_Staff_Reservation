<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $no_telepon = htmlspecialchars($_POST['no_telepon']);
    $email = htmlspecialchars($_POST['email']);
    $alamat = htmlspecialchars($_POST['alamat']);

    // Persiapkan query stored procedure
    $query = "CALL InsertTamu(?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($db, $query)) {
        mysqli_stmt_bind_param($stmt, "ssss", $no_telepon, $nama, $email, $alamat);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<p class='text-green-500'>Pendaftaran berhasil!</p>";
        } else {
            echo "<p class='text-red-500'>Error: " . mysqli_stmt_error($stmt) . "</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p class='text-red-500'>Query error: " . mysqli_error($db) . "</p>";
    }

    // Jika ada hasil sebelumnya, bersihkan
    while (mysqli_next_result($db)) {;}
    
    mysqli_close($db);
}
?>