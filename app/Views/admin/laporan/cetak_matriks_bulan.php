<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Matriks Bulanan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table-matriks { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .table-matriks th, .table-matriks td { border: 1px solid #000; padding: 2px; text-align: center; font-size: 9px; }
        .th-nama { width: 150px; text-align: left; padding-left: 5px; }
        .cell-hadir { background-color: #a8e6cf; }
        .cell-sakit { background-color: #fdcb6e; }
        .cell-izin { background-color: #74b9ff; }
        .cell-alfa { background-color: #ff7675; }
        .cell-libur { background-color: #dfe6e9; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3 style="margin:0;"><?= strtoupper($organisasi['nama_organisasi']) ?></h3>
        <p style="margin:5px 0;">MATRIKS KEHADIRAN ANGGOTA - <?= strtoupper($namaBulan[$bulan]) ?> <?= $tahun ?></p>
        <?php if($info_rt): ?><p style="margin:0;">RT: <?= $info_rt ?></p><?php endif; ?>
    </div>

    <table class="table-matriks">
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">No</th>
                <th rowspan="2" class="th-nama">Nama Anggota</th>
                <th colspan="<?= $jumlah_hari ?>">Tanggal</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jumlah_hari; $d++): ?>
                    <th><?= $d ?></th>
                <?php endfor; ?>
                <th>H</th><th>S</th><th>I</th><th>A</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $mapHari = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];

            foreach($users as $k => $u): ?>
                <tr>
                    <td><?= $k+1 ?></td>
                    <td style="text-align:left; padding-left:5px; white-space:nowrap; overflow:hidden;"><?= $u['nama_lengkap'] ?></td>
                    <?php 
                        $h=0; $s=0; $i=0; $a=0;
                        for($d=1; $d<=$jumlah_hari; $d++):
                            // PERBAIKAN: Format tanggal harus Y-m-d dengan leading zero (2024-02-08)
                            $tgl = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
                            
                            $status = isset($rekap[$u['id']][$d]) ? $rekap[$u['id']][$d] : '';
                            
                            $dayNameIng = date('l', strtotime($tgl));
                            $dayNameInd = $mapHari[$dayNameIng];

                            // Cek Libur Nasional & Hari Efektif
                            $isLiburNasional = in_array($tgl, $libur_nasional);
                            $isHariEfektif = in_array($dayNameInd, $hari_efektif); 
                            
                            $bgClass = '';
                            $symbol = '';

                            if($status) {
                                // Jika ada data absensi, tampilkan sesuai status
                                if($status == 'Hadir' || $status == 'Terlambat') { $h++; $bgClass = 'cell-hadir'; $symbol = '•'; }
                                elseif($status == 'Sakit') { $s++; $bgClass = 'cell-sakit'; $symbol = 'S'; }
                                elseif($status == 'Izin') { $i++; $bgClass = 'cell-izin'; $symbol = 'I'; }
                                elseif($status == 'Alfa') { $a++; $bgClass = 'cell-alfa'; $symbol = 'A'; }
                                elseif($status == 'Cepat Pulang') { $h++; $bgClass = 'cell-hadir'; $symbol = '•'; } 
                            } else {
                                // Jika TIDAK ada data absensi
                                if ($isLiburNasional || !$isHariEfektif) {
                                    // Jika hari libur nasional ATAU bukan hari kerja (misal Minggu) -> Libur
                                    $bgClass = 'cell-libur'; 
                                } else {
                                    // Hari efektif tapi tidak absen -> Otomatis Alfa
                                    $a++; 
                                    $bgClass = 'cell-alfa'; 
                                    $symbol = 'A';
                                }
                            }
                    ?>
                        <td class="<?= $bgClass ?>"><?= $symbol ?></td>
                    <?php endfor; ?>
                    <td><?= $h ?></td><td><?= $s ?></td><td><?= $i ?></td><td><?= $a ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div style="margin-top:10px; font-size:9px;">
        <strong>Keterangan:</strong> • : Hadir, S : Sakit, I : Izin, A : Alfa (Termasuk Otomatis)
    </div>
</body>
</html>