<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">

<style>
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
    .card-header-modern { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 25px; color: white; display: flex; justify-content: space-between; align-items: center; }
    .table-modern thead th { background-color: #f8f9fa; color: #607080; border-bottom: 2px solid #eef2f7; padding: 15px; text-transform: uppercase; font-size: 0.85rem; font-weight: 700; }
    .table-modern tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f2f4f8; font-size: 0.95rem; }
    .table-modern tbody tr:hover { background-color: #fcfdff; }
    .btn-back { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 12px; padding: 8px 20px; text-decoration: none; font-weight: 600; transition: all 0.3s; backdrop-filter: blur(5px); }
    .btn-back:hover { background: white; color: #11998e; transform: translateY(-2px); }
    .empty-state { text-align: center; padding: 60px 20px; color: #a0aec0; }
    .avatar-student { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 700; font-size: 1.1rem; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-success fw-bold"><?= esc($kelas['nama_kelas']) ?></h3>
            <p class="text-subtitle text-muted">Daftar siswa kompetensi keahlian <?= esc($kelas['jurusan']) ?></p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/kelas') ?>">Data Kelas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lihat Siswa</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-2 rounded-3 me-3">
                    <i class="bi bi-people-fill fs-3 text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 text-white">Siswa Terdaftar</h5>
                    <small class="text-white text-opacity-75">Total: <?= count($siswa) ?> Siswa</small>
                </div>
            </div>
            <a href="<?= base_url('admin/kelas') ?>" class="btn-back">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <?php if(empty($siswa)): ?>
                    <div class="empty-state">
                        <img src="<?= base_url('assets/compiled/svg/no-data.svg') ?>" alt="No Data" style="height: 150px; margin-bottom: 20px; opacity: 0.5;">
                        <h5 class="text-muted">Belum ada siswa di kelas ini.</h5>
                        <p class="small">Silakan tambahkan siswa melalui menu <b>Data Siswa</b>.</p>
                    </div>
                <?php else: ?>
                    <table class="table table-modern" id="table1">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>WhatsApp Ortu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $key => $s) : ?>
                                <tr>
                                    <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                    <td><span class="fw-bold text-dark bg-light px-2 py-1 rounded"><?= esc($s['nisn']) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-student bg-light-success text-success me-3">
                                                <?= substr($s['nama_lengkap'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold"><?= esc($s['nama_lengkap']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($s['no_wa_ortu']): ?>
                                            <a href="https://wa.me/<?= esc($s['no_wa_ortu']) ?>" target="_blank" class="text-success text-decoration-none fw-bold">
                                                <i class="bi bi-whatsapp me-1"></i> <?= esc($s['no_wa_ortu']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Tidak ada nomor</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/datatables.js') ?>"></script>
<?= $this->endSection() ?>