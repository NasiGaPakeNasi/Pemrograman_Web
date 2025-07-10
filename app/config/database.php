<?php
// config/database.php
// Konfigurasi database
define('DB_SERVER', 'localhost'); // Server database Anda
define('DB_USERNAME', 'root');   // Username database Anda
define('DB_PASSWORD', '');       // Password database Anda (kosong jika tidak ada)
define('DB_NAME', 'db_dashboardphp'); // Ganti dengan nama database Anda

// Buat koneksi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

?>