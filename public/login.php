<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/auth.css">
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
    require_once __DIR__ . '/../app/includes/auth.php';

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