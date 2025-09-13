-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 05:25 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris_ptun`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `nomor_inventaris` varchar(50) DEFAULT NULL,
  `nomor_serial` varchar(50) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `kondisi` enum('Baik','Rusak','Hilang') DEFAULT 'Baik',
  `status` enum('Tersedia','Dipinjam','Maintenance') DEFAULT 'Tersedia',
  `tanggal_pembelian` date DEFAULT NULL,
  `harga_pembelian` int(11) DEFAULT NULL,
  `garansi_sampai` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `nomor_inventaris`, `nomor_serial`, `kategori_id`, `lokasi_id`, `kondisi`, `status`, `tanggal_pembelian`, `harga_pembelian`, `garansi_sampai`, `deskripsi`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Dell', NULL, NULL, NULL, NULL, 'Baik', 'Tersedia', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:44:27', NULL),
(2, 'Printer Canon', NULL, NULL, NULL, NULL, 'Baik', 'Dipinjam', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:44:27', NULL),
(3, 'AC Split', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:44:27', NULL),
(4, 'Laptop Dell Inspiron 15', NULL, NULL, NULL, NULL, 'Baik', 'Tersedia', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:03', NULL),
(5, 'Printer Canon LBP2900', NULL, NULL, NULL, NULL, 'Baik', 'Dipinjam', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:03', NULL),
(6, 'AC Split Daikin', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:03', NULL),
(7, 'Printer HP LaserJet', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:03', NULL),
(8, 'Laptop Dell Inspiron 15', NULL, NULL, NULL, NULL, 'Baik', 'Tersedia', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:08', NULL),
(9, 'Printer Canon LBP2900', NULL, NULL, NULL, NULL, 'Baik', 'Dipinjam', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:08', NULL),
(10, 'AC Split Daikin', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:08', NULL),
(11, 'Printer HP LaserJet', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:56:08', NULL),
(12, 'Laptop Dell Inspiron 15', NULL, NULL, NULL, NULL, 'Baik', 'Tersedia', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:58:27', NULL),
(13, 'Printer Canon LBP2900', NULL, NULL, NULL, NULL, 'Baik', 'Dipinjam', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:58:27', NULL),
(14, 'AC Split Daikin', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:58:27', NULL),
(15, 'Printer HP LaserJet', NULL, NULL, NULL, NULL, 'Baik', 'Maintenance', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:58:27', NULL),
(16, 'Laptop Dell Inspiron 15', NULL, NULL, NULL, NULL, 'Baik', 'Tersedia', NULL, NULL, NULL, NULL, NULL, '2025-09-01 15:59:28', NULL),
(17, 'Printer Canon LBP2900', '111', '111', 14, 14, 'Baik', 'Dipinjam', '2002-12-12', 123, '2004-02-11', 'asdasdasd', '1756782072_str_20251.png', '2025-09-01 15:59:28', '2025-09-02 11:01:12'),
(19, 'Printer HP LaserJet', '123', '123', 2, 11, 'Baik', 'Maintenance', '2002-02-22', 123123121, '2003-02-22', 'asdasdasdasdasdaweqweqwe', '1756779435_Screenshot 2025-08-20 103503.png', '2025-09-01 15:59:28', '2025-09-02 10:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Laptop', 'Kategori barang elektronik seperti laptop, printer, dll'),
(2, 'Printer', NULL),
(3, 'AC', 'asdawd'),
(4, 'Komputer & Laptop', NULL),
(5, 'Printer & Scanner', NULL),
(6, 'AC & Elektronik', NULL),
(7, 'Furniture', NULL),
(8, 'Alat Tulis Kantor', NULL),
(9, 'Jaringan & Komunikasi', NULL),
(10, 'Komputer & Laptop', NULL),
(11, 'Printer & Scanner', NULL),
(12, 'AC & Elektronik', NULL),
(13, 'Furniture', NULL),
(14, 'Alat Tulis Kantor', NULL),
(15, 'Jaringan & Komunikasi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(50) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `gedung` varchar(100) DEFAULT NULL,
  `lantai` int(11) DEFAULT 0,
  `ruangan` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah_barang` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `nama_lokasi`, `dibuat_pada`, `gedung`, `lantai`, `ruangan`, `deskripsi`, `jumlah_barang`) VALUES
(1, 'Ruang Ketua', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(2, 'Ruang Administrasi', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(3, 'Ruang Sidang 1', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(4, 'Ruang Ketua', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(5, 'Ruang Wakil Ketua', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(6, 'Ruang Sekretaris', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(7, 'Ruang Panitera', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(8, 'Ruang Administrasi', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(9, 'Ruang Sidang 1', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(10, 'Ruang Sidang 2', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(11, 'Ruang IT', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(12, 'Ruang Arsip', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(13, 'Ruang Umum', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(14, 'Ruang Ketua', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(15, 'Ruang Wakil Ketua', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(16, 'Ruang Sekretaris', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(17, 'Ruang Panitera', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(18, 'Ruang Administrasi', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(19, 'Ruang Sidang 1', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(20, 'Ruang Sidang 2', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(21, 'Ruang IT', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(22, 'Ruang Arsip', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0),
(23, 'Ruang Umum', '2025-09-02 02:59:57', NULL, 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `id_barang` int(10) UNSIGNED DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `tanggal_maintenance` date NOT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `biaya` decimal(15,2) DEFAULT 0.00,
  `status` enum('Dijadwalkan','Selesai','Pending') DEFAULT 'Dijadwalkan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `barang_id`, `id_barang`, `nama_barang`, `tanggal_maintenance`, `jenis`, `biaya`, `status`) VALUES
(1, 1, NULL, 'AC Split Daikin', '2024-12-20', NULL, '0.00', 'Dijadwalkan'),
(2, NULL, NULL, 'Printer HP LaserJet', '2024-12-25', NULL, '0.00', 'Dijadwalkan'),
(3, 1, NULL, 'AC Split Daikin', '2024-12-20', NULL, '0.00', 'Dijadwalkan'),
(4, NULL, NULL, 'Printer HP LaserJet', '2024-12-25', NULL, '0.00', 'Dijadwalkan'),
(5, 1, 1, 'AC Split Daikin', '2025-01-15', 'Perawatan AC Tahunan', '500000.00', 'Selesai'),
(6, NULL, 2, 'Printer HP LaserJet', '2025-02-10', 'Ganti Cartridge', '350000.00', 'Selesai'),
(7, 1, 3, 'AC Split Daikin', '2025-03-05', 'Pembersihan Filter', '150000.00', 'Dijadwalkan'),
(8, NULL, 4, 'Laptop Lenovo ThinkPad', '2025-04-12', 'Perbaikan Keyboard', '800000.00', 'Pending'),
(9, NULL, 5, 'Proyektor Epson', '2025-05-20', 'Kalibrasi & Pembersihan', '400000.00', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `target_kembali` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Aktif','Kembali','Terlambat') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id` int(11) NOT NULL,
  `peminjaman_id` int(11) DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `kondisi` enum('Baik','Rusak','Hilang') DEFAULT 'Baik',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Administrator PTUN','Panitera','Sekretaris','Ketua','Wakil Ketua','Hakim','Panitera Muda','Staf Administrasi','Operator IT','Security','Cleaning Service') DEFAULT 'Staf Administrasi',
  `status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `last_login`) VALUES
(1, 'admin', 'admin@ptun-banjarmasin.go.id', '21232f297a57a5a743894a0e4a801fc3', '', 'Aktif', '2025-09-02 11:16:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
