<?php
$judul = 'Daftar Kategori';
$halaman = 'kategori';
require_once __DIR__ . '/includes/header.php';

$daftar = $pdo->query(
    'SELECT k.id_kategori, k.nama_kategori, COUNT(p.id_produk) AS jumlah_produk
     FROM kategori k LEFT JOIN produk p ON p.id_kategori = k.id_kategori
     GROUP BY k.id_kategori, k.nama_kategori ORDER BY k.nama_kategori'
)->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Daftar Kategori</h1>
    <a href="kategori_form.php" class="btn btn-primary">+ Tambah Kategori</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle bg-white">
        <thead class="table-primary">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th class="text-end">Jumlah Produk</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($daftar as $no => $k): ?>
            <tr>
                <td><?= $no + 1 ?></td>
                <td><?= e($k['nama_kategori']) ?></td>
                <td class="text-end">
                    <a href="produk.php?kategori=<?= (int) $k['id_kategori'] ?>"><?= (int) $k['jumlah_produk'] ?> produk</a>
                </td>
                <td class="text-center text-nowrap">
                    <a href="kategori_form.php?id=<?= (int) $k['id_kategori'] ?>" class="btn btn-sm btn-warning">Ubah</a>
                    <form method="post" action="kategori_hapus.php" class="d-inline form-hapus"
                          data-pesan="Yakin ingin menghapus kategori &quot;<?= e($k['nama_kategori']) ?>&quot;?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $k['id_kategori'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$daftar): ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada kategori.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
