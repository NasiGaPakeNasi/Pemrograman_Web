<?php
// app/controllers/api/proses_checkout.php
// Skrip ini memproses data checkout dari keranjang dan menyimpannya ke tabel 'pesanan'

// Sertakan file koneksi database pusat
require_once __DIR__ . '/../../config/database.php';
// Sertakan model Cart untuk fungsi menambahkan pesanan
require_once __DIR__ . '/../../models/Cart.php';

// Pastikan request adalah POST dan ada data menu yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['menu'])) {
    $menu_names = $_POST['menu'];
    $prices = $_POST['harga'];
    $quantities = (array) $_POST['jumlah']; // Ensure quantities is an array

    $checkoutSuccess = true;
    foreach ($menu_names as $index => $name) {
        $price = (int)$prices[$index];
        $quantity = (int)$quantities[$index];
        $subtotal = $price * $quantity;

        // Panggil fungsi dari model Cart untuk menambahkan item pesanan
        if (!addOrderItem($conn, $name, $price, $quantity, $subtotal)) {
            $checkoutSuccess = false;
            // Anda bisa log error di sini atau menyimpan pesan error untuk ditampilkan
            break;
        }
    }

    // Tutup koneksi database setelah semua operasi selesai
    $conn->close();

    // PERBAIKAN DI SINI: PATH RELATIF YANG BENAR
    // Untuk ke public/pembayaran.php dari app/controllers/api/, perlu naik TIGA level (../../../)
    if ($checkoutSuccess) {
        // Redirect ke halaman pembayaran. Anda bisa meneruskan ID pesanan atau total belanja jika diperlukan
        // Sekarang $_POST['total_belanja'] seharusnya sudah tersedia dari form
        header("Location: ../../../public/pembayaran.php?status=success&total_belanja=" . urlencode($_POST['total_belanja'] ?? 0));
        exit;
    } else {
        // Jika gagal, arahkan kembali ke keranjang dengan pesan error
        header("Location: ../../../public/keranjang.php?checkout_status=failed");
        exit;
    }
} else {
    // Jika akses langsung atau tidak ada data yang valid, redirect ke halaman keranjang
    header("Location: ../../../public/keranjang.php");
    exit;
}
?>
