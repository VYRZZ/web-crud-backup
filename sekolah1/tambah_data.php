<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk Admin.'); window.location='index.php';</script>";
    exit;
}

include 'koneksi.php';

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $nis           = trim($_POST['nis']);
    $nama          = trim($_POST['nama']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    $alamat        = trim($_POST['alamat']);
    $nomor_telepon = trim($_POST['nomor_telepon']);

    if (empty($nis) || empty($nama) || empty($jenis_kelamin) || empty($alamat) || empty($nomor_telepon)) {
        $error = "Semua field wajib diisi!";
    } else {
        $check_stmt = mysqli_prepare($koneksi, "SELECT nis FROM data_siswa WHERE nis = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $nis);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "NIS sudah terdaftar! Gunakan NIS lain.";
        } else {
            $insert_stmt = mysqli_prepare($koneksi, "INSERT INTO data_siswa (nis, nama, jenis_kelamin, alamat, nomor_telepon) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sssss", $nis, $nama, $jenis_kelamin, $alamat, $nomor_telepon);

            if (mysqli_stmt_execute($insert_stmt)) {
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal menambahkan data: " . mysqli_error($koneksi);
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Siswa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7f5;
            color: #333;
            padding: 30px 20px;
        }
        .container { max-width: 600px; margin: auto; }
        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid #dfe7e2;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: #52796f;
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #444; }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }
        textarea { resize: vertical; height: 80px; }
        .btn-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn-submit {
            background: #52796f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-submit:hover { background: #3f6259; }
        .btn-back {
            background: #888;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .btn-back:hover { background: #666; }
        .alert {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="header">
            <h2>Tambah Data Siswa</h2>
        </div>

        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nis">NIS</label>
                <input type="text" id="nis" name="nis" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="">-- Pilih --</option>
                    <option value="L">Laki-laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" required></textarea>
            </div>
            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" required>
            </div>
            <div class="btn-group">
                <button type="submit" name="submit" class="btn-submit">Simpan</button>
                <a href="index.php" class="btn-back">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>