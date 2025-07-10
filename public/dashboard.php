<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

require_once __DIR__ . '/../app/includes/auth.php';// Untuk fungsi getPurchaseHistory

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$purchaseHistory = getPurchaseHistory($userId);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Riwayat Pembelian</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="profile-picture">
    <img id="preview" src="<?php echo htmlspecialchars($userProfile['profile_pic_url'] ?? 'path/to/default-avatar.png'); ?>" alt="Foto Profil" />
</div>

<form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="profile_picture" id="profile_picture" required>
    <button type="submit" name="upload" class="btn-upload">Ganti Foto</button>
</form>

<label for="nama-pengguna">Nama Pengguna:</label>

    <div class="profile-card dashboard-card">
        <div class="profile-picture">
            <img id="preview" src="" alt="Foto Profil" />
        </div>
        <label for="nama-pengguna">Nama Pengguna:</label>
        <input type="text" id="nama-pengguna" value="<?php echo htmlspecialchars($username); ?>" disabled />
        <form action="logout.php" method="POST">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="history-card dashboard-card">
        <h2>Riwayat Pembelian</h2>

        <?php if (!empty($purchaseHistory)): ?>
            <?php foreach ($purchaseHistory as $entry): ?>
                <div class="history-entry">
                    <div class="date"><?php echo htmlspecialchars($entry['tanggal']); ?></div>
                    <ul>
                        </ul>
                    <div class="total">Total: <?php echo htmlspecialchars($entry['total']); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada riwayat pembelian.</p>
        <?php endif; ?>
    </div>

<script>
    // Contoh untuk mengganti src gambar profil (jika Anda memiliki URL gambar di DB)
    // const profilePicUrl = "<?php // echo htmlspecialchars($userProfile['profile_pic_url'] ?? 'default.jpg'); ?>";
    // document.getElementById('preview').src = profilePicUrl;
</script>

</body>
</html>