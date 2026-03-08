<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
    .card-header-gradient { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); padding: 30px; color: white; position: relative; overflow: hidden; }
    .card-header-gradient::before { content: ''; position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .card-header-gradient::after { content: ''; position: absolute; bottom: -30px; left: 20px; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    
    .form-label-custom { font-weight: 700; color: #555; margin-bottom: 10px; font-size: 0.9rem; }
    .form-control-modern { border: 2px solid #eef2f7; border-radius: 12px; padding: 12px 15px; font-weight: 500; transition: all 0.3s; background-color: #fcfcfc; }
    .form-control-modern:focus { border-color: #435ebe; box-shadow: 0 5px 15px rgba(67, 94, 190, 0.1); background: #fff; }
    .input-group-text-modern { background: #f8f9fa; border: 2px solid #eef2f7; border-right: none; border-radius: 12px 0 0 12px; color: #435ebe; padding-left: 20px; padding-right: 20px; }
    .form-control-modern-group { border-left: none; border-radius: 0 12px 12px 0; }
    .btn-save { background: linear-gradient(90deg, #435ebe, #25396f); border: none; border-radius: 12px; padding: 12px 30px; font-weight: 600; box-shadow: 0 4px 15px rgba(67, 94, 190, 0.3); transition: all 0.3s; color: white; letter-spacing: 0.5px; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(67, 94, 190, 0.4); color: white; }
    .btn-cancel { background: #f1f3f5; color: #6c757d; border: none; border-radius: 12px; padding: 12px 30px; font-weight: 600; transition: all 0.3s; }
    .btn-cancel:hover { background: #e9ecef; color: #495057; }
    .helper-text { font-size: 0.8rem; color: #a0aec0; margin-top: 5px; }

    .upload-zone { border: 2px dashed #dce1e6; border-radius: 15px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: #f8f9fa; position: relative; }
    .upload-zone:hover { border-color: #435ebe; background: #eef2ff; }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold"><?= isset($guru) ? 'Edit Data Guru' : 'Tambah Guru Baru' ?></h3>
            <p class="text-subtitle text-muted">Lengkapi formulir di bawah ini dengan data yang valid.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/guru') ?>">Data Guru</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= isset($guru) ? 'Edit' : 'Tambah' ?></li>
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
                            <i class="bi bi-person-video3 fs-2"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">Informasi Guru</h5>
                            <p class="mb-0 text-white text-opacity-75 small">Pastikan NIP dan Nomor WhatsApp benar.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= isset($guru) ? base_url('admin/guru/update/' . $guru['id']) : base_url('admin/guru/create') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nip" class="form-label-custom">Nomor Induk Pegawai (NIP)</label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group" 
                                           id="nip" name="nip" 
                                           placeholder="Masukkan NIP (Boleh dikosongkan jika belum ada)" 
                                           value="<?= old('nip', isset($guru['nip']) ? $guru['nip'] : '') ?>">
                                </div>
                                <div class="helper-text"><i class="bi bi-info-circle me-1"></i> Jika kosong, sistem akan membuat ID otomatis.</div>
                            </div>

                            <div class="col-12">
                                <label for="nama_guru" class="form-label-custom">Nama Lengkap Guru <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nama_guru') ? 'is-invalid' : '' ?>" 
                                           id="nama_guru" name="nama_guru" 
                                           placeholder="Contoh: Drs. H. Budi Santoso, M.Pd" 
                                           value="<?= old('nama_guru', isset($guru['nama_guru']) ? $guru['nama_guru'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nama_guru') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan" class="form-label-custom">Jabatan / Mapel <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-briefcase"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('jabatan') ? 'is-invalid' : '' ?>" 
                                           id="jabatan" name="jabatan" 
                                           placeholder="Contoh: Guru Matematika" 
                                           value="<?= old('jabatan', isset($guru['jabatan']) ? $guru['jabatan'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('jabatan') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="no_wa" class="form-label-custom">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-whatsapp"></i></span>
                                    <input type="number" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('no_wa') ? 'is-invalid' : '' ?>" 
                                           id="no_wa" name="no_wa" 
                                           placeholder="Contoh: 081234567890" 
                                           value="<?= old('no_wa', isset($guru['no_wa']) ? $guru['no_wa'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('no_wa') ?>
                                    </div>
                                </div>
                                <div class="helper-text text-success"><i class="bi bi-check-circle me-1"></i> Nomor ini akan menerima notifikasi absensi.</div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label-custom">Foto Guru</label>
                                <div class="upload-zone" onclick="document.getElementById('foto').click()">
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    
                                    <div id="upload-placeholder" class="<?= (isset($guru['foto']) && $guru['foto'] != 'default.jpg') ? 'd-none' : '' ?>">
                                        <div class="bg-white p-3 rounded-circle d-inline-block shadow-sm mb-2">
                                            <i class="bi bi-camera-fill text-primary fs-3"></i>
                                        </div>
                                        <p class="mb-0 fw-bold text-dark">Klik untuk upload foto</p>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    </div>

                                    <div id="preview-container" class="<?= (isset($guru['foto']) && $guru['foto'] != 'default.jpg') ? '' : 'd-none' ?>">
                                        <?php 
                                            $foto = (isset($guru['foto']) && $guru['foto']) ? $guru['foto'] : 'default.jpg';
                                            $fotoUrl = base_url('uploads/foto_guru/' . $foto);
                                        ?>
                                        <img src="<?= $fotoUrl ?>" id="preview-img" style="width: 120px; height: 120px; object-fit: cover; border-radius: 15px; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                        <p class="mt-2 text-success small fw-bold mb-0" id="preview-text">
                                            <?= (isset($guru['foto']) && $guru['foto'] != 'default.jpg') ? '<i class="bi bi-image"></i> Foto saat ini' : '<i class="bi bi-check-circle-fill"></i> Foto terpilih' ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('foto') ?>
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/guru') ?>" class="btn btn-cancel">Batal</a>
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