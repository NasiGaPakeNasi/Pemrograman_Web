<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete = "DELETE FROM keranjang WHERE id=$id";
    mysqli_query($conn, $delete);
}

header("Location: read_data.php");
exit;
?>