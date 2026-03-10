<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Matriks Agenda Bulanan</title>
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
        <p style="margin:5px 0;">MATRIKS KEHADIRAN AGENDA (<?= strtoupper($kategori) ?>)</p>
        <p style="margin:0;">Nama Agenda: <b><?= $agendaName ?></b> | Bulan: <?= $namaBulan[(int)$bulan] ?> <?= $tahun ?></p>
    </div>

    <table class="table-matriks">
        <thead>
            <tr>
                <th rowspan="2" width="20">No</th>
                <th rowspan="2" class="th-nama">Nama Peserta</th>
                <th colspan="<?= cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun) ?>">Tanggal Pelaksanaan</th>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                <?php 
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                for($d=1; $d<=$daysInMonth; $d++): 
                ?>
                <th width="15"><?= $d ?></th>
                <?php endfor; ?>
                <th width="20">H</th>
                <th width="20">S</th>
                <th width="20">I</th>
                <th width="20">A</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $key => $u): ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td class="th-nama"><?= $u['nama_lengkap'] ?></td>
                    <?php 
                    $h=0; $s=0; $i=0; $a=0;

                    for($d=1; $d<=$daysInMonth; $d++): 
                        $currentDate = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
                        $isAgendaDay = in_array($currentDate, $agendaDates);
                        $bgClass = ''; $symbol = '';

                        if(isset($rekap[$u['id']][$d])) {
                            $status = $rekap[$u['id']][$d];
                            if($status == 'Hadir' || $status == 'Terlambat') { $h++; $bgClass = 'cell-hadir'; $symbol = '•'; }
                            elseif($status == 'Sakit') { $s++; $bgClass = 'cell-sakit'; $symbol = 'S'; }
                            elseif($status == 'Izin') { $i++; $bgClass = 'cell-izin'; $symbol = 'I'; }
                            elseif($status == 'Alfa') { $a++; $bgClass = 'cell-alfa'; $symbol = 'A'; }
                        } else {
                            if ($isAgendaDay) {
                                $a++; 
                                $bgClass = 'cell-alfa'; 
                                $symbol = 'A';
                            } else {
                                $bgClass = 'cell-libur'; 
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
        <strong>Keterangan:</strong> • : Hadir, S : Sakit, I : Izin, A : Alfa
    </div>
</body>
</html>