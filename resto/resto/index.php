<?php
include 'config.php';

// Ambil data produk untuk ditampilkan di beranda
$sql = "SELECT * FROM products WHERE status = 'active' ORDER BY purchase_count DESC LIMIT 4";
$result = $conn->query($sql);
$popular_products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $popular_products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKO ENCANG</title>
    <link rel="stylesheet" href="style.css">
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

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1>Selamat Datang di Toko Encang</h1>
            <p>Silahkan memilih makanan dan minuman yang mau anda pesan</p>
            <div class="hero-buttons">
                <a href="makanan.php" class="btn btn-primary">Lihat Menu Makanan</a>
                <a href="minuman.php" class="btn btn-accent">Lihat Menu Minuman</a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="container">
        <h2 class="section-title">Menu Populer</h2>
        <div class="products" id="featuredProducts">
            <?php if (count($popular_products) > 0): ?>
                <?php foreach ($popular_products as $product): ?>
                    <div class="product-card">
                        <?php if ($product['purchase_count'] > 10): ?>
                            <div class="popular-badge">Terlaris</div>
                        <?php endif; ?>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="product-purchase-count">Sudah dibeli <?php echo $product['purchase_count']; ?> kali</p>
                            <form method="POST" action="add_to_cart.php" style="margin-top: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-success btn-small">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">Belum ada produk. Silakan login sebagai admin untuk menambahkan produk.</p>
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
</body>
</html>
<?php $conn->close(); ?>