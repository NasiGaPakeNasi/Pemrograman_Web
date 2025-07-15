<?php
// admin/dashboard.php
// Halaman dashboard untuk Administrator Warkop Bejo

session_start(); // Pastikan sesi dimulai

// Sertakan file auth untuk memeriksa status admin
require_once __DIR__ . '/../app/includes/auth.php';

// Lindungi halaman: hanya admin yang bisa mengakses
redirectToLoginIfNotAdmin();

// Jika sudah login dan admin, ambil data sesi
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard | Warkop Bejo</title>
    <link rel="stylesheet" href="../public/css/dashboard.css"> <!-- Gunakan CSS yang sudah ada -->
    <style>
        /* Gaya tambahan khusus admin jika diperlukan */
        .admin-nav {
            background-color: #333;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .admin-nav a:hover {
            background-color: #555;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            color: #004d40;
        }
        .admin-features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .feature-card {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #00796b;
            text-align: center;
            width: 250px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .feature-card h3 {
            color: #004d40;
            margin-bottom: 15px;
        }
        .feature-card p {
            color: #555;
            margin-bottom: 20px;
        }
        .feature-card a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #004d40;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .feature-card a:hover {
            background-color: #00695c;
        }
    </style>
</head>
<body>

    <div class="topbar">Admin Panel Warkop Bejo</div>
    <div class="admin-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_menu.php">Kelola Menu</a>
        <!-- Tambahkan link lain untuk fitur admin di masa mendatang -->
        <a href="../public/logout.php">Logout</a>
    </div>

    <div class="welcome-message">
        Halo, Admin <?php echo htmlspecialchars($username); ?>! Selamat datang di Panel Administrasi.
    </div>

    <div class="admin-features">
        <div class="feature-card">
            <h3>Kelola Menu</h3>
            <p>Tambah, edit, atau hapus item menu kopi dan makanan.</p>
            <a href="manage_menu.php">Buka</a>
        </div>
        <div class="feature-card">
            <h3>Lihat Pesanan</h3>
            <p>Pantau pesanan yang masuk dari pelanggan.</p>
            <a href="#">Buka (Segera Hadir)</a>
        </div>
        <div class="feature-card">
            <h3>Kelola Pengguna</h3>
            <p>Lihat dan kelola akun pengguna.</p>
            <a href="#">Buka (Segera Hadir)</a>
        </div>
    </div>

</body>
</html>
