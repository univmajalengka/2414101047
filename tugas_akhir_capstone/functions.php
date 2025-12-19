<?php
// Fungsi-fungsi yang digunakan

// ===== BOOKING FUNCTIONS =====

/**
 * Tambah booking baru
 */
function addBooking($conn, $data) {
    $nama = $conn->real_escape_string($data['nama_pemesan']);
    $nomor_hp = $conn->real_escape_string($data['nomor_hp']);
    $tanggal_pesan = $conn->real_escape_string($data['tanggal_pesan']);
    $waktu_mulai = $conn->real_escape_string($data['waktu_pelaksanaan_mulai']);
    $waktu_hari = (int)$data['waktu_pelaksanaan_hari'];
    $pelayanan = $conn->real_escape_string($data['pelayanan']);
    $jumlah_peserta = (int)$data['jumlah_peserta'];
    $harga_paket = (float)$data['harga_paket'];
    $jumlah_tagihan = (float)$data['jumlah_tagihan'];
    
    $sql = "INSERT INTO bookings (nama_pemesan, nomor_hp, tanggal_pesan, waktu_pelaksanaan_mulai, 
            waktu_pelaksanaan_hari, pelayanan, jumlah_peserta, harga_paket, jumlah_tagihan) 
            VALUES ('$nama', '$nomor_hp', '$tanggal_pesan', '$waktu_mulai', $waktu_hari, 
            '$pelayanan', $jumlah_peserta, $harga_paket, $jumlah_tagihan)";
    
    if ($conn->query($sql) === TRUE) {
        return array('success' => true, 'message' => 'Pesanan berhasil dibuat!', 'id' => $conn->insert_id);
    } else {
        return array('success' => false, 'message' => 'Error: ' . $conn->error);
    }
}

/**
 * Update booking
 */
function updateBooking($conn, $id, $data) {
    $id = (int)$id;
    $nama = $conn->real_escape_string($data['nama_pemesan']);
    $nomor_hp = $conn->real_escape_string($data['nomor_hp']);
    $tanggal_pesan = $conn->real_escape_string($data['tanggal_pesan']);
    $waktu_mulai = $conn->real_escape_string($data['waktu_pelaksanaan_mulai']);
    $waktu_hari = (int)$data['waktu_pelaksanaan_hari'];
    $pelayanan = $conn->real_escape_string($data['pelayanan']);
    $jumlah_peserta = (int)$data['jumlah_peserta'];
    $harga_paket = (float)$data['harga_paket'];
    $jumlah_tagihan = (float)$data['jumlah_tagihan'];
    
    $sql = "UPDATE bookings SET nama_pemesan='$nama', nomor_hp='$nomor_hp', 
            tanggal_pesan='$tanggal_pesan', waktu_pelaksanaan_mulai='$waktu_mulai',
            waktu_pelaksanaan_hari=$waktu_hari, pelayanan='$pelayanan', 
            jumlah_peserta=$jumlah_peserta, harga_paket=$harga_paket, 
            jumlah_tagihan=$jumlah_tagihan WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        return array('success' => true, 'message' => 'Pesanan berhasil diperbarui!');
    } else {
        return array('success' => false, 'message' => 'Error: ' . $conn->error);
    }
}

/**
 * Hapus booking
 */
function deleteBooking($conn, $id) {
    $id = (int)$id;
    $sql = "DELETE FROM bookings WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        return array('success' => true, 'message' => 'Pesanan berhasil dihapus!');
    } else {
        return array('success' => false, 'message' => 'Error: ' . $conn->error);
    }
}

/**
 * Ambil booking by ID
 */
function getBookingById($conn, $id) {
    $id = (int)$id;
    $sql = "SELECT * FROM bookings WHERE id=$id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Ambil semua bookings
 */
function getAllBookings($conn) {
    $sql = "SELECT * FROM bookings ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $bookings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    return $bookings;
}

/**
 * Ambil bookings by nomor HP
 */
function getBookingsByPhone($conn, $nomor_hp) {
    $nomor_hp = $conn->real_escape_string($nomor_hp);
    $sql = "SELECT * FROM bookings WHERE nomor_hp='$nomor_hp' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $bookings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    return $bookings;
}

// ===== USER FUNCTIONS =====

/**
 * Register atau cek user
 */
function registerUser($conn, $nomor_hp, $nama, $email = '') {
    $nomor_hp = $conn->real_escape_string($nomor_hp);
    $nama = $conn->real_escape_string($nama);
    $email = $conn->real_escape_string($email);
    
    // Cek apakah user sudah ada
    $check_sql = "SELECT * FROM users WHERE nomor_hp='$nomor_hp'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // User sudah ada, update jika ada data baru
        $update_sql = "UPDATE users SET nama='$nama' WHERE nomor_hp='$nomor_hp'";
        if ($email) {
            $update_sql = "UPDATE users SET nama='$nama', email='$email' WHERE nomor_hp='$nomor_hp'";
        }
        
        if ($conn->query($update_sql) === TRUE) {
            return array('success' => true, 'message' => 'Data user berhasil diperbarui!');
        } else {
            return array('success' => false, 'message' => 'Error: ' . $conn->error);
        }
    } else {
        // User baru
        $insert_sql = "INSERT INTO users (nomor_hp, nama, email) VALUES ('$nomor_hp', '$nama', '$email')";
        
        if ($conn->query($insert_sql) === TRUE) {
            return array('success' => true, 'message' => 'User berhasil terdaftar!');
        } else {
            return array('success' => false, 'message' => 'Error: ' . $conn->error);
        }
    }
}

/**
 * Cek apakah nomor HP valid
 */
function isValidPhoneNumber($nomor_hp) {
    // Format nomor HP Indonesia
    $pattern = '/^(\+62|0)[0-9]{9,12}$/';
    return preg_match($pattern, $nomor_hp);
}

// ===== FORMAT FUNCTIONS =====

/**
 * Format ke Rupiah
 */
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

/**
 * Format tanggal ke bahasa Indonesia
 */
function formatTanggalIndo($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

?>
