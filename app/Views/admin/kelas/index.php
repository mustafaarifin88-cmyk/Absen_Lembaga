<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-modern { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
    .card-header-modern { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); padding: 25px; color: white; display: flex; justify-content: space-between; align-items: center; }
    .btn-add-new { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); border-radius: 12px; padding: 8px 20px; font-weight: 600; transition: all 0.3s; backdrop-filter: blur(5px); }
    .btn-add-new:hover { background: white; color: #435ebe; transform: translateY(-2px); }
    .table-modern thead th { background-color: #f8f9fa; color: #607080; border-bottom: 2px solid #eef2f7; padding: 15px; }
    .table-modern tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f2f4f8; }
    
    .link-kelas { color: #435ebe; font-weight: bold; text-decoration: none; transition: 0.3s; }
    .link-kelas:hover { color: #25396f; text-decoration: underline; }
    
    .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: none; margin: 0 2px; }
    .btn-zip { background: #e6fffa; color: #00b894; }
    .btn-zip:hover { background: #00b894; color: white; }
    .btn-edit { background: #eef2ff; color: #435ebe; }
    .btn-edit:hover { background: #435ebe; color: white; }
    .btn-delete { background: #fff0f0; color: #ff4c4c; }
    .btn-delete:hover { background: #ff4c4c; color: white; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Kelas</h3>
            <p class="text-subtitle text-muted">Klik nama kelas untuk melihat daftar siswa.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            })
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= session()->getFlashdata('error') ?>',
            })
        </script>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header-modern">
            <h5 class="mb-0 text-white"><i class="bi bi-diagram-3-fill me-2"></i> Daftar Kelas</h5>
            <a href="<?= base_url('admin/kelas/new') ?>" class="btn-add-new"><i class="bi bi-plus-lg"></i> Tambah Kelas</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table table-modern" id="table1">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Kelas</th>
                            <th>Jurusan / Kompetensi</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kelas as $key => $k) : ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                <td>
                                    <a href="<?= base_url('admin/kelas/students/' . $k['id']) ?>" class="link-kelas" title="Lihat Siswa">
                                        <i class="bi bi-folder2-open me-2"></i> <?= esc($k['nama_kelas']) ?>
                                    </a>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($k['jurusan']) ?></span></td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/kelas/download-qr/' . $k['id']) ?>" class="btn-action btn-zip" title="Download QR Code Satu Kelas (ZIP)">
                                        <i class="bi bi-file-earmark-zip-fill"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('admin/kelas/edit/' . $k['id']) ?>" class="btn-action btn-edit" title="Edit Data">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $k['id'] ?>)" class="btn-action btn-delete" title="Hapus Data">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    <form id="delete-form-<?= $k['id'] ?>" action="<?= base_url('admin/kelas/delete/' . $k['id']) ?>" method="get" class="d-none"></form>
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
            title: 'Hapus Kelas?', text: "Data siswa di dalam kelas ini akan kehilangan referensi kelas!",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4c4c', cancelButtonColor: '#8592a3', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); })
    }
</script>

<?= $this->endSection() ?>