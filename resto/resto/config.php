<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "toko_encang";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Fungsi untuk menampilkan notifikasi
function showNotification($message, $type) {
    $_SESSION['notification'] = array(
        'message' => $message,
        'type' => $type
    );
}

// Fungsi untuk mendapatkan notifikasi
function getNotification() {
    if (isset($_SESSION['notification'])) {
        $notification = $_SESSION['notification'];
        unset($_SESSION['notification']);
        return $notification;
    }
    return null;
}

// Fungsi untuk menambah ke keranjang
function addToCart($product_id, $conn) {
    $sql = "SELECT * FROM products WHERE id = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Cek apakah produk sudah ada di keranjang
        $item_index = -1;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] == $product_id) {
                $item_index = $index;
                break;
            }
        }
        
        if ($item_index !== -1) {
            // Jika sudah ada, tambah quantity
            $_SESSION['cart'][$item_index]['quantity'] += 1;
            $_SESSION['cart'][$item_index]['subtotal'] = $_SESSION['cart'][$item_index]['price'] * $_SESSION['cart'][$item_index]['quantity'];
        } else {
            // Jika belum ada, tambah item baru
            $_SESSION['cart'][] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1,
                'subtotal' => $product['price']
            );
        }
        return true;
    }
    return false;
}

// Fungsi untuk update jumlah item di keranjang
function updateCartCount() {
    if (isset($_SESSION['cart'])) {
        return array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
    return 0;
}

// Fungsi untuk reindex cart array
function reindexCart() {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
?>