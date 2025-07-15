<?php
session_start(); 
require_once __DIR__ . '/../app/config/database.php';
require_once PROJECT_ROOT . '/app/models/Menu.php';
$menuOptions = getAllMenu($conn);
$conn->close();

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn && isset($_SESSION['is_admin']) ? ($_SESSION['is_admin'] == 1) : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang | Warkop Bejo</title>
  <link rel="stylesheet" href="css/style.css"> 
  <link rel="stylesheet" href="css/keranjang.css">
</head>
<body>
  <div id="notif"></div>

  <header>
    <div class="topbar">Warkop Bejo</div>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="keranjang.php" class="active">Keranjang</a></li>
        <?php if ($isLoggedIn): ?>
            <?php if ($isAdmin): ?>
                <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
            <?php else: ?>
                <li><a href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <section class="cart-section">
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

    <form id="checkout-form" action="../app/controllers/checkout_controller.php" method="POST">
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

  <script src="js/keranjang.js"></script>
</body>
</html>
