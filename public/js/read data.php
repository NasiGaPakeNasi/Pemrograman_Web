<?php
include 'config.php';
$result = mysqli_query($conn, "SELECT * FROM keranjang ORDER BY id DESC");
?>

<h2>Data Keranjang</h2>
<table border="1" cellpadding="10" cellspacing="0">
  <tr>
    <th>ID</th>
    <th>Menu</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Subtotal</th>
    <th>Aksi</th>
  </tr>
  <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['menu'] ?></td>
      <td>Rp<?= number_format($row['harga']) ?></td>
      <td><?= $row['jumlah'] ?></td>
      <td>Rp<?= number_format($row['harga'] * $row['jumlah']) ?></td>
      <td>
        <a href="update.php?id=<?= $row['id'] ?>">Edit</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
