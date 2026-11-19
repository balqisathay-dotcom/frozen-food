<?php
$judul = 'Daftar Produk';
$halaman = 'produk';
require_once __DIR__ . '/includes/header.php';

$id_kategori = ambil_id($_GET['kategori'] ?? 0);
$id_supplier = ambil_id($_GET['supplier'] ?? 0);
$cari = trim($_GET['cari'] ?? '');

$daftar_kategori = $pdo->query('SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori')->fetchAll();
$daftar_supplier = $pdo->query('SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier')->fetchAll();

$sql = 'SELECT p.id_produk, p.nama_produk, p.harga, p.stok, k.nama_kategori, s.nama_supplier
        FROM produk p
        JOIN kategori k ON k.id_kategori = p.id_kategori
        JOIN supplier s ON s.id_supplier = p.id_supplier
        WHERE 1 = 1';
$params = [];

if ($id_kategori > 0) {
    $sql .= ' AND p.id_kategori = ?';
    $params[] = $id_kategori;
}
if ($id_supplier > 0) {
    $sql .= ' AND p.id_supplier = ?';
    $params[] = $id_supplier;
}
if ($cari !== '') {
    $sql .= ' AND p.nama_produk LIKE ?';
    $params[] = '%' . $cari . '%';
}
$sql .= ' ORDER BY p.nama_produk';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$daftar_produk = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Daftar Produk</h1>
    <a href="produk_form.php" class="btn btn-primary">+ Tambah Produk</a>
</div>

<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="kategori" class="form-select">
            <option value="0">Semua Kategori</option>
            <?php foreach ($daftar_kategori as $k): ?>
                <option value="<?= (int) $k['id_kategori'] ?>" <?= $id_kategori === (int) $k['id_kategori'] ? 'selected' : '' ?>>
                    <?= e($k['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="supplier" class="form-select">
            <option value="0">Semua Supplier</option>
            <?php foreach ($daftar_supplier as $s): ?>
                <option value="<?= (int) $s['id_supplier'] ?>" <?= $id_supplier === (int) $s['id_supplier'] ? 'selected' : '' ?>>
                    <?= e($s['nama_supplier']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="cari" class="form-control" placeholder="Cari nama produk" value="<?= e($cari) ?>">
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary">Filter</button>
        <a href="produk.php" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle bg-white">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Stok</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($daftar_produk as $no => $p): ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= e($p['nama_produk']) ?></td>
                <td><?= e($p['nama_kategori']) ?></td>
                <td><?= e($p['nama_supplier']) ?></td>
                <td class="text-end"><?= e(rupiah($p['harga'])) ?></td>
                <td class="text-end"><?= (int) $p['stok'] ?></td>
                <td class="text-center text-nowrap">
                    <a href="produk_form.php?id=<?= (int) $p['id_produk'] ?>" class="btn btn-sm btn-warning">Ubah</a>
                    <form method="post" action="produk_hapus.php" class="d-inline form-hapus"
                          data-pesan="Yakin ingin menghapus produk &quot;<?= e($p['nama_produk']) ?>&quot;?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $p['id_produk'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$daftar_produk): ?>
            <tr><td colspan="7" class="text-center text-muted">Tidak ada produk ditemukan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<p class="text-muted">Menampilkan <?= count($daftar_produk) ?> produk.</p>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
