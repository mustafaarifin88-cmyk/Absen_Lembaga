<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Agenda IPPM</title>
    <link rel="icon" href="<?= base_url('assets/icon.ico') ?>" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 70px; height: auto; }
        .org-name { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .org-address { font-size: 12px; margin: 5px 0 0; }
        .report-title { text-align: center; font-weight: bold; font-size: 14px; margin: 20px 0; text-transform: uppercase; text-decoration: underline; }
        .period { text-align: center; font-size: 12px; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .status-alfa { color: red; font-weight: bold; }
        .signature { float: right; width: 200px; text-align: center; margin-top: 50px; }
        .signature .name { font-weight: bold; text-decoration: underline; margin-top: 70px; }
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

    <div class="report-title">LAPORAN KEHADIRAN AGENDA IPPM</div>
    <div class="period">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th>Nama Peserta</th>
                <th width="15%">Kategori</th>
                <th width="20%">Nama Agenda</th>
                <th width="10%">Jam Absen</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data kehadiran pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach($laporan as $key => $row): 
                    $nama = $row['user_type'] == 'pengurus' ? $row['nama_pengurus'] : $row['nama_anggota'];
                    $jamTampil = ($row['jam_absen'] == '-' || !$row['jam_absen']) ? '-' : date('H:i', strtotime($row['jam_absen']));
                    $classStatus = ($row['status'] == 'Alfa') ? 'status-alfa' : '';
                ?>
                <tr>
                    <td class="text-center"><?= $key + 1 ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $nama ?></td>
                    <td class="text-center">
                        <?= strtoupper($row['user_type']) ?>
                    </td>
                    <td><?= $row['nama_agenda'] ?></td>
                    <td class="text-center"><?= $jamTampil ?></td>
                    <td class="text-center <?= $classStatus ?>"><?= $row['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <p><?= isset($organisasi['kabupaten']) ? $organisasi['kabupaten'] : 'Tempat' ?>, <?= date('d F Y') ?></p>
        <p>Kepala Instansi</p>
        <p class="name"><?= $organisasi['kepala_instansi'] ?></p>
    </div>
</body>
</html>