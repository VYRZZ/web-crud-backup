<?php
include 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM data_siswa ORDER BY nis ASC");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            color: #333;
            background: #f5f7f5;
            padding: 30px 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            width: 600px;
            height: 600px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-image: url('images.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            position: relative;
            z-index: 1;
        }

        .header {
            background: #52796f;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.10);
        }

        .header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #e8f1ed;
            font-size: 14px;
        }

        .btn-tambah {
            background: white;
            color: #355c4a;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
        }

        .btn-tambah:hover {
            background: #e8f1ed;
        }

        .table-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 8px;
            border: 1px solid #dfe7e2;
            overflow-x: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        thead {
            background: #e8f1ed;
        }

        th {
            text-align: left;
            padding: 13px 15px;
            font-size: 13px;
            color: #355c4a;
            border-bottom: 1px solid #dfe7e2;
        }

        td {
            padding: 13px 15px;
            font-size: 14px;
            border-bottom: 1px solid #eeeeee;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8faf9;
        }

        .nomor {
            color: #888;
            width: 50px;
        }

        .gender {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            background: #e8f1ed;
            color: #355c4a;
        }

        .aksi {
            white-space: nowrap;
        }

        .edit {
            color: #52796f;
            text-decoration: none;
            font-weight: bold;
            margin-right: 12px;
        }

        .edit:hover {
            text-decoration: underline;
        }

        .delete {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .delete:hover {
            text-decoration: underline;
        }

        .empty {
            text-align: center;
            padding: 40px !important;
            color: #888;
        }

        @media (max-width: 700px) {

            body {
                padding: 20px 12px;
            }

            body::before {
                width: 350px;
                height: 350px;
                opacity: 0.06;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header h2 {
                font-size: 21px;
            }

            .btn-tambah {
                width: 100%;
                text-align: center;
            }

            .table-card {
                border-radius: 8px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="header">

        <div>

            <h2>Daftar Data Siswa</h2>

            <p>
                Kelola data siswa yang tersimpan di dalam sistem.
            </p>

        </div>

        <a
            href="tambah_data.php"
            class="btn-tambah">
            + Tambah Data Siswa
        </a>

    </div>

    <div class="table-card">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>L/P</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

            <?php

            $no = 1;

            if (mysqli_num_rows($query) > 0) {

                while ($row = mysqli_fetch_assoc($query)) {

            ?>

                <tr>

                    <td class="nomor">
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nis']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nama']); ?>
                    </td>

                    <td>

                        <span class="gender">
                            <?= htmlspecialchars($row['jenis_kelamin']); ?>
                        </span>

                    </td>

                    <td>
                        <?= htmlspecialchars($row['alamat']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['nomor_telepon']); ?>
                    </td>

                    <td class="aksi">

                        <a
                            href="edit_data.php?nis=<?= urlencode($row['nis']); ?>"
                            class="edit">
                            Edit
                        </a>

                        <a
                            href="hapus_data.php?nis=<?= urlencode($row['nis']); ?>"
                            class="delete"
                            onclick="return confirm('Yakin ingin menghapus data siswa ini?');">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="7" class="empty">
                        Belum ada data siswa.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>
