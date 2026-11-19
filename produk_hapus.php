<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    set_flash('danger', 'Permintaan tidak valid.');
    redirect('produk.php');
}

$id = ambil_id($_POST['id'] ?? 0);
if ($id === 0) {
    set_flash('danger', 'Produk tidak valid.');
    redirect('produk.php');
}

try {
    $stmt = $pdo->prepare('DELETE FROM produk WHERE id_produk = ?');
    $stmt->execute([$id]);
    set_flash('success', 'Produk berhasil dihapus.');
} catch (PDOException $ex) {
    set_flash('danger', 'Produk gagal dihapus.');
}
redirect('produk.php');
