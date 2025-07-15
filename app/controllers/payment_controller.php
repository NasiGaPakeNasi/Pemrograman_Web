<?php
// app/controllers/payment_controller.php
// Controller untuk memproses pembayaran final dan menyimpan transaksi.

session_start();

// Memuat file-file yang diperlukan
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- PERBAIKAN UTAMA: Validasi yang lebih ketat ---
// 1. Pastikan metode adalah POST.
// 2. Pastikan ada data keranjang di sesi.
// 3. Pastikan pengguna sudah login dan user_id tersimpan di sesi.
if ($_SERVER["REQUEST_METHOD"] !== "POST" 
    || !isset($_SESSION['checkout_cart']) 
    || !isset($_SESSION['user_id'])) {
    
    // Jika salah satu kondisi tidak terpenuhi, redirect ke halaman yang sesuai.
    // Ini mencegah error fatal dan membuat alur lebih aman.
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "public/login.php?status=login_required");
    } else {
        header("Location: " . BASE_URL . "public/keranjang.php?status=cart_expired");
    }
    exit();
}
    
// Ambil semua data yang diperlukan dari sesi dan form POST
$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['checkout_cart'];
$total_belanja = (float)$_SESSION['checkout_total'];
$uang_tunai = (float)$_POST['uang_tunai'];
$kembalian = $uang_tunai - $total_belanja;

// Validasi dasar
if ($uang_tunai < $total_belanja) {
    header("Location: " . BASE_URL . "public/pembayaran.php?status=failed&message=" . urlencode("Uang tunai tidak cukup!"));
    exit();
}

// Mulai transaksi database untuk memastikan semua query berhasil atau tidak sama sekali
$conn->begin_transaction();

try {
    // 1. Simpan ke tabel 'purchases' untuk riwayat pembelian pengguna
    // Menggunakan NOW() untuk timestamp otomatis dari MySQL
    $stmt_purchases = $conn->prepare("INSERT INTO purchases (user_id, tanggal, items, total) VALUES (?, NOW(), ?, ?)");
    
    // Konversi array item menjadi string JSON untuk disimpan di database
    $items_json = json_encode($cart_items);
    
    // Bind parameter ke query
    $stmt_purchases->bind_param("isd", $user_id, $items_json, $total_belanja);
    
    // Jalankan query
    $stmt_purchases->execute();
    
    // Dapatkan ID dari pembelian yang baru saja dibuat untuk digunakan di struk
    $id_pembelian_baru = $conn->insert_id;
    $stmt_purchases->close();
    
    // Jika semua query berhasil, commit (simpan permanen) perubahan ke database
    $conn->commit();

    // Hapus sesi keranjang setelah transaksi berhasil agar tidak bisa diproses ulang
    unset($_SESSION['checkout_cart']);
    unset($_SESSION['checkout_total']);

    // Redirect ke halaman struk dengan ID pembelian yang baru
    header("Location: " . BASE_URL . "public/struk.php?id_pembelian=" . $id_pembelian_baru);
    exit();

} catch (mysqli_sql_exception $e) {
    // Jika terjadi error di salah satu query, batalkan semua perubahan
    $conn->rollback(); 
    
    // Catat error untuk debugging (bisa dilihat di log error server)
    error_log("Payment process failed: " . $e->getMessage());
    
    // Redirect kembali ke halaman pembayaran dengan pesan error
    header("Location: " . BASE_URL . "public/pembayaran.php?status=failed&message=" . urlencode("Pembayaran gagal karena kesalahan sistem."));
    exit();
}

// Tutup koneksi database di akhir skrip
$conn->close();
?>
