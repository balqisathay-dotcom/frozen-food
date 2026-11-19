-- Database aplikasi pencatatan produk frozen food Pak Budi
CREATE DATABASE IF NOT EXISTS frozen_food
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE frozen_food;

DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;

CREATE TABLE kategori (
    id_kategori   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_kategori),
    UNIQUE KEY uq_nama_kategori (nama_kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE supplier (
    id_supplier   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_supplier VARCHAR(100) NOT NULL,
    telepon       VARCHAR(20)  NOT NULL,
    alamat        VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_supplier),
    UNIQUE KEY uq_nama_supplier (nama_supplier)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produk (
    id_produk   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nama_produk VARCHAR(150) NOT NULL,
    id_kategori INT UNSIGNED NOT NULL,
    id_supplier INT UNSIGNED NOT NULL,
    harga       INT UNSIGNED NOT NULL,
    stok        INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_produk),
    KEY idx_produk_kategori (id_kategori),
    KEY idx_produk_supplier (id_supplier),
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori)
        REFERENCES kategori (id_kategori)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_produk_supplier FOREIGN KEY (id_supplier)
        REFERENCES supplier (id_supplier)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data contoh
INSERT INTO kategori (id_kategori, nama_kategori) VALUES
    (1, 'Nugget'),
    (2, 'Sosis'),
    (3, 'Bakso'),
    (4, 'Dimsum'),
    (5, 'Kentang Goreng');

INSERT INTO supplier (id_supplier, nama_supplier, telepon, alamat) VALUES
    (1, 'PT Sumber Pangan Beku', '081234567801', 'Jl. Industri Raya No. 12, Bekasi'),
    (2, 'CV Berkah Frozen', '081234567802', 'Jl. Pasar Baru No. 45, Bandung'),
    (3, 'UD Nusantara Food', '081234567803', 'Jl. Raya Surabaya No. 8, Sidoarjo'),
    (4, 'PT Lezat Abadi', '081234567804', 'Jl. Gatot Subroto No. 21, Jakarta');

INSERT INTO produk (nama_produk, id_kategori, id_supplier, harga, stok) VALUES
    ('Nugget Ayam 500 gr', 1, 1, 42000, 60),
    ('Nugget Ayam Stick 400 gr', 1, 4, 38000, 45),
    ('Nugget Ikan 450 gr', 1, 2, 40000, 30),
    ('Sosis Ayam 500 gr', 2, 1, 35000, 80),
    ('Sosis Sapi Bakar 300 gr', 2, 3, 32000, 50),
    ('Bakso Sapi 500 gr', 3, 2, 45000, 70),
    ('Bakso Ayam 500 gr', 3, 3, 38000, 55),
    ('Bakso Keju Isi 300 gr', 3, 4, 36000, 25),
    ('Dimsum Ayam 10 pcs', 4, 2, 28000, 40),
    ('Dimsum Udang 10 pcs', 4, 4, 33000, 35),
    ('Kentang Goreng Shoestring 1 kg', 5, 1, 48000, 65),
    ('Kentang Goreng Crinkle 1 kg', 5, 3, 50000, 20);
