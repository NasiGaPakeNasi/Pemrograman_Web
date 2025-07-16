<?php
// public/struk.php (Versi Adaptasi)
// Menggabungkan tampilan dari kode teman dengan keamanan dan struktur proyek saat ini.

session_start();

// --- Pengecekan Keamanan & Validasi ---
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?status=login_required");
    exit();
}
if (!isset($_GET['id_pembelian']) || !is_numeric($_GET['id_pembelian'])) {
    header("Location: dashboard.php?status=invalid_receipt_access");
    exit();
}

// --- Memuat Koneksi Database ---
// Menggunakan koneksi terpusat dan aman dari proyek Anda.
require_once __DIR__ . '/../app/config/database.php';

$id_pembelian = (int)$_GET['id_pembelian'];
$user_id = (int)$_SESSION['user_id'];
$data_pembelian = null;
$items_pembelian = [];

// --- Mengambil Data Header Transaksi ---
// Menggunakan Prepared Statement untuk keamanan.
// Mengambil data spesifik berdasarkan ID, bukan hanya yang terbaru.
$stmt_header = $conn->prepare(
    "SELECT tanggal, total, '0' as diskon, total as total_bayar, '0' as uang_tunai, '0' as kembalian, ? as kasir 
     FROM purchases 
     WHERE id = ? AND user_id = ?"
);
$username_kasir = $_SESSION['username'];
$stmt_header->bind_param("sii", $username_kasir, $id_pembelian, $user_id);
$stmt_header->execute();
$result_header = $stmt_header->get_result();
if ($result_header->num_rows === 1) {
    $data_pembelian = $result_header->fetch_assoc();
}
$stmt_header->close();

// Jika data tidak ditemukan, redirect.
if ($data_pembelian === null) {
    header("Location: dashboard.php?status=receipt_not_found");
    exit();
}

// --- Mengambil Data Detail Item ---
// Mengambil data dari kolom 'items' yang disimpan sebagai JSON.
$stmt_items = $conn->prepare("SELECT items FROM purchases WHERE id = ?");
$stmt_items->bind_param("i", $id_pembelian);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
$items_json = $result_items->fetch_assoc()['items'];
$items_pembelian = json_decode($items_json, true);
$stmt_items->close();

$conn->close();
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembelian - Warkop Bejo</title>
  <link rel="stylesheet" href="css/style_struk.css">
</head>
<body>

  <div class="info">
    <p><?= date('d/m/Y • H:i', strtotime($data_pembelian['tanggal'])) ?><br>Kasir: <?= htmlspecialchars($data_pembelian['kasir']) ?></p>
  </div>

  <h2>Warkop Bejo</h2>

  <div class="container">
    <div class="left">
      <h3>Riwayat Penjualan</h3>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Makanan</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($items_pembelian)): ?>
            <?php foreach($items_pembelian as $item): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                <td><?= htmlspecialchars($item['jumlah']) ?></td>
                <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div class="buttons">
        <a href="menu.php" class="btn-blue" style="text-decoration: none; padding: 10px; display: inline-block; text-align: center;">+ Tambah Transaksi Baru</a>
      </div>
      <div class="buttons">
        <button class="btn-blue" onclick="window.print()">Simpan & Cetak Struk</button>
        <a href="dashboard.php" class="btn-gray" style="text-decoration: none; padding: 10px; display: inline-block; text-align: center;">Riwayat Transaksi</a>
      </div>
    </div>

    <div class="right">
      <h3>TOTAL BELANJA</h3>
      <div class="total-box">
        <p>Total: <?= number_format($data_pembelian['total'], 0, ',', '.') ?></p>
        <p>Diskon: – <?= number_format($data_pembelian['diskon'], 0, ',', '.') ?></p>
        <div class="green">Total Bayar: <?= number_format($data_pembelian['total_bayar'], 0, ',', '.') ?></div>
        <a href="dashboard.php" class="btn-transaksi">Kembali ke Dashboard</a>
      </div>
    </div>
  </div>

</body>
</html>