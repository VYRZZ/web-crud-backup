<?php
include 'koneksi.php';

$nis_lama = $_GET['nis'] ?? '';

if ($nis_lama == '') {
    die("NIS tidak ditemukan.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM data_siswa WHERE nis = ?"
);

mysqli_stmt_bind_param($stmt, "s", $nis_lama);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data siswa tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (isset($_POST['simpan'])) {

    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = trim($_POST['alamat']);
    $nomor_telepon = trim($_POST['nomor_telepon']);

    $cek = mysqli_prepare(
        $koneksi,
        "SELECT nis FROM data_siswa WHERE nis = ? AND nis != ?"
    );

    mysqli_stmt_bind_param($cek, "ss", $nis, $nis_lama);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {

        mysqli_stmt_close($cek);

        echo "<script>
                alert('NIS tersebut sudah digunakan oleh siswa lain!');
                window.history.back();
              </script>";

        exit;
    }

    mysqli_stmt_close($cek);

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE data_siswa SET
            nis = ?,
            nama = ?,
            jenis_kelamin = ?,
            alamat = ?,
            nomor_telepon = ?
        WHERE nis = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "ssssss",
        $nis,
        $nama,
        $jenis_kelamin,
        $alamat,
        $nomor_telepon,
        $nis_lama
    );

    if (mysqli_stmt_execute($update)) {

        mysqli_stmt_close($update);

        echo "<script>
                alert('Data siswa berhasil diperbarui!');
                window.location='index.php';
              </script>";

        exit;

    } else {

        echo "Gagal mengubah data: "
            . mysqli_stmt_error($update);
    }

    mysqli_stmt_close($update);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Data Siswa</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7f5;
            color: #333;
            padding: 35px 20px;
        }

        .container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
        }

        .header {
            background: #52796f;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 22px;
            margin-bottom: 6px;
        }

        .header p {
            color: #e8f1ed;
            font-size: 14px;
        }

        .form-card {
            background: #ffffff;
            border: 1px solid #dfe7e2;
            border-radius: 8px;
            padding: 28px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            color: #444;
            margin-bottom: 7px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fff;
            color: #333;
            font-family: inherit;
            font-size: 14px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #6b9080;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-simpan {
            border: none;
            background: #52796f;
            color: white;
            padding: 11px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-simpan:hover {
            background: #3f6259;
        }

        .btn-batal {
            display: inline-block;
            padding: 10px 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            background: white;
        }

        .btn-batal:hover {
            background: #f3f3f3;
        }

        @media (max-width: 600px) {

            body {
                padding: 20px 12px;
            }

            .header h2 {
                font-size: 20px;
            }

            .form-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-simpan,
            .btn-batal {
                width: 100%;
                text-align: center;
            }

        }

    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h2>Edit Data Siswa</h2>

        <p>
            Ubah data siswa yang diperlukan pada formulir berikut.
        </p>

    </div>

    <div class="form-card">

        <form method="POST" action="">

            <div class="form-group">

                <label for="nis">
                    NIS
                </label>

                <input
                    type="text"
                    id="nis"
                    name="nis"
                    value="<?= htmlspecialchars($data['nis']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="nama">
                    Nama
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?= htmlspecialchars($data['nama']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="jenis_kelamin">
                    Jenis Kelamin
                </label>

                <select
                    id="jenis_kelamin"
                    name="jenis_kelamin"
                    required
                >

                    <option value="L"
                        <?= ($data['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>
                        Laki-laki
                    </option>

                    <option value="P"
                        <?= ($data['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>
                        Perempuan
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label for="alamat">
                    Alamat
                </label>

                <textarea
                    id="alamat"
                    name="alamat"
                    rows="4"
                    required
                ><?= htmlspecialchars($data['alamat']); ?></textarea>

            </div>

            <div class="form-group">

                <label for="nomor_telepon">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    id="nomor_telepon"
                    name="nomor_telepon"
                    value="<?= htmlspecialchars($data['nomor_telepon']); ?>"
                    required
                >

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="index.php"
                    class="btn-batal"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>
