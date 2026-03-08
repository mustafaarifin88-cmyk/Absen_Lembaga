<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Profil Saya</h3>
            <p class="text-subtitle text-muted">Kelola informasi akun dan kata sandi Anda.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/profil/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="row">
            <!-- Kolom Kiri: Kartu Profil -->
            <div class="col-12 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div style="height: 100px; background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);"></div>
                    <div class="card-body text-center" style="margin-top: -50px;">
                        <div class="d-flex justify-content-center mb-3 position-relative">
                            <?php 
                                $fotoName = !empty($user['foto']) ? $user['foto'] : 'default.jpg';
                                $fotoUrl = base_url('uploads/foto_profil/' . $fotoName);
                            ?>
                            <img src="<?= $fotoUrl ?>" 
                                 alt="Foto Profil" 
                                 class="rounded-circle border border-4 border-white shadow-sm" 
                                 style="width: 100px; height: 100px; object-fit: cover;"
                                 id="profile-preview-card">
                             
                             <label for="foto" class="position-absolute bottom-0 start-50 translate-middle-x mb-0" style="cursor: pointer;">
                                <span class="badge rounded-pill bg-light text-primary border shadow-sm"><i class="bi bi-camera-fill"></i> Ganti</span>
                             </label>
                             <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-0"><?= esc($user['nama_lengkap']) ?></h5>
                        <p class="text-muted small mb-1">@<?= esc($user['username']) ?></p>
                        <span class="badge bg-light-success text-success"><?= strtoupper(esc($user['level'])) ?></span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Edit -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title fw-bold mb-0">Edit Informasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control <?= $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>">
                                <div class="invalid-feedback"><?= $validation->getError('nama_lengkap') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Username</label>
                                <input type="text" name="username" class="form-control <?= $validation->hasError('username') ? 'is-invalid' : '' ?>" value="<?= old('username', $user['username']) ?>">
                                <div class="invalid-feedback"><?= $validation->getError('username') ?></div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">
                        
                        <h6 class="fw-bold mb-3"><i class="bi bi-lock-fill me-2"></i> Ganti Password</h6>
                        <div class="alert alert-info small py-2 px-3 mb-3">
                            <i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak ingin mengganti password.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Password Baru</label>
                                <input type="password" name="password" class="form-control <?= $validation->hasError('password') ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Konfirmasi Password</label>
                                <input type="password" name="password_confirm" class="form-control <?= $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>">
                                <div class="invalid-feedback"><?= $validation->getError('password_confirm') ?></div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 10px;">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview-card').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>