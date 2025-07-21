<?php
session_start(); // Pastikan sesi dimulai karena kita membutuhkan $_SESSION['user_id']

// Memuat file konfigurasi dan model yang diperlukan
require_once '../app/config/database.php'; // Untuk koneksi database
require_once '../app/includes/auth.php';    // Untuk fungsi autentikasi dan redirect (redirectToLoginIfNotAdmin)
require_once '../app/models/User.php';     // Untuk fungsi pengelolaan pengguna (deleteUser)

// Pastikan hanya admin yang bisa mengakses halaman ini
redirectToLoginIfNotAdmin();

$message = '';
$status = '';

// Periksa apakah ID pengguna yang akan dihapus telah disediakan melalui parameter GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id']; // Pastikan ID adalah integer
    $currentUserId = $_SESSION['user_id']; // Dapatkan ID pengguna yang sedang login dari sesi

    // Mencegah admin menghapus akunnya sendiri
    if ($userId == $currentUserId) {
        $message = "Anda tidak bisa menghapus akun Anda sendiri!";
        $status = "error";
    } else {
        // Panggil fungsi deleteUser langsung dari model User.php
        // Karena 'deleteUser' adalah fungsi dan bukan method dari sebuah class
        if (deleteUser($conn, $userId)) { // Panggil fungsi deleteUser dengan $conn dan $userId yang diperoleh dari GET
            $message = "Pengguna berhasil dihapus!";
            $status = "success";
        } else {
            $message = "Gagal menghapus pengguna. Terjadi kesalahan database.";
            $status = "error";
        }
    }
} else {
    // Jika ID pengguna tidak disediakan atau tidak valid
    $message = "ID pengguna tidak valid atau tidak ditemukan.";
    $status = "error";
}

// Tutup koneksi database
$conn->close();

// Redirect kembali ke halaman manajemen pengguna dengan pesan status
header('Location: manage_users.php?status=' . $status . '&message=' . urlencode($message));
exit();
?>