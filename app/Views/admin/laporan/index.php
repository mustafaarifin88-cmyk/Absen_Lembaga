<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-menu {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        cursor: pointer;
    }

    .card-menu:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .card-header-menu {
        padding: 20px;
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header-blue { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); }
    .header-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .header-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    
    .card-body p {
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>

<div class="page-heading mb-4">
    <h3>Pusat Laporan Anggota</h3>
    <p class="text-muted">Pilih jenis laporan yang ingin dicetak.</p>
</div>

<div class="page-content">
    <div class="row g-4">
        
        <!-- Matriks Laporan -->
        <div class="col-12 mb-2">
            <h5 class="fw-bold text-primary"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Laporan Matriks</h5>
        </div>

        <div class="col-md-6">
            <div class="card card-menu" onclick="document.getElementById('form-matriks-bulan').submit()">
                <div class="card-header-menu header-purple">
                    <i class="bi bi-calendar-month fs-4"></i> Matriks Bulanan
                </div>
                <div class="card-body">
                    <p class="mb-0">Cetak matriks kehadiran seluruh anggota dalam satu bulan (Tampilan Grid Tanggal 1-31).</p>
                    <form id="form-matriks-bulan" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="matriks_bulan">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-menu" onclick="document.getElementById('form-matriks-tahun').submit()">
                <div class="card-header-menu header-purple">
                    <i class="bi bi-calendar-range fs-4"></i> Matriks Tahunan
                </div>
                <div class="card-body">
                    <p class="mb-0">Rekapitulasi total kehadiran per bulan dalam satu tahun penuh.</p>
                    <form id="form-matriks-tahun" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="matriks_tahun">
                    </form>
                </div>
            </div>
        </div>

        <!-- Laporan Detail & Rekap -->
        <div class="col-12 mt-4 mb-2">
            <h5 class="fw-bold text-primary"><i class="bi bi-file-earmark-text-fill me-2"></i> Laporan Detail & Rekap</h5>
        </div>

        <div class="col-md-3">
            <div class="card card-menu" onclick="document.getElementById('form-pengurus-detail').submit()">
                <div class="card-header-menu header-blue">
                    <i class="bi bi-person-badge fs-4"></i> Detail Pengurus
                </div>
                <div class="card-body">
                    <p class="mb-0">Log absensi harian pengurus (Jam Masuk/Pulang).</p>
                    <form id="form-pengurus-detail" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="pengurus_detail">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-menu" onclick="document.getElementById('form-pengurus-rekap').submit()">
                <div class="card-header-menu header-blue">
                    <i class="bi bi-table fs-4"></i> Rekap Pengurus
                </div>
                <div class="card-body">
                    <p class="mb-0">Ringkasan kehadiran pengurus per bulan.</p>
                    <form id="form-pengurus-rekap" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="pengurus_rekap">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-menu" onclick="document.getElementById('form-anggota-detail').submit()">
                <div class="card-header-menu header-green">
                    <i class="bi bi-people-fill fs-4"></i> Detail Anggota
                </div>
                <div class="card-body">
                    <p class="mb-0">Log absensi harian anggota per RT.</p>
                    <form id="form-anggota-detail" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="anggota_detail">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-menu" onclick="document.getElementById('form-anggota-rekap').submit()">
                <div class="card-header-menu header-green">
                    <i class="bi bi-table fs-4"></i> Rekap Anggota
                </div>
                <div class="card-body">
                    <p class="mb-0">Ringkasan kehadiran anggota per bulan.</p>
                    <form id="form-anggota-rekap" action="<?= base_url('admin/laporan/filter') ?>" method="get">
                        <input type="hidden" name="type" value="anggota_rekap">
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>