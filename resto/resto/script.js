// Data produk (simulasi database)
let products = JSON.parse(localStorage.getItem('products')) || [
    {
        id: 1,
        name: "Nasi Goreng Spesial",
        category: "makanan",
        price: 25000,
        description: "Nasi goreng dengan campuran seafood dan sayuran segar",
        image: "https://source.unsplash.com/random/300x200/?fried-rice",
        purchaseCount: 15
    },
    {
        id: 2,
        name: "Ayam Bakar Madu",
        category: "makanan",
        price: 35000,
        description: "Ayam bakar dengan bumbu madu yang manis dan gurih",
        image: "https://source.unsplash.com/random/300x200/?grilled-chicken",
        purchaseCount: 12
    },
    {
        id: 3,
        name: "Es Jeruk Segar",
        category: "minuman",
        price: 12000,
        description: "Es jeruk segar dengan potongan jeruk asli",
        image: "https://source.unsplash.com/random/300x200/?orange-juice",
        purchaseCount: 20
    },
    {
        id: 4,
        name: "Kopi Hitam",
        category: "minuman",
        price: 15000,
        description: "Kopi hitam pilihan dengan aroma yang harum",
        image: "https://source.unsplash.com/random/300x200/?coffee",
        purchaseCount: 18
    }
];

// Inisialisasi data contoh jika belum ada
if (!localStorage.getItem('products')) {
    localStorage.setItem('products', JSON.stringify(products));
}

// Cek status login dan tampilkan tombol logout
function checkLoginStatus() {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const logoutBtn = document.getElementById('logoutBtn');
    
    if (logoutBtn) {
        if (isLoggedIn) {
            logoutBtn.style.display = 'inline-block';
        } else {
            logoutBtn.style.display = 'none';
        }
        
        // Event listener untuk logout
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.setItem('isLoggedIn', 'false');
            showNotification('Anda telah logout', 'success');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1000);
        });
    }
}

// Update jumlah item di keranjang
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = document.querySelector('.cart-count');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
    
    if (cartCount) {
        cartCount.textContent = totalItems;
        cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
    }
}

// Tampilkan notifikasi
function showNotification(message, type) {
    const notification = document.getElementById('notification');
    if (!notification) return;
    
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.display = 'none';
    }, 3000);
}

// Buat kartu produk untuk halaman makanan/minuman (dengan tombol tambah ke keranjang)
function createProductCard(product, showAdminButtons = false) {
    const card = document.createElement('div');
    card.className = 'product-card';
    
    // Tampilkan tombol edit/hapus hanya jika diminta dan admin sudah login
    const actionButtons = (showAdminButtons && localStorage.getItem('isLoggedIn') === 'true') ? `
        <div class="action-buttons">
            <button class="btn btn-warning btn-small edit-btn" data-id="${product.id}">Edit</button>
            <button class="btn btn-danger btn-small delete-btn" data-id="${product.id}">Hapus</button>
        </div>
    ` : '';
    
    // Tambahkan tombol tambah ke keranjang untuk halaman makanan/minuman (bukan admin)
    const addToCartButton = !showAdminButtons ? `
        <div class="action-buttons">
            <button class="btn btn-success btn-small add-to-cart-btn" data-id="${product.id}">Tambah ke Keranjang</button>
        </div>
    ` : '';
    
    card.innerHTML = `
        <img src="${product.image}" alt="${product.name}" class="product-image">
        <div class="product-info">
            <h3 class="product-title">${product.name}</h3>
            <p class="product-price">Rp ${product.price.toLocaleString('id-ID')}</p>
            <p class="product-description">${product.description}</p>
            ${actionButtons}
            ${addToCartButton}
        </div>
    `;
    
    // Tambah event listener untuk tombol edit (jika ada)
    const editBtn = card.querySelector('.edit-btn');
    if (editBtn) {
        editBtn.addEventListener('click', () => editProduct(product));
    }
    
    // Tambah event listener untuk tombol hapus (jika ada)
    const deleteBtn = card.querySelector('.delete-btn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', () => deleteProduct(product.id));
    }
    
    // Tambah event listener untuk tombol tambah ke keranjang (jika ada)
    const addToCartBtn = card.querySelector('.add-to-cart-btn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => addToCart(product));
    }
    
    return card;
}

