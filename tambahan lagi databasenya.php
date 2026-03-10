CREATE TABLE `jadwal_rapat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_rapat` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_akhir` time NOT NULL,
  `peserta` enum('Semua','Pengurus','Anggota') NOT NULL DEFAULT 'Semua',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;