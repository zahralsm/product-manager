<?php

require_once "../config/database.php";

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ATK Store</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f7fb;
            color: #333;
        }

        /* HEADER */

        .header {
            background-color: #3b82f6;
            color: white;
            padding: 25px 20px;
        }

        .header-content {
            max-width: 1100px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0 0 5px;
            font-size: 27px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
        }

        /* BUTTON */

        .btn-tambah {
            background-color: #fbbf24;
            color: #333;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background-color: #f59e0b;
        }

        /* CONTAINER */

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .judul {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .judul h2 {
            margin: 0;
            color: #2563eb;
        }

        /* TABLE */

        .table-container {
            background-color: white;
            border-radius: 8px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #3b82f6;
            color: white;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        /* BUTTON AKSI */

        .btn-edit {
            background-color: #facc15;
            color: #333;
            padding: 7px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-edit:hover {
            background-color: #eab308;
        }

        .btn-hapus {
            background-color: #ef4444;
            color: white;
            padding: 7px 10px;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-hapus:hover {
            background-color: #dc2626;
        }

        .aksi {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .aksi form {
            margin: 0;
        }

        /* EMPTY */

        .kosong {
            background-color: white;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            color: #777;
        }

        /* RESPONSIVE */

        @media (max-width: 600px) {

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-tambah {
                display: inline-block;
            }

            .judul {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            th,
            td {
                padding: 9px;
                font-size: 13px;
            }

        }

    </style>

</head>

<body>


<header class="header">

    <div class="header-content">

        <div>
            <h1>ATK Store</h1>

            <p>
                Sistem Pengelolaan Produk Alat Tulis Kantor
            </p>
        </div>

        <a href="tambah.php" class="btn-tambah">
            + Tambah Produk
        </a>

    </div>

</header>


<main class="container">


    <div class="judul">

        <h2>Daftar Produk</h2>

    </div>


    <?php if (count($products) > 0): ?>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($products as $index => $product): ?>

                        <tr>

                            <td>
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $product['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $product['kategori'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                Rp <?= number_format(
                                    $product['harga'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td>
                                <?= (int) $product['stok'] ?>
                            </td>

                            <td>

                                <div class="aksi">

                                    <a
                                        href="edit.php?id=<?= (int) $product['id'] ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="hapus.php"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Yakin ingin menghapus produk ini?'
                                        );"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $product['id'] ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars(
                                                $_SESSION['csrf_token'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn-hapus"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="kosong">

            <h3>Belum ada produk</h3>

            <p>
                Silakan tambahkan produk terlebih dahulu.
            </p>

        </div>

    <?php endif; ?>


</main>

</body>

</html>