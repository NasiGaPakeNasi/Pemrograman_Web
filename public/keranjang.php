<?php
// public/keranjang.php
// Halaman keranjang belanja Warkop Bejo

session_start(); // Mulai sesi di awal skrip

// Sertakan file koneksi database pusat
// Path ini relatif dari public/keranjang.php ke app/config/database.php
require_once __DIR__ . '/../app/config/database.php';

// Sertakan model Cart menggunakan PROJECT_ROOT
// PROJECT_ROOT didefinisikan di app/config/database.php
require_once PROJECT_ROOT . '/app/models/Cart.php';

// Inisialisasi variabel untuk menampung item keranjang
$cartItems = [];

// Cek apakah pengguna sudah login (untuk navbar dinamis)
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn ? ($_SESSION['is_admin'] == 1) : false;

// Cek jika ada data keranjang yang dikirim dari sessionStorage (dari menu.php)
if (isset($_GET['cartData'])) {
    $cartDataJson = $_GET['cartData'];
    $cartItems = json_decode($cartDataJson, true);
} 
// PENTING: BLOK PEMROSESAN POST UNTUK CHECKOUT SUDAH DIHAPUS DARI SINI
// KARENA SEKARANG DITANGANI SEPENUHNYA OLEH app/controllers/api/proses_checkout.php

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang | Warkop Bejo</title>
  <link rel="stylesheet" href="css/style.css"> <link rel="stylesheet" href="css/keranjang.css"> <style>
    /* Gaya CSS inline ini dipertahankan di sini untuk kemudahan, */
    /* tapi idealnya bisa dipindahkan sepenuhnya ke public/css/keranjang.css */

    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }

    .cart-section {
      padding: 20px;
      max-width: 800px;
      margin: auto;
      margin-top: 30px; /* Tambahkan margin-top agar tidak terlalu dekat dengan navbar */
    }

    .menu-select {
      margin-bottom: 20px;
      display: flex; /* Untuk menata elemen select dan button */
      gap: 10px;
      align-items: center;
    }

    .menu-select select {
      flex-grow: 1; /* Agar select memenuhi ruang */
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    .menu-select button {
      padding: 8px 15px;
      background-color: #004d40; /* Warna tombol yang konsisten */
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      transition: background-color 0.3s;
    }
    .menu-select button:hover {
        background-color: #00695c;
    }

    .cart-item {
      display: flex;
      align-items: center;
      background: #fff;
      padding: 15px; /* Sedikit lebih besar */
      margin-bottom: 15px; /* Jarak antar item */
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1); /* Shadow lebih halus */
    }

    .cart-item img {
      width: 90px; /* Ukuran gambar sedikit lebih besar */
      height: 90px;
      object-fit: cover;
      margin-right: 20px;
      border-radius: 8px;
    }

    .item-details {
      flex-grow: 1;
    }

    .item-details h3 {
      margin-bottom: 5px;
      font-size: 18px; /* Ukuran font lebih besar */
      color: #004d40;
    }

    .item-details p {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #555;
    }

    .item-details label {
        font-size: 14px;
        color: #555;
    }

    .item-details input[type="number"] {
      width: 60px; /* Lebar input jumlah */
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-left: 5px;
      text-align: center;
    }

    .subtotal {
        font-weight: bold;
        color: #333;
        margin-top: 8px !important; /* Pastikan margin-top diterapkan */
        font-size: 15px;
    }

    .remove-btn {
      background-color: #dc3545; /* Merah untuk hapus */
      color: white;
      padding: 8px 15px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin-left: 15px; /* Jarak dari detail item */
    }
    .remove-btn:hover {
        background-color: #c82333;
    }

    .cart-summary {
      margin-top: 40px;
      background-color: #fff;
      padding: 25px; /* Padding lebih besar */
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      /* Perubahan di sini untuk menata total dan tombol secara horizontal */
      display: flex; /* Menggunakan flexbox */
      justify-content: space-between; /* Memisahkan total dan tombol */
      align-items: center; /* Pusatkan secara vertikal */
      width: 100%; /* Agar memenuhi lebar kontainer */
      max-width: 800px; /* Batasi lebar agar tidak terlalu lebar */
      box-sizing: border-box; /* Pastikan padding termasuk dalam lebar */
      min-width: unset; /* Hapus min-width sebelumnya */
      margin-left: auto; /* Tetap pusatkan jika lebar kurang dari max-width */
      margin-right: auto;
      border-top: 4px solid #004d40; /* Aksen hijau */
    }
    .cart-summary h3 {
        font-size: 22px;
        color: #004d40;
        margin-bottom: 0; /* Hapus margin-bottom agar sejajar */
    }
  
    .checkout-btn {
      padding: 12px 28px; /* Ukuran tombol lebih besar */
      background-color: #28a745; /* Hijau untuk checkout */
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 30px; /* Bentuk kapsul */
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 0; /* Hapus margin-top agar sejajar */
    }
  
    .checkout-btn:hover {
      background-color: #218838;
    }

    #notif {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #4caf50;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      display: none;
      opacity: 0;
      transition: opacity 0.4s ease;
      animation: slideDown 0.5s forwards;
      z-index: 1000;
      cursor: pointer;
    }

    #notif.error {
        background-color: #e74c3c; /* Warna merah untuk notifikasi error */
    }

    @keyframes slideDown {
      from {
        transform: translate(-50%, -20px);
        opacity: 0;
      }
      to {
        transform: translate(-50%, 0);
        opacity: 1;
      }
    }

    /* Media query untuk responsif jika total dan tombol perlu menumpuk di layar kecil */
    @media (max-width: 600px) {
        .cart-summary {
            flex-direction: column; /* Menumpuk vertikal di layar kecil */
            align-items: flex-start; /* Rata kiri */
        }
        .cart-summary h3, .checkout-btn {
            width: 100%; /* Ambil lebar penuh */
            text-align: center;
            margin-top: 10px; /* Tambahkan sedikit jarak */
        }
        .cart-summary h3 {
            margin-bottom: 5px;
        }
    }
  </style>
