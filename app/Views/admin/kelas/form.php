<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-form {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .card-header-gradient::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .card-header-gradient::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 20px;
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .form-label-custom {
        font-weight: 700;
        color: #555;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .form-control-modern {
        border: 2px solid #eef2f7;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 500;
        transition: all 0.3s;
        background-color: #fcfcfc;
    }
    
    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.1);
        background: #fff;
    }

    .input-group-text-modern {
        background: #f8f9fa;
        border: 2px solid #eef2f7;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #435ebe;
        padding-left: 20px;
        padding-right: 20px;
    }

    .form-control-modern-group {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .btn-save {
        background: linear-gradient(90deg, #435ebe, #25396f);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(67, 94, 190, 0.3);
        transition: all 0.3s;
        color: white;
        letter-spacing: 0.5px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 94, 190, 0.4);
        color: white;
    }

    .btn-cancel {
        background: #f1f3f5;
        color: #6c757d;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #e9ecef;
        color: #495057;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold"><?= isset($kelas) ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h3>
            <p class="text-subtitle text-muted">Kelola data kelas dan kompetensi keahlian.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/kelas') ?>">Data Kelas</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= isset($kelas) ? 'Edit' : 'Tambah' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card card-form">
                <div class="card-header-gradient">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                            <i class="bi bi-diagram-3-fill fs-2"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">Informasi Kelas</h5>
                            <p class="mb-0 text-white text-opacity-75 small">Pastikan nama kelas dan jurusan sesuai format.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= isset($kelas) ? base_url('admin/kelas/update/' . $kelas['id']) : base_url('admin/kelas/create') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nama_kelas" class="form-label-custom">Nama Kelas <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-layers-fill"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nama_kelas') ? 'is-invalid' : '' ?>" 
                                           id="nama_kelas" name="nama_kelas" 
                                           placeholder="Contoh: X Multimedia 1" 
                                           value="<?= old('nama_kelas', isset($kelas['nama_kelas']) ? $kelas['nama_kelas'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nama_kelas') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="jurusan" class="form-label-custom">Kompetensi Keahlian / Jurusan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-mortarboard-fill"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('jurusan') ? 'is-invalid' : '' ?>" 
                                           id="jurusan" name="jurusan" 
                                           placeholder="Contoh: Teknik Komputer dan Jaringan" 
                                           value="<?= old('jurusan', isset($kelas['jurusan']) ? $kelas['jurusan'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('jurusan') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/kelas') ?>" class="btn btn-cancel">Batal</a>
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-save me-2"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>