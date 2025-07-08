<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    $menu = $_POST['menu'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    for ($i = 0; $i < count($menu); $i++) {
        $nama = $conn->real_escape_string($menu[$i]);
        $h = (int)$harga[$i];
        $j = (int)$jumlah[$i];
        $subtotal = $h * $j;

        $sql = "INSERT INTO pesanan (nama_menu, harga, jumlah, subtotal) 
                VALUES ('$nama', $h, $j, $subtotal)";
        $conn->query($sql);
    }

    // Redirect kembali ke halaman utama dengan notifikasi sukses
    header("Location: keranjang.html?success=1");
    exit;
} else {
    header("Location: keranjang.html");
    exit;
}
?>
