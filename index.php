<?php
$judul = 'Beranda';
$halaman = 'beranda';
require_once __DIR__ . '/includes/header.php';

$total_produk = (int) $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
$total_kategori = (int) $pdo->query('SELECT COUNT(*) FROM kategori')->fetchColumn();
$total_supplier = (int) $pdo->query('SELECT COUNT(*) FROM supplier')->fetchColumn();
$total_stok = (int) $pdo->query('SELECT COALESCE(SUM(stok), 0) FROM produk')->fetchColumn();

$per_kategori = $pdo->query(
    'SELECT k.id_kategori, k.nama_kategori, COUNT(p.id_produk) AS jumlah
     FROM kategori k LEFT JOIN produk p ON p.id_kategori = k.id_kategori
     GROUP BY k.id_kategori, k.nama_kategori ORDER BY k.nama_kategori'
)->fetchAll();

$per_supplier = $pdo->query(
    'SELECT s.id_supplier, s.nama_supplier, COUNT(p.id_produk) AS jumlah
     FROM supplier s LEFT JOIN produk p ON p.id_supplier = s.id_supplier
     GROUP BY s.id_supplier, s.nama_supplier ORDER BY s.nama_supplier'
)->fetchAll();
?>
<h1 class="h3 mb-4">Selamat datang, Pak Budi</h1>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card card-stat text-center"><div class="card-body">
            <div class="display-6"><?= $total_produk ?></div><div class="text-muted">Produk</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat text-center"><div class="card-body">
            <div class="display-6"><?= $total_kategori ?></div><div class="text-muted">Kategori</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat text-center"><div class="card-body">
            <div class="display-6"><?= $total_supplier ?></div><div class="text-muted">Supplier</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card card-stat text-center"><div class="card-body">
            <div class="display-6"><?= $total_stok ?></div><div class="text-muted">Total Stok</div>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Produk per Kategori</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($per_kategori as $baris): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="produk.php?kategori=<?= (int) $baris['id_kategori'] ?>"><?= e($baris['nama_kategori']) ?></a>
                        <span class="badge bg-primary rounded-pill"><?= (int) $baris['jumlah'] ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (!$per_kategori): ?>
                    <li class="list-group-item text-muted">Belum ada kategori.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-semibold">Produk per Supplier</div>
            <ul class="list-group list-group-flush">
                <?php foreach ($per_supplier as $baris): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="produk.php?supplier=<?= (int) $baris['id_supplier'] ?>"><?= e($baris['nama_supplier']) ?></a>
                        <span class="badge bg-primary rounded-pill"><?= (int) $baris['jumlah'] ?></span>
                    </li>
                <?php endforeach; ?>
                <?php if (!$per_supplier): ?>
                    <li class="list-group-item text-muted">Belum ada supplier.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
