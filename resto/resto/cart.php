<?php
include 'config.php';

// Proses update quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            if ($quantity <= 0) {
                // Hapus item jika quantity <= 0
                $_SESSION['cart'] = array_filter($_SESSION['cart'], function($cart_item) use ($product_id) {
                    return $cart_item['id'] != $product_id;
                });
            } else {
                $item['quantity'] = $quantity;
                $item['subtotal'] = $item['price'] * $quantity;
            }
            break;
        }
    }
    // Reindex array setelah menghapus item
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    showNotification('Keranjang berhasil diperbarui!', 'success');
    header("Location: cart.php");
    exit();
}

// Proses hapus item
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
        return $item['id'] != $product_id;
    });
    // Reindex array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    showNotification('Produk dihapus dari keranjang!', 'success');
    header("Location: cart.php");
    exit();
}

// Proses kosongkan keranjang
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = array();
    showNotification('Keranjang berhasil dikosongkan!', 'success');
    header("Location: cart.php");
    exit();
}

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    
    if (empty($_SESSION['cart'])) {
        showNotification('Keranjang belanja kosong!', 'error');
    } else {
        // Hitung total
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['subtotal'];
        }
        
        // Generate order number
        $order_number = 'ORDER-' . date('YmdHis') . '-' . rand(1000, 9999);
        
        // Simpan pesanan
        $conn->begin_transaction();
        try {
            // Insert order
            $sql_order = "INSERT INTO orders (order_number, total, payment_method, customer_name) VALUES (?, ?, ?, ?)";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->bind_param("sdss", $order_number, $total, $payment_method, $customer_name);
            $stmt_order->execute();
            $order_id = $conn->insert_id;
            
            // Insert order items dan update purchase_count
            foreach ($_SESSION['cart'] as $item) {
                $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)";
                $stmt_item = $conn->prepare($sql_item);
                $stmt_item->bind_param("iiidd", $order_id, $item['id'], $item['quantity'], $item['price'], $item['subtotal']);
                $stmt_item->execute();
                
                // Update purchase_count
                $sql_update = "UPDATE products SET purchase_count = purchase_count + ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ii", $item['quantity'], $item['id']);
                $stmt_update->execute();
            }
            
            $conn->commit();
            
            // Kosongkan keranjang
            $_SESSION['cart'] = array();
            
            showNotification('Pesanan berhasil! No. Pesanan: ' . $order_number, 'success');
            header("Location: order_success.php?order_number=" . $order_number);
            exit();
            
        } catch (Exception $e) {
            $conn->rollback();
            showNotification('Error: ' . $e->getMessage(), 'error');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - TOKO ENCANG</title>
    <link rel="stylesheet" href="style.css">
            <style>
        /* Tambahan CSS untuk perbaikan tampilan */
        .quantity-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .quantity-btn {
            width: 30px;
            height: 30px;
            background-color: #4ecdc4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        
        .quantity-btn:hover {
            background-color: #45b7af;
        }
        
        .quantity-btn:active {
            transform: scale(0.95);
        }
        
        .quantity-input {
            width: 50px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .update-btn {
            padding: 6px 12px;
            background-color: #ffa726;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        
        .update-btn:hover {
            background-color: #f57c00;
        }
        
        /* Tombol Kosongkan Keranjang */
        .clear-cart-btn {
            padding: 8px 15px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            margin-right: 10px;
            font-weight: 600;
        }
        
        .clear-cart-btn:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }
        
        .checkout-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid #28a745;
        }
        
        .cart-summary {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .cart-actions-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">TOKO ENCANG</div>
                <nav>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="makanan.php">Makanan</a></li>
                        <li><a href="minuman.php">Minuman</a></li>
                        <li><a href="cart.php" class="cart-btn">
                            🛒 Keranjang
                            <span class="cart-count"><?php echo updateCartCount(); ?></span>
                        </a></li>
                        <li><a href="login.php" class="admin-btn">Admin</a></li>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <li><a href="logout.php" class="logout-btn">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Keranjang Belanja -->
    <section class="container">
        <h2 class="section-title">Keranjang Belanja</h2>
        <div class="cart-container" id="cartContainer">
            <?php if (!empty($_SESSION['cart'])): ?>
                <!-- Ringkasan Keranjang -->
                <div class="cart-summary">
                    <h4>Ringkasan Pesanan</h4>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        $total += $item['subtotal'];
                    ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span><?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['quantity']; ?>x)</span>
                            <span>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1em;">
                        <span>Total</span>
                        <span>Rp <?php echo number_format($total, 0, ',', '.'); ?></span>
                    </div>
                </div>

                                <!-- Aksi Keranjang -->
                <div class="cart-actions-top">
                    <a href="?clear=true" class="clear-cart-btn" onclick="return confirm('Kosongkan seluruh keranjang?')">
                        🗑️ Kosongkan Keranjang
                    </a>
                    <div style="font-weight: bold; color: #333;">
                        Total Items: <?php echo updateCartCount(); ?> | 
                        Total Harga: Rp <?php echo number_format($total, 0, ',', '.'); ?>
                    </div>
                </div>
                
                                <!-- Tabel Detail Keranjang -->
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
                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                            <tr>
                                <td>
                                    <div class="cart-product-info">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-product-image">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <!-- PERBAIKAN: Form yang benar untuk update quantity -->
                                    <form method="POST" action="" class="quantity-form">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <div class="quantity-controls">
                                            <button type="button" class="quantity-btn minus-btn" data-product-id="<?php echo $item['id']; ?>">-</button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="<?php echo $item['quantity']; ?>" 
                                                   min="1" 
                                                   max="99"
                                                   class="quantity-input"
                                                   id="quantity-<?php echo $item['id']; ?>"
                                                   readonly>
                                            <button type="button" class="quantity-btn plus-btn" data-product-id="<?php echo $item['id']; ?>">+</button>
                                        </div>
                                        <button type="submit" name="update_quantity" class="update-btn" style="margin-top: 5px;">Update</button>
                                    </form>
                                </td>
                                <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="?remove=<?php echo $item['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Hapus <?php echo htmlspecialchars($item['name']); ?> dari keranjang?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Form Checkout -->
                <div class="checkout-section">
                    <h4>Informasi Pesanan</h4>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="customer_name">Nama Pelanggan</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" value="" required style="max-width: 300px;">
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran</label>
                            <select id="payment_method" name="payment_method" class="form-control" required style="max-width: 300px;">
                                <option value="cod">COD (Bayar di Tempat)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                        <button type="submit" name="checkout" class="btn btn-success" style="padding: 12px 30px; font-size: 16px;">
                            ✅ Proses Pesanan
                        </button>
                    </form>
                </div>
                </div>
            <?php else: ?>
                <div class="empty-cart">
                    <i>🛒</i>
                    <h3>Keranjang Belanja Kosong</h3>
                    <p>Silakan tambahkan produk ke keranjang belanja Anda</p>
                    <a href="makanan.php" class="btn btn-primary">Lihat Menu Makanan</a>
                    <a href="minuman.php" class="btn btn-accent">Lihat Menu Minuman</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Notifikasi -->
    <?php 
    $notification = getNotification();
    if ($notification): ?>
        <div class="notification <?php echo $notification['type']; ?>">
            <?php echo $notification['message']; ?>
        </div>
        <script>
            setTimeout(() => {
                const notification = document.querySelector('.notification');
                if (notification) notification.style.display = 'none';
            }, 3000);
        </script>
    <?php endif; ?>

        <script>
        // Fungsi untuk mengatur quantity dengan tombol +/-
        document.addEventListener('DOMContentLoaded', function() {
            // Tombol plus
            document.querySelectorAll('.plus-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const input = document.getElementById('quantity-' + productId);
                    let value = parseInt(input.value);
                    if (value < 99) {
                        input.value = value + 1;
                    }
                });
            });
            
            // Tombol minus
            document.querySelectorAll('.minus-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const input = document.getElementById('quantity-' + productId);
                    let value = parseInt(input.value);
                    if (value > 1) {
                        input.value = value - 1;
                    }
                });
            });
            
            // Auto-submit ketika quantity diubah (opsional)
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Validasi input
                    let value = parseInt(this.value);
                    if (value < 1) this.value = 1;
                    if (value > 99) this.value = 99;
                });
            });
            
            // Submit form ketika tombol update diklik
            document.querySelectorAll('.quantity-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Validasi sebelum submit
                    const input = this.querySelector('.quantity-input');
                    let value = parseInt(input.value);
                    if (value < 1) {
                        e.preventDefault();
                        alert('Jumlah tidak boleh kurang dari 1');
                        input.value = 1;
                    }
                    if (value > 99) {
                        e.preventDefault();
                        alert('Jumlah tidak boleh lebih dari 99');
                        input.value = 99;
                    }
                });
            });
        });
        
        // Fungsi untuk update quantity secara instan (tanpa tombol update)
        function updateQuantityInstantly(productId, change) {
            const input = document.getElementById('quantity-' + productId);
            let currentValue = parseInt(input.value);
            let newValue = currentValue + change;
            
            if (newValue >= 1 && newValue <= 99) {
                // Submit form secara otomatis
                const form = input.closest('form');
                input.value = newValue;
                form.submit();
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>