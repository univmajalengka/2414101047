<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');
    $nama = trim($_POST['nama'] ?? '');

    if ($nomor_hp === '' || !isValidPhoneNumber($nomor_hp)) $errors[] = 'Nomor HP tidak valid.';
    if ($nama === '') $errors[] = 'Nama harus diisi.';

    if (empty($errors)) {
        registerUser($conn, $nomor_hp, $nama);
        $_SESSION['nomor_hp'] = $nomor_hp;
        $_SESSION['nama'] = $nama;
        header('Location: my_bookings.php'); exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Pesanan Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="padding-top:30px;max-width:480px;">
        <h2>Login / Masuk Dengan Nomor HP</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"><?php foreach($errors as $e) echo '<div>'.$e.'</div>'; ?></div>
        <?php endif; ?>

        <form method="post" class="form-container">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Nomor HP (contoh: 081234567890)</label>
                <input type="text" name="nomor_hp" value="<?php echo htmlspecialchars($_POST['nomor_hp'] ?? ''); ?>">
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn-submit">Masuk / Daftar</button>
                <a class="btn-cancel" href="index.php">Kembali</a>
            </div>
        </form>
    </div>
</body>
</html>