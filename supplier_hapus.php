<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    set_flash('danger', 'Permintaan tidak valid.');
    redirect('supplier.php');
}

$id = ambil_id($_POST['id'] ?? 0);
if ($id === 0) {
    set_flash('danger', 'Supplier tidak valid.');
    redirect('supplier.php');
}

// Supplier yang masih dipakai produk tidak boleh dihapus
$stmt = $pdo->prepare('SELECT COUNT(*) FROM produk WHERE id_supplier = ?');
$stmt->execute([$id]);
$jumlah = (int) $stmt->fetchColumn();

if ($jumlah > 0) {
    set_flash('warning', 'Supplier tidak dapat dihapus karena masih dipakai oleh ' . $jumlah . ' produk.');
    redirect('supplier.php');
}

try {
    $stmt = $pdo->prepare('DELETE FROM supplier WHERE id_supplier = ?');
    $stmt->execute([$id]);
    set_flash('success', 'Supplier berhasil dihapus.');
} catch (PDOException $ex) {
    set_flash('danger', 'Supplier gagal dihapus karena masih terhubung dengan data lain.');
}
redirect('supplier.php');
