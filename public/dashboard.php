<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/auth.php';

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$purchaseHistory = getPurchaseHistory($userId);
$conn->close();

$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

function getDayNameInIndonesian($date) {
    $dayNames = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $day = date('l', strtotime($date));
    return $dayNames[$day];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard Pengguna | Warkop Bejo</title>
    <!-- Perbaikan Path CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/dashboard.css">
</head>
<body>
    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>public/index.php">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/menu.php">Menu</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/keranjang.php">Keranjang</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin Panel</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>public/dashboard.php" class="active">Dashboard</a></li>
                <?php endif; ?>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Halo, <?php echo htmlspecialchars($username); ?> &#9662;</a>
                    <div class="dropdown-content">
                        <a href="<?php echo BASE_URL; ?>public/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
        <div class="profile-card dashboard-card">
            <div class="profile-picture">
                <img id="preview" src="https://placehold.co/120x120/004d40/FFFFFF?text=<?php echo strtoupper(substr($username, 0, 1)); ?>" alt="Foto Profil" />
                <label for="file-upload" class="upload-icon">&#9998;</label>
                <input id="file-upload" type="file" accept="image/*"/>
            </div>
            <h3><?php echo htmlspecialchars($username); ?></h3>
            <p>Selamat datang di dashboard Anda!</p>
            <form action="<?php echo BASE_URL; ?>public/logout.php" method="POST">
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        <div class="history-card dashboard-card">
            <h2>Riwayat Pembelian</h2>
            <div class="history-list">
                <?php if (!empty($purchaseHistory)): ?>
                    <?php foreach ($purchaseHistory as $entry): ?>
                        <div class="history-entry">
                            <div class="date-header">
                                <span class="day"><?php echo getDayNameInIndonesian($entry['tanggal']); ?></span>
                                <span class="date"><?php echo date('d F Y, H:i', strtotime($entry['tanggal'])); ?></span>
                            </div>
                            <ul>
                                <?php
                                if (!empty($entry['items']) && is_array($entry['items'])) {
                                    foreach ($entry['items'] as $itemDetail) {
                                        echo "<li>";
                                        echo "<span>" . htmlspecialchars($itemDetail['nama_menu'] ?? 'N/A') . " (" . htmlspecialchars($itemDetail['jumlah'] ?? 1) . "x)</span>";
                                        echo "<span>Rp" . number_format($itemDetail['subtotal'] ?? 0, 0, ',', '.') . "</span>";
                                        echo "</li>";
                                    }
                                } else {
                                    echo "<li>Detail item tidak tersedia.</li>";
                                }
                                ?>
                            </ul>
                            <div class="total">
                                <span>Total Belanja</span>
                                <span>Rp<?php echo number_format($entry['total'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-history">Tidak ada riwayat pembelian.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        const fileUpload = document.getElementById('file-upload');
        const preview = document.getElementById('preview');
        fileUpload.addEventListener('change', function() {
            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = () => { preview.src = reader.result; };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>
