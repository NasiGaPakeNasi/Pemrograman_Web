<?php
// public/pembayaran.php
session_start();

// --- PERBAIKAN UTAMA ---
// Jika tidak ada data keranjang di sesi (pengguna datang ke halaman ini secara tidak sah),
// kembalikan mereka ke halaman keranjang.
if (!isset($_SESSION['checkout_cart']) || empty($_SESSION['checkout_cart'])) {
    // Anda bisa mengarahkan ke keranjang atau halaman utama
    header("Location: keranjang.php");
    exit();
}

// Memuat file konfigurasi setelah validasi sesi
require_once __DIR__ . '/../app/config/database.php';

// --- PERBAIKAN UTAMA: Ambil total belanja dari SESI, bukan dari URL ---
$total_belanja = isset($_SESSION['checkout_total']) ? $_SESSION['checkout_total'] : 0;

// Ambil pesan status jika ada (misalnya, jika pembayaran gagal)
$status_pembayaran = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Cek status login untuk menampilkan navbar dengan benar
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn && isset($_SESSION['is_admin']) ? ($_SESSION['is_admin'] == 1) : false;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pembayaran | Warkop Bejo</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pembayaran.css"> <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Gaya tambahan untuk pemilihan metode pembayaran */
        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .payment-method-btn {
            background-color: #f0f0f0;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 15px 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
            color: #555;
            font-weight: 500;
        }

        .payment-method-btn i {
            font-size: 32px;
            margin-bottom: 10px;
            color: #004d40;
        }

        .payment-method-btn.selected {
            background-color: #e0f2f1;
            border-color: #00796b;
            box-shadow: 0 0 10px rgba(0, 121, 107, 0.2);
            color: #004d40;
        }

        .qr-code-display {
            text-align: center;
            margin-top: 20px;
            display: none; /* Awalnya sembunyikan */
        }

        .qr-code-display img {
            max-width: 200px;
            height: auto;
            border: 5px solid #004d40;
            border-radius: 8px;
        }
        .qr-code-display p {
            margin-top: 10px;
            font-weight: bold;
            color: #004d40;
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
                <?php if ($isLoggedIn): ?>
                    <?php if ($isAdmin): ?>
                        <li><a href="../admin/dashboard.php">Admin Panel</a></li>
                    <?php else: ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?= htmlspecialchars($username) ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="payment-container">
        <h2>Pembayaran Warkop Bejo</h2>

        <?php if ($status_pembayaran === 'failed'): ?>
            <p class="message error"><?= $message ?></p>
        <?php endif; ?>

        <div class="payment-details">
            <p>Total yang harus dibayar:</p>
            <p class="total-amount">Rp<?= number_format($total_belanja, 0, ',', '.'); ?></p>
        </div>

        <form action="../app/controllers/payment_controller.php" method="POST">
            <input type="hidden" name="payment_method" id="payment_method_input" value="Tunai"> <div class="payment-methods">
                <div class="payment-method-btn selected" id="btn_tunai">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Tunai</span>
                </div>
                <div class="payment-method-btn" id="btn_qris">
                    <i class="fas fa-qrcode"></i>
                    <span>QRIS</span>
                </div>
            </div>

            <div class="form-group" id="uang_tunai_group">
                <label for="uang_tunai">Uang Tunai Diberikan:</label>
                <input type="number" id="uang_tunai" name="uang_tunai" step="1" min="0" required>
            </div>

            <div class="qr-code-display" id="qris_display">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=WARKOPBEJO_QRIS_<?= $total_belanja ?>" alt="QR Code Pembayaran">
                <p>Scan QRIS di atas untuk membayar Rp<?= number_format($total_belanja, 0, ',', '.') ?></p>
                <p style="font-size: 0.9em; color: #666;">(Pastikan jumlah yang ditransfer sesuai)</p>
            </div>
            
            <button type="submit" class="btn-pay">Bayar Sekarang</button>
        </form>
    </div>

    <script>
        const totalBelanja = <?= $total_belanja ?>;
        const btnTunai = document.getElementById('btn_tunai');
        const btnQris = document.getElementById('btn_qris');
        const uangTunaiGroup = document.getElementById('uang_tunai_group');
        const uangTunaiInput = document.getElementById('uang_tunai');
        const qrisDisplay = document.getElementById('qris_display');
        const paymentMethodInput = document.getElementById('payment_method_input');

        function selectPaymentMethod(method) {
            if (method === 'Tunai') {
                btnTunai.classList.add('selected');
                btnQris.classList.remove('selected');
                uangTunaiGroup.style.display = 'block';
                uangTunaiInput.setAttribute('required', 'required');
                uangTunaiInput.value = totalBelanja; // Otomatis isi dengan total
                uangTunaiInput.min = totalBelanja > 0 ? totalBelanja : 0; // Set min to total if > 0
                qrisDisplay.style.display = 'none';
                paymentMethodInput.value = 'Tunai';
            } else if (method === 'QRIS') {
                btnQris.classList.add('selected');
                btnTunai.classList.remove('selected');
                uangTunaiGroup.style.display = 'none';
                uangTunaiInput.removeAttribute('required');
                uangTunaiInput.value = ''; // Kosongkan jika QRIS
                qrisDisplay.style.display = 'block';
                paymentMethodInput.value = 'QRIS';
            }
        }

        // Event Listeners untuk tombol
        btnTunai.addEventListener('click', () => selectPaymentMethod('Tunai'));
        btnQris.addEventListener('click', () => selectPaymentMethod('QRIS'));

        // Panggil saat halaman dimuat untuk set default (Tunai)
        document.addEventListener('DOMContentLoaded', () => {
            selectPaymentMethod('Tunai');
        });
    </script>
</body>
</html>