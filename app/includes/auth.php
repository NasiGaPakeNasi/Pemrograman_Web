<?php
// includes/auth.php

require_once __DIR__ . '/../config/database.php'; // Panggil file koneksi database
require_once __DIR__ . '/functions.php'; // Panggil file fungsi umum

function registerUser($username, $password) {
    global $conn; // Mengakses variabel koneksi dari luar scope fungsi

    // Sanitize input
    $username = sanitizeInput($username);
    $password = sanitizeInput($password); // Password akan di-hash nanti

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        return "exists"; // Username sudah terdaftar
    }
    $stmt->close();

    // Hash password sebelum menyimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan user baru ke database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $stmt->close();
        return "success"; // Registrasi berhasil
    } else {
        $stmt->close();
        return "error"; // Gagal registrasi
    }
}

function loginUser($username, $password) {
    global $conn;

    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Ambil user dari database
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password yang di-hash
        if (password_verify($password, $user['password'])) {
            $stmt->close();
            return [
                'status' => 'success',
                'user_id' => $user['id'],
                'username' => $user['username']
            ];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Password salah.'];
        }
    } else {
        $stmt->close();
        return ['status' => 'error', 'message' => 'Username tidak ditemukan.'];
    }
}

// Fungsi untuk mengambil riwayat pembelian
function getPurchaseHistory($userId) {
    global $conn;

    $history = [];
    $stmt = $conn->prepare("SELECT tanggal, items, total FROM purchases WHERE user_id = ? ORDER BY tanggal DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Asumsikan 'items' disimpan sebagai JSON string di database
        $row['items'] = json_decode($row['items'], true);
        $history[] = $row;
    }
    $stmt->close();
    return $history;
}
?>