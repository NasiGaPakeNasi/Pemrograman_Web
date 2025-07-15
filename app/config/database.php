<?php
// app/config/database.php
// Konfigurasi database pusat untuk aplikasi Warkop Bejo

// Definisikan konstanta PROJECT_ROOT
// Ini akan menjadi jalur absolut ke direktori utama proyek (Pemrograman_Web/)
// dirname(__DIR__) dari app/config/ akan membawa kita ke app/, lalu dirname lagi ke root proyek
define('PROJECT_ROOT', dirname(dirname(__DIR__)));

// Definisikan konstanta koneksi database
define('DB_SERVER', 'localhost'); // Server database Anda
define('DB_USERNAME', 'root');   // Username database Anda
define('DB_PASSWORD', '');       // Password database Anda (kosong jika tidak ada)
define('DB_NAME', 'warkop_bejo_db'); // Pastikan ini adalah nama database yang Anda gunakan

// Buat koneksi ke database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    // Jika koneksi gagal, hentikan eksekusi skrip dan tampilkan pesan error
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set karakter set untuk koneksi (penting untuk mencegah masalah encoding)
$conn->set_charset("utf8mb4");

// Catatan: Variabel $conn dan konstanta PROJECT_ROOT akan tersedia
// di file mana pun yang menyertakan database.php
?>
