<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/functions.php';

$halaman = $halaman ?? '';
$flash = get_flash();

function nav_aktif($nama, $halaman)
{
    return $nama === $halaman ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Frozen Food Pak Budi') ?> - Frozen Food Pak Budi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">&#10052; Frozen Food Pak Budi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link<?= nav_aktif('beranda', $halaman) ?>" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link<?= nav_aktif('produk', $halaman) ?>" href="produk.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link<?= nav_aktif('kategori', $halaman) ?>" href="kategori.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link<?= nav_aktif('supplier', $halaman) ?>" href="supplier.php">Supplier</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container my-4">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['tipe']) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['pesan']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
