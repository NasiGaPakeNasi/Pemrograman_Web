<?php
// admin/manage_menu.php
// Halaman untuk mengelola (CRUD) item menu oleh Administrator

session_start(); // Pastikan sesi dimulai

// Sertakan file auth untuk memeriksa status admin
require_once __DIR__ . '/../app/includes/auth.php';
// Sertakan file koneksi database pusat
require_once __DIR__ . '/../app/config/database.php';
// Sertakan model Menu untuk interaksi data menu
require_once __DIR__ . '/../app/models/Menu.php';

// Lindungi halaman: hanya admin yang bisa mengakses
redirectToLoginIfNotAdmin();

// Ambil semua item menu dari database
$menuItems = getAllMenu($conn);

// Tutup koneksi database
$conn->close();

// Inisialisasi pesan notifikasi
$message = '';
$status = '';

// Cek jika ada notifikasi dari operasi CRUD sebelumnya (misal: setelah tambah/edit/hapus)
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Kelola Menu | Admin Warkop Bejo</title>
    <link rel="stylesheet" href="../public/css/dashboard.css"> <!-- Gunakan CSS yang sudah ada -->
    <style>
        /* Gaya tambahan khusus admin untuk tabel dan tombol */
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
            max-width: 900px;
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
        .add-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }
        .add-button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: 600;
        }
        td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .actions .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .actions .edit-btn:hover {
            background-color: #0056b3;
        }
        .actions .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .actions .delete-btn:hover {
            background-color: #c82333;
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
        <h2>Kelola Menu</h2>

        <?php if ($message): ?>
            <div class="message <?= $status ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <a href="add_menu.php" class="add-button">Tambah Menu Baru</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menuItems)): ?>
                    <?php foreach ($menuItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id_menu']) ?></td>
                            <td>
                                <?php if (!empty($item['gambar'])): ?>
                                    <!-- Pastikan path gambar benar relatif dari manage_menu.php -->
                                    <img src="../public/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_menu']) ?>">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars(substr($item['deskripsi'], 0, 100)) . (strlen($item['deskripsi']) > 100 ? '...' : '') ?></td>
                            <td class="actions">
                                <a href="edit_menu.php?id=<?= htmlspecialchars($item['id_menu']) ?>" class="edit-btn">Edit</a>
                                <a href="delete_menu.php?id=<?= htmlspecialchars($item['id_menu']) ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Tidak ada menu yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
