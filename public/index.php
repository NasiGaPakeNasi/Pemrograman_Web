<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Warkop Bejo</title>
  <link rel="stylesheet" href="css/style.css" /> 
</head>
<body>
  <header>
    <div class="topbar">Warkop Bejo</div>
    <nav class="navbar">
      <div class="logo">WARKOP BEJO</div>
      <ul class="nav-links">
        <li><a href="home.html">Home</a></li>
        <li><a href="menu.html">Menu</a></li>
        <li><a href="keranjang.html">Keranjang</a></li>
        <li><a href="kontak.html">Kontak</a></li>
        <li><a href="login.php">Login</a></li>
      </ul>
    </nav>
  </header>

 <section class="hero">
  <div class="hero-overlay">
    <img src="images/Warkop Bejo 1.jpg" alt="Warkop Interior">
    <div class="hero-text">
      <h1>Selamat Datang di Warkop Bejo</h1>
      <p>Nongkrong Asik 24 Jam!</p>
    </div>
  </div>
</section>  

 <section class="menu-section">
  <h2>View The Menu</h2>
  <div class="menu-items">
    <?php include 'public/koneksi.php'; ?>
  </div>
</section>

  <section class="about-section">
    <h2>About Us</h2>
    <div class="about-content">
      <div class="about-text">
        <h4>Our Company</h4>
        <p>Rawrr.</p>
        <h4>Our Coffee</h4>
        <p>Rawrr.</p>
        <h4>Customer Service</h4>
        <p>Rawrr.</p>
      </div>
      <img src="images/gambar4.png" alt="About us image" />
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <h2>Contact Us</h2>
    <button>View More</button>
    <div class="contacts">
      <p>Hubungi Kami:</p>
      <ul>
        <li>📘 Warkop Bejo</li>
        <li>📱 WarkopBejoAja</li>
        <li>🎥 Warkop Bejo Official</li>
        <li>📷 @WarkopBejo</li>
      </ul>
    </div>
  </section>
  
  <script src="js/main.js"></script>
   <!-- Footer -->
  <footer class="footer">
    <p>© 2025 Warkop Bejo. Nongkrong Asik 24 Jam!</p>
  </footer>

</body>
</html>

