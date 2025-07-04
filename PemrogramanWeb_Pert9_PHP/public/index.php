<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form action="" method="POST"> 
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
</form>
<p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/../includes/auth.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginResult = loginUser($username, $password);

    if ($loginResult['status'] === 'success') {
        // Set session
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        // Redirect ke dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p style='color: red;'>" . $loginResult['message'] . "</p>";
    }
}
?>
</body>
</html>