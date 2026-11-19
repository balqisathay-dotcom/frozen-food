<?php
$halaman = 'produk';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = ambil_id($_GET['id'] ?? ($_POST['id'] ?? 0));
$data = ['nama_produk' => '', 'id_kategori' => '', 'id_supplier' => '', 'harga' => '', 'stok' => ''];
$errors = [];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM produk WHERE id_produk = ?');
    $stmt->execute([$id]);
    $ada = $stmt->fetch();
    if (!$ada) {
        set_flash('danger', 'Produk tidak ditemukan.');
        redirect('produk.php');
    }
    $data = $ada;
}

$daftar_kategori = $pdo->query('SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori')->fetchAll();
$daftar_supplier = $pdo->query('SELECT id_supplier, nama_supplier FROM supplier ORDER BY nama_supplier')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama_produk'] = trim($_POST['nama_produk'] ?? '');
    $data['id_kategori'] = trim($_POST['id_kategori'] ?? '');
    $data['id_supplier'] = trim($_POST['id_supplier'] ?? '');
    $data['harga'] = trim($_POST['harga'] ?? '');
    $data['stok'] = trim($_POST['stok'] ?? '');

    if (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid, silakan coba lagi.';
    }
    if ($data['nama_produk'] === '') {
        $errors[] = 'Nama produk tidak boleh kosong.';
    } elseif (mb_strlen($data['nama_produk']) > 150) {
        $errors[] = 'Nama produk maksimal 150 karakter.';
    }

    $ada_kategori = false;
    foreach ($daftar_kategori as $k) {
        if ((string) $k['id_kategori'] === $data['id_kategori']) {
            $ada_kategori = true;
        }
    }
    if ($data['id_kategori'] === '') {
        $errors[] = 'Kategori harus dipilih.';
    } elseif (!$ada_kategori) {
        $errors[] = 'Kategori tidak valid.';
    }

    $ada_supplier = false;
    foreach ($daftar_supplier as $s) {
        if ((string) $s['id_supplier'] === $data['id_supplier']) {
            $ada_supplier = true;
        }
    }
    if ($data['id_supplier'] === '') {
        $errors[] = 'Supplier harus dipilih.';
    } elseif (!$ada_supplier) {
        $errors[] = 'Supplier tidak valid.';
    }

    if ($data['harga'] === '') {
        $errors[] = 'Harga tidak boleh kosong.';
    } elseif (!angka_valid($data['harga']) || strlen($data['harga']) > 9) {
        $errors[] = 'Harga harus berupa angka bulat positif (maksimal 9 digit).';
    }
    if ($data['stok'] === '') {
        $errors[] = 'Stok tidak boleh kosong.';
    } elseif (!angka_valid($data['stok']) || strlen($data['stok']) > 9) {
        $errors[] = 'Stok harus berupa angka bulat positif (maksimal 9 digit).';
    }

    if (!$errors) {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare(
                    'UPDATE produk SET nama_produk = ?, id_kategori = ?, id_supplier = ?, harga = ?, stok = ?
                     WHERE id_produk = ?'
                );
                $stmt->execute([$data['nama_produk'], $data['id_kategori'], $data['id_supplier'], $data['harga'], $data['stok'], $id]);
                set_flash('success', 'Produk berhasil diubah.');
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO produk (nama_produk, id_kategori, id_supplier, harga, stok) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([$data['nama_produk'], $data['id_kategori'], $data['id_supplier'], $data['harga'], $data['stok']]);
                set_flash('success', 'Produk berhasil ditambahkan.');
            }
            redirect('produk.php');
        } catch (PDOException $ex) {
            $errors[] = 'Data gagal disimpan. Silakan coba lagi.';
        }
    }
}

$judul = $id > 0 ? 'Ubah Produk' : 'Tambah Produk';
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
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" id="nama_produk" name="nama_produk" class="form-control" maxlength="150"
               value="<?= e($data['nama_produk']) ?>" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="id_kategori" class="form-label">Kategori</label>
            <select id="id_kategori" name="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($daftar_kategori as $k): ?>
                    <option value="<?= (int) $k['id_kategori'] ?>" <?= (string) $data['id_kategori'] === (string) $k['id_kategori'] ? 'selected' : '' ?>>
                        <?= e($k['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="id_supplier" class="form-label">Supplier</label>
            <select id="id_supplier" name="id_supplier" class="form-select" required>
                <option value="">-- Pilih Supplier --</option>
                <?php foreach ($daftar_supplier as $s): ?>
                    <option value="<?= (int) $s['id_supplier'] ?>" <?= (string) $data['id_supplier'] === (string) $s['id_supplier'] ? 'selected' : '' ?>>
                        <?= e($s['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="text" inputmode="numeric" id="harga" name="harga" class="form-control"
                   value="<?= e($data['harga']) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="text" inputmode="numeric" id="stok" name="stok" class="form-control"
                   value="<?= e($data['stok']) ?>" required>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="produk.php" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
