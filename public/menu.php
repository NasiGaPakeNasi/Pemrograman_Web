<?php
// public/menu.php
// Halaman daftar menu Warkop Bejo

session_start(); // Mulai sesi untuk navbar dinamis

// Sertakan file koneksi database pusat
// Path ini relatif dari public/menu.php ke app/config/database.php
// Di dalam database.php, konstanta PROJECT_ROOT akan didefinisikan.
require_once __DIR__ . '/../app/config/database.php'; 

// Sertakan model Menu menggunakan PROJECT_ROOT
// PROJECT_ROOT akan membantu memastikan path selalu benar dari mana pun file ini diakses
require_once PROJECT_ROOT . '/app/models/Menu.php';

// Ambil semua item menu dari database
$menuItems = getAllMenu($conn);

// Tutup koneksi database
$conn->close();

// Cek apakah pengguna sudah login (untuk menampilkan navbar dinamis)
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
$isAdmin = $isLoggedIn ? ($_SESSION['is_admin'] == 1) : false;

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu | Warkop Bejo</title>
  <link rel="stylesheet" href="css/style.css"> <!-- Menggunakan style.css untuk navbar dan gaya umum -->
  <style>
    /* Gaya CSS yang dioptimalkan untuk halaman menu */
    /* Beberapa gaya mungkin sudah ada di style.css, bisa dihapus duplikasinya di sini */
    body {
      margin: 0;
      padding-top: 20px; /* Sesuaikan padding-top agar tidak tertutup navbar */
      background: linear-gradient(#ffffff, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header lama yang tidak lagi relevan (jika ada) disembunyikan */
    .header {
        display: none;
    }

    .main {
      display: flex;
      margin-top: 30px;
      gap: 40px;
      flex-wrap: wrap;
      max-width: 1200px; /* Batasi lebar agar konsisten */
      margin-left: auto;
      margin-right: auto;
      padding: 0 20px; /* Padding di sisi */
    }

    .menu-section {
      flex: 2;
      min-width: 300px; /* Agar tidak terlalu kecil di layar sedang */
    }

    .menu-title {
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 28px; /* Ukuran font lebih besar */
      color: #004d40; /* Warna konsisten */
      text-align: center; /* Pusatkan judul */
      padding-bottom: 10px;
      border-bottom: 2px solid #eee; /* Garis bawah */
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Ukuran item lebih besar */
      grid-gap: 25px; /* Jarak antar item */
    }

    .menu-item {
      width: 100%;
      height: 180px; /* Tinggi item lebih besar */
      background-color: white; /* Latar belakang putih */
      border-radius: 12px;
      padding: 15px; /* Padding lebih besar */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Shadow lebih menonjol */
      transition: background-color 0.2s, transform 0.2s;
      cursor: pointer;
    }

    .menu-item:hover {
      background-color: #e0f2f1; /* Warna hover lebih cerah */
      transform: translateY(-5px); /* Sedikit naik */
    }
    
    .menu-item img { /* Tambahkan gaya untuk gambar jika ada di menu-item */
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .menu-item h3 { /* Gaya untuk nama menu */
        font-size: 1.1em;
        margin-bottom: 5px;
        color: #004d40;
    }

    .menu-item p { /* Gaya untuk harga */
        font-size: 0.9em;
        color: #666;
    }

    .cart {
      flex: 1;
      background-color: white; /* Latar belakang putih */
      border-radius: 12px;
      padding: 25px; /* Padding lebih besar */
      min-height: 300px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-top: 4px solid #004d40; /* Aksen hijau */
    }

    .cart strong {
        display: block;
        text-align: center;
        margin-bottom: 15px;
        font-size: 20px;
        color: #004d40;
    }

    .cart ul {
        list-style: none; /* Hapus bullet point */
        padding-left: 0;
        margin-bottom: 15px;
    }

    .cart li {
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
        font-size: 15px;
        color: #555;
    }

    .cart li:last-child {
        border-bottom: none;
    }

    .cart p {
        margin: 0;
    }

    .cart button {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 15px;
    }

    .cart button:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
        padding: 20px;
      }

      .admin-nav button { /* Perbaikan jika menggunakan admin-nav di menu.php */
        padding: 10px 20px;
      }

      .menu-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        grid-gap: 15px;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="topbar">Warkop Bejo</div>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="menu.php" class="active">Menu</a></li>
        <li><a href="keranjang.php">Keranjang</a></li>
        <?php if ($isLoggedIn): // Tampilkan link dashboard/logout jika sudah login ?>
            <?php if ($isAdmin): // Tampilkan link ke dashboard admin jika admin ?>
                <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
            <?php else: // Tampilkan link ke dashboard pengguna biasa jika bukan admin ?>
                <li><a href="dashboard.php">Dashboard</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout (<?php echo htmlspecialchars($username); ?>)</a></li>
        <?php else: // Tampilkan link login/register jika belum login ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <div class="main">
    <div class="menu-section">
      <div class="menu-title">Menu</div>
      <div class="menu-grid">
        <?php
        // Loop melalui item menu yang diambil dari database
        if (!empty($menuItems)) {
            foreach ($menuItems as $item) {
                // Tambahkan data-attribute untuk ID, nama, dan harga agar mudah diakses JavaScript
                // Menggunakan ID menu dari database untuk identifikasi yang lebih baik
                echo "<div class='menu-item' data-id='" . htmlspecialchars($item['id_menu']) . "' data-nama='" . htmlspecialchars($item['nama_menu']) . "' data-harga='" . htmlspecialchars($item['harga']) . "' data-gambar='" . htmlspecialchars($item['gambar']) . "'>";
                // Tampilkan gambar jika ada
                if (!empty($item['gambar'])) {
                    echo "<img src='" . htmlspecialchars($item['gambar']) . "' alt='" . htmlspecialchars($item['nama_menu']) . "'>";
                }
                echo "<h3>" . htmlspecialchars($item['nama_menu']) . "</h3>";
                echo "<p>Rp " . number_format($item['harga'], 0, ',', '.') . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Belum ada menu yang tersedia.</p>";
        }
        ?>
      </div>
    </div>
    <div class="cart">
      <strong>Keranjang Anda</strong><br><br>
      <p>Belum ada item di keranjang.</p>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>© 2025 Warkop Bejo. Nongkrong Asik 24 Jam!</p>
  </footer>

  <!-- Script -->
  <script>
    // Pindahkan logika JavaScript ke file terpisah jika kompleks
    // Untuk contoh ini, saya biarkan di sini dulu untuk demonstrasi
    const cart = document.querySelector('.cart');
    const cartItems = {};

    // Tambahkan event listener ke setiap item menu
    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', () => {
        const id = item.dataset.id;
        const nama = item.dataset.nama;
        const harga = parseFloat(item.dataset.harga);
        const gambar = item.dataset.gambar; // Ambil path gambar

        if (cartItems[id]) {
          cartItems[id].jumlah += 1;
        } else {
          cartItems[id] = {
            nama: nama,
            harga: harga,
            jumlah: 1,
            gambar: gambar // Simpan path gambar di keranjang
          };
        }

        updateCart();
      });
    });

    // Fungsi untuk memperbarui tampilan keranjang
    function updateCart() {
      cart.innerHTML = '<strong>Keranjang Anda</strong><br><br>';
      const keys = Object.keys(cartItems);

      if (keys.length === 0) {
        cart.innerHTML += '<p>Belum ada item di keranjang.</p>';
        return;
      }

      const ul = document.createElement('ul');
      ul.style.paddingLeft = '0'; // Hapus padding default ul

      keys.forEach(id => {
        const item = cartItems[id];
        const subtotal = item.harga * item.jumlah;
        // Tampilkan gambar kecil di item keranjang (opsional)
        // Perbaikan path gambar di sini: tambahkan "../public/" jika gambar disimpan relatif dari root proyek
        const itemImageSrc = item.gambar ? (item.gambar.startsWith('images/') ? '../public/' + item.gambar : item.gambar) : '';
        const itemImage = itemImageSrc ? `<img src="${itemImageSrc}" alt="${item.nama}" style="width:30px; height:30px; object-fit:cover; border-radius:3px; margin-right:10px;">` : '';
        
        li = document.createElement('li');
        li.innerHTML = `${itemImage} ${item.nama} (${item.jumlah}x) - Rp${subtotal.toLocaleString('id-ID')}`;
        li.style.display = 'flex';
        li.style.alignItems = 'center';
        ul.appendChild(li);
      });

      cart.appendChild(ul);
      
      const totalP = document.createElement('p');
      totalP.style.fontWeight = 'bold';
      totalP.textContent = `Total: Rp${Object.values(cartItems).reduce((sum, item) => sum + (item.harga * item.jumlah), 0).toLocaleString('id-ID')}`;
      cart.appendChild(totalP);

      const checkoutBtn = document.createElement('button');
      checkoutBtn.textContent = 'Lanjut ke Keranjang';
      checkoutBtn.style.marginTop = '10px';
      checkoutBtn.style.padding = '8px 15px';
      checkoutBtn.style.backgroundColor = '#28a745';
      checkoutBtn.style.color = 'white';
      checkoutBtn.style.border = 'none';
      checkoutBtn.style.borderRadius = '5px';
      checkoutBtn.style.cursor = 'pointer';
      checkoutBtn.addEventListener('click', () => {
        // Simpan data keranjang ke sessionStorage atau localStorage
        sessionStorage.setItem('currentCart', JSON.stringify(cartItems));
        // Redirect ke halaman keranjang
        window.location.href = 'keranjang.php';
      });
      cart.appendChild(checkoutBtn);
    }
  </script>
</body>
</html>
