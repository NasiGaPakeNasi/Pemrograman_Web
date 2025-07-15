<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

// Cek jika ada pesan status dari URL
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Registrasi Akun Baru</h2>

        <?php
        // --- PERBAIKAN: Menampilkan pesan notifikasi ---
        if ($status === 'reg_exists') {
            echo "<p class='message error'>Username sudah terdaftar. Silakan gunakan username lain.</p>";
        }
        if ($status === 'reg_failed') {
            echo "<p class='message error'>Registrasi gagal. Terjadi kesalahan.</p>";
        }
        ?>

        <!-- PERBAIKAN: Form action mengarah ke controller -->
        <form action="../app/controllers/auth_controller.php" method="POST">
            <div class="form-group">
                <label for="new_username">Username Baru</label>
                <input type="text" id="new_username" name="new_username" required />
            </div>
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required />
            </div>
            <!-- PERBAIKAN: Menambahkan 'name' pada tombol untuk identifikasi di controller -->
            <button type="submit" name="register" class="btn-submit">Daftar</button>
        </form>
        <p class="switch-auth">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
