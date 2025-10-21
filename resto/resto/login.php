<?php
include 'config.php';

// Jika sudah login, redirect ke admin
if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === 'Asep' && $password === 'Lasep123') {
        $_SESSION['admin'] = true;
        showNotification('Login berhasil! Mengarahkan ke panel admin...', 'success');
        header("Location: admin.php");
        exit();
    } else {
        showNotification('Username atau password salah!', 'error');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TOKO ENCANG</title>
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
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Login Form -->
    <section class="container">
        <div class="login-container">
            <div class="login-form">
                <img src="a.jpg" alt="Foto Admin" class="login-image">
                <h2>Login Admin</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                </form>
            </div>
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