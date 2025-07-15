<?php
include 'koneksi.php';

// Ambil transaksi terbaru
$sql = "SELECT * FROM penjualan ORDER BY id_penjualan DESC LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Ambil detail item penjualan
$id_penjualan = $data['id_penjualan'];
$items = $conn->query("SELECT dp.*, p.nama_produk, p.harga 
                      FROM detail_penjualan dp
                      JOIN produk p ON dp.id_produk = p.id_produk
                      WHERE dp.id_penjualan = $id_penjualan");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  
  <meta charset="UTF-8">
  <title>Warkop Bejo</title>
<link rel="stylesheet" href="public/css/style_struck.css">
</head>
<body>

  <div class="info">
    <p><?= date('d/m/Y • H:i', strtotime($data['tanggal'])) ?><br>Kasir: <?= $data['kasir'] ?></p>
  </div>

  <h2>Warkop Bejo</h2>

  <div class="container">
    <!-- LEFT COLUMN -->
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
          <?php while($row = $items->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $row['nama_produk'] ?></td>
              <td><?= $row['qty'] ?></td>
              <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td><?= number_format($row['subtotal'], 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <div class="buttons">
        <button class="btn-blue">+ Tambah Produk</button>
      </div>
      <div class="buttons">
        <button class="btn-blue">Simpan & Cetak Struk</button>
        <button class="btn-gray">Reset Transaksi</button>
      </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="right">
      <h3>TOTAL BELANJA</h3>
      <div class="total-box">
        <p>Total: <?= number_format($data['total'], 0, ',', '.') ?></p>
        <p>Diskon: – <?= number_format($data['diskon'], 0, ',', '.') ?></p>
        <div class="green">Total: <?= number_format($data['total_bayar'], 0, ',', '.') ?></div>
        <p>Uang Tunai: <?= number_format($data['uang_tunai'], 0, ',', '.') ?></p>
        <div class="red">Kembalian: <?= number_format($data['kembalian'], 0, ',', '.') ?></div>
        <button class="btn-transaksi">Riwayat Transaksi</button>
      </div>
    </div>
  </div>

</body>
</html>
