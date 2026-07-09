-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Bulan Mei 2026 pada 11.47
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `id_menu` int(100) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(100) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `jenis` enum('makanan','minuman') NOT NULL,
  `harga_porsi` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `jenis`, `harga_porsi`) VALUES
(1, 'Ayam Betutu', 'makanan', 35000),
(2, 'Ayam Kremes', 'makanan', 28000),
(4, 'Gado-Gado', 'makanan', 25000),
(5, 'Ikan Asam Padeh', 'makanan', 40000),
(6, 'Ikan Bakar', 'makanan', 45000),
(7, 'Mie Aceh', 'makanan', 25000),
(8, 'Model Palembang', 'makanan', 20000),
(9, 'Nasi Goreng', 'makanan', 25000),
(10, 'Nasi Liwet', 'makanan', 32000),
(11, 'Nasi Putih', 'makanan', 8000),
(12, 'Pempek', 'makanan', 22000),
(13, 'Rawon', 'makanan', 35000),
(14, 'Sate Lilit', 'makanan', 30000),
(15, 'Sate Madura', 'makanan', 30000),
(16, 'Sop Buntut', 'makanan', 55000),
(17, 'Soto Betawi', 'makanan', 38000),
(18, 'Tahu Gejrot', 'makanan', 15000),
(19, 'Bajigur', 'minuman', 15000),
(20, 'Es Cendol', 'minuman', 18000),
(21, 'Es Jeruk Peras', 'minuman', 12000),
(23, 'Es Kuwut', 'minuman', 18000),
(24, 'Es Lemon Tea', 'minuman', 15000),
(25, 'Es Pisang Ijo', 'minuman', 22000),
(26, 'Teh Talua', 'minuman', 18000),
(27, 'Wedang Jahe', 'minuman', 15000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `metode`
--

CREATE TABLE `metode` (
  `id_metode` int(11) NOT NULL,
  `metode_pembayaran` enum('QRIS','Kartu Debit','E-Wallet','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `metode`
--

INSERT INTO `metode` (`id_metode`, `metode_pembayaran`) VALUES
(1, 'QRIS'),
(2, 'Kartu Debit'),
(3, 'E-Wallet');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telp` int(12) NOT NULL,
  `level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `password`, `nama_pelanggan`, `alamat`, `no_telp`, `level`) VALUES
(1, 'wira', '1213', 'wirangga', 'RD Jibja', 344, 'user'),
(5, 'admin', 'admin123', 'Administrator Toko', 'Kantor Utama', 812345678, 'admin'),
(6, 'aggsy', '123', 'angga', 'palm ', 880808, 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_metode` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pelanggan`, `id_metode`, `total_bayar`, `tanggal`) VALUES
(15, 1, 2, 103000, '2025-07-21 06:48:21'),
(16, 1, 1, 65000, '2025-07-21 07:42:24'),
(17, 1, 1, 66000, '2026-04-20 20:37:09'),
(18, 1, 2, 50000, '2026-04-20 20:43:43'),
(19, 1, 1, 112000, '2026-04-20 20:44:08'),
(20, 1, 3, 85000, '2026-04-21 08:24:44'),
(21, 1, 1, 50000, '2026-04-21 09:44:48'),
(22, 1, 2, 119000, '2026-04-21 10:10:24'),
(23, 1, 1, 80000, '2026-05-04 08:21:19'),
(24, 1, 1, 280000, '2026-05-05 11:35:22'),
(25, 1, 2, 147000, '2026-05-05 14:36:49'),
(26, 1, 2, 52000, '2026-05-05 14:52:53'),
(27, 1, 3, 15000, '2026-05-05 14:53:58'),
(28, 1, 2, 45000, '2026-05-05 15:41:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD UNIQUE KEY `uq_user_menu` (`username`,`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `metode`
--
ALTER TABLE `metode`
  ADD PRIMARY KEY (`id_metode`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_metode` (`id_metode`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `metode`
--
ALTER TABLE `metode`
  MODIFY `id_metode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_metode`) REFERENCES `metode` (`id_metode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
