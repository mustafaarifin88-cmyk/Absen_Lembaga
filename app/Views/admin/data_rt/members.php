<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">

<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header-modern {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        color: #607080;
        border-bottom: 2px solid #eef2f7;
        padding: 15px;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex align-items-center">
        <a href="<?= base_url('admin/data-rt') ?>" class="btn btn-light rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left text-primary fs-5"></i>
        </a>
        <div>
            <h3 class="mb-0 fw-bold text-primary">Anggota <?= esc($rt['nama_rt']) ?></h3>
            <p class="text-muted mb-0">Daftar anggota yang terdaftar di RT ini.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people-fill fs-4"></i>
                <span class="fw-bold fs-5">Total: <?= count($anggota) ?> Anggota</span>
            </div>
            <div>
                <?php if(count($anggota) > 0): ?>
                <a href="<?= base_url('admin/data-rt/download-qr/' . $rt['id']) ?>" class="btn btn-light text-success fw-bold">
                    <i class="bi bi-qr-code-scan me-2"></i> Download Semua QR
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <?php if(empty($anggota)): ?>
                    <div class="text-center p-5">
                        <img src="<?= base_url('assets/images/empty.svg') ?>" alt="Empty" style="width: 150px; opacity: 0.5;">
                        <p class="text-muted mt-3 fw-bold">Belum ada anggota di RT ini.</p>
                        <a href="<?= base_url('admin/anggota/new') ?>" class="btn btn-sm btn-primary mt-2">Tambah Anggota</a>
                    </div>
                <?php else: ?>
                    <table class="table table-modern table-hover" id="table1">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Lengkap</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($anggota as $key => $s): ?>
                                <tr>
                                    <td class="text-center"><?= $key + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('uploads/foto_anggota/' . ($s['foto'] ? $s['foto'] : 'default.jpg')) ?>" class="user-avatar me-3" alt="Foto">
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold"><?= esc($s['nama_lengkap']) ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($s['foto'] && $s['foto'] != 'default.jpg'): ?>
                                            <span class="badge bg-success">Ada</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Default</span>
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