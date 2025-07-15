// public/js/keranjang.js
// Logika JavaScript untuk halaman keranjang belanja

const cartContainer = document.getElementById("cart-container");
const totalEl = document.getElementById("total");
const hiddenInputs = document.getElementById("hidden-inputs");
const notif = document.getElementById("notif");
const checkoutForm = document.getElementById("checkout-form");

let currentCart = {}; // Objek untuk menyimpan item keranjang saat ini

// Fungsi untuk memuat keranjang dari sessionStorage saat halaman dimuat
function loadCartFromSession() {
    const storedCart = sessionStorage.getItem('currentCart');
    if (storedCart) {
        currentCart = JSON.parse(storedCart);
        renderCart(); // Render ulang tampilan keranjang
    }
}

// Fungsi untuk menambahkan item ke keranjang (jika ada fitur tambah dari halaman keranjang)
// Fungsi ini dipanggil oleh onclick="tambahKeKeranjang()" di tombol
function tambahKeKeranjang() {
    const select = document.getElementById("menu");
    // Pastikan select.value tidak kosong dan memiliki format yang diharapkan
    if (!select || !select.value) {
        console.error("Select element or its value is missing.");
        showNotif("Pilih menu terlebih dahulu.", "error");
        return;
    }
    const parts = select.value.split("|");
    // Pastikan ada setidaknya 3 bagian: gambar, nama, harga
    if (parts.length < 3) {
        console.error("Invalid menu item data format from select option:", select.value);
        showNotif("Format data menu tidak valid.", "error");
        return;
    }

    const img = parts[0];
    const nama = parts[1];
    const harga = parseFloat(parts[2]);
    
    // Validasi dasar untuk data yang diambil
    if (!nama || isNaN(harga)) { // Cek nama dan apakah harga adalah angka
        console.error("Invalid menu item data (name or price is missing/invalid).", {nama, harga});
        showNotif("Data menu tidak lengkap atau harga tidak valid.", "error");
        return;
    }

    const id = Date.now().toString(); // Gunakan timestamp sebagai ID unik sementara

    let existingItemId = null;
    for (const cartId in currentCart) {
        if (currentCart[cartId].nama === nama) { // Cek berdasarkan nama untuk menggabungkan item yang sama
            existingItemId = cartId;
            break;
        }
    }

    if (existingItemId) {
        currentCart[existingItemId].jumlah += 1;
    } else {
        currentCart[id] = {
            img: img,
            nama: nama,
            harga: harga,
            jumlah: 1
        };
    }
    
    sessionStorage.setItem('currentCart', JSON.stringify(currentCart)); // Simpan ke session
    renderCart(); // Perbarui tampilan
    showNotif(`"${nama}" berhasil ditambahkan ke keranjang!`, "success"); // Notifikasi sukses
}

// Fungsi untuk merender (menggambar ulang) item keranjang di halaman
function renderCart() {
    cartContainer.innerHTML = ""; // Bersihkan konten keranjang yang ada
    let totalHarga = 0;

    // Loop melalui setiap item di currentCart
    for (const id in currentCart) {
        if (currentCart.hasOwnProperty(id)) {
            const item = currentCart[id];
            
            // Validasi item sebelum merender
            if (!item || !item.nama || isNaN(item.harga) || !item.jumlah) {
                console.warn("Skipping invalid cart item:", item);
                continue; // Lewati item yang tidak valid
            }

            const subtotal = item.harga * item.jumlah;
            totalHarga += subtotal;

            const itemDiv = document.createElement("div");
            itemDiv.className = "cart-item";
            itemDiv.dataset.id = id; // Simpan ID untuk referensi

            // Pastikan item.img ada, jika tidak gunakan placeholder
            const imgSrc = item.img ? htmlspecialchars(item.img) : 'https://placehold.co/80x80/E0E0E0/333333?text=NoImg';

            itemDiv.innerHTML = `
                <img src="${imgSrc}" alt="${htmlspecialchars(item.nama)}">
                <div class="item-details">
                    <h3>${htmlspecialchars(item.nama)}</h3>
                    <p>Harga: Rp${item.harga.toLocaleString('id-ID')}</p>
                    <label>Jumlah:
                        <input type="number" value="${item.jumlah}" min="1" 
                               onchange="updateSubtotal('${id}', this.value)">
                    </label>
                    <p class="subtotal">Subtotal: Rp${subtotal.toLocaleString('id-ID')}</p>
                </div>
                <button type="button" class="remove-btn" onclick="hapusItem('${id}')">Hapus</button>
            `;
            cartContainer.appendChild(itemDiv);
        }
    }
    // Perbarui total harga di tampilan
    totalEl.textContent = "Total: Rp" + totalHarga.toLocaleString('id-ID');

    // Simpan total harga ke sessionStorage juga jika diperlukan untuk halaman checkout
    sessionStorage.setItem('totalHargaKeranjang', totalHarga);
}

