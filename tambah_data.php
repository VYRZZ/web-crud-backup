<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {

    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $alamat = trim($_POST['alamat']);
    $nomor_telepon = trim($_POST['nomor_telepon']);

    $cek = mysqli_prepare(
        $koneksi,
        "SELECT nis FROM data_siswa WHERE nis = ?"
    );

    if (!$cek) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($cek, "s", $nis);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {

        mysqli_stmt_close($cek);

        echo "<script>
                alert('NIS sudah terdaftar!');
                window.location='tambah_data.php';
              </script>";

        exit;
    }

    mysqli_stmt_close($cek);

    $query = mysqli_prepare(
        $koneksi,
        "INSERT INTO data_siswa
        (nis, nama, jenis_kelamin, alamat, nomor_telepon)
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$query) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $query,
        "sssss",
        $nis,
        $nama,
        $jenis_kelamin,
        $alamat,
        $nomor_telepon
    );

    if (mysqli_stmt_execute($query)) {

        mysqli_stmt_close($query);

        echo "<script>
                alert('Data siswa berhasil ditambahkan!');
                window.location='index.php';
              </script>";

        exit;

    } else {

        $error = mysqli_stmt_error($query);
        mysqli_stmt_close($query);

        die("Data gagal ditambahkan: " . $error);
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
            transition: border-color 0.2s ease;
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

        input::placeholder,
        textarea::placeholder {
            color: #aaa;
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
            transition: background 0.2s ease;
        }

        .btn-simpan:hover {
            background: #3f6259;
        }

        .btn-kembali {
            display: inline-block;
            padding: 10px 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            background: white;
            transition: background 0.2s ease;
        }

        .btn-kembali:hover {
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
            .btn-kembali {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <h2>Tambah Data Siswa</h2>

        <p>
            Isi formulir berikut untuk menambahkan siswa baru.
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
                    placeholder="Masukkan NIS"
                    autocomplete="off"
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
                    placeholder="Masukkan nama lengkap"
                    autocomplete="name"
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

                    <option value="" disabled selected>
                        Pilih jenis kelamin
                    </option>

                    <option value="L">
                        Laki-laki
                    </option>

                    <option value="P">
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
                    placeholder="Masukkan alamat siswa"
                    required
                ></textarea>

            </div>

            <div class="form-group">

                <label for="nomor_telepon">
                    Nomor Telepon
                </label>

                <input
                    type="tel"
                    id="nomor_telepon"
                    name="nomor_telepon"
                    placeholder="Contoh: 08123456789"
                    autocomplete="tel"
                    required
                >

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    name="simpan"
                    class="btn-simpan"
                >
                    Simpan Data
                </button>

                <a
                    href="index.php"
                    class="btn-kembali"
                >
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>
