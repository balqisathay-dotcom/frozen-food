<?php
/**
 * Konfigurasi koneksi database (PDO).
 * Sesuaikan nilai di bawah dengan pengaturan MySQL/MariaDB Anda.
 */

// Sembunyikan error PHP dari tampilan pengguna
ini_set('display_errors', '0');
error_reporting(E_ALL);

const DB_HOST = 'localhost';
const DB_NAME = 'frozen_food';
const DB_USER = 'root';
const DB_PASS = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa pengaturan di config.php dan pastikan database sudah diimpor.');
}
