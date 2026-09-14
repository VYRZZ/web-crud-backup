<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "data_sekolah";

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur charset
mysqli_set_charset($koneksi, "utf8mb4");

?>
