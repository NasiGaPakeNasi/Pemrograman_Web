<?php
session_start(); // Pastikan sesi dimulai di awal skrip
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/auth.css"> <!-- Pastikan ini adalah CSS untuk styling form login -->
    <!-- Hapus link ke style.css atau main.js yang tidak relevan untuk halaman login -->
</head>

<body>
    <div class="auth-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>
        <p class="switch-auth">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        
    </div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sertakan file koneksi database pusat
    // Path ini relatif dari public/login.php ke app/config/database.php
    require_once __DIR__ . '/../app/config/database.php';
    // Sertakan file auth menggunakan PROJECT_ROOT
    require_once PROJECT_ROOT . '/app/includes/auth.php'; 

    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginResult = loginUser($username, $password);

    if ($loginResult['status'] === 'success') {
        // Set session
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['is_admin'] = $loginResult['is_admin']; // Simpan status admin ke sesi

        // Redirect berdasarkan status admin
        if ($_SESSION['is_admin'] == 1) {
            // Gunakan "../" untuk naik satu level dari public/ ke root proyek, lalu masuk ke admin/
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: dashboard.php"); // Redirect ke dashboard pengguna biasa (tetap di public/)
        }
        exit();
    } else {
        // Tampilkan pesan error jika login gagal
        echo "<p class='message error' style='text-align: center; margin-top: 15px;'>" . htmlspecialchars($loginResult['message']) . "</p>";
    }
}
?>
</body>
</html>
