<?php
session_start();

// Memuat konfigurasi database
require_once '../app/config/database.php';
// Memuat fungsi autentikasi
require_once PROJECT_ROOT . '/app/includes/auth.php';
// Memuat fungsi-fungsi menu
require_once PROJECT_ROOT . '/app/models/Menu.php';

// Cek apakah user login dan apakah admin
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : ''; // Ambil username jika login
$is_admin = $is_logged_in ? isAdmin() : false;

// Dapatkan semua menu untuk ditampilkan di homepage
$menuItems = getAllMenu($conn);

// Tutup koneksi database (penting untuk dilakukan setelah semua query selesai)
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warkop Bejo - Nongkrong Asik 24 Jam</title>
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
                <?php if ($is_logged_in): ?>
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

  <section class="about-section">
    <h2>Tentang Kami</h2>
    <div class="about-content">
      <div class="about-text">
        <h4>Perusahaan Kami</h4>
        <p>Warkop Bejo adalah destinasi nyaman bagi pecinta kopi dan tempat nongkrong asik, dirancang untuk menghadirkan suasana hangat dan ramah secara online maupun fisik. Kami adalah lebih dari sekadar kedai kopi; kami adalah komunitas tempat setiap individu dapat bersantai, bekerja, atau bersosialisasi.</p>
        <h4>Kopi Kami</h4>
        <p>Kami berkomitmen pada kualitas kopi dan layanan pelanggan prima. Kami menyajikan kopi pilihan terbaik yang diproses dengan cermat untuk pengalaman rasa yang autentik dan tak terlupakan, sesuai dengan nilai-nilai yang kami junjung tinggi.</p>
        <h4>Layanan Pelanggan</h4>
        <p>Kepuasan Anda adalah prioritas kami. Tim layanan pelanggan kami siap membantu Anda dengan ramah dan profesional, memastikan setiap kunjungan atau interaksi menjadi pengalaman yang menyenangkan dan mulus.</p>
      </div>
      <img src="<?php echo BASE_URL; ?>public/images/gambar4.png" alt="Gambar Ilustrasi Kopi">
    </div>
  </section>

  <section class="contact-section">
    <h2>Hubungi Kami</h2>
    <p>Kami senang mendengar dari Anda! Terhubunglah dengan kami melalui berbagai platform media sosial di bawah ini, atau kunjungi lokasi fisik kami kapan saja.</p>
    <button class="view-more-btn">Lihat Lebih Banyak</button>
    <div class="contacts">
      <ul>
        <li>Facebook: Warkop Bejo</li>
        <li>WhatsApp: WarkopBejoAja</li>
        <li>YouTube: Warkop Bejo Official</li>
        <li>Instagram: @WarkopBejo</li>
      </ul>
    </div>
  </section>

  <footer class="footer">
    <p>© <?php echo date("Y"); ?> Warkop Bejo. Semua Hak Cipta Dilindungi.</p>
  </footer>

  <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>
</html>