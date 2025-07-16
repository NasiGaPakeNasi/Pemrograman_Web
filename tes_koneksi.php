<?php

// --- GANTI BAGIAN INI DENGAN KREDENSIAL ANDA ---
$db_server = "sql123.epizy.com"; // Ganti dengan Host Name Anda
$db_username = "epiz_12345678"; // Ganti dengan User Name Anda
$db_password = "T7hTs0tbGK76v"; // Ganti dengan Password Anda
$db_name = "epiz_12345678_warkop_bejo_db"; // Ganti dengan Database Name Anda
// ---------------------------------------------

echo "Mencoba menghubungkan ke database... <br>";

// Membuat koneksi
$conn = new mysqli($db_server, $db_username, $db_password, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    // Jika gagal, tampilkan pesan error yang spesifik
    die("<h1>KONEKSI GAGAL!</h1> <p>Error: " . $conn->connect_error . "</p> <p><b>Mohon periksa kembali semua kredensial database Anda.</b></p>");
}

// Jika berhasil
echo "<h1>SELAMAT! KONEKSI DATABASE BERHASIL.</h1>";
echo "<p>Ini berarti kredensial database Anda sudah benar.</p>";
echo "<p>Masalah 'Error 500' kemungkinan besar disebabkan oleh path 'require_once' yang salah di dalam file proyek Anda.</p>";

$conn->close();

?>