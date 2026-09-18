<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk Admin.'); window.location='index.php';</script>";
    exit;
}

include 'koneksi.php';

if (isset($_GET['nis']) && !empty($_GET['nis'])) {
    $nis = $_GET['nis'];

    $stmt = mysqli_prepare($koneksi, "DELETE FROM data_siswa WHERE nis = ?");
    mysqli_stmt_bind_param($stmt, "s", $nis);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: index.php");
exit;
?>