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
    }

    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 94, 190, 0.3);
    }

    .btn-cancel {
        background: #f1f3f5;
        color: #607080;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #e9ecef;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex align-items-center">
        <a href="<?= base_url('admin/data-rt') ?>" class="btn btn-light rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left text-primary fs-5"></i>
        </a>
        <div>
            <h3 class="mb-0 fw-bold text-primary"><?= isset($rt) ? 'Edit Data RT' : 'Tambah RT Baru' ?></h3>
            <p class="text-muted mb-0">Kelola data Rukun Tetangga.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-form">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0 text-white"><i class="bi bi-geo-alt-fill me-2"></i> Informasi RT</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="<?= isset($rt) ? base_url('admin/data-rt/update/' . $rt['id']) : base_url('admin/data-rt/create') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="nama_rt" class="form-label-custom">Nama RT</label>
                            <input type="text" class="form-control form-control-modern <?= $validation->hasError('nama_rt') ? 'is-invalid' : '' ?>" 
                                   id="nama_rt" name="nama_rt" 
                                   placeholder="Contoh: RT 001" 
                                   value="<?= old('nama_rt', isset($rt['nama_rt']) ? $rt['nama_rt'] : '') ?>">
                            <div class="invalid-feedback">
                                <?= $validation->getError('nama_rt') ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-5">
                            <a href="<?= base_url('admin/data-rt') ?>" class="btn btn-cancel">Batal</a>
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-save me-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>