<?php

include("koneksi.php");

// cek apakah tombol daftar sudah diklik atau belum?
if(isset($_POST['daftar'])){
	
	// ambil data dari formulir
	$nama = $_POST['nama'];
	$alamat = $_POST['alamat'];
	$jk = $_POST['jenis_kelamin'];
	$agama = $_POST['agama'];
	$sekolah = $_POST['sekolah_asal']; // Perbaikan Error 1: Tambah $
	
	// Siapkan query SQL menggunakan placeholder '?'
	$sql = "INSERT INTO calon_siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES (?, ?, ?, ?, ?)"; // Perbaikan Error 3: Ganti VALUE menjadi VALUES

	// Inisialisasi prepared statement
	$stmt = mysqli_prepare($db, $sql);

	// Periksa jika statement berhasil diinisialisasi
	if ($stmt) {
		// Bind parameter (mengaitkan variabel dengan placeholder '?' pada query)
		// s = string, i = integer, d = double, b = blob
		mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $jk, $agama, $sekolah);

		// Eksekusi statement
		$query = mysqli_stmt_execute($stmt);

		// Tutup statement
		mysqli_stmt_close($stmt);

		// Cek apakah eksekusi berhasil
		if( $query ) {
			// kalau berhasil alihkan ke halaman index.php dengan status=sukses
			header('Location: index.php?status=sukses');
		} else {
			// kalau gagal alihkan ke halaman index.php dengan status=gagal
			header('Location: index.php?status=gagal'); // Perbaikan Error 5: index.php
		}
	} else {
		// Jika statement gagal disiapkan
		die("Gagal menyiapkan statement: " . mysqli_error($db));
	}
	
	
} else {
	die("Akses dilarang...");
}

?>