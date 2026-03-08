<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
    .card-header-gradient { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); padding: 30px; color: white; position: relative; overflow: hidden; }
    .card-header-gradient::before { content: ''; position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    .card-header-gradient::after { content: ''; position: absolute; bottom: -20px; left: 20px; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; }
    
    .form-label-custom { font-weight: 700; color: #555; margin-bottom: 10px; font-size: 0.9rem; }
    .form-control-modern { border: 2px solid #eef2f7; border-radius: 12px; padding: 12px 15px; font-weight: 500; transition: all 0.3s; background-color: #fcfcfc; }
    .form-control-modern:focus { border-color: #435ebe; box-shadow: 0 5px 15px rgba(67, 94, 190, 0.1); background: #fff; }
    .form-select-modern { border: 2px solid #eef2f7; border-radius: 0 12px 12px 0; padding: 12px 15px; font-weight: 500; background-color: #fcfcfc; cursor: pointer; }
    .form-select-modern:focus { border-color: #435ebe; box-shadow: 0 5px 15px rgba(67, 94, 190, 0.1); }
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
            <h3 class="mb-1 text-primary fw-bold"><?= isset($siswa) ? 'Edit Data Siswa' : 'Tambah Siswa Baru' ?></h3>
            <p class="text-subtitle text-muted">Lengkapi formulir di bawah ini dengan data siswa yang valid.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/siswa') ?>">Data Siswa</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= isset($siswa) ? 'Edit' : 'Tambah' ?></li>
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
                            <i class="bi bi-backpack-fill fs-2"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold">Informasi Siswa</h5>
                            <p class="mb-0 text-white text-opacity-75 small">Pastikan NISN dan data kontak orang tua benar.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="<?= isset($siswa) ? base_url('admin/siswa/update/' . $siswa['id']) : base_url('admin/siswa/create') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nisn" class="form-label-custom">NISN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-card-text"></i></span>
                                    <input type="number" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nisn') ? 'is-invalid' : '' ?>" 
                                           id="nisn" name="nisn" 
                                           placeholder="Nomor Induk Siswa Nasional" 
                                           value="<?= old('nisn', isset($siswa['nisn']) ? $siswa['nisn'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nisn') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="nama_lengkap" class="form-label-custom">Nama Lengkap Siswa <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>" 
                                           id="nama_lengkap" name="nama_lengkap" 
                                           placeholder="Contoh: Ahmad Fauzan" 
                                           value="<?= old('nama_lengkap', isset($siswa['nama_lengkap']) ? $siswa['nama_lengkap'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('nama_lengkap') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="kelas_id" class="form-label-custom">Kelas & Jurusan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-diagram-3-fill"></i></span>
                                    <select class="form-select form-select-modern <?= $validation->hasError('kelas_id') ? 'is-invalid' : '' ?>" id="kelas_id" name="kelas_id">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <?php foreach ($kelas as $k) : ?>
                                            <option value="<?= $k['id'] ?>" <?= ($k['id'] == old('kelas_id', isset($siswa['kelas_id']) ? $siswa['kelas_id'] : '')) ? 'selected' : '' ?>>
                                                <?= $k['nama_kelas'] ?> - <?= $k['jurusan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('kelas_id') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="no_wa_ortu" class="form-label-custom">WhatsApp Orang Tua <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-modern"><i class="bi bi-whatsapp"></i></span>
                                    <input type="number" class="form-control form-control-modern form-control-modern-group <?= $validation->hasError('no_wa_ortu') ? 'is-invalid' : '' ?>" 
                                           id="no_wa_ortu" name="no_wa_ortu" 
                                           placeholder="Contoh: 081234567890" 
                                           value="<?= old('no_wa_ortu', isset($siswa['no_wa_ortu']) ? $siswa['no_wa_ortu'] : '') ?>">
                                    <div class="invalid-feedback ps-2">
                                        <?= $validation->getError('no_wa_ortu') ?>
                                    </div>
                                </div>
                                <div class="helper-text text-success"><i class="bi bi-check-circle me-1"></i> Notifikasi absensi akan dikirim ke nomor ini.</div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label-custom">Foto Siswa</label>
                                <div class="upload-zone" onclick="document.getElementById('foto').click()">
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    
                                    <div id="upload-placeholder" class="<?= (isset($siswa['foto']) && $siswa['foto'] != 'default.jpg') ? 'd-none' : '' ?>">
                                        <div class="bg-white p-3 rounded-circle d-inline-block shadow-sm mb-2">
                                            <i class="bi bi-camera-fill text-primary fs-3"></i>
                                        </div>
                                        <p class="mb-0 fw-bold text-dark">Klik untuk upload foto</p>
                                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                    </div>

                                    <div id="preview-container" class="<?= (isset($siswa['foto']) && $siswa['foto'] != 'default.jpg') ? '' : 'd-none' ?>">
                                        <?php 
                                            $foto = (isset($siswa['foto']) && $siswa['foto']) ? $siswa['foto'] : 'default.jpg';
                                            $fotoUrl = base_url('uploads/foto_siswa/' . $foto);
                                        ?>
                                        <img src="<?= $fotoUrl ?>" id="preview-img" style="width: 120px; height: 120px; object-fit: cover; border-radius: 15px; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                        <p class="mt-2 text-success small fw-bold mb-0" id="preview-text">
                                            <?= (isset($siswa['foto']) && $siswa['foto'] != 'default.jpg') ? '<i class="bi bi-image"></i> Foto saat ini' : '<i class="bi bi-check-circle-fill"></i> Foto terpilih' ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-danger small mt-1">
                                    <?= $validation->getError('foto') ?>
                                </div>
                            </div>

                            <div class="col-12 mt-5 d-flex gap-2 justify-content-end">
                                <a href="<?= base_url('admin/siswa') ?>" class="btn btn-cancel">Batal</a>
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