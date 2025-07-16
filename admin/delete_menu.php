<?php
// admin/delete_menu.php
// Skrip untuk menghapus item menu oleh Administrator

session_start(); // Pastikan sesi dimulai

// Sertakan file auth untuk memeriksa status admin
require_once __DIR__ . '/../app/includes/auth.php';
// Sertakan file koneksi database pusat
require_once __DIR__ . '/../app/config/database.php';
// Sertakan model Menu untuk interaksi data menu
require_once __DIR__ . '/../app/models/Menu.php';

// Lindungi halaman: hanya admin yang bisa mengakses
redirectToLoginIfNotAdmin();

// Pastikan ID menu ada di URL dan merupakan angka
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_menu.php?status=error&message=" . urlencode("ID menu tidak valid atau tidak ditemukan."));
    exit();
}

$id_menu = intval($_GET['id']); // Ambil ID menu dari URL

// Opsional: Sebelum menghapus entri database, hapus juga file gambar terkait
$menuItem = getMenuById($conn, $id_menu); // Ambil detail menu untuk mendapatkan path gambar
if ($menuItem && !empty($menuItem['gambar'])) {
    $imagePath = "../public/" . $menuItem['gambar'];
    // Pastikan file ada dan bukan gambar default sebelum dihapus
    if (file_exists($imagePath) && $menuItem['gambar'] != 'images/default_menu.jpg') {
        unlink($imagePath); // Hapus file gambar dari server
    }
}

// Panggil fungsi deleteMenu dari model
if (deleteMenu($conn, $id_menu)) {
    $message = "Menu berhasil dihapus!";
    $status = "success";
} else {
    $message = "Gagal menghapus menu. Terjadi kesalahan database.";
    $status = "error";
}

// Tutup koneksi database
$conn->close();

// Redirect kembali ke halaman manage_menu.php dengan pesan status
header("Location: manage_menu.php?status=" . $status . "&message=" . urlencode($message));
exit();
?>
