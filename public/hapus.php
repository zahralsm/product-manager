<?php

session_start();

require_once "../config/database.php";

// Pastikan request menggunakan POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Metode request tidak diizinkan.");
}

// Cek CSRF token
$csrfToken = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $csrfToken)
) {
    http_response_code(403);
    die("CSRF token tidak valid.");
}

// Validasi ID
$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    die("ID produk tidak valid.");
}

// Hapus produk menggunakan prepared statement
$stmt = $pdo->prepare(
    "DELETE FROM products WHERE id = ?"
);

$stmt->execute([$id]);

// Kembali ke halaman utama
header("Location: index.php");
exit;