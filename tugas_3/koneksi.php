<?php

$server = "localhost";
$user = "root";
$password = "";
$nama_database = "pendaftaran_siswa";

$db = mysqli_connect($server, $user, $password, $nama_database);

if( !$db ){
    // Stop program jika gagal terhubung ke database
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}

?>