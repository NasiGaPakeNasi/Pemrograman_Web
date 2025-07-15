<?php
// app/models/Menu.php
// Model untuk mengelola data menu

// Fungsi untuk mendapatkan semua item menu dari database
function getAllMenu($conn) {
    $menuItems = []; // Inisialisasi array kosong untuk menampung item menu
    // Query untuk memilih semua kolom dari tabel 'menu'
    $query = "SELECT id_menu, nama_menu, harga, deskripsi, gambar FROM menu ORDER BY nama_menu ASC";
    
    // Jalankan query menggunakan koneksi yang diberikan
    $result = $conn->query($query);

    // Periksa jika query berhasil dan ada baris data yang ditemukan
    if ($result && $result->num_rows > 0) {
        // Ambil setiap baris data sebagai array asosiatif dan tambahkan ke $menuItems
        while($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }
    }
    // Kembalikan array item menu
    return $menuItems;
}

// Fungsi untuk mendapatkan detail satu item menu berdasarkan ID
function getMenuById($conn, $id_menu) {
    // Gunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT id_menu, nama_menu, harga, deskripsi, gambar FROM menu WHERE id_menu = ?");
    $stmt->bind_param("i", $id_menu); // "i" menunjukkan bahwa parameter adalah integer
    $stmt->execute(); // Jalankan statement
    $result = $stmt->get_result(); // Dapatkan hasilnya

    // Kembalikan satu baris data sebagai array asosiatif, atau null jika tidak ditemukan
    return $result->fetch_assoc();
}

// Fungsi baru: Menambahkan item menu baru ke database
function addMenu($conn, $nama_menu, $harga, $deskripsi, $gambar) {
    // Gunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("INSERT INTO menu (nama_menu, harga, deskripsi, gambar) VALUES (?, ?, ?, ?)");
    // "sdss" berarti string (nama_menu), double/decimal (harga), string (deskripsi), string (gambar)
    $stmt->bind_param("sdss", $nama_menu, $harga, $deskripsi, $gambar);
    
    // Jalankan statement
    if ($stmt->execute()) {
        $stmt->close(); // Tutup statement setelah eksekusi
        return true; // Berhasil menambahkan menu
    } else {
        // Log error jika ada masalah saat eksekusi
        error_log("Error adding menu item: " . $stmt->error);
        $stmt->close();
        return false; // Gagal menambahkan menu
    }
}

// Fungsi baru: Memperbarui item menu yang sudah ada di database
function updateMenu($conn, $id_menu, $nama_menu, $harga, $deskripsi, $gambar_path = null) {
    // Bangun query UPDATE secara dinamis tergantung apakah ada gambar baru
    if ($gambar_path !== null) {
        $stmt = $conn->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ?, gambar = ? WHERE id_menu = ?");
        // "sdssi" berarti string, double/decimal, string, string, integer
        $stmt->bind_param("sdssi", $nama_menu, $harga, $deskripsi, $gambar_path, $id_menu);
    } else {
        $stmt = $conn->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ? WHERE id_menu = ?");
        // "sdsi" berarti string, double/decimal, string, integer
        $stmt->bind_param("sdsi", $nama_menu, $harga, $deskripsi, $id_menu);
    }
    
    // Jalankan statement
    if ($stmt->execute()) {
        $stmt->close();
        return true; // Berhasil memperbarui menu
    } else {
        error_log("Error updating menu item: " . $stmt->error);
        $stmt->close();
        return false; // Gagal memperbarui menu
    }
}

// Fungsi baru: Menghapus item menu dari database
function deleteMenu($conn, $id_menu) {
    // Gunakan Prepared Statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("DELETE FROM menu WHERE id_menu = ?");
    $stmt->bind_param("i", $id_menu); // "i" menunjukkan bahwa parameter adalah integer
    
    // Jalankan statement
    if ($stmt->execute()) {
        $stmt->close();
        return true; // Berhasil menghapus menu
    } else {
        error_log("Error deleting menu item: " . $stmt->error);
        $stmt->close();
        return false; // Gagal menghapus menu
    }
}

?>