// Buat kartu produk untuk halaman beranda (tanpa tombol)
function createFeaturedProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    
    // Tambahkan badge untuk produk populer
    const popularBadge = product.purchaseCount > 10 ? `
        <div class="popular-badge">Terlaris</div>
    ` : '';
    
    card.innerHTML = `
        ${popularBadge}
        <img src="${product.image}" alt="${product.name}" class="product-image">
        <div class="product-info">
            <h3 class="product-title">${product.name}</h3>
            <p class="product-price">Rp ${product.price.toLocaleString('id-ID')}</p>
            <p class="product-description">${product.description}</p>
            <p class="product-purchase-count">Sudah dibeli ${product.purchaseCount} kali</p>
        </div>
    `;
    
    return card;
}

// Tambah produk ke keranjang
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Cek apakah produk sudah ada di keranjang
    const existingItemIndex = cart.findIndex(item => item.id === product.id);
    
    if (existingItemIndex !== -1) {
        // Jika sudah ada, tambah jumlah
        cart[existingItemIndex].quantity += 1;
    } else {
        // Jika belum ada, tambah item baru
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${product.name} ditambahkan ke keranjang`, 'success');
}

// Tampilkan keranjang belanja
function displayCart() {
    const cartContainer = document.getElementById('cartContainer');
    if (!cartContainer) return;
    
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <i>🛒</i>
                <h3>Keranjang Belanja Kosong</h3>
                <p>Silakan tambahkan produk ke keranjang belanja Anda</p>
                <a href="makanan.html" class="btn btn-primary">Lihat Menu</a>
            </div>
        `;
        return;
    }
    
    let cartHTML = `
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    let total = 0;
    
    cart.forEach(item => {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        
        cartHTML += `
            <tr>
                <td>
                    <div class="cart-product-info">
                        <img src="${item.image}" alt="${item.name}" class="cart-product-image">
                        <span>${item.name}</span>
                    </div>
                </td>
                <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                <td>
                    <div class="quantity-controls">
                        <button class="quantity-btn minus-btn" data-id="${item.id}">-</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn plus-btn" data-id="${item.id}">+</button>
                    </div>
                </td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td>
                    <button class="remove-btn" data-id="${item.id}">Hapus</button>
                </td>
            </tr>
        `;
    });
    
    cartHTML += `
            </tbody>
        </table>
        <div class="cart-total">
            <strong>Total: Rp ${total.toLocaleString('id-ID')}</strong>
        </div>
        <div class="cart-actions">
            <button class="btn btn-danger" id="clearCart">Kosongkan Keranjang</button>
            <button class="btn btn-success" id="checkout">Proses Pesanan</button>
        </div>
    `;
    
    cartContainer.innerHTML = cartHTML;
    
    // Setup event listeners untuk keranjang
    setupCartEvents();
}

// Setup event listeners untuk keranjang
function setupCartEvents() {
    // Tombol tambah jumlah
    document.querySelectorAll('.plus-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const productId = parseInt(e.target.dataset.id);
            updateCartQuantity(productId, 1);
        });
    });
    
    // Tombol kurangi jumlah
    document.querySelectorAll('.minus-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const productId = parseInt(e.target.dataset.id);
            updateCartQuantity(productId, -1);
        });
    });
    
    // Tombol hapus item
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const productId = parseInt(e.target.dataset.id);
            removeFromCart(productId);
        });
    });
    
    // Tombol kosongkan keranjang
    const clearCartBtn = document.getElementById('clearCart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    
    // Tombol checkout
    const checkoutBtn = document.getElementById('checkout');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', showCheckoutModal);
    }
}

// Update jumlah item di keranjang
function updateCartQuantity(productId, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const itemIndex = cart.findIndex(item => item.id === productId);
    
    if (itemIndex !== -1) {
        cart[itemIndex].quantity += change;
        
        // Jika jumlah <= 0, hapus item
        if (cart[itemIndex].quantity <= 0) {
            cart.splice(itemIndex, 1);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        displayCart();
    }
}

// Hapus item dari keranjang
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCart();
    showNotification('Produk dihapus dari keranjang', 'success');
}

// Kosongkan keranjang
function clearCart() {
    if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        localStorage.removeItem('cart');
        updateCartCount();
        displayCart();
        showNotification('Keranjang berhasil dikosongkan', 'success');
    }
}

// Tampilkan modal checkout
function showCheckoutModal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    // Hapus modal yang sudah ada
    const existingModal = document.getElementById('checkoutModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modalHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Checkout Pesanan</h3>
                <button class="close-modal">&times;</button>
            </div>
            
            <div class="order-summary">
                <h4>Ringkasan Pesanan</h4>
                ${cart.map(item => `
                    <div class="summary-row">
                        <span>${item.name} (${item.quantity}x)</span>
                        <span>Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</span>
                    </div>
                `).join('')}
                <div class="summary-row total-row">
                    <span><strong>Total</strong></span>
                    <span><strong>Rp ${total.toLocaleString('id-ID')}</strong></span>
                </div>
            </div>
            
            <div class="payment-methods">
                <h4>Metode Pembayaran</h4>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment" value="cod" required>
                        <span>COD (Bayar di Tempat)</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="transfer" required>
                        <span>Transfer Bank</span>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment" value="ewallet" required>
                        <span>E-Wallet</span>
                    </label>
                </div>
            </div>
            
            <button class="btn btn-success btn-block" id="confirmOrder" disabled>Konfirmasi Pesanan</button>
        </div>
    `;
    
    // Buat modal
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'checkoutModal';
    modal.innerHTML = modalHTML;
    document.body.appendChild(modal);
    
    // Tampilkan modal
    modal.style.display = 'block';
    
    // Setup event listeners untuk modal
    setupCheckoutModalEvents();
}

