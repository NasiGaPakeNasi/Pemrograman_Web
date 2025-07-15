<?php
session_start();
require_once __DIR__ . '/../app/includes/auth.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php';

redirectToLoginIfNotAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_users.php?status=error&message=ID pengguna tidak valid.");
    exit();
}

$id = (int)$_GET['id'];
$user = getUserById($conn, $id);

if (!$user) {
    header("Location: manage_users.php?status=error&message=Pengguna tidak ditemukan.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Mencegah admin mengubah status dirinya sendiri menjadi user biasa
    if ($id === $_SESSION['user_id'] && $is_admin == 0) {
        $message = "Anda tidak dapat mengubah peran Anda sendiri menjadi user biasa.";
        $status = "error";
    } else {
        if (updateUser($conn, $id, $username, $is_admin)) {
            header("Location: manage_users.php?status=success&message=Data pengguna berhasil diperbarui.");
            exit();
        } else {
            $message = "Gagal memperbarui data pengguna.";
            $status = "error";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Pengguna | Admin Warkop Bejo</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">Warkop Bejo</div>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="manage_menu.php"><i class="fas fa-utensils"></i> <span>Kelola Menu</span></a></li>
            <li><a href="manage_users.php" class="active"><i class="fas fa-users"></i> <span>Kelola Pengguna</span></a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>

    <main class="main-content">
        <header class="main-header">
            <h1>Edit Pengguna</h1>
            <p>Perbarui detail untuk pengguna: <?= htmlspecialchars($user['username']) ?></p>
        </header>

        <section class="content-form">
            <?php if (!empty($message)): ?>
                <div class="message-container <?= $status ?>">
                    <p><?= $message ?></p>
                </div>
            <?php endif; ?>

            <form action="edit_user.php?id=<?= $id ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Peran (Role)</label>
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" <?= $user['is_admin'] ? 'checked' : '' ?>>
                        <label for="is_admin">Jadikan sebagai Admin</label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="manage_users.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
