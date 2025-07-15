<?php
// public/index.php
// Halaman utama Warkop Bejo

session_start(); // Mulai sesi di awal skrip

// SERTAKAN DULU DATABASE.PHP UNTUK MENDAPATKAN PROJECT_ROOT
require_once __DIR__ . '/../app/config/database.php'; 

// SERTAKAN FILE LAIN MENGGUNAKAN PROJECT_ROOT
require_once PROJECT_ROOT . '/app/models/Menu.php';

// Ambil semua item menu dari database menggunakan fungsi dari model
$menuItems = getAllMenu($conn);

// Tutup koneksi database setelah semua data yang dibutuhkan diambil
$conn->close();

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn ? ($_SESSION['is_admin'] == 1) : false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warkop Bejo</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <header>
    <div class="topbar">Warkop Bejo</div>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="menu.php">Menu</a></li>
        <li><a href="keranjang.php">Keranjang</a></li>
        <?php if ($isLoggedIn): // Tampilkan link dashboard/logout jika sudah login ?>
            <?php if ($isAdmin): // Tampilkan link ke dashboard admin jika admin ?>
                <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
            <?php else: // Tampilkan link ke dashboard pengguna biasa jika bukan admin ?>
                <li><a href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a></li>
        <?php else: // Tampilkan link login/register jika belum login ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <section class="hero">
    <div class="hero-overlay">
      <img src="images/Warkop Bejo 1.jpg" alt="Warkop Interior">
      <div class="hero-text">
        <h1>Selamat Datang di Warkop Bejo</h1>
        <p>Nongkrong Asik 24 Jam!</p>
      </div>
    </div>
  </section>

  <section class="menu-section">
    <h2>View The Menu</h2>
    <div class="menu-items">
      <?php
      // Periksa apakah ada item menu yang ditemukan
      if (!empty($menuItems)) {
          // Loop melalui setiap item menu dan tampilkan dalam format HTML
          foreach ($menuItems as $item) {
              echo "<div class='item'>";
              // Pastikan path gambar benar dan gunakan htmlspecialchars untuk keamanan
              echo "<img src='" . htmlspecialchars($item['gambar']) . "' alt='" . htmlspecialchars($item['nama_menu']) . "' />";
              echo "<h3>" . htmlspecialchars($item['nama_menu']) . "</h3>";
              // Format harga agar lebih mudah dibaca
              echo "<p>Harga: Rp " . number_format($item['harga'], 0, ',', '.') . "</p>";
              echo "<p>" . htmlspecialchars($item['deskripsi']) . "</p>";
              echo "</div>";
          }
      } else {
          // Tampilkan pesan jika tidak ada menu yang tersedia
          echo "<p>Belum ada menu yang tersedia saat ini.</p>";
      }
      ?>
    </div>
  </section>

  <section class="about-section">
    <h2>About Us</h2>
    <div class="about-content">
      <div class="about-text">
        <h4>Our Company</h4>
        <p>Rawrr.</p>
        <h4>Our Coffee</h4>
        <p>Rawrr.</p>
        <h4>Customer Service</h4>
        <p>Rawrr.</p>
      </div>
      <img src="images/gambar4.png" alt="About us image" />
    </div>
  </section>

  <section class="contact-section">
    <h2>Contact Us</h2>
      <p>Hubungi Kami:</p>
      <ul>
        <li>📘 Warkop Bejo</li>
        <li>📱 WarkopBejoAja</li>
        <li>🎥 Warkop Bejo Official</li>
        <li>📷 @WarkopBejo</li>
      </ul>
    </div>
  </section>
  
  <script src="js/main.js"></script>
   <footer class="footer">
    <p>© 2025 Warkop Bejo. Nongkrong Asik 24 Jam!</p>
  </footer>

</body>
</html>
