<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Guru</title>
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
        
        .header { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        .btn-print { background: #435ebe; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; text-decoration: none; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <a href="javascript:window.close()" class="btn-print" style="background: #6c757d;">Tutup</a>
        <button onclick="window.print()" class="btn-print">Cetak</button>
    </div>
    
    <div class="header">
        <h3>REKAPITULASI ABSENSI GURU BULANAN</h3>
        <h4><?= strtoupper($sekolah['nama_sekolah']) ?></h4>
        <p>
            <?php
            $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
            echo "Bulan: " . $namaBulan[(int)$bulan] . " " . $tahun;
            ?>
        </p>
    </div>

    <table class="table-rekap">
        <thead>
            <tr>
                <th rowspan="2" width="30">No</th>
                <th rowspan="2" width="200">Nama Guru</th>
                <th colspan="<?= $jumlah_hari ?>">Tanggal</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <?php for($d=1; $d<=$jumlah_hari; $d++): ?>
                    <th width="20"><?= $d ?></th>
                <?php endfor; ?>
                <th width="30">H</th>
                <th width="30">S</th>
                <th width="30">I</th>
                <th width="30">A</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $hariIni = (int)date('d');
            $bulanIni = (int)date('m');
            $tahunIni = (int)date('Y');
            $isBulanBerjalan = ($bulan == $bulanIni && $tahun == $tahunIni);

            // Mapping Nama Hari untuk Cek Libur
            $mapHari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];

            foreach($users as $user): 
                $h=0; $s=0; $i=0; $a=0;
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-left"><?= $user['nama_guru'] ?></td>
                
                <?php for($d=1; $d<=$jumlah_hari; $d++): ?>
                    <?php 
                        // Format Tanggal Penuh Y-m-d untuk pengecekan
                        $dateFull = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                        
                        // Cek Hari (Senin, Selasa...)
                        $hariInggris = date('l', strtotime($dateFull));
                        $hariIndo = $mapHari[$hariInggris];
                        
                        // Cek apakah Libur Rutin (Sabtu/Minggu sesuai setting)
                        $isLiburRutin = (isset($hari_efektif[$hariIndo]) && $hari_efektif[$hariIndo] == 0);
                        
                        // Cek apakah Libur Nasional (Tanggal Merah)
                        $isLiburNasional = in_array($dateFull, $libur_nasional);

                        $statusRaw = isset($rekap[$user['id']][$d]) ? $rekap[$user['id']][$d] : '';
                        $tampil = '';
                        $bg = '';

                        // JIKA LIBUR (Rutin atau Nasional) -> Arsir & Skip Alfa
                        if ($isLiburRutin || $isLiburNasional) {
                            $bg = 'bg-libur'; 
                            // Jika ada yang absen di hari libur (lembur?), tetap tampilkan statusnya
                            if (!empty($statusRaw)) {
                                $tampil = $statusRaw;
                                // Hitung total jika perlu, tapi biasanya hari libur tidak dihitung A
                                if($statusRaw == 'H') $h++;
                            }
                        } else {
                            // HARI KERJA BIASA
                            
                            // LOGIKA ALFA OTOMATIS
                            if (empty($statusRaw)) {
                                // Jika tanggal sudah lewat ATAU bulan lalu, tandai Alfa
                                if ( ($isBulanBerjalan && $d <= $hariIni) || ($tahun < $tahunIni) || ($tahun == $tahunIni && $bulan < $bulanIni) ) {
                                    $statusRaw = 'A';
                                }
                            }
                            
                            $tampil = $statusRaw;
                        }

                        // Mewarnai Cell & Menghitung Total (Hanya jika bukan libur atau ada data spesifik)
                        if($statusRaw == 'H' || $statusRaw == 'T') { 
                            $h++; $bg='status-h'; $tampil='H'; // T = Terlambat dihitung Hadir
                        } elseif($statusRaw == 'S') { 
                            $s++; $bg='status-s'; 
                        } elseif($statusRaw == 'I') { 
                            $i++; $bg='status-i'; 
                        } elseif($statusRaw == 'A') { 
                            $a++; $bg='status-a'; 
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
        <p><?= isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : 'Tempat' ?>, <?= date('d') ?> <?= $namaBulan[(int)date('m')] ?> <?= date('Y') ?></p>
        <p>Kepala Sekolah</p>
        <br><br><br>
        <p><b><?= $sekolah['kepala_sekolah'] ?></b></p>
        <p>NIP. <?= $sekolah['nip_kepsek'] ?></p>
    </div>

</body>
</html>