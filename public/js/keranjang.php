<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "warkop_bejo";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data['items'] as $item) {
    $nama = $conn->real_escape_string($item['nama']);
    $harga = (int)$item['harga'];
    $jumlah = (int)$item['jumlah'];
    $subtotal = (int)$item['subtotal'];

    $conn->query("INSERT INTO keranjang (nama, harga, jumlah, subtotal) VALUES ('$nama', $harga, $jumlah, $subtotal)");
}

echo json_encode(["status" => "sukses"]);

$conn->close();
?>