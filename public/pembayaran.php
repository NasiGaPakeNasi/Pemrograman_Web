<?php
// public/pembayaran.php
session_start();

// --- PERBAIKAN UTAMA ---
// Jika tidak ada data keranjang di sesi (pengguna datang ke halaman ini secara tidak sah),
// kembalikan mereka ke halaman keranjang.
if (!isset($_SESSION['checkout_cart']) || empty($_SESSION['checkout_cart'])) {
    // Anda bisa mengarahkan ke keranjang atau halaman utama
    header("Location: keranjang.php");
    exit();
}

// Memuat file konfigurasi setelah validasi sesi
require_once __DIR__ . '/../app/config/database.php';

// --- PERBAIKAN UTAMA: Ambil total belanja dari SESI, bukan dari URL ---
$total_belanja = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 0;

// Ambil pesan status jika ada (misalnya, jika pembayaran gagal)
$status_pembayaran = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Cek status login untuk menampilkan navbar dengan benar
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn && isset($_SESSION['is_admin']) ? ($_SESSION['is_admin'] == 1) : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pembayaran | Warkop Bejo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pembayaran.css"> <!-- CSS terpisah untuk halaman pembayaran -->
</head>
<body>
    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="menu.php">Menu</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout (<?= htmlspecialchars($username) ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="payment-container">
        <h2>Halaman Pembayaran</h2>

        <?php if ($status_pembayaran === 'failed'): ?>
            <p class="message error"><?= $message ?></p>
        <?php endif; ?>

        <div class="payment-details">
            <p>Total yang harus dibayar:</p>
            <p class="total-amount">Rp<?= number_format($total_belanja, 0, ',', '.'); ?></p>
        </div>

        <!-- Form ini akan mengirim data ke payment_controller.php yang sudah kita perbaiki -->
        <form action="../app/controllers/payment_controller.php" method="POST">
            <div class="form-group">
                <label for="uang_tunai">Uang Tunai Diberikan:</label>
                <input type="number" id="uang_tunai" name="uang_tunai" step="1" min="<?= $total_belanja > 0 ? $total_belanja : 0; ?>" required>
            </div>
            <button type="submit" class="btn-pay">Bayar Sekarang</button>
        </form>
    </div>
</body>
</html>
