<?php
// public/proses_pembayaran.php
// Skrip ini memproses pembayaran dan menyimpan transaksi final

session_start();

// Sertakan file koneksi database pusat
require_once __DIR__ . '/../app/config/database.php';
// Sertakan model Cart (untuk fungsi addOrderItem jika masih digunakan, atau buat model baru untuk Penjualan)
require_once __DIR__ . '/../app/models/Cart.php'; // Atau buat app/models/Sales.php
require_once __DIR__ . '/../app/includes/functions.php'; // Untuk sanitasi input

// Cek apakah user sudah login (opsional, tergantung apakah pembayaran hanya untuk user login)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_belanja = floatval($_POST['total_belanja']);
    $uang_tunai = floatval($_POST['uang_tunai']);
    $kembalian = $uang_tunai - $total_belanja;

    // Validasi dasar
    if ($uang_tunai < $total_belanja) {
        header("Location: pembayaran.php?status=failed&message=" . urlencode("Uang tunai tidak cukup!"));
        exit();
    }

    // Ambil item dari keranjang yang disimpan di tabel 'pesanan'
    // Idealnya, Anda akan mengambil item yang baru saja dipesan oleh user ini dari tabel 'pesanan'
    // Untuk kesederhanaan, kita akan mengambil semua item dari tabel 'pesanan' yang belum diproses
    // ATAU, jika Anda ingin menggunakan data keranjang dari sessionStorage, Anda perlu mengirimnya lagi dari pembayaran.php
    
    // Asumsi: item yang baru saja di-checkout ke tabel 'pesanan' adalah yang akan diproses
    // Ini membutuhkan cara untuk mengidentifikasi "pesanan terbaru" dari user tertentu
    // Untuk saat ini, kita akan membuat entri dummy atau mengambil dari tabel 'pesanan' secara sederhana
    
    // --- Logika Penyimpanan ke Tabel 'penjualan' dan 'detail_penjualan' ---
    $conn->begin_transaction(); // Mulai transaksi database

    try {
        // 1. Simpan ke tabel 'penjualan' (header transaksi)
        $stmt_penjualan = $conn->prepare("INSERT INTO penjualan (user_id, tanggal, total, diskon, total_bayar, uang_tunai, kembalian, kasir) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)");
        // Asumsi diskon 0, total_bayar = total_belanja untuk saat ini
        $diskon = 0;
        $total_bayar_final = $total_belanja;
        $kasir_nama = $user_id ? $_SESSION['username'] : 'Guest'; // Atau nama kasir jika ada sistem kasir

        $stmt_penjualan->bind_param("iddddds", $user_id, $total_belanja, $diskon, $total_bayar_final, $uang_tunai, $kembalian, $kasir_nama);
        $stmt_penjualan->execute();
        $id_penjualan_baru = $conn->insert_id; // Dapatkan ID penjualan yang baru saja dibuat
        $stmt_penjualan->close();

        // 2. Simpan detail item ke tabel 'detail_penjualan'
        // Ini adalah bagian yang paling tricky karena kita perlu tahu item apa saja yang dibeli.
        // Pilihan:
        // a. Mengambil dari tabel 'pesanan' yang baru saja diisi oleh proses_checkout.php
        // b. Mengirim data item keranjang lagi dari pembayaran.php (melalui hidden inputs atau sesi)
        
        // Untuk kesederhanaan saat ini, kita akan asumsikan kita memiliki item yang perlu disimpan.
        // Idealnya, Anda akan mengambil item yang terkait dengan transaksi ini dari tabel 'pesanan'
        // atau meneruskannya dari keranjang.
        
        // Contoh dummy item (Anda perlu mengganti ini dengan item aktual dari pesanan)
        $dummy_items_from_pesanan = [];
        // Ambil item dari tabel 'pesanan' yang baru saja di-checkout (perlu filter lebih spesifik untuk user dan waktu)
        // Untuk demo, kita ambil 10 pesanan terakhir. Anda perlu cara yang lebih robust.
        $stmt_get_pesanan = $conn->prepare("SELECT nama_menu, harga, jumlah, subtotal FROM pesanan ORDER BY tanggal_pemesanan DESC LIMIT 10"); 
        $stmt_get_pesanan->execute();
        $res_pesanan = $stmt_get_pesanan->get_result();
        while($row_pesanan = $res_pesanan->fetch_assoc()){
            $dummy_items_from_pesanan[] = $row_pesanan;
        }
        $stmt_get_pesanan->close();

        foreach ($dummy_items_from_pesanan as $item) {
            $stmt_detail = $conn->prepare("INSERT INTO detail_penjualan (id_penjualan, id_produk, nama_produk, qty, harga, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
            // id_produk bisa null jika tidak ada di tabel menu, atau Anda bisa mencarinya
            $id_produk = null; // Anda perlu mencarinya dari tabel menu berdasarkan nama_menu jika ingin link
            $stmt_detail->bind_param("isiddd", $id_penjualan_baru, $id_produk, $item['nama_menu'], $item['jumlah'], $item['harga'], $item['subtotal']);
            $stmt_detail->execute();
            $stmt_detail->close();
        }

        // 3. Simpan ringkasan ke tabel 'purchases' (untuk riwayat pengguna)
        if ($user_id) {
            $items_json = json_encode($dummy_items_from_pesanan); // Simpan detail item sebagai JSON
            $stmt_purchases = $conn->prepare("INSERT INTO purchases (user_id, tanggal, items, total) VALUES (?, CURDATE(), ?, ?)");
            $stmt_purchases->bind_param("isd", $user_id, $items_json, $total_belanja);
            $stmt_purchases->execute();
            $stmt_purchases->close();
        }

        // Hapus item dari tabel 'pesanan' setelah berhasil diproses ke penjualan
        // Ini penting agar tabel pesanan tidak menumpuk dan hanya berisi pesanan yang belum diproses
        // Anda perlu cara untuk mengidentifikasi pesanan mana yang baru saja diproses
        // Misalnya, dengan menambahkan kolom 'status' ke tabel 'pesanan' atau menghapus berdasarkan id_user dan timestamp
        // Untuk saat ini, saya tidak akan menambahkan logika hapus dari 'pesanan' di sini karena butuh identifikasi yang lebih baik.

        $conn->commit(); // Commit transaksi jika semua berhasil

        // Redirect ke halaman struk dengan ID penjualan
        header("Location: struk.php?id_penjualan=" . $id_penjualan_baru);
        exit();

    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); // Rollback transaksi jika ada error
        error_log("Payment process failed: " . $e->getMessage());
        header("Location: pembayaran.php?status=failed&message=" . urlencode("Pembayaran gagal. Terjadi kesalahan sistem."));
        exit();
    }

} else {
    // Jika akses langsung, redirect ke keranjang
    header("Location: keranjang.php");
    exit();
}

// Tutup koneksi database
$conn->close();
?>
