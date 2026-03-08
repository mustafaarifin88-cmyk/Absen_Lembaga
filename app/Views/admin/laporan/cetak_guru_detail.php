<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Detail Absensi Guru</title>
    <link rel="icon" href="<?= base_url('assets/icon.ico') ?>" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 210mm; 
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 3px double #25396f;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .logo-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            width: 100%;
        }

        .header-text h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            text-transform: uppercase;
            color: #25396f;
            letter-spacing: 1px;
        }

        .header-text p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }

        .report-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .report-title h3 {
            margin: 0;
            font-size: 18px;
            text-decoration: underline;
            text-underline-offset: 5px;
            color: #000;
        }

        .report-title p {
            margin: 5px 0 0;
            font-size: 14px;
            font-style: italic;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }

        thead th {
            background-color: #435ebe;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #435ebe;
        }

        tbody td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 4px;
        }
        .status-hadir { color: #198754; background: #e8f5e9; }
        .status-terlambat { color: #dc3545; background: #fbe9eb; }
        .status-sakit { color: #0dcaf0; background: #e0f7fa; }
        .status-izin { color: #ffc107; background: #fff8e1; }
        .status-alfa { color: #212529; background: #f2f2f2; }
        .status-cepat-pulang { color: #d39e00; background: #fff3cd; }

        .text-danger-custom {
            color: #d63384;
            font-weight: bold;
            font-style: italic;
        }

        .signature-section {
            float: right;
            width: 350px;
            text-align: center;
            margin-top: 30px;
        }

        .signature-date {
            margin-bottom: 5px;
            white-space: nowrap;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 70px;
        }

        .signature-name {
            font-weight: 800;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .signature-nip {
            font-size: 13px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .btn-print {
            background: #435ebe;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(67, 94, 190, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-back {
            background: #6c757d;
            margin-right: 10px;
        }

        .text-muted-small {
            font-size: 10px;
            color: #777;
            display: block;
            margin-top: 2px;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; -webkit-print-color-adjust: exact; }
            .container { width: 100%; max-width: none; padding: 0; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="<?= base_url('admin/laporan') ?>" class="btn-print btn-back">
             &larr; Kembali
        </a>
        <a href="#" onclick="window.print()" class="btn-print">
            Cetak Laporan
        </a>
    </div>

    <div class="container">
        <header>
            <div class="logo-wrapper">
                <?php 
                    $logo = isset($sekolah['logo']) && !empty($sekolah['logo']) ? $sekolah['logo'] : 'default_logo.png';
                    $logoPath = 'uploads/logo/' . $logo;
                ?>
                <img src="<?= base_url($logoPath) ?>" alt="Logo Sekolah" class="logo">
            </div>
            <div class="header-text">
                <h2><?= isset($sekolah['nama_sekolah']) ? strtoupper($sekolah['nama_sekolah']) : 'NAMA SEKOLAH' ?></h2>
                <p><?= isset($sekolah['alamat_lengkap']) ? $sekolah['alamat_lengkap'] : 'Alamat Sekolah' ?></p>
                <p>Kab. <?= isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : 'Kabupaten' ?></p>
            </div>
        </header>

        <div class="report-title">
            <h3>LAPORAN DETAIL ABSENSI GURU</h3>
            <p>Periode: <?= date('d M Y', strtotime($tgl_awal)) ?> s/d <?= date('d M Y', strtotime($tgl_akhir)) ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%" style="text-align:center">No</th>
                    <th>Nama Guru</th>
                    <th>Tanggal</th>
                    <th style="text-align:center">Masuk</th>
                    <th style="text-align:center">Pulang</th>
                    <th style="text-align:center">Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($absensi)) : ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 20px; font-style:italic; color:#999;">
                            Tidak ada data absensi guru pada periode ini.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php 
                    $no = 1; 
                    foreach ($absensi as $row) : 
                        $statusClass = 'status-' . strtolower(str_replace(' ', '-', $row['status']));
                        if ($row['status'] == 'Cepat Pulang') $statusClass = 'status-cepat-pulang';
                    ?>
                        <tr>
                            <td style="text-align:center"><?= $no++ ?></td>
                            <td>
                                <span style="font-weight:700"><?= esc($row['nama_lengkap']) ?></span>
                                <span class="text-muted-small">NIP: <?= esc($row['nomor_induk']) ?></span>
                                <span class="text-muted-small"><?= esc($row['jabatan']) ?></span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td style="text-align:center"><?= $row['jam_masuk'] ? date('H:i', strtotime($row['jam_masuk'])) : '-' ?></td>
                            <td style="text-align:center"><?= $row['jam_pulang'] ? date('H:i', strtotime($row['jam_pulang'])) : '-' ?></td>
                            <td style="text-align:center">
                                <span class="status <?= $statusClass ?>">
                                    <?= strtoupper($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $ket = esc($row['keterangan']);
                                    if (strpos($ket, 'Cepat Pulang') !== false || strpos($ket, 'Terlambat') !== false) {
                                        echo '<span class="text-danger-custom">' . $ket . '</span>';
                                    } else {
                                        echo $ket ? $ket : '-';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
            function tanggal_indo($tanggal) {
                $bulan = array (
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                );
                $pecahkan = explode('-', $tanggal);
                return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
            }
            $tglSekarang = date('Y-m-d');
        ?>

        <div class="signature-section">
            <div class="signature-date">
                <?= isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : 'Tempat' ?>, <?= tanggal_indo($tglSekarang) ?>
            </div>
            <div class="signature-title">Kepala Sekolah</div>
            <br><br><br>
            <div class="signature-name"><?= isset($sekolah['kepala_sekolah']) ? $sekolah['kepala_sekolah'] : '( ........................... )' ?></div>
            <div class="signature-nip">NIP. <?= isset($sekolah['nip_kepsek']) ? $sekolah['nip_kepsek'] : '-' ?></div>
        </div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>