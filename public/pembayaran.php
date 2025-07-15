<?php
// public/pembayaran.php
// Halaman Pembayaran Warkop Bejo

session_start();

// Sertakan file koneksi database (jika diperlukan untuk data tambahan)
require_once __DIR__ . '/../app/config/database.php';

// Ambil total belanja dari parameter URL
// Pastikan ini mengambil nilai dengan benar
$total_belanja = isset($_GET['total_belanja']) ? floatval($_GET['total_belanja']) : 0;
$status_pembayaran = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pembayaran | Warkop Bejo</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Gaya umum -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .payment-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #004d40;
            text-align: center;
        }
        .payment-container h2 {
            color: #004d40;
            margin-bottom: 20px;
        }
        .payment-details p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .payment-details .total-amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .form-group input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            text-align: center;
        }
        .btn-pay {
            background-color: #004d40;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-pay:hover {
            background-color: #00695c;
        }
        .message {
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 14px;
            color: white;
            font-weight: 500;
            text-align: center;
        }
        .message.success {
            background-color: #2e7d32;
        }
        .message.error {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <header>
        <div class="topbar">Warkop Bejo</div>
        <nav class="navbar">
            <div class="logo">WARKOP BEJO</div>
            <ul class="nav-links">
                <li><a href="menu.php">Menu</a></li>
                <li><a href="keranjang.php">Keranjang</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['is_admin'] == 1): ?>
                        <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="payment-container">
        <h2>Halaman Pembayaran</h2>

        <?php if ($status_pembayaran === 'success'): ?>
            <p class="message success">Pesanan Anda berhasil dibuat. Silakan lanjutkan pembayaran.</p>
        <?php endif; ?>

        <div class="payment-details">
            <p>Total yang harus dibayar:</p>
            <p class="total-amount">Rp<?php echo number_format($total_belanja, 0, ',', '.'); ?></p>
        </div>

        <form action="proses_pembayaran.php" method="POST">
            <div class="form-group">
                <label for="uang_tunai">Uang Tunai Diberikan:</label>
                <input type="number" id="uang_tunai" name="uang_tunai" step="1" min="<?php echo $total_belanja; ?>" required>
            </div>
            <input type="hidden" name="total_belanja" value="<?php echo htmlspecialchars($total_belanja); ?>">
            <button type="submit" class="btn-pay">Bayar Sekarang</button>
        </form>
    </div>

</body>
</html>
