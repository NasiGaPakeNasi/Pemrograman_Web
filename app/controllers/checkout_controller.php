<?php
// app/controllers/checkout_controller.php
// Controller ini menangani data dari keranjang dan menyiapkannya untuk pembayaran.

session_start();

require_once __DIR__ . '/../config/database.php';

// Pastikan request adalah POST dan ada data menu yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['menu'])) {
    
    // Validasi apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "public/login.php?redirect=keranjang");
        exit();
    }

    $cart_items = [];
    $total_belanja = 0;

    $menu_names = $_POST['menu'];
    $prices = $_POST['harga'];
    $quantities = (array) $_POST['jumlah'];

    foreach ($menu_names as $index => $name) {
        $price = (float)$prices[$index];
        $quantity = (int)$quantities[$index];
        $subtotal = $price * $quantity;

        // Kumpulkan item keranjang dalam format yang rapi
        $cart_items[] = [
            'nama_menu' => $name,
            'harga' => $price,
            'jumlah' => $quantity,
            'subtotal' => $subtotal
        ];
        
        $total_belanja += $subtotal;
    }

    // --- PERBAIKAN UTAMA: Simpan data keranjang ke Sesi PHP ---
    // Ini jauh lebih aman daripada mengambil data dari database secara acak.
    $_SESSION['checkout_cart'] = $cart_items;
    $_SESSION['checkout_total'] = $total_belanja;

    // Arahkan ke halaman pembayaran
    header("Location: " . BASE_URL . "public/pembayaran.php");
    exit;

} else {
    // Jika akses tidak sah, kembalikan ke halaman keranjang
    header("Location: " . BASE_URL . "public/keranjang.php");
    exit;
}
?>
