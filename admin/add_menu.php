<?php
// admin/add_menu.php
// Halaman untuk menambahkan item menu baru oleh Administrator

session_start(); // Pastikan sesi dimulai

// Sertakan file auth untuk memeriksa status admin
require_once __DIR__ . '/../app/includes/auth.php';
// Sertakan file koneksi database pusat
require_once __DIR__ . '/../app/config/database.php';
// Sertakan model Menu untuk interaksi data menu
require_once __DIR__ . '/../app/models/Menu.php'; // Pastikan baris ini ada dan path-nya benar
// Sertakan fungsi umum untuk sanitasi input
require_once __DIR__ . '/../app/includes/functions.php';

// Lindungi halaman: hanya admin yang bisa mengakses
redirectToLoginIfNotAdmin();

$message = '';
$status = '';

// Proses form submission jika ada data POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan sanitasi input
    $nama_menu = sanitizeInput($_POST['nama_menu']);
    $harga = filter_var($_POST['harga'], FILTER_VALIDATE_FLOAT); // Validasi harga sebagai float
    $deskripsi = sanitizeInput($_POST['deskripsi']);
    $gambar = ''; // Inisialisasi variabel gambar

    // Validasi input dasar
    if (empty($nama_menu) || $harga === false || $harga <= 0 || empty($deskripsi)) {
        $message = "Semua kolom harus diisi dengan benar. Harga harus angka positif.";
        $status = "error";
    } else {
        // Penanganan upload gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../public/images/"; // Folder tempat menyimpan gambar
            // Dapatkan ekstensi file
            $imageFileType = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            // Buat nama file unik untuk menghindari konflik
            $newFileName = uniqid('menu_') . '.' . $imageFileType;
            $target_file = $target_dir . $newFileName;
            
            // Periksa tipe file
            $check = getimagesize($_FILES['gambar']['tmp_name']);
            if($check !== false) {
                // Pindahkan file yang diupload ke folder target
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                    $gambar = 'images/' . $newFileName; // Simpan path relatif ke database
                } else {
                    $message = "Maaf, terjadi kesalahan saat mengunggah gambar.";
                    $status = "error";
                }
            } else {
                $message = "File bukan gambar atau format tidak didukung.";
                $status = "error";
            }
        } else {
            // Jika tidak ada gambar diupload, atau ada error upload
            // Anda bisa set gambar default atau biarkan kosong
            $gambar = ''; // Atau 'images/default_menu.jpg'
        }

        // Hanya tambahkan menu jika tidak ada error upload gambar atau jika tidak ada gambar yang diupload
        if ($status !== "error") {
            // Panggil fungsi addMenu dari model
            if (addMenu($conn, $nama_menu, $harga, $deskripsi, $gambar)) {
                $message = "Menu baru berhasil ditambahkan!";
                $status = "success";
                // Redirect ke halaman manage_menu.php setelah sukses
                header("Location: manage_menu.php?status=" . $status . "&message=" . urlencode($message));
                exit();
            } else {
                $message = "Gagal menambahkan menu. Terjadi kesalahan database.";
                $status = "error";
            }
        }
    }
    // Tutup koneksi database
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tambah Menu Baru | Admin Warkop Bejo</title>
    <link rel="stylesheet" href="../public/css/dashboard.css"> <!-- Gunakan CSS yang sudah ada -->
    <style>
        /* Gaya tambahan khusus untuk form */
        body {
            padding: 20px;
            background-color: #f7f7f7;
            font-family: 'Poppins', sans-serif;
        }
        .admin-nav {
            background-color: #333;
            padding: 10px 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .admin-nav a:hover {
            background-color: #555;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #004d40;
        }
        h2 {
            text-align: center;
            color: #004d40;
            margin-bottom: 25px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: calc(100% - 22px); /* Sesuaikan dengan padding */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group textarea:focus,
        .form-group input[type="file"]:focus {
            outline: none;
            border-color: #00796b;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-submit, .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Untuk link */
            display: inline-block; /* Untuk link */
            text-align: center;
        }
        .btn-submit {
            background-color: #004d40;
            color: white;
        }
        .btn-submit:hover {
            background-color: #00695c;
        }
        .btn-cancel {
            background-color: #ccc;
            color: #333;
        }
        .btn-cancel:hover {
            background-color: #bbb;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="topbar">Admin Panel Warkop Bejo</div>
    <div class="admin-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="manage_menu.php">Kelola Menu</a>
        <a href="../public/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Tambah Menu Baru</h2>

        <?php if ($message): ?>
            <div class="message <?= $status ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form action="add_menu.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_menu">Nama Menu:</label>
                <input type="text" id="nama_menu" name="nama_menu" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="number" id="harga" name="harga" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar Menu:</label>
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <small>Format: JPG, JPEG, PNG, GIF</small>
            </div>
            <div class="button-group">
                <a href="manage_menu.php" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Tambah Menu</button>
            </div>
        </form>
    </div>

</body>
</html>
