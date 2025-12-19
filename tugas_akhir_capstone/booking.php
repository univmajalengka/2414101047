<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$errors = [];
$success = null;
$editing = false;

// Daftar pelayanan dan harga (default). Ubah sesuai kebutuhan atau ambil dari DB.
if (!isset($services) || !is_array($services)) {
    $services = [
        'Transport' => 150000,
        'Guide' => 200000,
        'Makan' => 50000,
        'Tiket Masuk' => 100000
    ];
}


// Load booking jika ada param id (editing)
$booking = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bid = (int)$_GET['id'];
    $booking = getBookingById($conn, $bid);
    if ($booking) $editing = true;
}

// Tangani submit form (create / update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_pemesan'] ?? '');
    $nomor_hp = trim($_POST['nomor_hp'] ?? '');
    $tanggal_pesan = $_POST['tanggal_pesan'] ?? date('Y-m-d');
    $waktu_mulai = $_POST['waktu_pelaksanaan_mulai'] ?? date('Y-m-d');
    $waktu_hari = max(1, (int)($_POST['waktu_pelaksanaan_hari'] ?? 1));
    $pelayanan_arr = $_POST['pelayanan'] ?? [];
    if (!is_array($pelayanan_arr)) $pelayanan_arr = [$pelayanan_arr];
    $pelayanan = implode(', ', array_map('trim', $pelayanan_arr));
    $jumlah_peserta = max(1, (int)($_POST['jumlah_peserta'] ?? 1));
    $harga_paket = (float)($_POST['harga_paket'] ?? 0);
    $jumlah_tagihan = (float)($_POST['jumlah_tagihan'] ?? 0);

    // Validasi sederhana
    if ($nama === '') $errors[] = 'Nama Pemesan harus diisi.';
    if ($nomor_hp === '') $errors[] = 'Nomor HP harus diisi.';
    if (empty($pelayanan_arr)) $errors[] = 'Pilih minimal satu pelayanan.';

    if (empty($errors)) {
        if (!empty($_POST['id'])) {
            // update
            $id = (int)$_POST['id'];
            $res = updateBooking($conn, $id, [
                'nama_pemesan' => $nama,
                'nomor_hp' => $nomor_hp,
                'tanggal_pesan' => $tanggal_pesan,
                'waktu_pelaksanaan_mulai' => $waktu_mulai,
                'waktu_pelaksanaan_hari' => $waktu_hari,
                'pelayanan' => $pelayanan,
                'jumlah_peserta' => $jumlah_peserta,
                'harga_paket' => $harga_paket,
                'jumlah_tagihan' => $jumlah_tagihan,
            ]);
        } else {
            // create
            $res = addBooking($conn, [
                'nama_pemesan' => $nama,
                'nomor_hp' => $nomor_hp,
                'tanggal_pesan' => $tanggal_pesan,
                'waktu_pelaksanaan_mulai' => $waktu_mulai,
                'waktu_pelaksanaan_hari' => $waktu_hari,
                'pelayanan' => $pelayanan,
                'jumlah_peserta' => $jumlah_peserta,
                'harga_paket' => $harga_paket,
                'jumlah_tagihan' => $jumlah_tagihan,
            ]);
        }

        if ($res['success']) {
            // Redirect ke daftar pesanan agar user melihat data
            header('Location: manage_bookings.php');
            exit;
        } else {
            $errors[] = $res['message'] ?? 'Terjadi kesalahan saat menyimpan.';
        }
    }
}

// Jika editing dan booking ada, gunakan data untuk prefill
$prefill = [
    'nama_pemesan' => $booking['nama_pemesan'] ?? '',
    'nomor_hp' => $booking['nomor_hp'] ?? '',
    'tanggal_pesan' => $booking['tanggal_pesan'] ?? date('Y-m-d'),
    'waktu_pelaksanaan_mulai' => $booking['waktu_pelaksanaan_mulai'] ?? date('Y-m-d'),
    'waktu_pelaksanaan_hari' => $booking['waktu_pelaksanaan_hari'] ?? 1,
    'pelayanan' => $booking['pelayanan'] ?? '',
    'jumlah_peserta' => $booking['jumlah_peserta'] ?? 1,
    'harga_paket' => $booking['harga_paket'] ?? 0,
    'jumlah_tagihan' => $booking['jumlah_tagihan'] ?? 0,
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Form Pemesanan - Karya Wisata Bali</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2 style="margin-top:30px;">Form Pemesanan Paket Wisata</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $e) echo '<div>'.$e.'</div>'; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Container untuk menampilkan error validasi client-side -->
        <div id="clientErrors" class="alert alert-danger" style="display:none;"></div>

        <form method="post" class="form-container">
            <?php if ($editing && $booking): ?>
                <input type="hidden" name="id" value="<?php echo (int)$booking['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Pemesan</label>
                <input type="text" name="nama_pemesan" value="<?php echo htmlspecialchars($prefill['nama_pemesan']); ?>">
            </div>

            <div class="form-group">
                <label>Nomor HP/Telp</label>
                <input type="text" name="nomor_hp" value="<?php echo htmlspecialchars($prefill['nomor_hp']); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Pesan</label>
                    <input type="date" name="tanggal_pesan" value="<?php echo $prefill['tanggal_pesan']; ?>">
                </div>
                <div class="form-group">
                    <label>Waktu Pelaksanaan Mulai</label>
                    <input type="date" name="waktu_pelaksanaan_mulai" value="<?php echo $prefill['waktu_pelaksanaan_mulai']; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Waktu Perjalanan (Hari)</label>
                    <input type="number" min="1" name="waktu_pelaksanaan_hari" id="waktu_pelaksanaan_hari" value="<?php echo (int)$prefill['waktu_pelaksanaan_hari']; ?>">
                </div>
                <div class="form-group">
                    <label>Jumlah Peserta</label>
                    <input type="number" min="1" name="jumlah_peserta" id="jumlah_peserta" value="<?php echo (int)$prefill['jumlah_peserta']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Pelayanan (pilih minimal 1)</label>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    <?php foreach($services as $sname => $price):
                        $checked = strpos($prefill['pelayanan'], $sname) !== false ? 'checked' : '';
                    ?>
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="pelayanan[]" value="<?php echo $sname; ?>" data-price="<?php echo $price; ?>" <?php echo $checked; ?>> <?php echo $sname.' ('.formatRupiah($price).'/org/hari)'; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Harga Paket Perjalanan (otomatis)</label>
                <input type="text" id="harga_paket_display" value="<?php echo formatRupiah($prefill['harga_paket']); ?>" readonly>
                <input type="hidden" name="harga_paket" id="harga_paket" value="<?php echo $prefill['harga_paket']; ?>">
            </div>

            <div class="form-group">
                <label>Jumlah Tagihan (otomatis)</label>
                <input type="text" id="jumlah_tagihan_display" value="<?php echo formatRupiah($prefill['jumlah_tagihan']); ?>" readonly>
                <input type="hidden" name="jumlah_tagihan" id="jumlah_tagihan" value="<?php echo $prefill['jumlah_tagihan']; ?>">
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-submit"><?php echo $editing ? 'Update Pesanan' : 'Buat Pesanan'; ?></button>
                <a href="index.php" class="btn-cancel">Kembali</a>
            </div>
        </form>
    </div>

