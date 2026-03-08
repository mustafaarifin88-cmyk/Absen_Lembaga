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
        position: relative;
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
        color: #555;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
    }

    .table-modern tbody tr:hover {
        background-color: #fcfdff;
    }

    .user-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #eef2f7;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .badge-level {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-admin {
        background: #eef2ff;
        color: #435ebe;
        border: 1px solid rgba(67, 94, 190, 0.1);
    }

    .badge-petugas {
        background: #e6fffa;
        color: #00b894;
        border: 1px solid rgba(0, 184, 148, 0.1);
    }

    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: none;
        transition: all 0.2s;
        margin: 0 2px;
    }

    .btn-edit {
        background: #fff8e1;
        color: #ffc107;
    }
    
    .btn-edit:hover {
        background: #ffc107;
        color: white;
    }

    .btn-delete {
        background: #fff0f0;
        color: #ff4c4c;
    }
    
    .btn-delete:hover {
        background: #ff4c4c;
        color: white;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Manajemen User</h3>
            <p class="text-subtitle text-muted">Kelola akun administrator dan petugas absensi.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
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

    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="d-flex align-items-center">
                <i class="bi bi-people-fill fs-3 me-3"></i>
                <div>
                    <h5 class="mb-0 text-white">Daftar Pengguna</h5>
                    <small class="text-white text-opacity-75">Total: <?= count($users) ?> Akun Terdaftar</small>
                </div>
            </div>
            <a href="<?= base_url('admin/users/new') ?>" class="btn-add-new">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table table-modern" id="table1">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="10%">Foto</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Level Akses</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $key => $user) : ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $key + 1 ?></td>
                                <td>
                                    <?php 
                                        $foto = $user['foto'] ? $user['foto'] : 'default.jpg';
                                        $fotoPath = 'uploads/foto_profil/' . $foto;
                                    ?>
                                    <img src="<?= base_url($fotoPath) ?>" class="user-avatar" alt="Foto">
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($user['nama_lengkap']) ?></span>
                                    <small class="d-block text-muted">Bergabung: <?= date('d M Y', strtotime($user['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-at me-1"></i> <?= esc($user['username']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-level <?= $user['level'] == 'admin' ? 'badge-admin' : 'badge-petugas' ?>">
                                        <?= strtoupper($user['level']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn-action btn-edit" title="Edit User">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    
                                    <?php if(session()->get('id') != $user['id']): ?>
                                        <button onclick="confirmDelete(<?= $user['id'] ?>)" class="btn-action btn-delete" title="Hapus User">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        <form id="delete-form-<?= $user['id'] ?>" action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="get" class="d-none">
                                            <?= csrf_field() ?>
                                        </form>
                                    <?php endif; ?>
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
            title: 'Hapus User?',
            text: "Akun ini akan dihapus permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4c4c',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

<?= $this->endSection() ?>