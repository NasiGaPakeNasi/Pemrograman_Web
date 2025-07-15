<?php
// app/config/database.php
// Konfigurasi database pusat dan konstanta global untuk aplikasi Warkop Bejo

// --- PERBAIKAN: Menentukan BASE_URL secara dinamis ---
// Ini membuat semua link dan redirect bekerja dengan benar di lingkungan mana pun.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// Mengasumsikan proyek berada di dalam folder 'Pemrograman_Web-Final'
$script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
// Menghapus subdirektori spesifik (seperti 'app/controllers') untuk mendapatkan root proyek
$project_folder = preg_replace('~(app|public)/.*~', '', $script_name);

define('BASE_URL', $protocol . '://' . $host . $project_folder);


// Definisikan konstanta PROJECT_ROOT
define('PROJECT_ROOT', dirname(dirname(__DIR__)));

// Definisikan konstanta koneksi database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'warkop_bejo_db');

// Buat koneksi ke database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set karakter set untuk koneksi
$conn->set_charset("utf8mb4");

ini_set('display_errors', 0);
error_reporting(0);
?>

