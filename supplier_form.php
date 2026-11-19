<?php
$halaman = 'supplier';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = ambil_id($_GET['id'] ?? ($_POST['id'] ?? 0));
$data = ['nama_supplier' => '', 'telepon' => '', 'alamat' => ''];
$errors = [];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM supplier WHERE id_supplier = ?');
    $stmt->execute([$id]);
    $ada = $stmt->fetch();
    if (!$ada) {
        set_flash('danger', 'Supplier tidak ditemukan.');
        redirect('supplier.php');
    }
    $data = $ada;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama_supplier'] = trim($_POST['nama_supplier'] ?? '');
    $data['telepon'] = trim($_POST['telepon'] ?? '');
    $data['alamat'] = trim($_POST['alamat'] ?? '');

    if (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid, silakan coba lagi.';
    }
    if ($data['nama_supplier'] === '') {
        $errors[] = 'Nama supplier tidak boleh kosong.';
    } elseif (mb_strlen($data['nama_supplier']) > 100) {
        $errors[] = 'Nama supplier maksimal 100 karakter.';
    }
    if ($data['telepon'] === '') {
        $errors[] = 'Telepon tidak boleh kosong.';
    } elseif (!preg_match('/^\+?\d{6,20}$/', $data['telepon'])) {
        $errors[] = 'Telepon harus berupa angka (6-20 digit).';
    }
    if ($data['alamat'] === '') {
        $errors[] = 'Alamat tidak boleh kosong.';
    } elseif (mb_strlen($data['alamat']) > 255) {
        $errors[] = 'Alamat maksimal 255 karakter.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM supplier WHERE nama_supplier = ? AND id_supplier <> ?');
        $stmt->execute([$data['nama_supplier'], $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            $errors[] = 'Nama supplier sudah ada.';
        }
    }

    if (!$errors) {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE supplier SET nama_supplier = ?, telepon = ?, alamat = ? WHERE id_supplier = ?');
                $stmt->execute([$data['nama_supplier'], $data['telepon'], $data['alamat'], $id]);
                set_flash('success', 'Supplier berhasil diubah.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO supplier (nama_supplier, telepon, alamat) VALUES (?, ?, ?)');
                $stmt->execute([$data['nama_supplier'], $data['telepon'], $data['alamat']]);
                set_flash('success', 'Supplier berhasil ditambahkan.');
            }
            redirect('supplier.php');
        } catch (PDOException $ex) {
            $errors[] = 'Data gagal disimpan. Silakan coba lagi.';
        }
    }
}

$judul = $id > 0 ? 'Ubah Supplier' : 'Tambah Supplier';
require_once __DIR__ . '/includes/header.php';
?>
<h1 class="h3 mb-3"><?= e($judul) ?></h1>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $pesan): ?>
                <li><?= e($pesan) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="card card-body" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $id ?>">
    <div class="mb-3">
        <label for="nama_supplier" class="form-label">Nama Supplier</label>
        <input type="text" id="nama_supplier" name="nama_supplier" class="form-control" maxlength="100"
               value="<?= e($data['nama_supplier']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="telepon" class="form-label">Telepon</label>
        <input type="text" inputmode="tel" id="telepon" name="telepon" class="form-control" maxlength="20"
               value="<?= e($data['telepon']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea id="alamat" name="alamat" class="form-control" rows="3" maxlength="255" required><?= e($data['alamat']) ?></textarea>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="supplier.php" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
