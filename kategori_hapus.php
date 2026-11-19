<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    set_flash('danger', 'Permintaan tidak valid.');
    redirect('kategori.php');
}

$id = ambil_id($_POST['id'] ?? 0);
if ($id === 0) {
    set_flash('danger', 'Kategori tidak valid.');
    redirect('kategori.php');
}

// Kategori yang masih dipakai produk tidak boleh dihapus
$stmt = $pdo->prepare('SELECT COUNT(*) FROM produk WHERE id_kategori = ?');
$stmt->execute([$id]);
$jumlah = (int) $stmt->fetchColumn();

if ($jumlah > 0) {
    set_flash('warning', 'Kategori tidak dapat dihapus karena masih dipakai oleh ' . $jumlah . ' produk.');
    redirect('kategori.php');
}

try {
    $stmt = $pdo->prepare('DELETE FROM kategori WHERE id_kategori = ?');
    $stmt->execute([$id]);
    set_flash('success', 'Kategori berhasil dihapus.');
} catch (PDOException $ex) {
    set_flash('danger', 'Kategori gagal dihapus karena masih terhubung dengan data lain.');
}
redirect('kategori.php');