</head>
<body>
  <div id="notif"></div>

  <header>
    <div class="topbar">Warkop Bejo</div>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="menu.php">Menu</a></li>
        <li><a href="keranjang.php" class="active">Keranjang</a></li>
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

  <section class="cart-section">
    <div class="menu-select">
      <label for="menu">Pilih Menu:</label>
      <select id="menu">
        <option value="images/Indocafe.jpg|Indocafe|5000">Indocafe - Rp5.000</option>
        <option value="images/Good Day Cappuccino.jpeg|Good Day Cappuccino|6000">Good Day Cappuccino - Rp6.000</option>
        <option value="images/Good Day Moccacino.jpg|Good Day Moccacino|6000">Good Day Moccacino - Rp6.000</option>
        <option value="images/Kopi Hitam Kapal Api.jpg|Kopi Hitam Kapal Api|4000">Kopi Hitam Kapal Api - Rp4.000</option>
        <option value="images/Americano.jpg|Americano|18000">Americano - Rp18.000</option>
        <option value="images/Creamy Latte.jpg|Creamy Latte|22000">Creamy Latte - Rp22.000</option>
        <option value="images/Coffe Latte.jpg|Coffe Latte|20000">Coffe Latte - Rp20.000</option>
        <option value="images/Es Teh Manis.jpg|Es Teh Manis|5000">Es Teh Manis - Rp5.000</option>
        <option value="images/Es Nutrisari.jpeg|Es Nutrisari|5000">Es Nutrisari - Rp5.000</option>
        <option value="images/Es Teh Tarik.jpeg|Es Teh Tarik|7000">Es Teh Tarik - Rp7.000</option>
        <option value="images/Es Jeruk.jpg|Es Jeruk|7000">Es Jeruk - Rp7.000</option>
        <option value="images/Jeruk Panas.jpg|Jeruk Panas|7000">Jeruk Panas - Rp5.000</option>
        <option value="images/Indomie Goreng.jpg|Indomie Goreng|10000">Indomie Goreng - Rp10.000</option>
        <option value="images/Indomie Kuah Soto.jpg|Indomie Kuah Soto|10000">Indomie Kuah Soto - Rp10.000</option>
        <option value="images/Indomie Kuah Ayam Bawang.jpg|Indomie Kuah Ayam Bawang|10000">Indomie Kuah Ayam Bawang - Rp10.000</option>
        <option value="images/Indomie Kuah Kari Ayam.jpg|Indomie Kuah Kari Ayam|10000">Indomie Kuah Kari Ayam - Rp10.000</option>
        <option value="images/Telor,sosis/bakso.jpg|Telor,sosis/bakso|5000">Telor,sosis/bakso - Rp5.000</option>
        <option value="images/Pisang Goreng Cokelat Keju.jpg|Pisang Goreng Cokelat Keju|15000">Pisang Goreng Cokelat Keju- Rp15.000</option>
        <option value="images/Kentang Goreng.jpg|Kentang Goreng|10000">Kentang Goreng - Rp10.000</option>
        <option value="images/Otak-otak Goreng.jpg|Otak-otak Goreng|10000">Otak-otak Goreng - Rp10.000</option>
        <option value="images/Sosis goreng.jpg|Sosis goreng|10000">Sosis goreng - Rp10.000</option>
        <option value="images/Nugget.jpg|Nugget|10000">Nugget - Rp10.000</option>
        <option value="images/Nasi Goreng Biasa.jpg|Nasi Goreng Biasa|15000">Nasi Goreng Biasa - Rp15.000</option>
        <option value="images/Nasi Goreng Spesial.jpg|Nasi Goreng Spesial|20000">Nasi Goreng Spesial - Rp20.000</option>
        <option value="images/Nasi Goreng Sosis/bakso.jpg|Nasi Goreng Sosis/bakso|17000">Nasi Goreng Sosis/bakso - Rp17000</option>
        <option value="images/Nasi Goreng Ati Ampela.jpg|Nasi Goreng Ati Ampela|17000">Nasi Goreng Ati Ampela - Rp17.000</option>
      </select>
      <button onclick="tambahKeKeranjang()">Tambah ke Keranjang</button>
    </div>

    <form id="checkout-form" action="../app/controllers/api/proses_checkout.php" method="POST">
      <div id="cart-container">
        </div>

      <div class="cart-summary">
        <h3 id="total">Total: Rp0</h3>
        <button type="submit" class="checkout-btn">Checkout</button>
      </div>

      <div id="hidden-inputs"></div>
    </form>
  </section>

  <script src="js/keranjang.js"></script>
</body>
</html>
          