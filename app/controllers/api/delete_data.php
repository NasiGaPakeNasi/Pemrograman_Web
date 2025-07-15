<?php
// app/controllers/api/delete_data.php
// Skrip ini menghapus item dari tabel 'keranjang'

// Sertakan file koneksi database pusat
// Path disesuaikan karena file ini pindah ke app/controllers/api/
require_once __DIR__ . '/../../config/database.php';
// Sertakan model Cart untuk fungsi delete
// Path disesuaikan karena file ini pindah ke app/controllers/api/
require_once __DIR__ . '/../../models/Cart.php';

// Pastikan ID item ada di URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah integer

    // Panggil fungsi dari model Cart untuk menghapus item
    // Fungsi deleteCartItem sudah menggunakan Prepared Statements
    if (deleteCartItem($conn, $id)) {
        $message = "Item berhasil dihapus dari keranjang.";
        $status = "success";
    } else {
        $message = "Gagal menghapus item dari keranjang: " . $conn->error; // Tambahkan pesan error dari koneksi
        $status = "error";
    }
    // Tutup koneksi
    $conn->close();
    // Redirect kembali ke halaman read_data dengan notifikasi
    header("Location: read_data.php?status=" . $status . "&message=" . urlencode($message));
    exit;
} else {
    // Jika ID tidak ditemukan, redirect dengan pesan error
    header("Location: read_data.php?status=error&message=" . urlencode("ID item tidak ditemukan."));
    exit;
}
?>
