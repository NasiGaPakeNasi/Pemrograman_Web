<?php
session_start();
// Memuat file config untuk mendapatkan BASE_URL (jika diperlukan untuk link aset)
require_once __DIR__ . '/../app/config/database.php';

// Cek jika ada pesan status dari URL (hasil redirect)
$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>

        <?php
        // --- PERBAIKAN: Menampilkan pesan notifikasi ---
        if ($status === 'reg_success') {
            echo "<p class='message success'>Registrasi berhasil! Silakan login.</p>";
        }
        if ($status === 'login_failed') {
            echo "<p class='message error'>" . htmlspecialchars($message) . "</p>";
        }
        ?>

        <!-- PERBAIKAN: Form action mengarah ke controller -->
        <form action="../app/controllers/auth_controller.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            <!-- PERBAIKAN: Menambahkan 'name' pada tombol untuk identifikasi di controller -->
            <button type="submit" name="login" class="btn-submit">Login</button>
        </form>
        <p class="switch-auth">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
