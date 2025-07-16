<?php
session_start(); 
require_once __DIR__ . '/../app/config/database.php';
require_once PROJECT_ROOT . '/app/models/Menu.php';

$menuOptions = getAllMenu($conn);
$conn->close();

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang | Warkop Bejo</title>
  <!-- Perbaikan Path CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css"> 
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/keranjang.css">
</head>
<body>
  <div id="notif"></div>

    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>public/index.php">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/menu.php">Menu</a></li>
                <li><a href="<?php echo BASE_URL; ?>public/keranjang.php" class="active">Keranjang</a></li>
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

  <section class="cart-section">
    <h2>Keranjang Belanja Anda</h2>
    <div class="menu-select">
      <label for="menu">Pilih Menu:</label>
      <select id="menu">
        <option value="" disabled selected>-- Tambah item dari menu --</option>
        <?php foreach ($menuOptions as $item): ?>
            <option value="<?= htmlspecialchars($item['gambar']) ?>|<?= htmlspecialchars($item['nama_menu']) ?>|<?= htmlspecialchars($item['harga']) ?>">
                <?= htmlspecialchars($item['nama_menu']) ?> - Rp<?= number_format($item['harga'], 0, ',', '.') ?>
            </option>
        <?php endforeach; ?>
      </select>
      <button onclick="tambahKeKeranjang()">Tambah ke Keranjang</button>
    </div>

    <!-- Perbaikan Form Action -->
    <form id="checkout-form" action="<?php echo BASE_URL; ?>app/controllers/checkout_controller.php" method="POST">
      <div id="cart-container">
        <p style='text-align:center; color:#777; padding: 20px;'>Keranjang Anda kosong.</p>
      </div>

      <div class="cart-summary">
        <h3 id="total">Total: Rp0</h3>
        <button type="button" id="checkout-button-js" class="checkout-btn">Checkout</button>
      </div>

      <div id="hidden-inputs"></div>
    </form>
  </section>

  <!-- Perbaikan Path JS -->
  <script src="<?php echo BASE_URL; ?>public/js/keranjang.js"></script>
</body>
</html>
