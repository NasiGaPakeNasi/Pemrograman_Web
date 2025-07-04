<?php
// Gabungan koneksi + query menu

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "warkop_db";

$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data menu
$query = "SELECT * FROM menu";
$result = mysqli_query($conn, $query);

// Tampilkan menu
while($row = mysqli_fetch_assoc($result)) {
  echo "<div class='item'>";
  echo "<img src='" . $row['gambar'] . "' alt='" . $row['nama_menu'] . "' style='width:100%; height:150px; object-fit:cover; border-radius:6px;'>";
  echo "<h3>" . $row['nama_menu'] . "</h3>";
  echo "<p>Harga: Rp " . $row['harga'] . "</p>";
  echo "<p>" . $row['deskripsi'] . "</p>";
  echo "</div>";
}

// Tutup koneksi
mysqli_close($conn);
?>
