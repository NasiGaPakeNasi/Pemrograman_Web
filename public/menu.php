<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warkop Bejo</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #ffffff;
      color: #333;
    }

    header {
      background-color: #7b4c4c;
      padding: 10px 20px;
      color: white;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .logo {
      font-weight: bold;
      font-size: 24px;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 15px;
      padding: 0;
    }

    .nav-links li a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 14px;
      border-radius: 6px;
      transition: background-color 0.2s ease;
    }

    .nav-links li a:hover {
      background-color: #5e3939;
    }

    .hero {
      position: relative;
      text-align: center;
    }

    .hero img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      filter: brightness(0.8);
    }

    .hero-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
    }

    .main {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      padding: 40px;
    }

    .menu-section {
      flex: 2;
    }

    .menu-title {
      font-size: 24px;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }

    .menu-item {
      background-color: #e0e0e0;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      font-weight: bold;
      box-shadow: 1px 1px 6px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .menu-item:hover {
      background-color: #cde9d8;
      transform: scale(1.05);
    }

    .cart {
      flex: 1;
      background-color: #f4f4f4;
      padding: 20px;
      border-radius: 12px;
      min-height: 300px;
      box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .cart strong {
      display: block;
      margin-bottom: 10px;
      font-size: 18px;
    }

    footer {
      background-color: #7b4c4c;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }

    @media (max-width: 768px) {
      .main {
        flex-direction: column;
        padding: 20px;
      }

      .nav-links {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
      }

      .hero img {
        height: 200px;
      }
    }
  </style>
</head>
<body>

  <!-- Header & Navbar -->
  <header>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">Menu</a></li>
        <li><a href="#">Keranjang</a></li>
        <li><a href="#">Kontak</a></li>
        <li><a href="#">Login</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero">
    <img src="images/Warkop Bejo 1.jpg" alt="Warkop Interior">
    <div class="hero-text">
      <h1>Selamat Datang di Warkop Bejo</h1>
      <p>Nongkrong Asik 24 Jam!</p>
    </div>
  </section>

  <!-- Menu & Cart -->
  <div class="main">
    <div class="menu-section">
      <div class="menu-title">Menu</div>
      <div class="menu-grid">
        <div class="menu-item">Kopi Susu<br>Rp 7.000</div>
        <div class="menu-item">Kopi<br>Rp 5.000</div>
        <div class="menu-item">Es Teh Manis<br>Rp 5.000</div>
        <div class="menu-item">Es Teh Tawar<br>Rp 4.000</div>
        <div class="menu-item">Es Jeruk<br>Rp 6.000</div>
        <div class="menu-item">Susu Jahe<br>Rp 8.000</div>
        <div class="menu-item">Air Mineral<br>Rp 4.000</div>
        <div class="menu-item">Nasi Goreng<br>Rp 15.000</div>
        <div class="menu-item">Nasi Uduk<br>Rp 12.000</div>
        <div class="menu-item">Nasi Ayam<br>Rp 15.000</div>
        <div class="menu-item">Nasi Telur<br>Rp 10.000</div>
        <div class="menu-item">Indomie<br>Rp 8.000</div>
        <div class="menu-item">Roti Bakar<br>Rp 12.000</div>
        <div class="menu-item">Pisang Bakar<br>Rp 12.000</div>
        <div class="menu-item">Kentang Goreng<br>Rp 15.000</div>
      </div>
    </div>

    <div class="cart">
      <strong>Keranjang Anda</strong>
      <p>Belum ada item di keranjang.</p>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>© 2025 Warkop Bejo. Nongkrong Asik 24 Jam!</p>
  </footer>

  <!-- Script -->
  <script>
    const cart = document.querySelector('.cart');
    const cartItems = {};

    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', () => {
        const text = item.innerHTML;
        const [nama, harga] = text.split('<br>');

        if (cartItems[nama]) {
          cartItems[nama].jumlah += 1;
        } else {
          cartItems[nama] = {
            harga: harga.trim(),
            jumlah: 1
          };
        }

        updateCart();
      });
    });

    function updateCart() {
      cart.innerHTML = '<strong>Keranjang Anda</strong><br><br>';
      const keys = Object.keys(cartItems);

      if (keys.length === 0) {
        cart.innerHTML += '<p>Belum ada item di keranjang.</p>';
        return;
      }

      const ul = document.createElement('ul');
      ul.style.paddingLeft = '20px';

      keys.forEach(nama => {
        const item = cartItems[nama];
        const li = document.createElement('li');
        li.textContent = `${nama} (${item.jumlah}x) - ${item.harga}`;
        ul.appendChild(li);
      });

      cart.appendChild(ul);
    }
  </script>
</body>
</html>
