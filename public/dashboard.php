<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan kembali ke halaman login atau index
    header("Location: index.php");
    exit();
}

// Sertakan file auth untuk fungsi getPurchaseHistory
// PROJECT_ROOT didefinisikan di app/config/database.php
require_once __DIR__ . '/../app/includes/auth.php'; 
// Sertakan file koneksi database pusat (diperlukan oleh auth.php, tapi pastikan juga ada)
require_once __DIR__ . '/../app/config/database.php';


$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$purchaseHistory = getPurchaseHistory($userId); // Ambil riwayat pembelian dari fungsi auth.php

// Tutup koneksi database setelah semua data yang dibutuhkan diambil
$conn->close();

// Cek apakah pengguna adalah admin (untuk navigasi)
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Pengguna | Warkop Bejo</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Menggunakan style.css untuk navbar -->
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Untuk gaya kartu dashboard -->
</head>
<body>
    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="menu.php">Menu</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
                <?php else: ?>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <div class="profile-card dashboard-card">
            <div class="profile-picture">
                <!-- Anda bisa menambahkan logika untuk menampilkan gambar profil di sini -->
                <!-- Untuk saat ini, placeholder atau gambar default -->
                <img id="preview" src="https://placehold.co/120x120/E0E0E0/333333?text=Profil" alt="Foto Profil" />
            </div>
            <label for="nama-pengguna">Nama Pengguna:</label>
            <input type="text" id="nama-pengguna" value="<?php echo htmlspecialchars($username); ?>" disabled />
            <form action="logout.php" method="POST">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        <div class="history-card dashboard-card">
            <h2>Riwayat Pembelian</h2>

            <?php if (!empty($purchaseHistory)): ?>
                <?php foreach ($purchaseHistory as $entry): ?>
                    <div class="history-entry">
                        <div class="date"><?php echo htmlspecialchars($entry['tanggal']); ?></div>
                        <ul>
                            <?php
                            // Asumsikan 'items' adalah array atau objek setelah json_decode
                            if (!empty($entry['items']) && is_array($entry['items'])) {
                                foreach ($entry['items'] as $itemDetail) {
                                    // Sesuaikan dengan struktur item di kolom 'items' tabel 'purchases'
                                    // Misalnya, jika disimpan sebagai [{"nama_menu": "Kopi", "jumlah": 2, "harga": 7000}]
                                    echo "<li>";
                                    echo htmlspecialchars($itemDetail['nama_menu'] ?? 'Nama Item Tidak Diketahui');
                                    echo " (" . htmlspecialchars($itemDetail['jumlah'] ?? 1) . "x)";
                                    echo " - Rp" . number_format($itemDetail['harga'] ?? 0, 0, ',', '.');
                                    echo "</li>";
                                }
                            } else {
                                echo "<li>Detail item tidak tersedia.</li>";
                            }
                            ?>
                        </ul>
                        <div class="total">Total: Rp<?php echo number_format($entry['total'], 0, ',', '.'); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada riwayat pembelian.</p>
            <?php endif; ?>
        </div>
    </main>

<!-- Hapus script main.js karena tidak diperlukan di halaman dashboard -->
<!-- <script src="js/main.js"></script> -->

</body>
</html>
