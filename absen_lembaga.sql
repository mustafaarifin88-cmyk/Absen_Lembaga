-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Mar 2026 pada 18.49
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
-- Database: `absen_lembaga`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `user_type` enum('pengurus','anggota') NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status` enum('Hadir','Alfa','Izin','Sakit','Terlambat','Cepat Pulang') DEFAULT 'Alfa',
  `keterangan` text DEFAULT NULL,
  `lokasi_lat` varchar(50) DEFAULT NULL,
  `lokasi_long` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `user_type`, `user_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `status`, `keterangan`, `lokasi_lat`, `lokasi_long`) VALUES
(1, 'pengurus', 2, '2026-02-16', '20:38:01', NULL, 'Hadir', NULL, '-0.20871683078148814', '100.61772132205861'),
(2, 'anggota', 4, '2026-02-18', '07:00:00', NULL, 'Hadir', '', '-', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_agenda`
--

CREATE TABLE `absensi_agenda` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_type` enum('pengurus','anggota') NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori` enum('ippm','masyarakat') NOT NULL,
  `agenda_id` int(11) NOT NULL,
  `nama_agenda` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_absen` time NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Hadir',
  `lokasi_lat` varchar(50) DEFAULT NULL,
  `lokasi_long` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi_agenda`
--

INSERT INTO `absensi_agenda` (`id`, `user_type`, `user_id`, `kategori`, `agenda_id`, `nama_agenda`, `tanggal`, `jam_absen`, `status`, `lokasi_lat`, `lokasi_long`) VALUES
(1, 'anggota', 3, 'ippm', 1, 'Rapat Koordinasi', '2026-02-16', '22:55:32', 'Hadir', '-', '-'),
(2, 'anggota', 3, 'masyarakat', 3, 'takjilan', '2026-02-16', '23:01:19', 'Hadir', '-7.936767343193593', '110.39196948582195');

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda_ippm`
--

CREATE TABLE `agenda_ippm` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_agenda` varchar(100) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_akhir` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agenda_ippm`
--

INSERT INTO `agenda_ippm` (`id`, `nama_agenda`, `hari`, `jam_mulai`, `jam_akhir`) VALUES
(1, 'Rapat Koordinasi', 'Senin', '19:30:00', '21:00:00'),
(2, 'Pengajian Rutin', 'Kamis', '18:30:00', '20:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda_masyarakat`
--

CREATE TABLE `agenda_masyarakat` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_agenda` varchar(100) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_akhir` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agenda_masyarakat`
--

INSERT INTO `agenda_masyarakat` (`id`, `nama_agenda`, `hari`, `jam_mulai`, `jam_akhir`) VALUES
(1, 'Kerja Bakti', 'Minggu', '07:00:00', '10:00:00'),
(2, 'Posyandu', 'Rabu', '08:00:00', '11:00:00'),
(3, 'takjilan', 'Senin', '16:00:00', '23:59:00'),
(4, 'takjilan', 'Selasa', '16:00:00', '23:59:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `rt_id` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id`, `nama_lengkap`, `rt_id`, `foto`, `qr_code`) VALUES
(71, 'Alfath Hudha P', 1, 'default.jpg', 'QR_A_71.png'),
(72, 'Andika Jaya S', 1, 'default.jpg', 'QR_A_72.png'),
(73, 'Bayu Khoirudin', 1, 'default.jpg', 'QR_A_73.png'),
(74, 'Revalina Yuni S', 1, 'default.jpg', 'QR_A_74.png'),
(75, 'Ani Khoirul Lisa', 1, 'default.jpg', 'QR_A_75.png'),
(76, 'Annisatul Khusnah', 1, 'default.jpg', 'QR_A_76.png'),
(77, 'Syafa Avirilia Fashalla', 1, 'default.jpg', 'QR_A_77.png'),
(78, 'Alfiyani', 1, 'default.jpg', 'QR_A_78.png'),
(79, 'Bayu Safrudin', 2, 'default.jpg', 'QR_A_79.png'),
(80, 'Egi Mutia Sari', 2, 'default.jpg', 'QR_A_80.png'),
(81, 'Rahma Putriani', 2, 'default.jpg', 'QR_A_81.png'),
(82, 'Risa Meiliana', 2, 'default.jpg', 'QR_A_82.png'),
(83, 'Rehan Taufiqur', 2, 'default.jpg', 'QR_A_83.png'),
(84, 'Retnan Hidayanto', 2, 'default.jpg', 'QR_A_84.png'),
(85, 'Sih Haryadi', 2, 'default.jpg', 'QR_A_85.png'),
(86, 'Galih Aji Prabowo', 2, 'default.jpg', 'QR_A_86.png'),
(87, 'Eka Juliyana Purwanty', 2, 'default.jpg', 'QR_A_87.png'),
(88, 'Yusuf Ma’rufin', 2, 'default.jpg', 'QR_A_88.png'),
(89, 'Lanang Ega Prasetya', 2, 'default.jpg', 'QR_A_89.png'),
(90, 'Syifa Aulia Putri', 2, 'default.jpg', 'QR_A_90.png'),
(91, 'Ade Sulistya', 2, 'default.jpg', 'QR_A_91.png'),
(92, 'Fajar Lilik S', 2, 'default.jpg', 'QR_A_92.png'),
(93, 'Fajar Sidiq P', 2, 'default.jpg', 'QR_A_93.png'),
(95, 'Husni Nur Utami', 3, 'default.jpg', 'QR_A_95.png'),
(96, 'Ibnu Maulana', 3, 'default.jpg', 'QR_A_96.png'),
(98, 'Muhammad Latif', 3, 'default.jpg', 'QR_A_98.png'),
(100, 'Ngadenan', 3, 'default.jpg', 'QR_A_100.png'),
(101, 'Nur Nilamsari Adella', 3, 'default.jpg', 'QR_A_101.png'),
(102, 'Resti Anisa S', 3, 'default.jpg', 'QR_A_102.png'),
(103, 'Roy Zudi Hermawan', 3, 'default.jpg', 'QR_A_103.png'),
(104, 'Singgih Budi S', 3, 'default.jpg', 'QR_A_104.png'),
(105, 'Winda Aulia Afni', 3, 'default.jpg', 'QR_A_105.png'),
(106, 'Hilmi Khoirul Anam', 3, 'default.jpg', 'QR_A_106.png'),
(107, 'Ramdhani Karuniawan', 3, 'default.jpg', 'QR_A_107.png'),
(108, 'Muhammad Wahid Nur Iqlas', 3, 'default.jpg', 'QR_A_108.png'),
(109, 'Hayyu Gandis Prindaru', 3, 'default.jpg', 'QR_A_109.png'),
(110, 'Satria Gafra Putratama', 3, 'default.jpg', 'QR_A_110.png'),
(111, 'Vidiani Dara Qudni', 3, 'default.jpg', 'QR_A_111.png'),
(112, 'Ahmad Nur Khamid', 3, 'default.jpg', 'QR_A_112.png'),
(113, 'Ahmad Syaiful M', 3, 'default.jpg', 'QR_A_113.png'),
(114, 'Ary Indriani', 3, 'default.jpg', 'QR_A_114.png'),
(115, 'Galang Wahyu R', 3, 'default.jpg', 'QR_A_115.png'),
(116, 'Hadi Syaiful N', 4, 'default.jpg', 'QR_A_116.png'),
(117, 'Iis Nur Kholifah', 4, 'default.jpg', 'QR_A_117.png'),
(118, 'Ina Santika', 4, 'default.jpg', 'QR_A_118.png'),
(120, 'Runy Salma M', 4, 'default.jpg', 'QR_A_120.png'),
(121, 'Muhammad Izzuddin Saputra', 4, 'default.jpg', 'QR_A_121.png'),
(122, 'Tika Puspita Sari', 4, 'default.jpg', 'QR_A_122.png'),
(123, 'Wahyu Nugroho', 4, 'default.jpg', 'QR_A_123.png'),
(124, 'Yurianto', 4, 'default.jpg', 'QR_A_124.png'),
(125, 'Risma dwi ariyani', 4, 'default.jpg', 'QR_A_125.png'),
(126, 'Arlin ferdiana', 4, 'default.jpg', 'QR_A_126.png'),
(127, 'Muhammad Wisnu Safrudin', 4, 'default.jpg', 'QR_A_127.png'),
(128, 'Sulis Pratama', 4, 'default.jpg', 'QR_A_128.png'),
(129, 'Farel Eka Sabekti', 4, 'default.jpg', 'QR_A_129.png'),
(130, 'Dinda Meirisya Anggraini', 4, 'default.jpg', 'QR_A_130.png'),
(131, 'Alfin Hanan Fatuhurahman', 4, 'default.jpg', 'QR_A_131.png'),
(132, 'Reno', 4, 'default.jpg', 'QR_A_132.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_version`
--

CREATE TABLE `app_version` (
  `id` int(11) NOT NULL,
  `version` varchar(10) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `app_version`
--

INSERT INTO `app_version` (`id`, `version`, `updated_at`) VALUES
(1, '9.9.9', '2026-02-15 23:09:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `libur_nasional`
--

CREATE TABLE `libur_nasional` (
  `id` int(11) NOT NULL,
  `nama_libur` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `libur_nasional`
--

INSERT INTO `libur_nasional` (`id`, `nama_libur`, `tanggal_mulai`, `tanggal_akhir`, `deskripsi`) VALUES
(1, 'Awal Ramadhan', '2026-02-09', '2026-02-12', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `organisasi`
--

CREATE TABLE `organisasi` (
  `id` int(11) NOT NULL,
  `nama_organisasi` varchar(100) NOT NULL,
  `alamat_lengkap` text NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT 'default_logo.png',
  `kepala_instansi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `organisasi`
--

INSERT INTO `organisasi` (`id`, `nama_organisasi`, `alamat_lengkap`, `kabupaten`, `logo`, `kepala_instansi`) VALUES
(1, 'Ikatan Pemuda Pemudi Mojolegi (IPPM)', 'Mojolegi, Karang Tengah, Imogiri, Bantul, Daerah Istimewa Yogykarta', 'Bantul', '1771347827_53a6c910d4cd9f240d13.png', 'Fajar Lilik Saputro');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengurus`
--

CREATE TABLE `pengurus` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengurus`
--

INSERT INTO `pengurus` (`id`, `nama_lengkap`, `jabatan`, `foto`, `qr_code`) VALUES
(3, 'Fajar L', 'Ketua IPPM', 'default.jpg', 'QR_P_3.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rt`
--

CREATE TABLE `rt` (
  `id` int(11) NOT NULL,
  `nama_rt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rt`
--

INSERT INTO `rt` (`id`, `nama_rt`) VALUES
(1, 'RT 03'),
(2, 'RT 04'),
(3, 'RT 05'),
(4, 'RT 06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_gps`
--

CREATE TABLE `setting_gps` (
  `id` int(11) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius_meter` int(11) NOT NULL DEFAULT 200
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `setting_gps`
--

INSERT INTO `setting_gps` (`id`, `latitude`, `longitude`, `radius_meter`) VALUES
(1, '-7.936767', '110.391969', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_hari`
--

CREATE TABLE `setting_hari` (
  `id` int(11) NOT NULL,
  `nama_hari` varchar(20) NOT NULL,
  `tampilkan` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `setting_hari`
--

INSERT INTO `setting_hari` (`id`, `nama_hari`, `tampilkan`) VALUES
(1, 'Senin', 1),
(2, 'Selasa', 1),
(3, 'Rabu', 1),
(4, 'Kamis', 1),
(5, 'Jumat', 1),
(6, 'Sabtu', 1),
(7, 'Minggu', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_jam`
--

CREATE TABLE `setting_jam` (
  `id` int(11) NOT NULL,
  `type` enum('pengurus','anggota') NOT NULL,
  `hari` varchar(20) NOT NULL,
  `jam_masuk_mulai` time NOT NULL DEFAULT '06:00:00',
  `jam_masuk_akhir` time NOT NULL DEFAULT '08:00:00',
  `jam_pulang_mulai` time NOT NULL DEFAULT '16:00:00',
  `jam_pulang_akhir` time NOT NULL DEFAULT '18:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_jam`
--

INSERT INTO `setting_jam` (`id`, `type`, `hari`, `jam_masuk_mulai`, `jam_masuk_akhir`, `jam_pulang_mulai`, `jam_pulang_akhir`) VALUES
(1, 'pengurus', 'Senin', '20:00:00', '21:00:00', '22:00:00', '23:00:00'),
(2, 'pengurus', 'Selasa', '07:00:00', '08:30:00', '16:00:00', '18:00:00'),
(3, 'pengurus', 'Rabu', '07:00:00', '08:30:00', '16:00:00', '18:00:00'),
(4, 'pengurus', 'Kamis', '07:00:00', '08:30:00', '16:00:00', '18:00:00'),
(5, 'pengurus', 'Jumat', '07:00:00', '08:30:00', '16:00:00', '18:00:00'),
(6, 'pengurus', 'Sabtu', '07:00:00', '08:30:00', '12:00:00', '14:00:00'),
(7, 'pengurus', 'Minggu', '00:00:00', '00:00:00', '00:00:00', '00:00:00'),
(8, 'anggota', 'Senin', '07:30:00', '09:00:00', '15:00:00', '17:00:00'),
(9, 'anggota', 'Selasa', '07:30:00', '09:00:00', '15:00:00', '17:00:00'),
(10, 'anggota', 'Rabu', '07:30:00', '09:00:00', '15:00:00', '17:00:00'),
(11, 'anggota', 'Kamis', '07:30:00', '09:00:00', '15:00:00', '17:00:00'),
(12, 'anggota', 'Jumat', '07:30:00', '09:00:00', '15:00:00', '17:00:00'),
(13, 'anggota', 'Sabtu', '07:30:00', '09:00:00', '12:00:00', '14:00:00'),
(14, 'anggota', 'Minggu', '00:00:00', '00:00:00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_theme`
--

CREATE TABLE `setting_theme` (
  `id` int(11) NOT NULL,
  `login_bg_type` enum('color','image') NOT NULL DEFAULT 'color',
  `login_bg_value` text DEFAULT NULL,
  `sidebar_bg_type` enum('color','image') NOT NULL DEFAULT 'color',
  `sidebar_bg_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_theme`
--

INSERT INTO `setting_theme` (`id`, `login_bg_type`, `login_bg_value`, `sidebar_bg_type`, `sidebar_bg_value`) VALUES
(1, 'image', '1771395140_78c999f1901a108297a8.jpg', 'color', '#ffffff');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `level` enum('admin','petugas') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `password`, `foto`, `level`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$57qSO2MRSClHSBp5.EDyTepRDkkgNiAHUKmUq5YnLeDVTjYd0g4AS', '1771247272_dae156edb60b60c7fdb2.png', 'admin', '2026-02-15 23:09:36', '2026-03-08 09:58:46'),
(2, 'Petugas Piket', 'petugas', '$2y$10$gY2tXG9Y/qcHfoqxcfkCguF8IyQgSbwYba1q1Kh76dFhEUPWRWdri', 'default.jpg', 'petugas', '2026-02-15 23:09:36', '2026-02-16 20:40:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `absensi_agenda`
--
ALTER TABLE `absensi_agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `agenda_ippm`
--
ALTER TABLE `agenda_ippm`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `agenda_masyarakat`
--
ALTER TABLE `agenda_masyarakat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rt_id` (`rt_id`);

--
-- Indeks untuk tabel `app_version`
--
ALTER TABLE `app_version`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `libur_nasional`
--
ALTER TABLE `libur_nasional`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengurus`
--
ALTER TABLE `pengurus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rt`
--
ALTER TABLE `rt`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_gps`
--
ALTER TABLE `setting_gps`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_hari`
--
ALTER TABLE `setting_hari`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_jam`
--
ALTER TABLE `setting_jam`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `setting_theme`
--
ALTER TABLE `setting_theme`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `absensi_agenda`
--
ALTER TABLE `absensi_agenda`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `agenda_ippm`
--
ALTER TABLE `agenda_ippm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `agenda_masyarakat`
--
ALTER TABLE `agenda_masyarakat`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT untuk tabel `app_version`
--
ALTER TABLE `app_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `libur_nasional`
--
ALTER TABLE `libur_nasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengurus`
--
ALTER TABLE `pengurus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `rt`
--
ALTER TABLE `rt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `setting_gps`
--
ALTER TABLE `setting_gps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `setting_hari`
--
ALTER TABLE `setting_hari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `setting_jam`
--
ALTER TABLE `setting_jam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `setting_theme`
--
ALTER TABLE `setting_theme`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_ibfk_1` FOREIGN KEY (`rt_id`) REFERENCES `rt` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
