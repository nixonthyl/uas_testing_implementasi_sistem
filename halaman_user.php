<?php 

session_start();

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Variabel untuk memicu pop-up JavaScript
$show_popup = false;
$last_item = "";

// Proses tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $item = $_POST['item_name'];
    $price = $_POST['item_price'];

    if (isset($_SESSION['cart'][$item])) {
        $_SESSION['cart'][$item]['qty'] += 1;
    } else {
        $_SESSION['cart'][$item] = [
            'price' => $price,
            'qty' => 1
        ];
    }
    // Set variabel untuk notifikasi
    $show_popup = true;
    $last_item = $item;
}

// Hitung total item unik di keranjang untuk angka badge
$total_item_keranjang = count($_SESSION['cart']);
?>

<?php

// Redirect ke login jika belum punya token (mencegah akses tanpa login)
if (empty($_SESSION['jwt_token'])) {
    header('Location: login.php');
    exit;
}

$jwt      = $_SESSION['jwt_token'];
$username = $_SESSION['username'] ?? '';
$level    = $_SESSION['level']    ?? 'user';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman User</title>
    <link rel="stylesheet" href="css/user_page.css">
    <style>
        /* Tambahan style untuk angka di keranjang agar terlihat jelas */
        .cart-count {
            background: red;
            color: white;
            padding: 2px 8px;
            border-radius: 50%;
            font-size: 14px;
            margin-left: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body data-jwt="<?= htmlspecialchars($jwt) ?>" 
      data-username="<?= htmlspecialchars($username) ?>" 
      data-level="<?= htmlspecialchars($level) ?>">

<header class="hero-header">
  <div class="overlay">
    <div class="hero-content" style="text-align: center;">
      <h1>Selamat Datang di Mangan Uenak</h1>
      <p>Pesan makanan favoritmu secara mudah dari rumah</p>
    </div>
  </div>
</header>

<h2>Selamat datang <?php echo htmlspecialchars($username); ?></h2>
<a href="logout.php" style="position: absolute; top: 20px; right: 30px; padding: 10px 20px; background-color: #dc3545; color: white; border-radius: 5px; text-decoration: none;">
    Logout
</a>

<div style="margin-bottom: 40px;">
    <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px;">Daftar Menu</h3>
    <div id="menu-container" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start;">
        <p id="menu-loading">Memuat menu restoran...</p>
    </div>
</div>

<div class="cart-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 50px;">
    <h3 style="margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px;">Keranjang Pesanan</h3>
    
    <div id="cart-container">
        <p>Memuat keranjang...</p>
    </div>
    
    <div id="cart-total" style="font-weight:bold; font-size:18px; margin-top:15px; text-align:right;"></div>
    
    <div id="checkout-section" style="display:none; margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px;">
        <label for="select-metode" style="font-weight:bold; font-size:14px; display:block; margin-bottom:8px;">Metode Pembayaran:</label>
        
        <select id="select-metode" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #ccc; font-size:14px;">
            <option value="">-- Pilih Metode --</option>
            <option value="1">QRIS</option>
            <option value="2">Kartu Debit</option>
            <option value="3">E-Wallet</option>
        </select>

        <button id="btn-confirm-checkout" onclick="doCheckout()" style="width:100%; padding:12px; font-weight:bold; cursor:pointer; background-color: #1D9E75; color: white; border: none; border-radius: 5px; font-size:16px;">
            Konfirmasi Checkout
        </button>
    </div>
</div>

<?php if ($show_popup): ?>
<script>
    alert("Berhasil! <?php echo $last_item; ?> telah masuk ke keranjang.");
</script>
<?php endif; ?>

<script src="assets/js/api_helper.js"></script>
<script>
async function loadMenu(jenis = null) {
    document.getElementById('menu-loading').style.display = 'block';

    // Kode baru yang memaksa API mengirim 50 data:
    const endpoint = jenis ? `/menu?jenis=${jenis}&limit=50` : '/menu?limit=50'; 
    const result = await apiRequest(endpoint, 'GET');

    if (!result || !result.ok) {
        document.getElementById('menu-container').innerHTML =
            '<p style="color:red;">Gagal memuat menu. Coba refresh halaman.</p>';
        return;
    }

    const items = result.data.data.items;
    
    // Looping data API dan terjemahkan ke bentuk HTML aslimu
    const html = items.map(item => {
        // Logika penarikan gambar yang sama persis dengan PHP aslimu!
        const namaFile = item.nama_menu.toLowerCase() + ".jpg";
        const folder = (item.jenis.toLowerCase() === 'minuman') ? 'minuman' : 'makanan';
        const pathFoto = `img/${folder}/${namaFile}`;

        return `
            <div class="product-card">
                <img src="${pathFoto}" alt="${item.nama_menu}" onerror="this.src='img/default.jpg'">
                <h3>${item.nama_menu}</h3>
                <p>Harga: Rp ${Number(item.harga_porsi).toLocaleString('id-ID')}</p>
                
                <button type="button" class="add-to-cart" onclick="addToCart(${item.id_menu}, '${item.nama_menu}')">
                    Tambah
                </button>
            </div>
        `;
    }).join('');

    document.getElementById('menu-container').innerHTML = html || '<p>Tidak ada menu tersedia.</p>';
}

async function addToCart(idMenu, namaMenu) {
    const result = await apiRequest('/cart', 'POST', {
        id_menu: idMenu,
        qty: 1
    });

    if (result && result.ok) {
        showToast(`${namaMenu} berhasil ditambahkan ke keranjang!`, 'success');
        
        if (typeof loadCart === 'function') {
            loadCart(); 
        }
    } else if (result) {
        showToast(result.data.message || 'Gagal menambah ke keranjang.', 'error');
    }
}

// --- FUNGSI KERANJANG BELANJA ---

async function loadCart() {
    // Memanggil API Keranjang (URL sudah disesuaikan)
    const result = await apiRequest('/cart', 'GET');

    if (!result || !result.ok) {
        document.getElementById('cart-container').innerHTML = '<p style="color:red;">Gagal memuat keranjang.</p>';
        return;
    }

    const { items, grand_total } = result.data.data;

    // Jika keranjang kosong
    if (items.length === 0) {
        document.getElementById('cart-container').innerHTML = '<p style="color:#666; font-style:italic;">Keranjang masih kosong.</p>';
        document.getElementById('checkout-section').style.display = 'none';
        document.getElementById('cart-total').textContent = '';
        return;
    }

    // Looping item keranjang menjadi HTML
    const html = items.map(item => `
        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eee;">
            <div style="flex:1; font-weight:bold; font-size:14px;">${item.nama_menu}</div>
            
            <div style="display:flex; align-items:center; gap:8px;">
                <button onclick="updateQty(${item.id_cart}, ${item.qty - 1})" style="padding:2px 8px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; border-radius:4px;">-</button>
                <span style="font-weight:bold; min-width:20px; text-align:center;">${item.qty}</span>
                <button onclick="updateQty(${item.id_cart}, ${item.qty + 1})" style="padding:2px 8px; cursor:pointer; background:#f0f0f0; border:1px solid #ccc; border-radius:4px;">+</button>
            </div>
            
            <div style="min-width:90px; text-align:right; font-size:14px;">
                Rp ${Number(item.subtotal).toLocaleString('id-ID')}
            </div>
            
            <button onclick="removeFromCart(${item.id_cart})" style="color:#D85A30; background:none; border:none; cursor:pointer; font-weight:bold; margin-left:10px;">X</button>
        </div>
    `).join('');

    document.getElementById('cart-container').innerHTML = html;
    document.getElementById('cart-total').textContent = 'Total: Rp ' + Number(grand_total).toLocaleString('id-ID');
    document.getElementById('checkout-section').style.display = 'block'; // Tampilkan tombol checkout
}

async function updateQty(idCart, newQty) {
    // TC_API_02: BVA — qty 0 berarti hapus, bukan update
    if (newQty <= 0) {
        removeFromCart(idCart);
        return;
    }
    if (newQty > 99) {
        showToast('Jumlah maksimal 99 porsi.', 'error');
        return;
    }

    // Update via API (URL sudah disesuaikan)
    const result = await apiRequest('/cart', 'PUT', {
        id_cart: idCart,
        qty: newQty
    });

    if (result && result.ok) {
        loadCart(); // Refresh otomatis tanpa kedip
    }
}

async function removeFromCart(idCart) {
    // Hapus via API (URL sudah disesuaikan)
    const result = await apiRequest(`/cart?id=${idCart}`, 'DELETE');
    
    if (result && result.ok) {
        showToast('Item dihapus dari keranjang.', 'info');
        loadCart(); // Refresh otomatis tanpa kedip
    }
}

// --- FUNGSI CHECKOUT (MODIFIKASI 5) ---
async function doCheckout() {
    const idMetode = document.getElementById('select-metode').value;
    const btn      = document.getElementById('btn-confirm-checkout');

    // TC_API_07: validasi field wajib di sisi client
    if (!idMetode) {
        showToast('Pilih metode pembayaran terlebih dahulu.', 'error');
        return;
    }

    // Nonaktifkan tombol saat proses
    btn.disabled    = true;
    btn.textContent = 'Memproses...';

    // Panggil API (URL disesuaikan)
    const result = await apiRequest('/checkout', 'POST', {
        id_metode: parseInt(idMetode)
    });

    if (result && result.ok) {
        const { id_pembayaran, total_bayar } = result.data.data;
        
        showToast(
            `Checkout berhasil! No. transaksi: #${id_pembayaran}`,
            'success'
        );

        // Refresh keranjang (seharusnya sudah kosong otomatis setelah checkout)
        setTimeout(() => {
            loadCart();
            btn.disabled    = false;
            btn.textContent = 'Konfirmasi Checkout';
            // Reset pilihan dropdown
            document.getElementById('select-metode').value = ""; 
        }, 1500);

    } else if (result) {
        showToast(result.data.message || 'Checkout gagal.', 'error');
        btn.disabled    = false;
        btn.textContent = 'Konfirmasi Checkout';
    }
}
// Tambahkan pemanggilan loadCart() di bagian paling bawah agar keranjang terisi saat halaman pertama kali dibuka
loadCart();

// Otomatis muat menu
loadMenu();
</script>

</body>
</html>