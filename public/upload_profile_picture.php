<?php
session_start();
require_once __DIR__ . '/../app/config/database.php'; // Panggil koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['upload'])) {
    $userId = $_SESSION['user_id'];
    $file = $_FILES['profile_picture'];

    // Ambil informasi file
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    // Dapatkan ekstensi file (jpg, png, dll)
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // Tentukan tipe file yang diizinkan
    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            // Batasi ukuran file (misal: 5MB)
            if ($fileSize < 5000000) {
                // Buat nama file baru yang unik untuk menghindari penimpaan file
                $fileNameNew = "profile_" . $userId . "." . $fileActualExt;
                
                // Tentukan lokasi penyimpanan file
                $fileDestination = 'uploads/profile_pictures/' . $fileNameNew;

                // Pindahkan file dari lokasi sementara ke lokasi tujuan
                move_uploaded_file($fileTmpName, $fileDestination);

                // Simpan path file ke database
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $fileDestination, $userId);
                $stmt->execute();
                $stmt->close();

                // Redirect kembali ke dashboard
                header("Location: dashboard.php?upload=success");
                exit();
            } else {
                echo "Ukuran file terlalu besar! Maksimal 5MB.";
            }
        } else {
            echo "Terjadi error saat mengunggah file.";
        }
    } else {
        echo "Tipe file tidak diizinkan! Hanya JPG, JPEG, PNG, dan GIF.";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>