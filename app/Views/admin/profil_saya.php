<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-profile {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    .profile-cover {
        background: linear-gradient(135deg, #435ebe 0%, #6f42c1 100%);
        height: 140px;
        position: relative;
        overflow: hidden;
    }
    
    .profile-cover::after {
        content: '';
        position: absolute;
        width: 150%;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        bottom: -30px;
        left: -25%;
        border-radius: 50%;
        transform: rotate(-5deg);
    }

    .profile-user-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #fff;
        position: relative;
        z-index: 2;
    }

    .profile-content {
        margin-top: -70px;
        text-align: center;
        padding-bottom: 2rem;
    }

    .form-control-modern {
        border: 2px solid #f0f2f5;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s;
        background-color: #f8f9fa;
    }

    .form-control-modern:focus {
        border-color: #435ebe;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
    }

    .upload-area {
        border: 2px dashed #dce1e6;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .upload-area:hover {
        border-color: #435ebe;
        background: #f4f6ff;
    }

    .upload-icon {
        font-size: 2rem;
        color: #a0aec0;
        margin-bottom: 10px;
    }

    .btn-save {
        background: linear-gradient(90deg, #435ebe, #3b50a0);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(67, 94, 190, 0.3);
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 94, 190, 0.4);
    }
    
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: rgba(67, 94, 190, 0.1);
        color: #435ebe;
        display: inline-block;
        margin-top: 10px;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Profil Saya</h3>
            <p class="text-subtitle text-muted">Kelola informasi akun dan kata sandi Anda.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profil</li>
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
        <!-- Kolom Kiri: Kartu Profil -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-profile h-100">
                <div class="profile-cover"></div>
                <div class="card-body profile-content">
                    <div class="d-flex justify-content-center mb-3">
                        <img src="<?= base_url('uploads/foto_profil/' . ($user['foto'] ? $user['foto'] : 'default.jpg')) ?>" 
                             alt="Foto Profil" 
                             class="profile-user-img" 
                             id="profile-preview-card">
                    </div>
                    
                    <h4 class="fw-bold text-dark mb-0"><?= esc($user['nama_lengkap']) ?></h4>
                    <p class="text-muted mb-1">@<?= esc($user['username']) ?></p>
                    <div class="status-badge"><?= strtoupper($user['level']) ?></div>
                    
                    <hr class="my-4" style="opacity: 0.1;">
                    
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <h6 class="mb-0 fw-bold">Status</h6>
                            <small class="text-success"><i class="bi bi-dot"></i> Aktif</small>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Bergabung</h6>
                            <small class="text-muted"><?= date('M Y', strtotime($user['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit -->
        <div class="col-12 col-lg-8">
            <div class="card card-profile">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="card-title fw-bold">Edit Informasi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/profil/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="nama_lengkap" class="form-label fw-bold text-gray-600">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-modern <?= $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" 
                                       id="nama_lengkap" name="nama_lengkap" 
                                       value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" 
                                       placeholder="Masukkan nama lengkap">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('nama_lengkap') ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="username" class="form-label fw-bold text-gray-600">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control form-control-modern <?= $validation->hasError('username') ? 'is-invalid' : '' ?>" 
                                           id="username" name="username" 
                                           value="<?= old('username', $user['username']) ?>" 
                                           placeholder="Username login">
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('username') ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password" class="form-label fw-bold text-gray-600">Password Baru <small class="text-muted fw-normal">(Biarkan kosong jika tidak ingin mengganti)</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-modern" 
                                           id="password" name="password" placeholder="Min. 6 karakter">
                                    <button class="btn btn-light border" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-gray-600">Foto Profil</label>
                                <div class="upload-area" onclick="document.getElementById('foto').click()">
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    <div id="upload-placeholder">
                                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                        <p class="mb-0 fw-bold text-muted">Klik untuk upload foto baru</p>
                                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 2MB</small>
                                    </div>
                                    <div id="preview-container" class="d-none mt-2">
                                        <img src="" id="preview-img" style="max-height: 150px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                        <p class="mt-2 text-success small fw-bold"><i class="bi bi-check-circle"></i> Foto terpilih</p>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('foto') ?>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-save text-white">
                                    <i class="bi bi-save me-2"></i> Simpan Perubahan
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
    // Fitur Preview Image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                // Update preview di dalam area upload
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('d-none');
                document.getElementById('preview-container').classList.remove('d-none');
                
                // Update juga foto di kartu profil kiri secara realtime agar user senang
                document.getElementById('profile-preview-card').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Fitur Show/Hide Password
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
    });
</script>

<?= $this->endSection() ?>