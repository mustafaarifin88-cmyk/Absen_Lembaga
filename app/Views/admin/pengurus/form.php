<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
    .card-header-gradient { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); padding: 30px; color: white; position: relative; overflow: hidden; }
    .card-header-gradient::before { content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .card-header-gradient::after { content: ''; position: absolute; bottom: -30px; left: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    
    .form-label-custom { font-weight: 700; color: #555; margin-bottom: 10px; font-size: 0.9rem; }
    .form-control-modern { border: 2px solid #eef2f7; border-radius: 12px; padding: 12px 15px; font-weight: 500; transition: all 0.3s; }
    .form-control-modern:focus { border-color: #435ebe; box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1); }
    
    .input-group-text-modern { background: #f8f9fa; border: 2px solid #eef2f7; border-right: none; color: #435ebe; border-radius: 12px 0 0 12px; }
    .form-control-modern-group { border-left: none; border-radius: 0 12px 12px 0; }
    
    .upload-area { border: 2px dashed #eef2f7; border-radius: 15px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s; position: relative; background: #fafbfc; }
    .upload-area:hover { border-color: #435ebe; background: #f4f7ff; }
    .preview-img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    
    .btn-save { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; transition: all 0.3s; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(67, 94, 190, 0.3); }
    .btn-cancel { background: #f1f3f5; color: #607080; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; transition: all 0.3s; }
    .btn-cancel:hover { background: #e9ecef; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex align-items-center">
        <a href="<?= base_url('admin/pengurus') ?>" class="btn btn-light rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left text-primary fs-5"></i>
        </a>
        <div>
            <h3 class="mb-0 fw-bold text-primary"><?= isset($pengurus) ? 'Edit Pengurus' : 'Tambah Pengurus Baru' ?></h3>
            <p class="text-muted mb-0">Isi data pengurus organisasi dengan lengkap.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-form">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0 text-white"><i class="bi bi-person-badge me-2"></i> Informasi Pengurus</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="<?= isset($pengurus) ? base_url('admin/pengurus/update/' . $pengurus['id']) : base_url('admin/pengurus/create') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="row g-4">
                            <div class="col-12 text-center mb-2">
                                <label class="form-label-custom d-block mb-3">Foto Profil</label>
                                <div class="upload-area" onclick="document.getElementById('foto').click()">
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    
                                    <div id="upload-placeholder" class="<?= isset($pengurus) && $pengurus['foto'] ? 'd-none' : '' ?>">
                                        <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-camera-fill fs-3 text-primary"></i>
                                        </div>
                                        <p class="mb-0 fw-bold text-muted">Klik untuk upload foto</p>
                                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                                    </div>

                                    <div id="preview-container" class="<?= isset($pengurus) && $pengurus['foto'] ? '' : 'd-none' ?>">
                                        <img id="preview-img" src="<?= isset($pengurus) ? base_url('uploads/foto_pengurus/' . $pengurus['foto']) : '' ?>" class="preview-img mb-2">
                                        <p class="mb-0 small text-primary fw-bold" id="preview-text">
                                            <?= isset($pengurus) ? 'Klik untuk ubah foto' : 'Foto terpilih' ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('foto') ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="nama_lengkap" class="form-label-custom">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" 
                                           id="nama_lengkap" name="nama_lengkap" 
                                           placeholder="Nama Lengkap Pengurus" 
                                           value="<?= old('nama_lengkap', isset($pengurus['nama_lengkap']) ? $pengurus['nama_lengkap'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nama_lengkap') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="jabatan" class="form-label-custom">Jabatan</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-briefcase-fill"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('jabatan') ? 'is-invalid' : '' ?>" 
                                           id="jabatan" name="jabatan" 
                                           placeholder="Contoh: Ketua, Sekretaris, Bendahara" 
                                           value="<?= old('jabatan', isset($pengurus['jabatan']) ? $pengurus['jabatan'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('jabatan') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/pengurus') ?>" class="btn btn-cancel">Batal</a>
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

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('upload-placeholder').classList.add('d-none');
                document.getElementById('preview-container').classList.remove('d-none');
                document.getElementById('preview-text').innerHTML = '<i class="bi bi-check-circle-fill"></i> Foto baru terpilih';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>