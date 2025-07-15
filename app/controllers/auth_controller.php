<?php
// app/controllers/auth_controller.php
// Controller ini menangani semua logika untuk registrasi dan login.

session_start();

// Memuat file konfigurasi dan fungsi yang diperlukan
require_once __DIR__ . '/../config/database.php';
require_once PROJECT_ROOT . '/app/includes/auth.php';

// --- Logika untuk Registrasi ---
if (isset($_POST['register'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    $registerResult = registerUser($username, $password);

    if ($registerResult === "success") {
        // Redirect ke halaman login dengan pesan sukses
        header("Location: " . BASE_URL . "public/login.php?status=reg_success");
        exit();
    } elseif ($registerResult === "exists") {
        // Redirect kembali ke halaman registrasi dengan pesan error
        header("Location: " . BASE_URL . "public/register.php?status=reg_exists");
        exit();
    } else {
        // Redirect kembali ke halaman registrasi dengan pesan error umum
        header("Location: " . BASE_URL . "public/register.php?status=reg_failed");
        exit();
    }
}

// --- Logika untuk Login ---
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $loginResult = loginUser($username, $password);

    if ($loginResult['status'] === 'success') {
        // Set session setelah login berhasil
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['is_admin'] = $loginResult['is_admin'];

        // Arahkan pengguna berdasarkan peran (admin atau user biasa)
        if ($_SESSION['is_admin'] == 1) {
            header("Location: " . BASE_URL . "admin/dashboard.php");
        } else {
            header("Location: " . BASE_URL . "public/dashboard.php");
        }
        exit();
    } else {
        // Jika login gagal, redirect kembali ke halaman login dengan pesan error
        header("Location: " . BASE_URL . "public/login.php?status=login_failed&message=" . urlencode($loginResult['message']));
        exit();
    }
}

// Jika tidak ada aksi yang cocok, redirect ke halaman utama
header("Location: " . BASE_URL . "public/index.php");
exit();
?>
