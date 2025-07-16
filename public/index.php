<?php
// public/index.php
session_start();

// Memuat file konfigurasi untuk BASE_URL dan koneksi DB
require_once __DIR__ . '/../app/config/database.php'; 
require_once PROJECT_ROOT . '/app/models/Menu.php';

$menuItems = getAllMenu($conn);
$conn->close();

// Cek status login untuk navbar
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn ? ($_SESSION['is_admin'] == 1) : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warkop Bejo - Nongkrong Asik 24 Jam</title>
  <!-- Perbaikan Path CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css" />
</head>
<body>
    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>public/index.php" class="active">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/menu.php">Menu</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/keranjang.php">Keranjang</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="<?php echo BASE_URL; ?>public/dashboard.php">Dashboard</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropbtn">Halo, <?php echo htmlspecialchars($username); ?> &#9662;</a>
                        <div class="dropdown-content">
                            <a href="<?php echo BASE_URL; ?>public/logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>public/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

  <section class="hero">
    <div class="hero-overlay">
      <!-- Perbaikan Path Gambar -->
      <img src="<?php echo BASE_URL; ?>public/images/Warkop Bejo 1.jpg" alt="Warkop Interior">
      <div class="hero-text">
        <h1>Selamat Datang di Warkop Bejo</h1>
        <p>Nongkrong Asik 24 Jam!</p>
      </div>
    </div>
  </section>

  <section class="menu-section">
    <h2>Menu Andalan Kami</h2>
    <div class="menu-items">
      <?php
      if (!empty($menuItems)) {
          foreach ($menuItems as $item) {
              echo "<div class='item'>";
              // Perbaikan Path Gambar
              $gambar_path = !empty($item['gambar']) ? BASE_URL . 'public/' . htmlspecialchars($item['gambar']) : 'https://placehold.co/280x180/E0E0E0/333333?text=No+Image';
              echo "<img src='" . $gambar_path . "' alt='" . htmlspecialchars($item['nama_menu']) . "' />";
              echo "<h3>" . htmlspecialchars($item['nama_menu']) . "</h3>";
              echo "<p>Rp " . number_format($item['harga'], 0, ',', '.') . "</p>";
              echo "<p class='deskripsi'>" . htmlspecialchars($item['deskripsi']) . "</p>";
              echo "</div>";
          }
      } else {
          echo "<p>Belum ada menu yang tersedia saat ini.</p>";
      }
      ?>
    </div>
  </section>

  <footer class="footer">
    <p>© <?php echo date("Y"); ?> Warkop Bejo. Semua Hak Cipta Dilindungi.</p>
  </footer>

</body>
</html>
