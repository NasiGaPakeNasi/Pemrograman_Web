<?php
// public/js/read data.php (Direkomendasikan: Pindahkan ke app/controllers/api/cart_read.php)
// Skrip ini membaca data dari tabel 'keranjang' dan menampilkannya dalam format tabel HTML.
// Catatan: Jika keranjang dikelola sepenuhnya di sisi klien dengan sessionStorage/localStorage,
// file ini mungkin tidak lagi diperlukan atau perlu disesuaikan untuk tujuan lain.

// Sertakan file koneksi database pusat
require_once __DIR__ . '/../../config/database.php';
// Sertakan model Cart untuk mendapatkan item keranjang
require_once __DIR__ . '/../../models/Cart.php';

// Ambil semua item keranjang dari database menggunakan fungsi dari model
$cartItems = getAllCartItems($conn);

// Tutup koneksi database
$conn->close();
?>

<h2>Data Keranjang</h2>
<table border="1" cellpadding="10" cellspacing="0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Menu</th>
      <th>Harga</th>
      <th>Jumlah</th>
      <th>Subtotal</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($cartItems)): ?>
      <?php foreach ($cartItems as $row) : ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($row['jumlah']) ?></td>
          <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
          <td>
            <!-- Link ke halaman update dengan ID item -->
            <a href="update data.php?id=<?= htmlspecialchars($row['id']) ?>">Edit</a> |
            <!-- Link ke halaman delete dengan ID item -->
            <a href="delete data.php?id=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="6">Tidak ada item di keranjang.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
