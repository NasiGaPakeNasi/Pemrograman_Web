<?php
// public/menu.php
// Halaman daftar menu Warkop Bejo

// Sertakan file koneksi database pusat
require_once __DIR__ . '/../app/config/database.php';
// Sertakan model Menu untuk mengambil data menu
require_once __DIR__ . '/../app/models/Menu.php';

// Ambil semua item menu dari database
$menuItems = getAllMenu($conn);

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Menu | Warkop Bejo</title>
  <style>
    /* Gaya CSS yang sudah ada, dipindahkan dari inline style */
    body {
      margin: 0;
      padding: 60px;
      background: linear-gradient(#ffffff, #ffffff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .logo {
      background-color: #d14343;
      color: white;
      padding: 20px 40px;
      font-weight: bold;
      font-size: 24px;
      border-radius: 8px;
      box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .nav {
      display: flex;
      gap: 20px;
    }

    .nav button {
      background-color: #7b4c4c;
      color: white;
      border: none;
      padding: 15px 30px;
      cursor: pointer;
      font-weight: bold;
      border-radius: 12px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .nav button:hover {
      background-color: #5e3939;
      transform: scale(1.05);
    }

    .main {
      display: flex;
      margin-top: 30px;
      gap: 40px;
      flex-wrap: wrap;
    }

    .menu-section {
      flex: 2;
    }

    .menu-title {
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 22px;
      color: #4b2e2e;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      grid-gap: 20px;
    }

    .menu-item {
      width: 100%;
      height: 150px;
      background-color: #e0e0e0;
      border-radius: 12px;
      padding: 8px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      text-align: center;
      box-shadow: 1px 1px 6px rgba(0, 0, 0, 0.1);
      transition: background-color 0.2s, transform 0.2s;
      /* Tambahkan cursor pointer untuk menunjukkan bisa diklik */
      cursor: pointer;
    }

    .menu-item:hover {
      background-color: #cde9d8;
      transform: scale(1.05);
    }

    .cart {
      flex: 1;
      background-color: #d3d3d3;
      border-radius: 12px;
      padding: 20px;
      min-height: 300px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .cart::before {
      content: "Keranjang Anda";
      font-weight: bold;
      display: block;
      margin-bottom: 10px;
      font-size: 18px;
      color: #333;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
      }

      .nav {
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
      }

      .menu-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
      }

      .nav button {
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="logo">Warkop Bejo</div>
    <div class="nav">
      <!-- Pastikan link ini mengarah ke file yang benar -->
      <button onclick="location.href='index.php'">Home</button>
      <button onclick="location.href='menu.php'">Menu</button>
      <button onclick="location.href='keranjang.php'">Keranjang</button>
      <button onclick="location.href='#'">Contact</button>
    </div>
  </div>

  <div class="main">
    <div class="menu-section">
      <div class="menu-title">Menu</div>
      <div class="menu-grid">
        <?php
        // Loop melalui item menu yang diambil dari database
        if (!empty($menuItems)) {
            foreach ($menuItems as $item) {
                // Tambahkan data-attribute untuk ID, nama, dan harga agar mudah diakses JavaScript
                echo "<div class='menu-item' data-id='" . htmlspecialchars($item['id_menu']) . "' data-nama='" . htmlspecialchars($item['nama_menu']) . "' data-harga='" . htmlspecialchars($item['harga']) . "'>";
                echo htmlspecialchars($item['nama_menu']) . "<br>Rp " . number_format($item['harga'], 0, ',', '.');
                echo "</div>";
            }
        } else {
            echo "<p>Belum ada menu yang tersedia.</p>";
        }
        ?>
      </div>
    </div>
    <div class="cart">
      <p>Belum ada item di keranjang.</p>
    </div>
  </div>

  <script>
    // Pindahkan logika JavaScript ke file terpisah jika kompleks
    // Untuk contoh ini, saya biarkan di sini dulu untuk demonstrasi
    const cart = document.querySelector('.cart');
    const cartItems = {}; // Objek untuk menyimpan item di keranjang

    // Tambahkan event listener ke setiap item menu
    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', () => {
        // Ambil data dari data-attribute
        const id = item.dataset.id;
        const nama = item.dataset.nama;
        const harga = parseFloat(item.dataset.harga); // Pastikan harga adalah angka

        // Jika item sudah ada di keranjang, tingkatkan jumlahnya
        if (cartItems[id]) {
          cartItems[id].jumlah += 1;
        } else {
          // Jika belum ada, tambahkan item baru ke keranjang
          cartItems[id] = {
            nama: nama,
            harga: harga,
            jumlah: 1
          };
        }

        updateCart(); // Perbarui tampilan keranjang
      });
    });

    // Fungsi untuk memperbarui tampilan keranjang
    function updateCart() {
      cart.innerHTML = '<strong>Keranjang Anda</strong><br><br>'; // Reset konten keranjang
      const keys = Object.keys(cartItems); // Dapatkan ID item di keranjang

      if (keys.length === 0) {
        cart.innerHTML += '<p>Belum ada item di keranjang.</p>'; // Jika keranjang kosong
        return;
      }

      const ul = document.createElement('ul'); // Buat elemen unordered list
      ul.style.paddingLeft = '20px';

      let totalKeranjang = 0; // Hitung total harga keranjang

      // Loop melalui item di keranjang dan tambahkan ke daftar
      keys.forEach(id => {
        const item = cartItems[id];
        const li = document.createElement('li');
        const subtotal = item.harga * item.jumlah;
        li.textContent = `${item.nama} (${item.jumlah}x) - Rp${subtotal.toLocaleString('id-ID')}`;
        ul.appendChild(li);
        totalKeranjang += subtotal;
      });

      cart.appendChild(ul); // Tambahkan daftar ke keranjang
      
      // Tampilkan total harga
      const totalP = document.createElement('p');
      totalP.style.fontWeight = 'bold';
      totalP.textContent = `Total: Rp${totalKeranjang.toLocaleString('id-ID')}`;
      cart.appendChild(totalP);

      // Tambahkan tombol checkout atau link ke halaman keranjang
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
