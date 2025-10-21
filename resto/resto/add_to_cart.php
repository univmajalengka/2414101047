<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    if (addToCart($product_id, $conn)) {
        showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
    } else {
        showNotification('Gagal menambahkan produk ke keranjang!', 'error');
    }
}

// Redirect kembali ke halaman sebelumnya
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>