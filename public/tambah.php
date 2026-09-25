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

    // Validasi nama
    if (strlen($nama) < 3) {
        $errors[] = "Nama produk minimal 3 karakter.";
    }

    // Validasi kategori
    if ($kategori === "") {
        $errors[] = "Kategori wajib diisi.";
    }

    // Validasi harga
    if ($harga === "" || !is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka dan tidak boleh negatif.";
    }

    // Validasi stok
    if ($stok === "" || !filter_var($stok, FILTER_VALIDATE_INT) || $stok < 0) {
        $errors[] = "Stok harus berupa bilangan bulat dan tidak boleh negatif.";
    }

    // Cek nama produk jika validasi sebelumnya tidak memiliki error
    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE nama = ?"
        );

        $stmt->execute([$nama]);

        if ($stmt->fetch()) {
            $errors[] = "Nama produk sudah digunakan.";
        }
    }

    // Simpan produk
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

        // Redirect agar refresh tidak membuat data terduplikasi
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

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }

        .btn {
            margin-top: 20px;
            padding: 11px 18px;
            border: none;
            border-radius: 7px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .errors li {
            margin-bottom: 5px;
        }

    </style>
</head>

<body>

<div class="container">

    <h1>Tambah Produk</h1>

    <?php if (!empty($errors)): ?>

        <div class="errors">

            <ul>
                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
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
            value="<?= htmlspecialchars($nama, ENT_QUOTES, "UTF-8") ?>"
            required
        >


        <label for="kategori">
            Kategori
        </label>

        <input
            type="text"
            id="kategori"
            name="kategori"
            value="<?= htmlspecialchars($kategori, ENT_QUOTES, "UTF-8") ?>"
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
            value="<?= htmlspecialchars($harga, ENT_QUOTES, "UTF-8") ?>"
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
            value="<?= htmlspecialchars($stok, ENT_QUOTES, "UTF-8") ?>"
            required
        >


        <button type="submit" class="btn">
            Simpan Produk
        </button>

    </form>


    <a href="index.php" class="back">
        ← Kembali ke daftar produk
    </a>

</div>

</body>
</html>