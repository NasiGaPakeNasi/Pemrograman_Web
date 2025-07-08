<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']); // pastikan ID adalah integer

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu = mysqli_real_escape_string($conn, $_POST['menu']);
    $harga = intval($_POST['harga']);
    $jumlah = intval($_POST['jumlah']);

    $update = "UPDATE keranjang SET menu='$menu', harga=$harga, jumlah=$jumlah WHERE id=$id";

    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='read_data.php';</script>";
    } else {
        echo "Gagal update data: " . mysqli_error($conn);
    }
    exit;
}

// Ambil data untuk form
$result = mysqli_query($conn, "SELECT * FROM keranjang WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data</title>
</head>
<body>

<h2>Edit Data ID <?= $data['id'] ?></h2>y

<form method="post">
    Menu: <input type="text" name="menu" value="<?= htmlspecialchars($data['menu']) ?>" required><br><br>
    Harga: <input type="number" name="harga" value="<?= $data['harga'] ?>" required><br><br>
    Jumlah: <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" required><br><br>
    <input type="submit" value="Simpan">
</form>

</body>
</html>
