<?php
// public/js/update data.php (Direkomendasikan: Pindahkan ke app/controllers/api/cart_update.php)
// Skrip ini memproses pembaruan item di tabel 'keranjang'

// Sertakan file koneksi database pusat
require_once __DIR__ . '/../../config/database.php';
// Sertakan model Cart untuk fungsi update
require_once __DIR__ . '/../../models/Cart.php';
// Sertakan fungsi umum untuk sanitasi input
require_once __DIR__ . '/../../includes/functions.php';

// Pastikan ID item ada di URL
if (!isset($_GET['id'])) {
    die("ID item tidak ditemukan.");
}

$id = intval($_GET['id']); // Pastikan ID adalah integer

// Jika form disubmit (metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan validasi input dari form
    $menu = trim($_POST['menu']);
    $harga = intval($_POST['harga']);
    $jumlah = intval($_POST['jumlah']);

    // Pastikan input valid
    if (empty($menu) || $harga <= 0 || $jumlah <= 0) {
        $message = "Input tidak valid. Pastikan nama menu tidak kosong, harga dan jumlah lebih dari 0.";
        $status = "error";
    } else {
        // Panggil fungsi dari model Cart untuk memperbarui item
        // Fungsi updateCartItem sudah menggunakan Prepared Statements
        if (updateCartItem($conn, $id, $jumlah)) { // Asumsi updateCartItem hanya butuh ID dan jumlah
            // Jika Anda ingin memperbarui nama dan harga juga, sesuaikan model Cart dan panggilannya
            $message = "Data berhasil diupdate!";
            $status = "success";
        } else {
            $message = "Gagal update data.";
            $status = "error";
        }
    }
    // Tutup koneksi
    $conn->close();
    // Redirect kembali ke halaman read_data dengan notifikasi
    header("Location: read data.php?status=" . $status . "&message=" . urlencode($message));
    exit;
}

// Ambil data item yang akan diedit untuk mengisi form
$itemToEdit = getMenuById($conn, $id); // Asumsi getMenuById bisa digunakan untuk mengambil detail item keranjang juga

if (!$itemToEdit) {
    die("Data item tidak ditemukan!");
}

// Tutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Data Keranjang</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 400px; margin: 20px auto; }
    label { display: block; margin-bottom: 8px; font-weight: bold; }
    input[type="text"], input[type="number"] { width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
    input[type="submit"] { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    input[type="submit"]:hover { background-color: #0056b3; }
    .message { padding: 10px; margin-top: 20px; border-radius: 5px; }
    .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
  </style>
</head>
<body>

<h2>Edit Data ID <?= htmlspecialchars($itemToEdit['id']) ?></h2>

<?php
// Tampilkan pesan notifikasi jika ada
if (isset($_GET['message']) && isset($_GET['status'])) {
    echo '<div class="message ' . htmlspecialchars($_GET['status']) . '">' . htmlspecialchars($_GET['message']) . '</div>';
}
?>

<form method="post">
    <label for="menu">Menu:</label>
    <input type="text" id="menu" name="menu" value="<?= htmlspecialchars($itemToEdit['nama']) ?>" required><br><br>
    <label for="harga">Harga:</label>
    <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($itemToEdit['harga']) ?>" required><br><br>
    <label for="jumlah">Jumlah:</label>
    <input type="number" id="jumlah" name="jumlah" value="<?= htmlspecialchars($itemToEdit['jumlah']) ?>" required><br><br>
    <input type="submit" value="Simpan">
</form>

</body>
</html>