// Fungsi untuk memperbarui subtotal item dan total keseluruhan saat jumlah diubah
function updateSubtotal(id, newQuantity) {
    const item = currentCart[id];
    if (item) {
        item.jumlah = parseInt(newQuantity);
        if (item.jumlah < 1) item.jumlah = 1; // Pastikan jumlah minimal 1
        sessionStorage.setItem('currentCart', JSON.stringify(currentCart)); // Simpan perubahan
        renderCart(); // Render ulang untuk memperbarui subtotal dan total
    }
}

// Fungsi untuk menghapus item dari keranjang
function hapusItem(id) {
    delete currentCart[id]; // Hapus item dari objek keranjang
    sessionStorage.setItem('currentCart', JSON.stringify(currentCart)); // Simpan perubahan
    renderCart(); // Render ulang tampilan
}

// Fungsi untuk menyiapkan data checkout sebelum form disubmit
if (checkoutForm) { // Pastikan form ada sebelum menambahkan event listener
    checkoutForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah form submit default

        if (Object.keys(currentCart).length === 0) {
            showNotif("Keranjang masih kosong.", "error");
            return false;
        }

        hiddenInputs.innerHTML = ""; // Bersihkan input tersembunyi sebelumnya
        let calculatedTotal = 0; // Hitung total lagi sebelum submit

        // Buat input tersembunyi untuk setiap item di keranjang
        for (const id in currentCart) {
            if (currentCart.hasOwnProperty(id)) {
                const item = currentCart[id];
                // Pastikan data item valid sebelum menambahkannya ke input tersembunyi
                if (!item || !item.nama || isNaN(item.harga) || isNaN(item.jumlah)) {
                    console.error("Invalid item data during checkout preparation:", item);
                    showNotif("Ada item yang tidak valid di keranjang. Mohon periksa kembali.", "error");
                    return false; // Hentikan proses checkout
                }

                hiddenInputs.innerHTML += `
                    <input type="hidden" name="menu[]" value="${htmlspecialchars(item.nama)}">
                    <input type="hidden" name="harga[]" value="${item.harga}">
                    <input type="hidden" name="jumlah[]" value="${item.jumlah}">
                `;
                calculatedTotal += item.harga * item.jumlah; // Tambahkan ke total
            }
        }
        
        // TAMBAHKAN INPUT TERSEMBUNYI UNTUK TOTAL BELANJA DI SINI
        hiddenInputs.innerHTML += `<input type="hidden" name="total_belanja" value="${calculatedTotal}">`;


        // Submit form secara manual setelah input tersembunyi ditambahkan
        checkoutForm.submit();
    });
}


// Fungsi untuk menampilkan notifikasi
function showNotif(message, type = "success") {
    notif.textContent = message;
    notif.className = ''; // Reset kelas
    notif.classList.add(type); // Tambahkan kelas tipe notifikasi
    notif.style.display = "block";
    notif.style.opacity = "1";

    setTimeout(() => {
        notif.style.opacity = "0";
        setTimeout(() => {
            notif.style.display = "none";
        }, 400);
    }, 5000);

    notif.onclick = () => notif.style.display = "none";
}

// Fungsi untuk membersihkan keranjang setelah checkout berhasil
function clearCart() {
    currentCart = {}; // Kosongkan objek keranjang
    sessionStorage.removeItem('currentCart'); // Hapus dari sessionStorage
    renderCart(); // Render ulang tampilan
}

// Helper function for HTML escaping (since we're using it in JS generated HTML)
function htmlspecialchars(str) {
    if (typeof str !== 'string' && !(str instanceof String)) {
        // Jika str bukan string, konversi ke string atau kembalikan string kosong
        console.warn("htmlspecialchars received non-string value:", str);
        return String(str || ''); // Konversi ke string, jika null/undefined jadi string kosong
    }
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return str.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Event listener saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    loadCartFromSession();

    // Cek parameter URL untuk notifikasi checkout
    const urlParams = new URLSearchParams(window.location.search);
    const checkoutStatus = urlParams.get('checkout_status');
    if (checkoutStatus === 'success') {
        showNotif("Pesanan telah berhasil dipesan. Silakan menunggu pesanan Anda ✅");
        clearCart(); // Bersihkan keranjang setelah sukses
    } else if (checkoutStatus === 'failed') {
        showNotif("Pesanan gagal diproses. Silakan coba lagi.", "error");
    }
});
