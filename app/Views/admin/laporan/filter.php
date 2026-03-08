<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-filter {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 40px 30px;
        color: white;
        position: relative;
    }

    .form-label-custom {
        font-weight: 700;
        color: #555;
    }

    .btn-print {
        background: linear-gradient(135deg, #ff5f6d 0%, #ffc371 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        font-weight: 700;
        transition: transform 0.2s;
    }
    
    .btn-print:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>

<div class="page-heading mb-4">
    <h3>Filter Laporan</h3>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-filter">
                <div class="card-header card-header-gradient">
                    <h4 class="mb-0 text-white">Parameter Laporan</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php 
                        $url = '';
                        if($type == 'pengurus_detail') $url = base_url('admin/laporan/cetak-pengurus-detail');
                        elseif($type == 'anggota_detail') $url = base_url('admin/laporan/cetak-anggota-detail');
                        elseif($type == 'pengurus_rekap') $url = base_url('admin/laporan/cetak-pengurus-rekap');
                        elseif($type == 'anggota_rekap') $url = base_url('admin/laporan/cetak-anggota-rekap');
                        elseif($type == 'matriks_bulan') $url = base_url('admin/laporan/cetak-matriks-bulanan');
                        elseif($type == 'matriks_tahun') $url = base_url('admin/laporan/cetak-matriks-tahunan');
                    ?>

                    <form action="<?= $url ?>" method="post" target="_blank">
                        <?= csrf_field() ?>
                        
                        <?php if(strpos($type, 'detail') !== false): ?>
                            <!-- Filter Tanggal Range -->
                            <div class="mb-3">
                                <label class="form-label-custom">Tanggal Mulai</label>
                                <input type="date" name="tgl_awal" class="form-control" value="<?= date('Y-m-01') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label-custom">Tanggal Akhir</label>
                                <input type="date" name="tgl_akhir" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        <?php elseif(strpos($type, 'matriks_tahun') !== false): ?>
                            <!-- Filter Tahun Saja -->
                            <div class="mb-3">
                                <label class="form-label-custom">Tahun</label>
                                <select name="tahun" class="form-select">
                                    <?php for($y=date('Y'); $y>=2020; $y--): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <!-- Filter Bulan Tahun (Rekap & Matriks Bulan) -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label-custom">Bulan</label>
                                    <select name="bulan" class="form-select">
                                        <?php 
                                        $bln = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                        foreach($bln as $k => $v): ?>
                                            <option value="<?= $k ?>" <?= date('n') == $k ? 'selected' : '' ?>><?= $v ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label-custom">Tahun</label>
                                    <select name="tahun" class="form-select">
                                        <?php for($y=date('Y'); $y>=2020; $y--): ?>
                                            <option value="<?= $y ?>"><?= $y ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(strpos($type, 'anggota') !== false || strpos($type, 'matriks') !== false): ?>
                            <div class="mb-4">
                                <label class="form-label-custom">Pilih RT (Opsional)</label>
                                <select name="rt_id" class="form-select">
                                    <option value="">-- Semua RT --</option>
                                    <?php foreach($rt as $r): ?>
                                        <option value="<?= $r['id'] ?>"><?= $r['nama_rt'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-print">
                            <i class="bi bi-printer-fill me-2"></i> Cetak Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>