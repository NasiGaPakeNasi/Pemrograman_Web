<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Warkop Bejo</title>
  <style>
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
    }

    .menu-item:hover {
      background-color: #cde9d8;
      transform: scale(1.05);
      cursor: pointer;
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
      <button>Home</button>
      <button>Menu</button>
      <button>Keranjang</button>
      <button>Contact</button>
    </div>
  </div>

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
      <p>Belum ada item di keranjang.</p>
    </div>
  </div>

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
