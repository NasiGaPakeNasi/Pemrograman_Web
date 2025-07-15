<?php
session_start();
require_once __DIR__ . '/../app/includes/auth.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php'; // Memuat model User baru

redirectToLoginIfNotAdmin();

$users = getAllUsers($conn);
$conn->close();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Kelola Pengguna | Admin Warkop Bejo</title>
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
            <h1>Kelola Pengguna</h1>
            <p>Lihat, edit, dan hapus data pengguna terdaftar.</p>
        </header>

        <?php if ($message): ?>
            <div class="message-container <?= $status ?>">
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>

        <section class="content-table">
            <h3>Daftar Pengguna</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Peran</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td>
                                    <span class="role <?= $user['is_admin'] ? 'admin' : 'user' ?>">
                                        <?= $user['is_admin'] ? 'Admin' : 'User' ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                    <!-- Mencegah admin menghapus diri sendiri -->
                                    <?php if ($_SESSION['user_id'] != $user['id']): ?>
                                        <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Tidak ada pengguna yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
