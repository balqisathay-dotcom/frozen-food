<?php
$judul = 'Daftar Supplier';
$halaman = 'supplier';
require_once __DIR__ . '/includes/header.php';

$daftar = $pdo->query(
    'SELECT s.id_supplier, s.nama_supplier, s.telepon, s.alamat, COUNT(p.id_produk) AS jumlah_produk
     FROM supplier s LEFT JOIN produk p ON p.id_supplier = s.id_supplier
     GROUP BY s.id_supplier, s.nama_supplier, s.telepon, s.alamat ORDER BY s.nama_supplier'
)->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Daftar Supplier</h1>
    <a href="supplier_form.php" class="btn btn-primary">+ Tambah Supplier</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle bg-white">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th class="text-end">Jumlah Produk</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($daftar as $no => $s): ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= e($s['nama_supplier']) ?></td>
                <td><?= e($s['telepon']) ?></td>
                <td><?= e($s['alamat']) ?></td>
                <td class="text-end">
                    <a href="produk.php?supplier=<?= (int) $s['id_supplier'] ?>"><?= (int) $s['jumlah_produk'] ?> produk</a>
                </td>
                <td class="text-center text-nowrap">
                    <a href="supplier_form.php?id=<?= (int) $s['id_supplier'] ?>" class="btn btn-sm btn-warning">Ubah</a>
                    <form method="post" action="supplier_hapus.php" class="d-inline form-hapus"
                          data-pesan="Yakin ingin menghapus supplier &quot;<?= e($s['nama_supplier']) ?>&quot;?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $s['id_supplier'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$daftar): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada supplier.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
