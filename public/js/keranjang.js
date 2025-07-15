// public/js/keranjang.js
// Logika JavaScript untuk halaman keranjang belanja (Versi Perbaikan Final)

// Mengambil semua elemen yang kita butuhkan dari halaman
const cartContainer = document.getElementById("cart-container");
const totalEl = document.getElementById("total");
const hiddenInputs = document.getElementById("hidden-inputs");
const notif = document.getElementById("notif");
const checkoutForm = document.getElementById("checkout-form");
// --- PERUBAHAN: Mengambil tombol checkout baru kita ---
const checkoutButton = document.getElementById("checkout-button-js");

let currentCart = {};

// Fungsi standar lainnya (tidak berubah)
function loadCartFromSession() {
    const storedCart = sessionStorage.getItem('currentCart');
    if (storedCart) {
        currentCart = JSON.parse(storedCart);
    }
    renderCart();
}

function tambahKeKeranjang() {
    const select = document.getElementById("menu");
    if (!select || !select.value) {
        showNotif("Pilih menu terlebih dahulu.", "error");
        return;
    }
    const parts = select.value.split("|");
    const img = parts[0], nama = parts[1], harga = parseFloat(parts[2]);
    if (!nama || isNaN(harga)) {
        showNotif("Data menu tidak valid.", "error");
        return;
    }
    let existingItemId = Object.keys(currentCart).find(id => currentCart[id].nama === nama);
    if (existingItemId) {
        currentCart[existingItemId].jumlah += 1;
    } else {
        const newId = Date.now().toString();
        currentCart[newId] = { img, nama, harga, jumlah: 1 };
    }
    sessionStorage.setItem('currentCart', JSON.stringify(currentCart));
    renderCart();
    showNotif(`"${nama}" ditambahkan!`, "success");
    select.selectedIndex = 0;
}

function renderCart() {
    cartContainer.innerHTML = "";
    let totalHarga = 0;
    if (Object.keys(currentCart).length === 0) {
        cartContainer.innerHTML = "<p style='text-align:center; color:#777; padding: 20px;'>Keranjang Anda kosong.</p>";
    } else {
        for (const id in currentCart) {
            const item = currentCart[id];
            const subtotal = item.harga * item.jumlah;
            totalHarga += subtotal;
            const itemDiv = document.createElement("div");
            itemDiv.className = "cart-item";
            const imgSrc = item.img && item.img !== 'null' ? htmlspecialchars(item.img) : 'https://placehold.co/90x90/E0E0E0/333333?text=NoImg';
            itemDiv.innerHTML = `
                <img src="${imgSrc}" alt="${htmlspecialchars(item.nama)}">
                <div class="item-details">
                    <h3>${htmlspecialchars(item.nama)}</h3><p>Harga: Rp${item.harga.toLocaleString('id-ID')}</p>
                    <label>Jumlah: <input type="number" value="${item.jumlah}" min="1" onchange="updateJumlah('${id}', this.value)"></label>
                    <p class="subtotal">Subtotal: Rp${subtotal.toLocaleString('id-ID')}</p>
                </div>
                <button type="button" class="remove-btn" onclick="hapusItem('${id}')">Hapus</button>`;
            cartContainer.appendChild(itemDiv);
        }
    }
    totalEl.textContent = "Total: Rp" + totalHarga.toLocaleString('id-ID');
}

function updateJumlah(id, newQuantity) {
    const qty = parseInt(newQuantity, 10);
    if (currentCart[id]) {
        currentCart[id].jumlah = (qty < 1 || isNaN(qty)) ? 1 : qty;
        sessionStorage.setItem('currentCart', JSON.stringify(currentCart));
        renderCart();
    }
}

function hapusItem(id) {
    if (currentCart[id]) {
        const itemName = currentCart[id].nama;
        delete currentCart[id];
        sessionStorage.setItem('currentCart', JSON.stringify(currentCart));
        renderCart();
        showNotif(`"${itemName}" dihapus.`);
    }
}

// --- PERBAIKAN FINAL FUNGSI CHECKOUT ---
// Kita tidak lagi memantau 'submit' pada form, tapi 'click' pada tombol
if (checkoutButton) {
    checkoutButton.addEventListener('click', function() {
        // 1. Validasi: Pastikan keranjang tidak kosong
        if (Object.keys(currentCart).length === 0) {
            showNotif("Keranjang masih kosong!", "error");
            return; // Hentikan proses
        }

        // 2. Isi data ke dalam input tersembunyi
        hiddenInputs.innerHTML = "";
        for (const id in currentCart) {
            const item = currentCart[id];
            hiddenInputs.innerHTML += `
                <input type="hidden" name="menu[]" value="${htmlspecialchars(item.nama)}">
                <input type="hidden" name="harga[]" value="${item.harga}">
                <input type="hidden" name="jumlah[]" value="${item.jumlah}">
            `;
        }

        // 3. Kirim form secara manual. Ini adalah cara paling andal.
        checkoutForm.submit();
    });
}

function showNotif(message, type = "success") {
    notif.textContent = message;
    notif.className = '';
    notif.classList.add(type);
    notif.style.display = "block";
    notif.style.opacity = "1";
    setTimeout(() => {
        notif.style.opacity = "0";
        setTimeout(() => { notif.style.display = "none"; }, 400);
    }, 2000);
}

function htmlspecialchars(str) {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(str || '').replace(/[&<>"']/g, m => map[m]);
}

document.addEventListener('DOMContentLoaded', loadCartFromSession);
