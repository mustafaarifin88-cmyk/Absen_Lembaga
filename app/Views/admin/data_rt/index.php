<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .card-header-modern {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add-new {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-new:hover {
        background: white;
        color: #435ebe;
        transform: translateY(-2px);
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        color: #607080;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid #eef2f7;
        padding: 15px;
    }

    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        transition: all 0.2s;
    }

    .btn-edit { background: #eef2ff; color: #435ebe; }
    .btn-edit:hover { background: #435ebe; color: white; }
    
    .btn-delete { background: #fff0f0; color: #ff4c4c; }
    .btn-delete:hover { background: #ff4c4c; color: white; }

    .btn-members { background: #e6fffa; color: #00b894; }
    .btn-members:hover { background: #00b894; color: white; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Rukun Tetangga (RT)</h3>
            <p class="text-subtitle text-muted">Kelola data RT organisasi.</p>
        </div>
    </div>
</div>

<div class="page-content">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill fs-4"></i>
                <span class="fw-bold fs-5">Daftar RT</span>
            </div>
            <a href="<?= base_url('admin/data-rt/new') ?>" class="btn btn-add-new">
                <i class="bi bi-plus-lg"></i> Tambah RT
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern table-hover" id="table1">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama RT</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rt as $key => $r): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td class="fw-bold text-dark"><?= esc($r['nama_rt']) ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/data-rt/members/' . $r['id']) ?>" class="btn-action btn-members me-1" title="Lihat Anggota">
                                        <i class="bi bi-people-fill"></i>
                                    </a>
                                    <a href="<?= base_url('admin/data-rt/edit/' . $r['id']) ?>" class="btn-action btn-edit me-1" title="Edit Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $r['id'] ?>)" class="btn-action btn-delete" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    <form id="delete-form-<?= $r['id'] ?>" action="<?= base_url('admin/data-rt/delete/' . $r['id']) ?>" method="get" class="d-none"></form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/datatables.js') ?>"></script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus RT?', 
            text: "Pastikan tidak ada anggota yang terdaftar di RT ini sebelum menghapus.",
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#ff4c4c', 
            cancelButtonColor: '#8592a3', 
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { 
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); 
        })
    }
</script>
<?= $this->endSection() ?>