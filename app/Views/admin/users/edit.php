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

    .form-select-modern {
        border: 2px solid #eef2f7;
        border-radius: 0 12px 12px 0;
        padding: 12px 15px;
        font-weight: 500;
        background-color: #fcfcfc;
        cursor: pointer;
    }

    .form-select-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.1);
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

    .upload-zone {
        border: 2px dashed #dce1e6;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
    }

    .upload-zone:hover {
        border-color: #435ebe;
        background: #eef2ff;
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

    .btn-toggle-password {
        background: transparent;
        border: none;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        z-index: 10;
        cursor: pointer;
    }
    
    .btn-toggle-password:hover {
        color: #435ebe;
    }
    
    .helper-text {
        font-size: 0.8rem;
        color: #a0aec0;
        margin-top: 5px;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Edit User</h3>
            <p class="text-subtitle text-muted">Perbarui informasi akun pengguna.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/users') ?>">Data User</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <i class="bi bi-person-lines-fill fs-2"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">Edit Informasi Akun</h5>
                            <p class="mb-0 text-white text-opacity-75 small">Ubah data login atau profil pengguna di bawah ini.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nama_lengkap" class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" 
                                           id="nama_lengkap" name="nama_lengkap" 
                                           placeholder="Nama Lengkap" 
                                           value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nama_lengkap') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label-custom">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('username') ? 'is-invalid' : '' ?>" 
                                           id="username" name="username" 
                                           placeholder="Username Login" 
                                           value="<?= old('username', $user['username']) ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('username') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="level" class="form-label-custom">Level Akses <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-shield-check"></i></span>
                                    <select class="form-select form-select-modern <?= $validation->hasError('level') ? 'is-invalid' : '' ?>" id="level" name="level">
                                        <option value="admin" <?= (old('level', $user['level']) == 'admin') ? 'selected' : '' ?>>Admin</option>
                                        <option value="petugas" <?= (old('level', $user['level']) == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                                    </select>
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('level') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password" class="form-label-custom">Password Baru</label>
                                <div class="input-group position-relative">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                           id="password" name="password" 
                                           placeholder="Biarkan kosong jika tidak ingin mengganti password">
                                    <button type="button" class="btn-toggle-password" onclick="togglePassword()">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                </div>
                                <div class="helper-text"><i class="bi bi-info-circle me-1"></i> Kosongkan jika password tidak berubah.</div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label-custom">Foto Profil</label>
                                <div class="upload-zone" onclick="document.getElementById('foto').click()">
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    
                                    <div id="upload-placeholder" class="d-none">
                                        <div class="bg-white p-3 rounded-circle d-inline-block shadow-sm mb-2">
                                            <i class="bi bi-camera-fill text-primary fs-3"></i>
                                        </div>
                                        <p class="mb-0 fw-bold text-dark">Klik untuk ganti foto</p>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    </div>

                                    <div id="preview-container">
                                        <?php 
                                            $foto = $user['foto'] ? $user['foto'] : 'default.jpg';
                                            $fotoUrl = base_url('uploads/foto_profil/' . $foto);
                                        ?>
                                        <img src="<?= $fotoUrl ?>" id="preview-img" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                        <p class="mt-2 text-muted small fw-bold mb-0" id="preview-text">Foto saat ini (Klik untuk ganti)</p>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('foto') ?>
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-cancel">Batal</a>
                                <button type="submit" class="btn btn-save">
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
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('d-none');
                document.getElementById('preview-container').classList.remove('d-none');
                document.getElementById('preview-text').classList.add('text-success');
                document.getElementById('preview-text').classList.remove('text-muted');
                document.getElementById('preview-text').innerHTML = '<i class="bi bi-check-circle-fill"></i> Foto baru terpilih';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>