<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warkop Bejo</title>
    <!-- Perbaikan Path CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login Warkop Bejo</h2>

        <?php
        if ($status === 'reg_success') {
            echo "<p class='message success'>Registrasi berhasil! Silakan login.</p>";
        }
        if ($status === 'login_failed') {
            echo "<p class='message error'>" . htmlspecialchars($message) . "</p>";
        }
        if ($status === 'login_required') {
            echo "<p class='message error'>Anda harus login untuk mengakses halaman tersebut.</p>";
        }
        ?>

        <!-- Perbaikan Form Action -->
        <form action="<?php echo BASE_URL; ?>app/controllers/auth_controller.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            <button type="submit" name="login" class="btn-submit">Login</button>
        </form>
        <p class="switch-auth">Belum punya akun? <a href="<?php echo BASE_URL; ?>public/register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
