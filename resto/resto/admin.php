<?php
include 'config.php';

// Redirect ke login jika bukan admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Buat folder uploads jika belum ada
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Proses tambah/update produk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['product_id'] ?? null;
    $name = $conn->real_escape_string($_POST['product_name']);
    $category = $conn->real_escape_string($_POST['product_category']);
    $price = floatval($_POST['product_price']);
    $description = $conn->real_escape_string($_POST['product_description']);
    $image = '';

    // Upload gambar jika ada
    if (!empty($_FILES['product_image']['name']) && $_FILES['product_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        $file_type = $_FILES['product_image']['type'];
        $file_size = $_FILES['product_image']['size'];
        
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $file_extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                $image = $target_file;
            }
        }
    }

    if ($id) {
        // Update produk
        if ($image) {
            $sql = "UPDATE products SET name='$name', category='$category', price=$price, description='$description', image='$image' WHERE id=$id";
        } else {
            $sql = "UPDATE products SET name='$name', category='$category', price=$price, description='$description' WHERE id=$id";
        }
        $message = "Produk berhasil diperbarui!";
    } else {
        // Tambah produk baru
        $image = $image ?: ($category == 'makanan' ? "https://source.unsplash.com/random/300x200/?food" : "https://source.unsplash.com/random/300x200/?drink");
        $sql = "INSERT INTO products (name, category, price, description, image) VALUES ('$name', '$category', $price, '$description', '$image')";
        $message = "Produk berhasil ditambahkan!";
    }

    if ($conn->query($sql) === TRUE) {
        showNotification($message, 'success');
    } else {
        showNotification("Error: " . $conn->error, 'error');
    }
}

// Proses hapus produk
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        showNotification("Produk berhasil dihapus!", 'success');
    } else {
        showNotification("Error: " . $conn->error, 'error');
    }
    header("Location: admin.php");
    exit();
}

// Ambil semua produk
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
$products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - TOKO ENCANG</title>
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
                        <li><a href="admin.php" class="admin-btn">Admin</a></li>
                        <li><a href="logout.php" class="logout-btn">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Admin Panel -->
    <section class="container">
        <h2 class="section-title">Kelola Produk</h2>
        
        <div class="admin-panel">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="product_id">
                <div class="form-group">
                    <label for="productName">Nama Produk</label>
                    <input type="text" id="productName" name="product_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="productCategory">Kategori</label>
                    <select id="productCategory" name="product_category" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="productPrice">Harga</label>
                    <input type="number" id="productPrice" name="product_price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="productDescription">Deskripsi</label>
                    <textarea id="productDescription" name="product_description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="productImage">Gambar Produk</label>
                    <input type="file" id="productImage" name="product_image" class="form-control" accept="image/*">
                    <small>Pilih gambar produk (format: JPG, PNG, GIF)</small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
                </div>
            </form>
        </div>

        <h3 class="section-subtitle">Daftar Produk</h3>
        <div class="products" id="adminProducts">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="product-purchase-count">Sudah dibeli <?php echo $product['purchase_count']; ?> kali</p>
                            <div class="action-buttons">
                                <button class="btn btn-warning btn-small" onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', '<?php echo $product['category']; ?>', <?php echo $product['price']; ?>, '<?php echo htmlspecialchars($product['description']); ?>')">Edit</button>
                                <a href="?delete=<?php echo $product['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Hapus produk?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-products">Belum ada produk. Silakan tambah produk menggunakan form di atas.</p>
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
        function editProduct(id, name, category, price, description) {
            document.getElementById('productId').value = id;
            document.getElementById('productName').value = name;
            document.getElementById('productCategory').value = category;
            document.getElementById('productPrice').value = price;
            document.getElementById('productDescription').value = description;
            
            // Scroll ke form
            document.querySelector('.admin-panel').scrollIntoView({ behavior: 'smooth' });
        }
        
        function resetForm() {
            document.getElementById('productId').value = '';
            document.getElementById('productName').value = '';
            document.getElementById('productCategory').value = '';
            document.getElementById('productPrice').value = '';
            document.getElementById('productDescription').value = '';
            document.getElementById('productImage').value = '';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>