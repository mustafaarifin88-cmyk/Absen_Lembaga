<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="page-heading"><h3>Laporan Sholat & Ekskul</h3></div>
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('admin/laporan-kegiatan/cetak') ?>" method="post" target="_blank">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="semua">Semua Kegiatan</option>
                            <option value="sholat">Sholat</option>
                            <option value="ekskul">Ekstrakurikuler</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3"><label>Dari Tanggal</label><input type="date" name="tgl_awal" class="form-control" value="<?= date('Y-m-01') ?>" required></div>
                    <div class="col-md-4 mb-3"><label>Sampai Tanggal</label><input type="date" name="tgl_akhir" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-printer"></i> Cetak Laporan PDF</button>
            </form>
        </div>
    </div>
    
    <div class="mt-4">
        <h4>Menu Koreksi</h4>
        <a href="<?= base_url('admin/koreksi-kegiatan') ?>" class="btn btn-warning w-100"><i class="bi bi-pencil-square"></i> Koreksi / Hapus Data Absensi Kegiatan</a>
    </div>
</div>
<?= $this->endSection() ?>