<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

require_once __DIR__ . '/../includes/auth.php';// Untuk fungsi getPurchaseHistory

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #d94f4f, #ffe8b3);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            padding: 20px;
            gap: 20px;
        }

        .container {
            /* Gaya awal container */
            background-color: #eab5a1;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 2px 2px 4px #222;
            border: 2px solid green;
            width: 300px;
            /* Tambahkan transisi untuk properti transform */
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            /* Gaya saat dihover: sedikit mengembang */
            transform: scale(1.05); /* Mengembang menjadi 105% dari ukuran semula */
            /* Properti lain tetap sama */
            background-color: #eab5a1;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 2px 2px 4px #222;
            border: 2px solid green;
            width: 300px;
        }

        .profile-picture {
            border: 2px solid green;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-bottom: 20px;
            position: relative;
            box-shadow: #222222 1px 1px 1px;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }

        input[type="file"],
        input[type="text"] {
            display: block;
            margin: 10px auto;
            width: 90%;
            padding: 8px;
        }

        button, input[type="button"] { /* Menambahkan input[type="button"] untuk logout */
            display: block;
            margin: 10px auto;
            width: 90%;
            padding: 10px;
            background-color: green;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #riwayat-container {
            background-color: #eab5a1;
            border-radius: 15px;
            padding: 20px;
            border: 2px solid green;
            box-shadow: 2px 2px 4px #222;
            width: 400px;
        }

        #riwayat-container:hover {
            background-color: #eab5a1;
            border-radius: 15px;
            padding: 20px;
            border: 2px solid green;
            box-shadow: 2px 2px 4px #222;
            width: 400px;
            transform: scale(1.05);
            transition: 0.3s ease-in-out;
        }

        b {
            color: darkgreen;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-picture">
        <img id="preview" src="" alt="Foto Profil" /> </div>
    <label><b>Nama Pengguna:</b></label>
    <input type="text" id="nama-pengguna" value="<?php echo htmlspecialchars($username); ?>" disabled />
    <form action="logout.php" method="POST"> <button type="submit">Logout</button>
    </form>
</div>

<div id="riwayat-container">
    <b>Riwayat Pembelian:</b><br/><br/>
    <?php if (!empty($purchaseHistory)): ?>
        <?php foreach ($purchaseHistory as $entry): ?>
            <b><?php echo htmlspecialchars($entry['tanggal']); ?></b><br/>
            <ul>
            <?php
            // Pastikan $entry['items'] adalah array sebelum melakukan iterasi
            if (is_array($entry['items'])) {
                foreach ($entry['items'] as $item) {
                    echo "<li>" . htmlspecialchars($item) . "</li>";
                }
            } else {
                // Jika bukan array, mungkin hanya satu item atau string
                echo "<li>" . htmlspecialchars($entry['items']) . "</li>";
            }
            ?>
            </ul>
            <b>Total: <?php echo htmlspecialchars($entry['total']); ?></b><br/><br/>
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