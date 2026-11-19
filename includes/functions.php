<?php
/**
 * Fungsi bantu yang dipakai di seluruh halaman.
 */

function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function set_flash($tipe, $pesan)
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function get_flash()
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function csrf_token()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid()
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}

/** Ambil ID positif dari parameter; kembalikan 0 jika tidak valid. */
function ambil_id($nilai)
{
    $id = filter_var($nilai, FILTER_VALIDATE_INT);
    return ($id !== false && $id > 0) ? $id : 0;
}

/** Cek angka bulat tidak negatif (hanya digit). */
function angka_valid($nilai)
{
    return preg_match('/^\d+$/', $nilai) === 1;
}
