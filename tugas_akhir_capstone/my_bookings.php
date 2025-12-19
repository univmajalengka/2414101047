<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

if (!isset($_SESSION['nomor_hp'])) {
    header('Location: login.php'); exit;
}

$nomor_hp = $_SESSION['nomor_hp'];

// handle delete (only for owner)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];
    // verify ownership
    $b = getBookingById($conn, $id);
    if ($b && $b['nomor_hp'] === $nomor_hp) {
        $res = deleteBooking($conn, $id);
        $msg = $res['message'];
    } else {
        $msg = 'Tidak dapat menghapus: bukan milik Anda.';
    }
}

$bookings = getBookingsByPhone($conn, $nomor_hp);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="padding-top:30px;">
        <h2>Pesanan Saya - <?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?></h2>
        <?php if (!empty($msg)): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No. HP</th>
                        <th>Tanggal Pesan</th>
                        <th>Mulai</th>
                        <th>Hari</th>
                        <th>Pelayanan</th>
                        <th>Peserta</th>
                        <th>Harga Paket</th>
                        <th>Jumlah Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($bookings as $b): ?>
                    <tr>
                        <td><?php echo $b['id']; ?></td>
                        <td><?php echo htmlspecialchars($b['nama_pemesan']); ?></td>
                        <td><?php echo htmlspecialchars($b['nomor_hp']); ?></td>
                        <td><?php echo htmlspecialchars($b['tanggal_pesan']); ?></td>
                        <td><?php echo htmlspecialchars($b['waktu_pelaksanaan_mulai']); ?></td>
                        <td><?php echo htmlspecialchars($b['waktu_pelaksanaan_hari']); ?></td>
                        <td><?php echo htmlspecialchars($b['pelayanan']); ?></td>
                        <td><?php echo htmlspecialchars($b['jumlah_peserta']); ?></td>
                        <td><?php echo formatRupiah($b['harga_paket']); ?></td>
                        <td><?php echo formatRupiah($b['jumlah_tagihan']); ?></td>
                        <td>
                            <a class="btn-edit" href="booking.php?id=<?php echo $b['id']; ?>">Edit</a>
                            <form method="post" style="display:inline" onsubmit="return confirm('Hapus pesanan ID <?php echo $b['id']; ?>?');">
                                <input type="hidden" name="delete_id" value="<?php echo $b['id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="index.php" style="display:inline-block;margin-top:20px;" class="btn-cancel">Kembali</a>
    </div>
</body>
</html>