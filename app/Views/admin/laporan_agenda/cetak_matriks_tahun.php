<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Matriks Agenda Tahunan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table-matriks { width: 100%; border-collapse: collapse; }
        .table-matriks th, .table-matriks td { border: 1px solid #000; padding: 4px; text-align: center; }
        .text-left { text-align: left; padding-left: 5px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3 style="margin:0;"><?= strtoupper($organisasi['nama_organisasi']) ?></h3>
        <p style="margin:5px 0;">REKAPITULASI AGENDA TAHUN <?= $tahun ?></p>
        <p style="margin:0;">Nama Agenda: <b><?= $agendaName ?></b></p>
    </div>

    <table class="table-matriks">
        <thead>
            <tr>
                <th rowspan="2" width="30">No</th>
                <th rowspan="2" class="text-left">Nama Peserta</th>
                <?php for($m=1; $m<=12; $m++): ?>
                    <th colspan="4"><?= date('M', mktime(0,0,0,$m,1)) ?></th>
                <?php endfor; ?>
                <th colspan="4" style="background:#f0f0f0;">TOTAL TAHUNAN</th>
            </tr>
            <tr>
                <?php for($m=1; $m<=12; $m++): ?>
                    <th width="15">H</th><th width="15">S</th><th width="15">I</th><th width="15">A</th>
                <?php endfor; ?>
                <th width="25" style="background:#f0f0f0;">H</th>
                <th width="25" style="background:#f0f0f0;">S</th>
                <th width="25" style="background:#f0f0f0;">I</th>
                <th width="25" style="background:#f0f0f0;">A</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $key => $u): ?>
            <tr>
                <td><?= $key+1 ?></td>
                <td class="text-left"><?= $u['nama_lengkap'] ?></td>
                <?php 
                $grandH=0; $grandS=0; $grandI=0; $grandA=0;
                for($m=1; $m<=12; $m++): 
                    $h = isset($rekap[$u['id']][$m]['H']) ? $rekap[$u['id']][$m]['H'] : 0;
                    $s = isset($rekap[$u['id']][$m]['S']) ? $rekap[$u['id']][$m]['S'] : 0;
                    $i = isset($rekap[$u['id']][$m]['I']) ? $rekap[$u['id']][$m]['I'] : 0;
                    
                    $totalAgenda = isset($agendaCountPerMonth[$m]) ? $agendaCountPerMonth[$m] : 0;
                    $totalNonAlfa = $h + $s + $i;
                    
                    $a = $totalAgenda - $totalNonAlfa;
                    if($a < 0) $a = 0; 

                    $grandH += $h; $grandS += $s; $grandI += $i; $grandA += $a;
                ?>
                    <td><?= $h>0?$h:'-' ?></td>
                    <td><?= $s>0?$s:'-' ?></td>
                    <td><?= $i>0?$i:'-' ?></td>
                    <td><?= $a>0?$a:'-' ?></td>
                <?php endfor; ?>
                <td style="background:#f0f0f0; font-weight:bold;"><?= $grandH ?></td>
                <td style="background:#f0f0f0; font-weight:bold;"><?= $grandS ?></td>
                <td style="background:#f0f0f0; font-weight:bold;"><?= $grandI ?></td>
                <td style="background:#f0f0f0; font-weight:bold;"><?= $grandA ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>