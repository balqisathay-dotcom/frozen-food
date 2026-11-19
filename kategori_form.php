<?php
$halaman = 'kategori';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = ambil_id($_GET['id'] ?? ($_POST['id'] ?? 0));
$data = ['nama_kategori' => ''];
$errors = [];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM kategori WHERE id_kategori = ?');
    $stmt->execute([$id]);
    $ada = $stmt->fetch();
    if (!$ada) {
        set_flash('danger', 'Kategori tidak ditemukan.');
        redirect('kategori.php');
    }
    $data = $ada;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama_kategori'] = trim($_POST['nama_kategori'] ?? '');

    if (!csrf_valid()) {
        $errors[] = 'Sesi tidak valid, silakan coba lagi.';
    }
    if ($data['nama_kategori'] === '') {
        $errors[] = 'Nama kategori tidak boleh kosong.';
    } elseif (mb_strlen($data['nama_kategori']) > 100) {
        $errors[] = 'Nama kategori maksimal 100 karakter.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM kategori WHERE nama_kategori = ? AND id_kategori <> ?');
        $stmt->execute([$data['nama_kategori'], $id]);
        if ((int) $stmt->fetchColumn() > 0) {
            $errors[] = 'Nama kategori sudah ada.';
        }
    }

    if (!$errors) {
        try {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?');
                $stmt->execute([$data['nama_kategori'], $id]);
                set_flash('success', 'Kategori berhasil diubah.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO kategori (nama_kategori) VALUES (?)');
                $stmt->execute([$data['nama_kategori']]);
                set_flash('success', 'Kategori berhasil ditambahkan.');
            }
            redirect('kategori.php');
        } catch (PDOException $ex) {
            $errors[] = 'Data gagal disimpan. Silakan coba lagi.';
        }
    }
}

$judul = $id > 0 ? 'Ubah Kategori' : 'Tambah Kategori';
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
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" maxlength="100"
               value="<?= e($data['nama_kategori']) ?>" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="kategori.php" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
