<?php
include 'config.php';

$order_number = $_GET['order_number'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - TOKO ENCANG</title>
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
                        <li><a href="login.php" class="admin-btn">Admin</a></li>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <li><a href="logout.php" class="logout-btn">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Success Message -->
    <section class="container">
        <div class="success-animation">
            <div class="checkmark">✓</div>
            <h2>Pesanan Berhasil!</h2>
            <p>Terima kasih atas pesanan Anda</p>
            <?php if ($order_number): ?>
                <p><strong>No. Pesanan:</strong> <span class="order-number"><?php echo htmlspecialchars($order_number); ?></span></p>
            <?php endif; ?>
            <div style="margin-top: 2rem;">
                <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                <a href="makanan.php" class="btn btn-accent">Pesan Lagi</a>
            </div>
        </div>
    </section>
</body>
</html>