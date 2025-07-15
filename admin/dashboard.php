<?php
session_start();

require_once __DIR__ . '/../app/includes/auth.php';
require_once __DIR__ . '/../app/config/database.php';

redirectToLoginIfNotAdmin();

$username = $_SESSION['username'];

// --- Fetch Data for Dashboard Widgets ---
// Total Pendapatan Hari Ini
$stmt_revenue = $conn->prepare("SELECT SUM(total) as total_revenue FROM purchases WHERE DATE(tanggal) = CURDATE()");
$stmt_revenue->execute();
$result_revenue = $stmt_revenue->get_result()->fetch_assoc();
$todays_revenue = $result_revenue['total_revenue'] ?? 0;
$stmt_revenue->close();

// Jumlah Pesanan Hari Ini
$stmt_orders = $conn->prepare("SELECT COUNT(id) as total_orders FROM purchases WHERE DATE(tanggal) = CURDATE()");
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result()->fetch_assoc();
$todays_orders = $result_orders['total_orders'] ?? 0;
$stmt_orders->close();

// Jumlah Pengguna Terdaftar
$stmt_users = $conn->prepare("SELECT COUNT(id) as total_users FROM users");
$stmt_users->execute();
$result_users = $stmt_users->get_result()->fetch_assoc();
$total_users = $result_users['total_users'] ?? 0;
$stmt_users->close();

// Jumlah Menu
$stmt_menus = $conn->prepare("SELECT COUNT(id_menu) as total_menus FROM menu");
$stmt_menus->execute();
$result_menus = $stmt_menus->get_result()->fetch_assoc();
$total_menus = $result_menus['total_menus'] ?? 0;
$stmt_menus->close();

// Aktivitas Terbaru (5 terakhir)
$recent_purchases = [];
$stmt_recent = $conn->prepare(
    "SELECT p.tanggal, u.username, p.total 
     FROM purchases p 
     JOIN users u ON p.user_id = u.id 
     ORDER BY p.tanggal DESC 
     LIMIT 5"
);
$stmt_recent->execute();
$result_recent = $stmt_recent->get_result();
while ($row = $result_recent->fetch_assoc()) {
    $recent_purchases[] = $row;
}
$stmt_recent->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard | Warkop Bejo</title>
    <!-- Link ke file CSS baru dan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">Warkop Bejo</div>
        <ul class="sidebar-nav">
            <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_menu.php"><i class="fas fa-utensils"></i> <span>Kelola Menu</span></a></li>
            <!-- Tambahkan link lain di sini jika perlu -->
            <!-- <li><a href="#"><i class="fas fa-users"></i> <span>Kelola Pengguna</span></a></li> -->
            <li><a href="manage_users.php"><i class="fas fa-users"></i> <span>Kelola Pengguna</span></a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <h1>Dashboard</h1>
            <p>Selamat datang kembali, Admin <?php echo htmlspecialchars($username); ?>!</p>
        </header>

        <section class="stats-grid">
            <div class="stat-card revenue">
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="info">
                    <h4>Pendapatan Hari Ini</h4>
                    <p>Rp<?= number_format($todays_revenue, 0, ',', '.') ?></p>
                </div>
            </div>
            <div class="stat-card orders">
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="info">
                    <h4>Pesanan Hari Ini</h4>
                    <p><?= $todays_orders ?></p>
                </div>
            </div>
            <div class="stat-card users">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="info">
                    <h4>Total Pengguna</h4>
                    <p><?= $total_users ?></p>
                </div>
            </div>
            <div class="stat-card menus">
                <div class="icon"><i class="fas fa-book-open"></i></div>
                <div class="info">
                    <h4>Jumlah Menu</h4>
                    <p><?= $total_menus ?></p>
                </div>
            </div>
        </section>

        <section class="recent-activity">
            <h3>Aktivitas Terbaru</h3>
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Total Belanja</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_purchases)): ?>
                        <?php foreach($recent_purchases as $purchase): ?>
                            <tr>
                                <td><?= htmlspecialchars($purchase['username']) ?></td>
                                <td>Rp<?= number_format($purchase['total'], 0, ',', '.') ?></td>
                                <td><?= date('d M Y, H:i', strtotime($purchase['tanggal'])) ?></td>
                                <td><span class="status new-order">Pesanan Baru</span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada aktivitas hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>