// Setup event listeners untuk modal checkout
function setupCheckoutModalEvents() {
    const modal = document.getElementById('checkoutModal');
    if (!modal) return;
    
    const closeBtn = modal.querySelector('.close-modal');
    const paymentOptions = modal.querySelectorAll('input[name="payment"]');
    const confirmBtn = modal.querySelector('#confirmOrder');
    
    // Fungsi untuk menutup modal
    const closeModal = () => {
        modal.remove();
    };
    
    // Tutup modal dengan tombol X
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    
    // Tutup modal ketika klik di luar konten
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Pilihan pembayaran
    paymentOptions.forEach(option => {
        option.addEventListener('change', function(e) {
            if (confirmBtn) {
                confirmBtn.disabled = false;
            }
            
            // Update styling untuk pilihan yang aktif
            paymentOptions.forEach(opt => {
                const label = opt.closest('.payment-option');
                if (opt.checked) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });
        });
    });
    
    // Konfirmasi pesanan
    if (confirmBtn) {
        confirmBtn.addEventListener('click', completeOrder);
    }
}

// Selesaikan pesanan
function completeOrder() {
    const modal = document.getElementById('checkoutModal');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const selectedPayment = document.querySelector('input[name="payment"]:checked');
    
    if (!selectedPayment) {
        showNotification('Pilih metode pembayaran terlebih dahulu', 'error');
        return;
    }
    
    const orderNumber = generateOrderNumber();
    
    const successHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Pesanan Berhasil</h3>
                <button class="close-modal">&times;</button>
            </div>
            
            <div class="success-animation">
                <div class="checkmark">✓</div>
                <h3>Terima Kasih!</h3>
                <p>Pesanan Anda telah berhasil diproses</p>
            </div>
            
            <div class="order-summary">
                <div class="summary-row">
                    <span>No. Pesanan:</span>
                    <span class="order-number">${orderNumber}</span>
                </div>
                <div class="summary-row">
                    <span>Total Pembayaran:</span>
                    <span>Rp ${total.toLocaleString('id-ID')}</span>
                </div>
                <div class="summary-row">
                    <span>Metode Pembayaran:</span>
                    <span>${getPaymentMethodName(selectedPayment.value)}</span>
                </div>
                ${selectedPayment.value === 'transfer' ? `
                    <div class="summary-row">
                        <span>Rekening Tujuan:</span>
                        <span>BCA 123-456-7890 (Toko Encang)</span>
                    </div>
                ` : ''}
            </div>
            
            <button class="btn btn-primary btn-block" id="closeSuccess">Tutup</button>
        </div>
    `;
    
    modal.querySelector('.modal-content').innerHTML = successHTML;
    
    // Update jumlah pembelian produk
    updateProductPurchaseCount(cart);
    
    // Kosongkan keranjang
    localStorage.removeItem('cart');
    updateCartCount();
    
    // Setup event listener untuk tombol tutup - FIXED
    const closeSuccessBtn = document.getElementById('closeSuccess');
    const closeModalBtn = modal.querySelector('.close-modal');
    
    if (closeSuccessBtn) {
        closeSuccessBtn.onclick = function() {
            modal.remove();
            if (window.location.pathname.includes('cart.html')) {
                displayCart();
            }
            showNotification('Pesanan berhasil!', 'success');
        };
    }
    
    if (closeModalBtn) {
        closeModalBtn.onclick = function() {
            modal.remove();
            if (window.location.pathname.includes('cart.html')) {
                displayCart();
            }
        };
    }
    
    // Tutup modal ketika klik di luar konten
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.remove();
            if (window.location.pathname.includes('cart.html')) {
                displayCart();
            }
        }
    };
}

// Update jumlah pembelian produk
function updateProductPurchaseCount(cart) {
    let products = JSON.parse(localStorage.getItem('products')) || [];
    
    cart.forEach(cartItem => {
        const productIndex = products.findIndex(p => p.id === cartItem.id);
        if (productIndex !== -1) {
            products[productIndex].purchaseCount = (products[productIndex].purchaseCount || 0) + cartItem.quantity;
        }
    });
    
    localStorage.setItem('products', JSON.stringify(products));
    
    // Simpan riwayat pesanan
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const selectedPayment = document.querySelector('input[name="payment"]:checked');
    orders.push({
        orderNumber: generateOrderNumber(),
        items: cart,
        total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
        paymentMethod: selectedPayment ? selectedPayment.value : 'cod',
        date: new Date().toLocaleString('id-ID')
    });
    localStorage.setItem('orders', JSON.stringify(orders));
}

// Generate nomor pesanan
function generateOrderNumber() {
    const timestamp = new Date().getTime();
    const random = Math.floor(Math.random() * 1000);
    return `ORDER-${timestamp}-${random}`;
}

// Dapatkan nama metode pembayaran
function getPaymentMethodName(method) {
    const methods = {
        'cod': 'COD (Bayar di Tempat)',
        'transfer': 'Transfer Bank',
        'ewallet': 'E-Wallet'
    };
    return methods[method] || method;
}

// Tampilkan semua produk (untuk halaman admin)
function displayProducts(showAdminButtons = false) {
    const products = JSON.parse(localStorage.getItem('products')) || [];
    const container = document.getElementById('adminProducts');
    
    if (!container) return;
    
    container.innerHTML = '';
    
    if (products.length === 0) {
        container.innerHTML = '<p class="no-products">Belum ada produk. Silakan tambah produk menggunakan form di atas.</p>';
        return;
    }
    
    products.forEach(product => {
        const productCard = createProductCard(product, showAdminButtons);
        container.appendChild(productCard);
    });
}

// Simpan produk
function saveProduct() {
    const productId = document.getElementById('productId');
    const productName = document.getElementById('productName');
    const productCategory = document.getElementById('productCategory');
    const productPrice = document.getElementById('productPrice');
    const productDescription = document.getElementById('productDescription');
    const productImage = document.getElementById('productImage');
    
    const id = productId.value ? parseInt(productId.value) : Date.now();
    const name = productName.value;
    const category = productCategory.value;
    const price = parseInt(productPrice.value);
    const description = productDescription.value;
    
    // Untuk gambar, kita akan menggunakan URL gambar yang dipilih atau default
    let imageUrl = category === 'makanan' 
        ? "https://source.unsplash.com/random/300x200/?food" 
        : "https://source.unsplash.com/random/300x200/?drink";
    
    // Jika ada file gambar yang dipilih, konversi ke URL
    if (productImage.files && productImage.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imageUrl = e.target.result;
            saveProductToStorage(id, name, category, price, description, imageUrl);
        };
        reader.readAsDataURL(productImage.files[0]);
    } else {
        saveProductToStorage(id, name, category, price, description, imageUrl);
    }
}

function saveProductToStorage(id, name, category, price, description, imageUrl) {
    let products = JSON.parse(localStorage.getItem('products')) || [];
    
    // Cek apakah produk sudah ada (edit mode)
    const existingProductIndex = products.findIndex(p => p.id === id);
    
    if (existingProductIndex !== -1) {
        // Update produk yang sudah ada (pertahankan purchaseCount)
        const existingPurchaseCount = products[existingProductIndex].purchaseCount || 0;
        products[existingProductIndex] = {
            id,
            name,
            category,
            price,
            description,
            image: imageUrl,
            purchaseCount: existingPurchaseCount
        };
        showNotification('Produk berhasil diperbarui!', 'success');
    } else {
        // Tambah produk baru dengan purchaseCount 0
        products.push({
            id,
            name,
            category,
            price,
            description,
            image: imageUrl,
            purchaseCount: 0
        });
        showNotification('Produk berhasil ditambahkan!', 'success');
    }
    
    // Simpan ke localStorage
    localStorage.setItem('products', JSON.stringify(products));
    
    // Reset form
    resetForm();
    
    // Refresh tampilan produk
    if (window.location.pathname.includes('admin.html')) {
        displayProducts(true);
    }
}

// Reset form
function resetForm() {
    const productId = document.getElementById('productId');
    const productName = document.getElementById('productName');
    const productCategory = document.getElementById('productCategory');
    const productPrice = document.getElementById('productPrice');
    const productDescription = document.getElementById('productDescription');
    const productImage = document.getElementById('productImage');
    const saveProduct = document.getElementById('saveProduct');
    const cancelEdit = document.getElementById('cancelEdit');
    
    if (productId) productId.value = '';
    if (productName) productName.value = '';
    if (productCategory) productCategory.value = '';
    if (productPrice) productPrice.value = '';
    if (productDescription) productDescription.value = '';
    if (productImage) productImage.value = '';
    if (saveProduct) saveProduct.textContent = 'Simpan Produk';
    if (cancelEdit) cancelEdit.style.display = 'none';
}

// Edit produk
function editProduct(product) {
    // Simpan produk yang akan diedit ke localStorage
    localStorage.setItem('productToEdit', JSON.stringify(product));
    
    // Redirect ke halaman admin
    window.location.href = 'admin.html';
}

// Hapus produk
function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        let products = JSON.parse(localStorage.getItem('products')) || [];
        products = products.filter(p => p.id !== id);
        localStorage.setItem('products', JSON.stringify(products));
        
        // Refresh tampilan produk
        if (window.location.pathname.includes('admin.html')) {
            displayProducts(true);
        } else {
            // Reload halaman jika bukan di admin
            window.location.reload();
        }
        
        showNotification('Produk berhasil dihapus!', 'success');
    }
}

// Fungsi untuk memuat data produk yang akan diedit (dipanggil di admin.html)
function loadProductToEdit() {
    const productToEdit = JSON.parse(localStorage.getItem('productToEdit'));
    
    if (productToEdit) {
        const productId = document.getElementById('productId');
        const productName = document.getElementById('productName');
        const productCategory = document.getElementById('productCategory');
        const productPrice = document.getElementById('productPrice');
        const productDescription = document.getElementById('productDescription');
        const saveProduct = document.getElementById('saveProduct');
        const cancelEdit = document.getElementById('cancelEdit');
        
        if (productId) productId.value = productToEdit.id;
        if (productName) productName.value = productToEdit.name;
        if (productCategory) productCategory.value = productToEdit.category;
        if (productPrice) productPrice.value = productToEdit.price;
        if (productDescription) productDescription.value = productToEdit.description;
        if (saveProduct) saveProduct.textContent = 'Update Produk';
        if (cancelEdit) cancelEdit.style.display = 'inline-block';
        
        // Hapus data dari localStorage setelah dimuat
        localStorage.removeItem('productToEdit');
    }
}

// Fungsi untuk menampilkan produk terpopuler (untuk halaman beranda)
function displayPopularProducts() {
    const products = JSON.parse(localStorage.getItem('products')) || [];
    const featuredContainer = document.getElementById('featuredProducts');
    
    if (!featuredContainer) return;
    
    featuredContainer.innerHTML = '';
    
    // Urutkan produk berdasarkan jumlah pembelian (descending)
    const popularProducts = [...products].sort((a, b) => {
        const countA = a.purchaseCount || 0;
        const countB = b.purchaseCount || 0;
        return countB - countA;
    }).slice(0, 4); // Ambil 4 produk terpopuler
    
    if (popularProducts.length === 0) {
        featuredContainer.innerHTML = '<p class="no-products">Belum ada produk. Silakan login sebagai admin untuk menambahkan produk.</p>';
        return;
    }
    
    popularProducts.forEach(product => {
        const productCard = createFeaturedProductCard(product);
        featuredContainer.appendChild(productCard);
    });
}

// Inisialisasi cart count saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Setup global event listeners untuk modal
    document.addEventListener('click', function(e) {
        // Tombol tutup modal
        if (e.target.classList.contains('close-modal')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                modal.remove();
            }
        }
        
        // Klik di luar modal untuk menutup
        if (e.target.classList.contains('modal')) {
            e.target.remove();
        }
    });
});