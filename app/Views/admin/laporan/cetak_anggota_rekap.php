<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Anggota</title>
    <link rel="icon" href="<?= base_url('assets/icon.ico') ?>" type="image/x-icon">
    <style>
        @page { size: landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 11px; -webkit-print-color-adjust: exact; }
        .table-rekap { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-rekap th, .table-rekap td { border: 1px solid #000; padding: 4px; text-align: center; }
        .text-left { text-align: left !important; padding-left: 5px; }
        h3, h4, p { margin: 2px 0; text-align: center; }
        
        .status-h { background-color: #d1e7dd; } 
        .status-s { background-color: #cff4fc; } 
        .status-i { background-color: #fff3cd; } 
        .status-a { background-color: #f8d7da; } 
        .bg-libur { background-color: #e9ecef; } /* Abu-abu untuk libur */
        
        .header { margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 70px; height: auto; }
        .org-name { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .org-address { font-size: 12px; margin: 5px 0 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <?php if ($organisasi['logo']): ?>
            <img src="<?= base_url('uploads/logo/' . $organisasi['logo']) ?>" class="logo" alt="Logo">
        <?php endif; ?>
        <div style="margin-left: 80px;">
            <p class="org-name"><?= $organisasi['nama_organisasi'] ?></p>
            <p class="org-address"><?= $organisasi['alamat_lengkap'] ?></p>
        </div>
    </div>

    <h3>REKAPITULASI KEHADIRAN ANGGOTA</h3>
    <p>Bulan: <?= $namaBulan[$bulan] ?> <?= $tahun ?> <?= $info_rt ? '| RT: '.$info_rt : '' ?></p>

    <table class="table-rekap">
        <thead>
            <tr>
                <th rowspan="2" width="30">No</th>
                <th rowspan="2" class="text-left" width="200">Nama Anggota</th>
                <th colspan="<?= $jumlah_hari ?>">Tanggal</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jumlah_hari; $d++): ?>
                    <th style="font-size: 9px; width: 20px;"><?= $d ?></th>
                <?php endfor; ?>
                <th width="30">H</th>
                <th width="30">S</th>
                <th width="30">I</th>
                <th width="30">A</th>
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
                <td class="text-left"><?= $u['nama_lengkap'] ?></td>
                
                <?php 
                    $h=0; $s=0; $i=0; $a=0;
                    for($d=1; $d<=$jumlah_hari; $d++):
                        $tgl = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
                        $statusRaw = isset($rekap[$u['id']][$d]) ? $rekap[$u['id']][$d] : '';
                        
                        $dayNameIng = date('l', strtotime($tgl));
                        $dayNameInd = $mapHari[$dayNameIng];

                        // Cek Libur
                        $isLiburNasional = in_array($tgl, $libur_nasional);
                        $isHariEfektif = in_array($dayNameInd, $hari_efektif); 
                        
                        $bg = '';
                        $tampil = '';

                        if($statusRaw) {
                            // Jika ada data absensi
                            $tampil = substr($statusRaw, 0, 1); 
                            if($statusRaw == 'Hadir' || $statusRaw == 'Terlambat' || $statusRaw == 'Cepat Pulang') { 
                                $h++; $bg='status-h'; $tampil='•';
                            } elseif($statusRaw == 'Sakit') { 
                                $s++; $bg='status-s'; $tampil='S';
                            } elseif($statusRaw == 'Izin') { 
                                $i++; $bg='status-i'; $tampil='I';
                            } elseif($statusRaw == 'Alfa') { 
                                $a++; $bg='status-a'; $tampil='A';
                            }
                        } else {
                            // Jika TIDAK ada data absensi
                            if ($isLiburNasional || !$isHariEfektif) {
                                // Hari Libur -> Kosongkan
                                $bg = 'bg-libur';
                                $tampil = ''; 
                            } else {
                                // Hari Efektif tapi tidak absen -> Otomatis Alfa
                                $a++; 
                                $bg = 'status-a'; 
                                $tampil = 'A';
                            }
                        }
                ?>
                    <td class="<?= $bg ?>"><?= $tampil ?></td>
                <?php endfor; ?>

                <td><strong><?= $h ?></strong></td>
                <td><strong><?= $s ?></strong></td>
                <td><strong><?= $i ?></strong></td>
                <td><strong><?= $a ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 200px; text-align: center;">
        <p><?= isset($organisasi['kabupaten']) ? $organisasi['kabupaten'] : 'Tempat' ?>, <?= date('d') ?> <?= $namaBulan[(int)date('m')] ?> <?= date('Y') ?></p>
        <p>Kepala Instansi</p>
        <br><br><br>
        <p><b><?= $organisasi['kepala_instansi'] ?></b></p>
    </div>
</body>
</html>