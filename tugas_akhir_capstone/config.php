<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'bali_tourism');

// Buat koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    // Database berhasil dibuat atau sudah ada
}

// Pilih database
$conn->select_db(DB_NAME);

// Set charset
$conn->set_charset("utf8");

// Buat tabel bookings jika belum ada
$sql_bookings = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pemesan VARCHAR(100) NOT NULL,
    nomor_hp VARCHAR(20) NOT NULL,
    tanggal_pesan DATE NOT NULL,
    waktu_pelaksanaan_mulai DATE NOT NULL,
    waktu_pelaksanaan_hari INT NOT NULL DEFAULT 1,
    pelayanan TEXT NOT NULL,
    jumlah_peserta INT NOT NULL,
    harga_paket DECIMAL(10, 2) NOT NULL,
    jumlah_tagihan DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_bookings) === TRUE) {
    // Tabel berhasil dibuat
}

// Buat tabel users jika belum ada
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_hp VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    // Tabel berhasil dibuat
}
?>
