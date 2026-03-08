<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-edit {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 40px 30px;
        color: white;
        position: relative;
    }

    .card-header-gradient::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50% 50% 0 0;
        transform: translate(30%, 30%);
    }

    .form-label-custom {
        font-weight: 700;
        color: #607080;
        margin-bottom: 8px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-group-text {
        background: #f8f9fa;
        border: 2px solid #eef2f7;
        border-right: none;
        color: #435ebe;
    }

    .form-control-modern {
        border: 2px solid #eef2f7;
        padding: 12px 15px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
    }

    .form-control-modern.single {
        border-radius: 10px;
    }

    .btn-save {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 94, 190, 0.3);
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex align-items-center">
        <a href="javascript:history.back()" class="btn btn-light rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left text-primary fs-5"></i>
        </a>
        <div>
            <h3 class="mb-0 fw-bold text-primary">Edit Absensi Manual</h3>
            <p class="text-muted mb-0">Koreksi data kehadiran anggota/pengurus.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-edit">
                <div class="card-header card-header-gradient text-center">
                    <h4 class="mb-1 text-white fw-bold"><?= $nama_user ?></h4>
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill mt-2 fw-bold text-uppercase">
                        <?= $absensi['user_type'] ?>
                    </span>
                    <p class="mb-0 mt-3 text-white-50"><i class="bi bi-calendar-event me-2"></i> <?= date('d F Y', strtotime($absensi['tanggal'])) ?></p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger mb-4"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/absensi/update-manual') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $absensi['id'] ?>">
                        <input type="hidden" name="user_id" value="<?= $absensi['user_id'] ?>">
                        <input type="hidden" name="user_type" value="<?= $absensi['user_type'] ?>">
                        <input type="hidden" name="tanggal" value="<?= $absensi['tanggal'] ?>">

                        <div class="row g-4">
                            
                            <div class="col-md-6">
                                <label class="form-label-custom">Jam Masuk</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-4"><i class="bi bi-clock-fill"></i></span>
                                    <input type="time" name="jam_masuk" class="form-control form-control-modern rounded-end-4" value="<?= $absensi['jam_masuk'] ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">Jam Pulang</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-4"><i class="bi bi-clock-history"></i></span>
                                    <input type="time" name="jam_pulang" class="form-control form-control-modern rounded-end-4" value="<?= $absensi['jam_pulang'] ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">Status Kehadiran</label>
                                <select name="status" class="form-select form-control-modern single">
                                    <option value="Hadir" <?= $absensi['status'] == 'Hadir' ? 'selected' : '' ?>>Hadir (Tepat Waktu)</option>
                                    <option value="Terlambat" <?= $absensi['status'] == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                                    <option value="Cepat Pulang" <?= $absensi['status'] == 'Cepat Pulang' ? 'selected' : '' ?>>Cepat Pulang</option>
                                    <option value="Sakit" <?= $absensi['status'] == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                    <option value="Izin" <?= $absensi['status'] == 'Izin' ? 'selected' : '' ?>>Izin</option>
                                    <option value="Alfa" <?= $absensi['status'] == 'Alfa' ? 'selected' : '' ?>>Alfa (Tanpa Keterangan)</option>
                                </select>
                                <div class="alert alert-light-primary d-flex align-items-center mt-2 p-2 border-0 rounded-3">
                                    <i class="bi bi-info-circle-fill text-primary me-2 fs-5"></i>
                                    <div class="small text-muted lh-sm">
                                        Jika memilih <b>Hadir</b> atau <b>Terlambat</b>, status akan otomatis disesuaikan oleh sistem berdasarkan Jam Masuk/Pulang yang Anda input di atas.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label-custom">Keterangan / Catatan</label>
                                <textarea name="keterangan" class="form-control form-control-modern single" rows="3" placeholder="Tambahkan keterangan..."><?= $absensi['keterangan'] ?></textarea>
                                <?php if(strpos($absensi['keterangan'], 'Cepat Pulang') !== false): ?>
                                    <small class="text-danger fw-bold mt-1 d-block">* Terdeteksi Cepat Pulang</small>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-save">
                                    Simpan Perubahan
                                </button>
                                <a href="javascript:history.back()" class="btn btn-light w-100 mt-2 text-muted fw-bold" style="border-radius: 12px; padding: 10px;">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>