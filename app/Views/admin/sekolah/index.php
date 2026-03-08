<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .school-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        border: none;
        height: 100%;
        transition: transform 0.3s ease;
    }

    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .school-header {
        background: linear-gradient(135deg, #25a6f3 0%, #0d6efd 100%);
        height: 150px;
        position: relative;
    }
    
    .school-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50px;
        background: #fff;
        clip-path: polygon(0 50%, 100% 100%, 100% 100%, 0% 100%);
    }

    .school-logo-wrapper {
        margin-top: -80px;
        display: flex;
        justify-content: center;
        position: relative;
        z-index: 2;
    }

    .school-logo {
        width: 140px;
        height: 140px;
        object-fit: contain;
        background: #fff;
        border-radius: 50%;
        padding: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .form-group label {
        font-weight: 600;
        color: #607080;
        margin-bottom: 0.5rem;
    }

    .form-control-modern {
        border: 2px solid #eef2f7;
        border-radius: 12px;
        padding: 0.7rem 1rem;
        transition: all 0.3s;
    }

    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
    }

    .upload-zone {
        border: 2px dashed #dce1e6;
        border-radius: 15px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .upload-zone:hover {
        border-color: #435ebe;
        background: #eef2ff;
    }

    .btn-save {
        background: linear-gradient(90deg, #435ebe, #25396f);
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(67, 94, 190, 0.3);
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 94, 190, 0.4);
        color: white;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Identitas Sekolah</h3>
            <p class="text-subtitle text-muted">Kelola informasi profil, logo, dan data kepala sekolah.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sekolah</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-lg-4 mb-4">
            <div class="school-card text-center pb-4">
                <div class="school-header"></div>
                <div class="school-logo-wrapper">
                    <?php 
                        $logo = isset($sekolah['logo']) && !empty($sekolah['logo']) ? $sekolah['logo'] : 'default_logo.png';
                        $logoPath = 'uploads/logo/' . $logo;
                    ?>
                    <img src="<?= base_url($logoPath) ?>" alt="Logo Sekolah" class="school-logo" id="preview-logo-card">
                </div>
                <div class="px-4 mt-3">
                    <h4 class="fw-bold text-dark mb-1"><?= isset($sekolah['nama_sekolah']) ? esc($sekolah['nama_sekolah']) : 'Nama Sekolah' ?></h4>
                    <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= isset($sekolah['kabupaten']) ? esc($sekolah['kabupaten']) : 'Kabupaten' ?></p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-light-primary text-primary">Akreditasi A</span>
                        <span class="badge bg-light-success text-success">Active</span>
                    </div>

                    <hr class="opacity-10 my-3">
                    
                    <div class="text-start">
                        <small class="text-uppercase text-muted fw-bold ls-1" style="font-size: 0.7rem;">Kepala Sekolah</small>
                        <div class="d-flex align-items-center mt-2">
                            <div class="avatar bg-warning me-3">
                                <span class="avatar-content fw-bold text-white">KS</span>
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark fw-bold"><?= isset($sekolah['kepala_sekolah']) ? esc($sekolah['kepala_sekolah']) : '-' ?></h6>
                                <small class="text-muted">NIP. <?= isset($sekolah['nip_kepsek']) ? esc($sekolah['nip_kepsek']) : '-' ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card school-card h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i> Edit Informasi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/sekolah/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Nama Sekolah</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                                        <input type="text" class="form-control form-control-modern <?= $validation->hasError('nama_sekolah') ? 'is-invalid' : '' ?>" 
                                               name="nama_sekolah" value="<?= old('nama_sekolah', isset($sekolah['nama_sekolah']) ? $sekolah['nama_sekolah'] : '') ?>">
                                    </div>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('nama_sekolah') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kabupaten / Kota</label>
                                    <input type="text" class="form-control form-control-modern <?= $validation->hasError('kabupaten') ? 'is-invalid' : '' ?>" 
                                           name="kabupaten" value="<?= old('kabupaten', isset($sekolah['kabupaten']) ? $sekolah['kabupaten'] : '') ?>">
                                    <div class="invalid-feedback d-block"><?= $validation->getError('kabupaten') ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat Lengkap</label>
                                    <input type="text" class="form-control form-control-modern <?= $validation->hasError('alamat_lengkap') ? 'is-invalid' : '' ?>" 
                                           name="alamat_lengkap" value="<?= old('alamat_lengkap', isset($sekolah['alamat_lengkap']) ? $sekolah['alamat_lengkap'] : '') ?>">
                                    <div class="invalid-feedback d-block"><?= $validation->getError('alamat_lengkap') ?></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <h6 class="text-muted text-uppercase fs-7 fw-bold mb-3">Data Kepala Sekolah</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Kepala Sekolah</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" class="form-control form-control-modern <?= $validation->hasError('kepala_sekolah') ? 'is-invalid' : '' ?>" 
                                               name="kepala_sekolah" value="<?= old('kepala_sekolah', isset($sekolah['kepala_sekolah']) ? $sekolah['kepala_sekolah'] : '') ?>">
                                    </div>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('kepala_sekolah') ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIP Kepala Sekolah</label>
                                    <input type="text" class="form-control form-control-modern <?= $validation->hasError('nip_kepsek') ? 'is-invalid' : '' ?>" 
                                           name="nip_kepsek" value="<?= old('nip_kepsek', isset($sekolah['nip_kepsek']) ? $sekolah['nip_kepsek'] : '') ?>">
                                    <div class="invalid-feedback d-block"><?= $validation->getError('nip_kepsek') ?></div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold mb-2">Upload Logo Baru</label>
                                <div class="upload-zone" onclick="document.getElementById('logo').click()">
                                    <input type="file" name="logo" id="logo" class="d-none" accept="image/*" onchange="previewLogo(this)">
                                    <div id="upload-placeholder">
                                        <i class="bi bi-cloud-arrow-up-fill text-primary fs-1 mb-2"></i>
                                        <h6 class="fw-bold text-dark">Klik untuk upload logo</h6>
                                        <p class="text-muted small mb-0">Format: PNG, JPG, JPEG. Maks: 2MB</p>
                                    </div>
                                    <div id="preview-container" class="d-none">
                                        <img src="" id="preview-img-form" style="max-height: 100px; border-radius: 10px;">
                                        <p class="mt-2 text-success small fw-bold mb-0"><i class="bi bi-check-circle-fill"></i> Logo siap diupload</p>
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
                // Update preview di dalam area form
                document.getElementById('preview-img-form').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('d-none');
                document.getElementById('preview-container').classList.remove('d-none');
                
                // Update preview real-time di kartu sekolah sebelah kiri
                document.getElementById('preview-logo-card').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>