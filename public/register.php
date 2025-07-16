<?php
session_start();
// Memuat file konfigurasi untuk BASE_URL
require_once __DIR__ . '/../app/config/database.php';

// Mengambil status dari URL jika ada (misalnya, setelah redirect)
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Warkop Bejo</title>
    <!-- Path CSS yang benar menggunakan BASE_URL -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Registrasi Akun Baru</h2>

        <?php
        // Menampilkan pesan error jika username sudah ada atau registrasi gagal
        if ($status === 'reg_exists') {
            echo "<p class='message error'>Username sudah terdaftar. Silakan gunakan username lain.</p>";
        }
        if ($status === 'reg_failed') {
            echo "<p class='message error'>Registrasi gagal. Terjadi kesalahan.</p>";
        }
        ?>

        <!-- Form action yang benar mengarah ke auth_controller.php -->
        <form action="<?php echo BASE_URL; ?>app/controllers/auth_controller.php" method="POST">
            <div class="form-group">
                <!-- Label dan input untuk username baru -->
                <label for="new_username">Username Baru</label>
                <input type="text" id="new_username" name="new_username" required />
            </div>
            <div class="form-group">
                <!-- Label dan input untuk password baru -->
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required />
            </div>
            <!-- Tombol submit dengan nama 'register' untuk diidentifikasi oleh controller -->
            <button type="submit" name="register" class="btn-submit">Daftar</button>
        </form>
        <!-- Link untuk kembali ke halaman login -->
        <p class="switch-auth">Sudah punya akun? <a href="<?php echo BASE_URL; ?>public/login.php">Login di sini</a></p>
    </div>
</body>
</html>
