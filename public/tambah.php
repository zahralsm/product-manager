<?php

require_once "../config/database.php";

$errors = [];

$nama = "";
$kategori = "";
$harga = "";
$stok = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");
    $harga = $_POST["harga"] ?? "";
    $stok = $_POST["stok"] ?? "";

    if (strlen($nama) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }

    if ($kategori === "") {
        $errors[] = "Kategori wajib diisi.";
    }

    if ($harga === "" || !is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka dan tidak boleh negatif.";
    }

    if ($stok === "" || !filter_var($stok, FILTER_VALIDATE_INT) || $stok < 0) {
        $errors[] = "Stok harus berupa bilangan bulat dan tidak boleh negatif.";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE nama = ?"
        );

        $stmt->execute([$nama]);

        if ($stmt->fetch()) {
            $errors[] = "Nama produk sudah digunakan.";
        }
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "INSERT INTO products (nama, kategori, harga, stok)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $nama,
            $kategori,
            $harga,
            $stok
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk</title>

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

        .header h1 {
            margin: 0;
            color: #2563eb;
        }

        .header p {
            margin-bottom: 0;
            color: #64748b;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
        }

        h2 {
            color: #2563eb;
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #bfdbfe;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .simpan {
            background-color: #3b82f6;
            color: white;
        }

        .kembali {
            background-color: #dbeafe;
            color: #2563eb;
            margin-left: 5px;
        }

        .errors {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 10px 15px;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>📦 Product Manager</h1>

    <p>
        Kelola data produk
    </p>

</div>


<div class="container">

    <h2>Tambah Produk</h2>


    <?php if (!empty($errors)): ?>

        <div class="errors">

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <form method="POST">

        <label for="nama">
            Nama Produk
        </label>

        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= htmlspecialchars(
                $nama,
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >


        <label for="kategori">
            Kategori
        </label>

        <input
            type="text"
            id="kategori"
            name="kategori"
            value="<?= htmlspecialchars(
                $kategori,
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >


        <label for="harga">
            Harga
        </label>

        <input
            type="number"
            id="harga"
            name="harga"
            min="0"
            value="<?= htmlspecialchars(
                $harga,
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >


        <label for="stok">
            Stok
        </label>

        <input
            type="number"
            id="stok"
            name="stok"
            min="0"
            value="<?= htmlspecialchars(
                $stok,
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
            required
        >


        <button
            type="submit"
            class="btn simpan"
        >
            Simpan Produk
        </button>


        <a
            href="index.php"
            class="btn kembali"
        >
            Kembali
        </a>

    </form>

</div>

</body>

</html>