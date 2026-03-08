<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Anggota</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px double #000; padding-bottom: 10px; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 5px; }
        .text-center { text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2 style="margin:0;"><?= strtoupper($organisasi['nama_organisasi']) ?></h2>
        <p style="margin:5px 0;"><?= $organisasi['alamat_lengkap'] ?></p>
        <h3 style="margin:10px 0 0 0;">LAPORAN KEHADIRAN ANGGOTA</h3>
        <p style="margin:0;">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Anggota</th>
                <th>Keterangan</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr><td colspan="8" class="text-center">Tidak ada data absensi pada periode ini.</td></tr>
            <?php else: ?>
                <?php foreach($laporan as $key => $row): ?>
                <tr>
                    <td class="text-center"><?= $key+1 ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td class="text-center"><?= $row['jabatan_or_rt'] ?></td>
                    <td class="text-center"><?= $row['jam_masuk'] ?></td>
                    <td class="text-center"><?= $row['jam_pulang'] ?></td>
                    <td class="text-center"><?= $row['status'] ?></td>
                    <td><?= $row['keterangan'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 200px; text-align: center;">
        <p><?= $organisasi['kabupaten'] ?>, <?= date('d F Y') ?></p>
        <p>Kepala Instansi</p>
        <br><br><br>
        <p><b><?= $organisasi['kepala_instansi'] ?></b></p>
    </div>
</body>
</html>