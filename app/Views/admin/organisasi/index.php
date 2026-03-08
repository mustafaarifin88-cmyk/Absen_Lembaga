<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .org-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        border: none;
        height: 100%;
        transition: transform 0.3s ease;
    }

    .org-header {
        background: linear-gradient(135deg, #25a6f3 0%, #0d6efd 100%);
        height: 150px;
        position: relative;
    }
    
    .org-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50px;
        background: #fff;
        clip-path: polygon(0 50%, 100% 100%, 100% 100%, 0% 100%);
    }

    .org-logo-wrapper {
        margin-top: -80px;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .org-logo {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 5px solid #fff;
        background: #fff;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        color: white;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.3);
    }
</style>

<div class="page-heading mb-4">
    <h3>Profil Organisasi</h3>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="org-card">
                <div class="org-header"></div>
                <div class="org-logo-wrapper">
                    <img src="<?= base_url('uploads/logo/' . ($organisasi['logo'] ? $organisasi['logo'] : 'default_logo.png')) ?>" class="org-logo" id="preview-logo-card">
                </div>
                <div class="card-body text-center pt-2">
                    <h4 class="fw-bold mb-1"><?= $organisasi['nama_organisasi'] ?></h4>
                    <p class="text-muted small mb-3"><?= $organisasi['kabupaten'] ?></p>
                    <hr class="mx-5 my-3 bg-light">
                    <div class="text-start px-3">
                        <div class="mb-3">
                            <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Kepala Instansi</small>
                            <p class="fw-bold text-dark mb-0"><?= $organisasi['kepala_instansi'] ?></p>
                        </div>
                        <div>
                            <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Alamat</small>
                            <p class="text-dark mb-0 small"><?= $organisasi['alamat_lengkap'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">Edit Informasi Organisasi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/organisasi/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Nama Organisasi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                                    <input type="text" name="nama_organisasi" class="form-control bg-light border-0" value="<?= $organisasi['nama_organisasi'] ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kepala Instansi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="kepala_instansi" class="form-control bg-light border-0" value="<?= $organisasi['kepala_instansi'] ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kabupaten/Kota</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" name="kabupaten" class="form-control bg-light border-0" value="<?= $organisasi['kabupaten'] ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" class="form-control bg-light border-0" rows="3" required><?= $organisasi['alamat_lengkap'] ?></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Logo Organisasi</label>
                                <div class="border-2 border-dashed p-4 text-center rounded-3 bg-light position-relative" onclick="document.getElementById('logo').click()" style="cursor: pointer;">
                                    <input type="file" name="logo" id="logo" class="d-none" accept="image/*" onchange="previewLogo(this)">
                                    <div id="upload-placeholder">
                                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                        <p class="text-muted small mb-0">Klik untuk upload logo baru (Max 2MB)</p>
                                    </div>
                                    <div id="preview-container" class="d-none">
                                        <img id="preview-img-form" src="" style="max-height: 100px;">
                                        <p class="text-success small mt-2 fw-bold"><i class="bi bi-check-circle"></i> Logo siap diupload</p>
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block"><?= $validation->getError('logo') ?></div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-save">
                                    <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-img-form').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('d-none');
                document.getElementById('preview-container').classList.remove('d-none');
                
                document.getElementById('preview-logo-card').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>