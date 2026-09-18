<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk Admin.'); window.location='index.php';</script>";
    exit;
}

include 'koneksi.php';

$error = '';

if (!isset($_GET['nis']) || empty($_GET['nis'])) {
    header("Location: index.php");
    exit;
}

$nis_param = $_GET['nis'];

if (isset($_POST['update'])) {
    $nama          = trim($_POST['nama']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    $alamat        = trim($_POST['alamat']);
    $nomor_telepon = trim($_POST['nomor_telepon']);

    if (empty($nama) || empty($jenis_kelamin) || empty($alamat) || empty($nomor_telepon)) {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE data_siswa SET nama = ?, jenis_kelamin = ?, alamat = ?, nomor_telepon = ? WHERE nis = ?");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $jenis_kelamin, $alamat, $nomor_telepon, $nis_param);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    }
}

$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM data_siswa WHERE nis = ?");
mysqli_stmt_bind_param($stmt_get, "s", $nis_param);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$data   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

if (!$data) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
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
        input[readonly] { background: #eee; cursor: not-allowed; }
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
            <h2>Edit Data Siswa</h2>
        </div>

        <?php if ($error): ?>
            <div class="alert"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nis">NIS (Tidab Bisa Diubah)</label>
                <input type="text" id="nis" value="<?= htmlspecialchars($data['nis']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="L" <?= ($data['jenis_kelamin'] === 'L') ? 'selected' : ''; ?>>Laki-laki (L)</option>
                    <option value="P" <?= ($data['jenis_kelamin'] === 'P') ? 'selected' : ''; ?>>Perempuan (P)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" required><?= htmlspecialchars($data['alamat']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="<?= htmlspecialchars($data['nomor_telepon']); ?>" required>
            </div>
            <div class="btn-group">
                <button type="submit" name="update" class="btn-submit">Update Data</button>
                <a href="index.php" class="btn-back">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>