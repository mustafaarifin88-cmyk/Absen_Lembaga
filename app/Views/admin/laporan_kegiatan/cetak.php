<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Kegiatan</title>
    <link rel="icon" href="<?= base_url('assets/icon.ico') ?>" type="image/x-icon">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 10px; position: relative; }
        .logo { position: absolute; left: 0; top: 0; width: 70px; height: auto; }
        .school-name { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .school-address { font-size: 12px; margin: 5px 0 0; }
        .report-title { text-align: center; font-weight: bold; font-size: 14px; margin: 20px 0; text-transform: uppercase; text-decoration: underline; }
        .period { text-align: center; font-size: 12px; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px 8px; vertical-align: middle; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .text-center { text-align: center; }
        .badge { padding: 2px 5px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid #ccc; }
        .badge-guru { background: #e3f2fd; color: #0d47a1; }
        .badge-siswa { background: #e8f5e9; color: #1b5e20; }
        
        .no-print { position: fixed; top: 10px; right: 10px; z-index: 999; }
        .btn { padding: 8px 15px; background: #333; color: #fff; text-decoration: none; border-radius: 4px; cursor: pointer; border: none; font-size: 12px; }
        .btn-back { background: #666; margin-right: 5px; }
        
        .signature { float: right; width: 250px; text-align: center; margin-top: 30px; }
        .signature p { margin: 0; }
        .signature .space { height: 60px; }
        .signature .name { font-weight: bold; text-decoration: underline; }

        @media print {
            .no-print { display: none; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print">
        <a href="<?= base_url('admin/laporan-kegiatan') ?>" class="btn btn-back">Kembali</a>
        <button onclick="window.print()" class="btn">Cetak</button>
    </div>

    <div class="header">
        <?php 
            $logo = isset($sekolah['logo']) && !empty($sekolah['logo']) ? $sekolah['logo'] : 'default_logo.png';
        ?>
        <img src="<?= base_url('uploads/logo/' . $logo) ?>" class="logo" alt="Logo">
        <h1 class="school-name"><?= isset($sekolah['nama_sekolah']) ? strtoupper($sekolah['nama_sekolah']) : 'NAMA SEKOLAH' ?></h1>
        <p class="school-address">
            <?= isset($sekolah['alamat_lengkap']) ? $sekolah['alamat_lengkap'] : '' ?><br>
            Kab. <?= isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : '' ?>
        </p>
    </div>

    <div class="report-title">LAPORAN KEGIATAN <?= strtoupper($kategori == 'semua' ? 'SHOLAT & EKSTRAKURIKULER' : $kategori) ?></div>
    <div class="period">
        Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th>Nama Lengkap</th>
                <th width="10%">Tipe</th>
                <th width="20%">Kegiatan</th>
                <th width="10%">Jam Absen</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach($laporan as $key => $row): 
                    $nama = ($row['user_type'] == 'guru') ? $row['nama_guru'] : $row['nama_siswa'];
                    $nomor = ($row['user_type'] == 'guru') ? $row['nip'] : $row['nisn'];
                ?>
                <tr>
                    <td class="text-center"><?= $key + 1 ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                    <td>
                        <strong><?= $nama ?></strong><br>
                        <small style="color: #666;"><?= $nomor ?></small>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-<?= $row['user_type'] ?>"><?= strtoupper($row['user_type']) ?></span>
                    </td>
                    <td>
                        <div style="font-weight: bold;"><?= $row['nama_kegiatan'] ?></div>
                        <small><?= ucfirst($row['kategori']) ?></small>
                    </td>
                    <td class="text-center"><?= date('H:i', strtotime($row['jam_absen'])) ?></td>
                    <td class="text-center"><?= $row['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature">
        <p><?= isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : 'Tempat' ?>, <?= date('d F Y') ?></p>
        <p>Kepala Sekolah</p>
        <div class="space"></div>
        <p class="name"><?= isset($sekolah['kepala_sekolah']) ? $sekolah['kepala_sekolah'] : '....................' ?></p>
        <p>NIP. <?= isset($sekolah['nip_kepsek']) ? $sekolah['nip_kepsek'] : '-' ?></p>
    </div>

</body>
</html>