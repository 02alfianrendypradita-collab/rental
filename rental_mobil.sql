-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Bulan Mei 2026 pada 09.06
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
-- Database: `rental_mobil`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sewa_mobil` (IN `p_id_user` INT, IN `p_id_mobil` INT, IN `p_jumlah` INT)   BEGIN

   INSERT INTO penyewaan(
       id_user,
       id_mobil,
       jumlah_sewa,
       tanggal_sewa
   )
   VALUES(
       p_id_user,
       p_id_mobil,
       p_jumlah,
       CURDATE()
   );

   UPDATE mobil
   SET jumlah = jumlah - p_jumlah
   WHERE id_mobil = p_id_mobil;

END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `status_mobil` (`jumlah` INT) RETURNS VARCHAR(20) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN

   DECLARE hasil VARCHAR(20);

   IF jumlah <= 0 THEN
       SET hasil = 'Tidak Tersedia';
   ELSE
       SET hasil = 'Tersedia';
   END IF;

   RETURN hasil;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '12345');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_penyewaan`
--

CREATE TABLE `laporan_penyewaan` (
  `id_laporan` int(11) NOT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `id_mobil` int(11) DEFAULT NULL,
  `jumlah_sewa` int(11) DEFAULT NULL,
  `tanggal_sewa` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `total_bayar` bigint(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_penyewaan`
--

INSERT INTO `laporan_penyewaan` (`id_laporan`, `nama_user`, `id_mobil`, `jumlah_sewa`, `tanggal_sewa`, `tanggal_kembali`, `total_bayar`, `status`) VALUES
(1, 'NEO', 4, 2, '0000-00-00', '2026-05-22', 2000000, 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `nama_mobil` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kondisi` varchar(50) DEFAULT NULL,
  `harga_sewa` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `nama_mobil`, `jumlah`, `kondisi`, `harga_sewa`, `gambar`) VALUES
(8, 'Avanza', 5, 'Baru', 300000, 'uploads/1779259996_Avanza.jpg'),
(9, 'Ayla', 4, 'Baru', 400000, 'uploads/1779260054_Ayla.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id_penyewaan` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `jumlah_sewa` int(11) NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `status` varchar(20) DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penyewaan`
--

INSERT INTO `penyewaan` (`id_penyewaan`, `nama_user`, `id_mobil`, `jumlah_sewa`, `tanggal_sewa`, `tanggal_kembali`, `status`) VALUES
(2, 'mansur', 9, 1, '2026-05-20', '2026-05-21', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` enum('admin','penyewa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `laporan_penyewaan`
--
ALTER TABLE `laporan_penyewaan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indeks untuk tabel `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`);

--
-- Indeks untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id_penyewaan`),
  ADD KEY `id_mobil` (`id_mobil`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `laporan_penyewaan`
--
ALTER TABLE `laporan_penyewaan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id_penyewaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD CONSTRAINT `penyewaan_ibfk_1` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
