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

    <title>Product Manager</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #eef5ff;
            margin: 0;
            padding: 30px;
        }

        .header {
            background-color: #dbeafe;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
        }

        .header-content {
            max-width: 1000px;
            margin: auto;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            margin: 0;
            color: #2563eb;
        }

        .header p {
            margin-bottom: 0;
            color: #64748b;
        }

        .tambah {
            background-color: #3b82f6;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .tambah:hover {
            background-color: #2563eb;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
        }

        h2 {
            color: #2563eb;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #60a5fa;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f8fbff;
        }

        .aksi {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .edit {
            background-color: #dbeafe;
            color: #2563eb;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .edit:hover {
            background-color: #bfdbfe;
        }

        .hapus {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 7px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .hapus:hover {
            background-color: #fecaca;
        }

        .kosong {
            text-align: center;
            color: #64748b;
            padding: 30px;
        }

        @media (max-width: 600px) {

            body {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .tambah {
                width: 100%;
                text-align: center;
            }

            table {
                font-size: 13px;
            }

            th,
            td {
                padding: 8px;
            }

        }

    </style>

</head>

<body>


<div class="header">

    <div class="header-content">

        <div>

            <h1>📦 Product Manager</h1>

            <p>
                Kelola data produk
            </p>

        </div>

        <a href="tambah.php" class="tambah">
            + Tambah Produk
        </a>

    </div>

</div>


<div class="container">

    <h2>Daftar Produk</h2>


    <?php if (count($products) > 0): ?>

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
                                    class="edit"
                                >
                                    Edit
                                </a>


                                <form
                                    action="hapus.php"
                                    method="POST"
                                    style="margin: 0;"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?');"
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
                                        class="hapus"
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


    <?php else: ?>

        <div class="kosong">

            <h3>Belum ada produk</h3>

            <p>
                Silakan tambahkan produk terlebih dahulu.
            </p>

        </div>

    <?php endif; ?>


</div>

</body>

</html>