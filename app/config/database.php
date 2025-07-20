<?php
// app/config/database.php

// 1. Definisikan konstanta-konstanta dasar
define('PROJECT_ROOT', dirname(dirname(__DIR__)));
define('BASE_URL', 'http://localhost/Github/Pemrograman_Web/');
// 2. Definisikan konstanta koneksi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'warkop_bejo_db');

// 3. Buat koneksi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 4. Cek koneksi
if ($conn->connect_error) {
    // Tampilkan pesan error jika koneksi gagal
    // Fungsi ini akan menghentikan eksekusi skrip selanjutnya
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set karakter set untuk koneksi
$conn->set_charset("utf8mb4");
?>