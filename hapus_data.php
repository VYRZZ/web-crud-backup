<?php
include 'koneksi.php';

$nis = $_GET['nis'];

$query = mysqli_query($koneksi, "DELETE FROM data_siswa WHERE nis='$nis'");

if ($query) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
