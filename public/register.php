<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Registrasi Akun Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="new_username">Username Baru</label>
                <input type="text" id="new_username" name="new_username" required />
            </div>
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required />
            </div>
            <button type="submit" class="btn-submit">Daftar</button>
        </form>
        <p class="switch-auth">Sudah punya akun? <a href="login.php">Login di sini</a></p>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/../app/includes/auth.php';
    require_once __DIR__ . '/../app/config/database.php';
    
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    $registerResult = registerUser($newUsername, $newPassword);

    if ($registerResult === "success") {
        echo "<p style='color: green;'>Registrasi berhasil! Silakan login.</p>";
        // Redirect ke halaman login setelah beberapa detik
        header("Refresh: 5; URL=index.php"); // Redirect otomatis setelah 2 detik
        exit();
    } elseif ($registerResult === "exists") {
        echo "<p style='color: red;'>Username sudah terdaftar. Silakan gunakan username lain.</p>";
    } else {
        echo "<p style='color: red;'>Registrasi gagal. Terjadi kesalahan.</p>";
    }
}
?>
        </div>

</body>
</html>