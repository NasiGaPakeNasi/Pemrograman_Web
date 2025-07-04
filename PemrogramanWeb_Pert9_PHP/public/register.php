<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Registrasi</title></head>
<body>
<h2>Registrasi Akun Baru</h2>
<form action="" method="POST"> 
    <input type="text" name="new_username" placeholder="Username Baru" required />
    <input type="password" name="new_password" placeholder="Password Baru" required />
    <button type="submit">Daftar</button>
</form>
<p>Sudah punya akun? <a href="index.php">Login di sini</a></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/../includes/auth.php';
    require_once __DIR__ . '/../config/database.php';
    
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
</body>
</html>