<script>
(function(){
    const form = document.querySelector('form.form-container');
    const serviceCheckboxes = Array.from(document.querySelectorAll('input[name="pelayanan[]"]'));
    const pesertaEl = document.getElementById('jumlah_peserta');
    const hariEl = document.getElementById('waktu_pelaksanaan_hari');
    const hargaPaketHidden = document.getElementById('harga_paket');
    const hargaPaketDisplay = document.getElementById('harga_paket_display');
    const jumlahTagihanHidden = document.getElementById('jumlah_tagihan');
    const jumlahTagihanDisplay = document.getElementById('jumlah_tagihan_display');
    const clientErrorsEl = document.getElementById('clientErrors');

    function parseNumber(n){ return Number(n) || 0; }

    function formatRupiah(amount){
        if (!isFinite(amount)) amount = 0;
        amount = Math.round(amount);
        return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function calc(){
        let harga = 0;
        serviceCheckboxes.forEach(cb => { if(cb.checked) harga += parseNumber(cb.dataset.price); });
        const peserta = Math.max(1, parseInt(pesertaEl.value || 1));
        const hari = Math.max(1, parseInt(hariEl.value || 1));
        const total = harga * peserta * hari;

        hargaPaketHidden.value = harga;
        hargaPaketDisplay.value = formatRupiah(harga);
        jumlahTagihanHidden.value = total.toFixed(2);
        jumlahTagihanDisplay.value = formatRupiah(total);
    }

    function isValidPhoneNumber(p){
        if (!p) return false;
        const digits = p.replace(/\D/g, '');
        return digits.length >= 7 && digits.length <= 15;
    }

    function showClientErrors(errs){
        if (!clientErrorsEl) return;
        if (!errs || errs.length === 0) { clientErrorsEl.style.display = 'none'; clientErrorsEl.innerHTML = ''; return; }
        clientErrorsEl.style.display = 'block';
        clientErrorsEl.innerHTML = errs.map(e => '<div>'+e+'</div>').join('');
    }

    function validate(){
        const errs = [];
        const nama = (document.querySelector('input[name="nama_pemesan"]') || {}).value || '';
        const hp = (document.querySelector('input[name="nomor_hp"]') || {}).value || '';
        if (nama.trim() === '') errs.push('Nama Pemesan harus diisi.');
        if (!isValidPhoneNumber(hp)) errs.push('Nomor HP tidak valid (minimal 7 digit).');
        if (serviceCheckboxes.every(cb => !cb.checked)) errs.push('Pilih minimal satu pelayanan.');
        if (parseInt(pesertaEl.value || 0) < 1) errs.push('Jumlah peserta harus lebih dari 0.');
        if (parseInt(hariEl.value || 0) < 1) errs.push('Waktu perjalanan (hari) harus minimal 1.');
        return errs;
    }

    // events
    serviceCheckboxes.forEach(cb => cb.addEventListener('change', calc));
    pesertaEl && pesertaEl.addEventListener('input', calc);
    hariEl && hariEl.addEventListener('input', calc);

    if (form){
        form.addEventListener('submit', function(e){
            const errs = validate();
            if (errs.length){
                e.preventDefault();
                showClientErrors(errs);
                // scroll to error container
                clientErrorsEl && clientErrorsEl.scrollIntoView({behavior: 'smooth', block: 'center'});
                return false;
            }
            // Clear any client errors before submit
            showClientErrors([]);
        });
    }

    // initial calc
    calc();
})();
</script>
</body>
</html>