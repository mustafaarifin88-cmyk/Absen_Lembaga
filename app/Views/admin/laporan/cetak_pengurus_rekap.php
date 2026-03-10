<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi Rapat Pengurus</title>
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
        .bg-libur { background-color: #e9ecef; }
        
        .header { margin-bottom: 20px; border-bottom: 2px double #000; padding-bottom: 10px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3 style="margin:0;"><?= strtoupper($organisasi['nama_organisasi']) ?></h3>
        <p style="margin:5px 0;"><?= $organisasi['alamat_lengkap'] ?></p>
        <h3 style="margin:10px 0 0 0;">REKAPITULASI ABSENSI RAPAT PENGURUS</h3>
        <p style="margin:0;">Bulan: <?= $namaBulan[(int)$bulan] ?> <?= $tahun ?></p>
    </div>

    <table class="table-rekap">
        <thead>
            <tr>
                <td rowspan="2" width="30"><strong>No</strong></td>
                <td rowspan="2" class="text-left" width="200"><strong>Nama Pengurus</strong></td>
                <td colspan="<?= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun) ?>"><strong>Tanggal Rapat</strong></td>
                <td colspan="4"><strong>Total</strong></td>
            </tr>
            <tr>
                <?php 
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                for($d=1; $d<=$daysInMonth; $d++): 
                ?>
                <td width="20"><strong><?= $d ?></strong></td>
                <?php endfor; ?>
                <td width="30" class="status-h"><strong>H</strong></td>
                <td width="30" class="status-s"><strong>S</strong></td>
                <td width="30" class="status-i"><strong>I</strong></td>
                <td width="30" class="status-a"><strong>A</strong></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $key => $u): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td class="text-left"><?= $u['nama_lengkap'] ?></td>
                
                <?php 
                $h=0; $s=0; $i=0; $a=0;

                for($d=1; $d<=$daysInMonth; $d++): 
                    $currentDate = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
                    $isRapatDay = in_array($currentDate, $rapatDates);
                    $bg = ''; $tampil = '';

                    if(isset($rekap[$u['id']][$d])) {
                        $statusRaw = $rekap[$u['id']][$d];
                        if($statusRaw == 'Hadir' || $statusRaw == 'Terlambat' || $statusRaw == 'Cepat Pulang') { 
                            $h++; $bg='status-h'; $tampil='H';
                        } elseif($statusRaw == 'Sakit') { 
                            $s++; $bg='status-s'; $tampil='S';
                        } elseif($statusRaw == 'Izin') { 
                            $i++; $bg='status-i'; $tampil='I';
                        } elseif($statusRaw == 'Alfa') { 
                            $a++; $bg='status-a'; $tampil='A';
                        }
                    } else {
                        if ($isRapatDay) {
                            $a++; 
                            $bg = 'status-a'; 
                            $tampil = 'A';
                        } else {
                            $bg = 'bg-libur'; 
                            $tampil = ''; 
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
        <p>Ketua Organisasi</p>
        <br><br><br>
        <p><b><?= $organisasi['kepala_instansi'] ?></b></p>
    </div>
</body>
</html>