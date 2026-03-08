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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-import:hover {
        background: #198754;
        color: white;
        border-color: #198754;
        transform: translateY(-2px);
    }

    .btn-add-new {
        background: white;
        color: #435ebe;
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
        background: #f0f0f0;
        color: #435ebe;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .upload-area {
        border: 2px dashed #dce1e6;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .upload-area:hover {
        border-color: #198754;
        background: #e9f7ef;
    }

    .table-modern thead th { background-color: #f8f9fa; color: #607080; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; border-bottom: 2px solid #eef2f7; padding: 15px; }
    .table-modern tbody td { padding: 15px; vertical-align: middle; color: #555; border-bottom: 1px solid #f2f4f8; font-size: 0.95rem; }
    .table-modern tbody tr:hover { background-color: #fcfdff; }
    .qr-thumbnail { width: 45px; height: 45px; border-radius: 8px; object-fit: cover; border: 2px solid #eef2f7; cursor: pointer; transition: transform 0.2s; background: white; padding: 2px; }
    .qr-thumbnail:hover { transform: scale(1.5); z-index: 10; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #435ebe; }
    .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; transition: all 0.2s; margin: 0 2px; }
    .btn-edit { background: #eef2ff; color: #435ebe; }
    .btn-edit:hover { background: #435ebe; color: white; }
    .btn-delete { background: #fff0f0; color: #ff4c4c; }
    .btn-delete:hover { background: #ff4c4c; color: white; }
    .btn-qr { background: #e6fffa; color: #00b894; }
    .btn-qr:hover { background: #00b894; color: white; }
    .badge-kelas { background: #f0f3ff; color: #435ebe; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; }
    .empty-qr { width: 45px; height: 45px; border-radius: 8px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 1.2rem; border: 2px dashed #dee2e6; }
    .table-avatar { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid #eef2f7; }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Siswa</h3>
            <p class="text-subtitle text-muted">Kelola data siswa, import data, dan generate QR Code.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Siswa</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <script>Swal.fire({icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, showConfirmButton: false})</script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>Swal.fire({icon: 'error', title: 'Gagal', text: '<?= session()->getFlashdata('error') ?>'})</script>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="d-flex align-items-center">
                <i class="bi bi-backpack-fill fs-3 me-3"></i>
                <div>
                    <h5 class="mb-0 text-white">Daftar Siswa</h5>
                    <small class="text-white text-opacity-75">Total: <?= count($siswa) ?> Siswa Terdaftar</small>
                </div>
            </div>
            
            <div class="d-flex">
                <button type="button" class="btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-earmark-excel-fill"></i> Import Excel
                </button>

                <a href="<?= base_url('admin/siswa/new') ?>" class="btn-add-new">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Siswa
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table table-modern" id="table1">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th width="10%">QR Code</th>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>WhatsApp Ortu</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa as $key => $s) : ?>
                            <tr>
                                <td><?= $key + 1 ?></td>
                                <td>
                                    <?php 
                                        $foto = $s['foto'] ? $s['foto'] : 'default.jpg';
                                        $fotoPath = 'uploads/foto_siswa/' . $foto;
                                    ?>
                                    <img src="<?= base_url($fotoPath) ?>" class="table-avatar" alt="Foto">
                                </td>
                                <td>
                                    <?php if ($s['qr_code'] && file_exists('uploads/qr/' . $s['qr_code'])) : ?>
                                        <img src="<?= base_url('uploads/qr/' . $s['qr_code']) ?>" class="qr-thumbnail" alt="QR" onclick="showQrModal('<?= base_url('uploads/qr/' . $s['qr_code']) ?>', '<?= esc($s['nama_lengkap']) ?>')">
                                    <?php else : ?>
                                        <div class="empty-qr" title="Belum ada QR"><i class="bi bi-qr-code"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="fw-bold text-dark"><?= esc($s['nisn']) ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-light-primary me-2"><span class="text-primary fw-bold"><?= substr($s['nama_lengkap'], 0, 1) ?></span></div>
                                        <?= esc($s['nama_lengkap']) ?>
                                    </div>
                                </td>
                                <td><span class="badge-kelas"><?= esc($s['nama_kelas']) ?> - <?= esc($s['jurusan']) ?></span></td>
                                <td><a href="https://wa.me/<?= esc($s['no_wa_ortu']) ?>" target="_blank" class="text-success fw-bold text-decoration-none"><i class="bi bi-whatsapp me-1"></i> <?= esc($s['no_wa_ortu']) ?></a></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/siswa/generate-qr/' . $s['id']) ?>" class="btn-action btn-qr" title="Generate QR"><i class="bi bi-qr-code-scan"></i></a>
                                    <a href="<?= base_url('admin/siswa/edit/' . $s['id']) ?>" class="btn-action btn-edit" title="Edit Data"><i class="bi bi-pencil-fill"></i></a>
                                    <button onclick="confirmDelete(<?= $s['id'] ?>)" class="btn-action btn-delete" title="Hapus Data"><i class="bi bi-trash-fill"></i></button>
                                    <form id="delete-form-<?= $s['id'] ?>" action="<?= base_url('admin/siswa/delete/' . $s['id']) ?>" method="get" class="d-none"></form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-success"><i class="bi bi-file-earmark-excel-fill me-2"></i> Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= base_url('admin/siswa/import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">1. Download Template</label>
                        <p class="text-muted small mb-2">Gunakan template ini agar format data sesuai. Lihat sheet 'Referensi ID Kelas'.</p>
                        <a href="<?= base_url('admin/siswa/template') ?>" class="btn btn-outline-success btn-sm w-100">
                            <i class="bi bi-download me-2"></i> Download Template Excel
                        </a>
                    </div>

                    <hr class="my-3 opacity-10">

                    <div class="mb-3">
                        <label class="form-label fw-bold">2. Upload File Excel</label>
                        <div class="upload-area" onclick="document.getElementById('file_excel').click()">
                            <input type="file" name="file_excel" id="file_excel" class="d-none" accept=".xls,.xlsx" onchange="updateFileName(this)" required>
                            <i class="bi bi-cloud-upload-fill text-success fs-1"></i>
                            <p class="mb-0 mt-2 fw-bold text-dark" id="file-label">Klik untuk pilih file</p>
                            <small class="text-muted">Format: .xlsx atau .xls</small>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success py-2 rounded-3 fw-bold">
                            <i class="bi bi-check-circle-fill me-2"></i> Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none;">
            <div class="modal-body text-center p-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <h5 class="mb-4 text-dark fw-bold" id="qrModalTitle">QR Code</h5>
                <div class="bg-white p-3 d-inline-block rounded-4 shadow-sm mb-3">
                    <img src="" id="qrModalImg" class="img-fluid" style="width: 250px; height: 250px;">
                </div>
                <p class="text-muted small mb-4">Gunakan QR Code ini untuk melakukan absensi kehadiran.</p>
                <a href="" id="downloadLink" download class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-download me-2"></i> Download QR
                </a>
                <button type="button" class="btn btn-light rounded-pill px-4 ms-2" data-bs-dismiss="modal">Tutup</button>
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
            title: 'Hapus Data?', text: "Data siswa yang dihapus tidak dapat dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ff4c4c', cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        })
    }

    function updateFileName(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-label').innerHTML = '<i class="bi bi-file-earmark-excel text-success"></i> ' + input.files[0].name;
        }
    }
</script>

<?= $this->endSection() ?>