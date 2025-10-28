<?php
// add_to_cart.php
session_start();

// Koneksi database - SESUAIKAN DENGAN HOSTING ANDA
$host = "localhost";
$username = "root"; 
$password = "";
$database = "toko_encang";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Fungsi notifikasi
function showNotification($message, $type) {
    $_SESSION['notification'] = array(
        'message' => $message,
        'type' => $type
    );
}

// Fungsi add to cart
function addToCart($product_id, $conn) {
    $sql = "SELECT * FROM products WHERE id = ? AND status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Inisialisasi cart jika belum ada
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        
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

// Proses tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    if (addToCart($product_id, $conn)) {
        showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
    } else {
        showNotification('Gagal menambahkan produk ke keranjang!', 'error');
    }
}

// Redirect kembali
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
