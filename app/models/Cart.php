<?php
// app/models/Cart.php
// Model untuk mengelola data keranjang dan pesanan

// Fungsi untuk menambahkan item pesanan ke database
// Menggunakan Prepared Statements untuk keamanan
function addOrderItem($conn, $nama_menu, $harga, $jumlah, $subtotal) {
    // Persiapkan query INSERT
    $stmt = $conn->prepare("INSERT INTO pesanan (nama_menu, harga, jumlah, subtotal) VALUES (?, ?, ?, ?)");
    
    // Bind parameter ke placeholder (?)
    // "siii" berarti string, integer, integer, integer
    $stmt->bind_param("siii", $nama_menu, $harga, $jumlah, $subtotal);
    
    // Jalankan statement
    if ($stmt->execute()) {
        $stmt->close(); // Tutup statement setelah eksekusi
        return true; // Berhasil menambahkan item
    } else {
        // Log error jika ada masalah saat eksekusi
        error_log("Error adding order item: " . $stmt->error);
        $stmt->close();
        return false; // Gagal menambahkan item
    }
}

// Fungsi untuk mendapatkan semua item dari keranjang (jika ada tabel keranjang yang terpisah)
// Contoh ini mengasumsikan tabel 'keranjang' ada dan digunakan untuk menyimpan item sementara
function getAllCartItems($conn) {
    $cartItems = [];
    $query = "SELECT id, nama, harga, jumlah, subtotal FROM keranjang ORDER BY id ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }
    return $cartItems;
}

// Fungsi untuk memperbarui jumlah item di keranjang
function updateCartItem($conn, $id, $jumlah) {
    $stmt = $conn->prepare("UPDATE keranjang SET jumlah = ?, subtotal = harga * ? WHERE id = ?");
    $stmt->bind_param("iii", $jumlah, $jumlah, $id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Error updating cart item: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

// Fungsi untuk menghapus item dari keranjang
function deleteCartItem($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Error deleting cart item: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

// Anda bisa menambahkan fungsi lain di sini, seperti:
// - getOrderHistory($conn, $userId) // Untuk riwayat pesanan pengguna

?>
