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

    .btn-import {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
        margin-right: 10px;
    }

    .btn-import:hover {
        background: white;
        color: #435ebe;
        transform: translateY(-2px);
    }

    .btn-add-new {
        background: white;
        color: #435ebe;
        border: none;
        border-radius: 12px;
        padding: 8px 20px;
        font-weight: 700;
        transition: all 0.3s;
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

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

    .btn-qr { background: #e6f7ff; color: #0099ff; }
    .btn-qr:hover { background: #0099ff; color: white; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Pengurus</h3>
            <p class="text-subtitle text-muted">Kelola data pengurus organisasi.</p>
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

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-badge fs-4"></i>
                <span class="fw-bold fs-5">Daftar Pengurus</span>
            </div>
            <div>
                <button type="button" class="btn btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Import Excel
                </button>
                <a href="<?= base_url('admin/pengurus/new') ?>" class="btn btn-add-new">
                    <i class="bi bi-plus-lg"></i> Tambah Pengurus
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern table-hover" id="table1">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%">Foto</th>
                            <th>Nama Lengkap</th>
                            <th>Jabatan</th>
                            <th width="10%" class="text-center">QR Code</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pengurus as $key => $p): ?>
                            <tr>
                                <td class="text-center"><?= $key + 1 ?></td>
                                <td>
                                    <img src="<?= base_url('uploads/foto_pengurus/' . ($p['foto'] ? $p['foto'] : 'default.jpg')) ?>" class="user-avatar" alt="Foto">
                                </td>
                                <td class="fw-bold text-dark"><?= esc($p['nama_lengkap']) ?></td>
                                <td><span class="badge bg-light-primary text-primary"><?= esc($p['jabatan']) ?></span></td>
                                <td class="text-center">
                                    <button onclick="showQrModal('<?= base_url('uploads/qr/' . $p['qr_code']) ?>', '<?= esc($p['nama_lengkap']) ?>')" class="btn-action btn-qr" title="Lihat QR">
                                        <i class="bi bi-qr-code"></i>
                                    </button>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/pengurus/edit/' . $p['id']) ?>" class="btn-action btn-edit me-1" title="Edit Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $p['id'] ?>)" class="btn-action btn-delete" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    <form id="delete-form-<?= $p['id'] ?>" action="<?= base_url('admin/pengurus/delete/' . $p['id']) ?>" method="get" class="d-none"></form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/pengurus/import') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Import Data Pengurus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">File Excel (.xlsx)</label>
                    <input type="file" name="file_excel" class="form-control" required accept=".xlsx, .xls">
                </div>
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-1"></i> Gunakan template yang disediakan agar format sesuai.
                </div>
                <a href="<?= base_url('admin/pengurus/download-template') ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-download me-1"></i> Download Template Excel
                </a>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-3">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="qrModalTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="qrModalImg" src="" class="img-fluid rounded border p-2 mb-3" style="max-width: 200px;">
                <a id="downloadLink" href="" download class="btn btn-primary w-100 btn-sm">
                    <i class="bi bi-download me-1"></i> Download QR
                </a>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/datatables.js') ?>"></script>

<script>
    function showQrModal(url, nama) {
        document.getElementById('qrModalImg').src = url;
        document.getElementById('qrModalTitle').innerText = nama;
        document.getElementById('downloadLink').href = url;
        var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
        myModal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?', text: "Data pengurus yang dihapus tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ff4c4c', cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        })
    }
</script>

<?= $this->endSection() ?>