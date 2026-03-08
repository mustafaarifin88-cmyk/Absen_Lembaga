<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-settings { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; }
    .nav-pills .nav-link { border-radius: 50px; padding: 10px 25px; font-weight: 600; margin-right: 10px; border: 1px solid #eef2f7; color: #607080; background: #fff; }
    .nav-pills .nav-link.active { background: #435ebe; color: #fff; border-color: #435ebe; box-shadow: 0 4px 10px rgba(67, 94, 190, 0.3); }
    .table-input td { padding: 10px 5px; vertical-align: middle; }
    .form-control-sm { border-radius: 8px; border: 1px solid #dce1e6; text-align: center; font-weight: 500; }
    .form-control-sm:focus { border-color: #435ebe; box-shadow: none; background: #fbfdff; }
    .day-badge { display: block; font-weight: bold; color: #435ebe; margin-bottom: 2px; }
    .btn-save-float { position: fixed; bottom: 30px; right: 30px; z-index: 1000; border-radius: 50px; padding: 12px 30px; font-weight: 700; box-shadow: 0 10px 30px rgba(67, 94, 190, 0.4); transition: transform 0.2s; }
    .btn-save-float:hover { transform: translateY(-3px); }
</style>

<div class="page-heading mb-4">
    <h3>Pengaturan Jam Absensi</h3>
    <p class="text-muted">Atur jam masuk dan pulang untuk Pengurus dan Anggota per hari.</p>
</div>

<div class="page-content">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/setting-jam/update') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="card card-settings mb-5">
            <div class="card-header bg-white pb-0 pt-4 px-4">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-pengurus-tab" data-bs-toggle="pill" data-bs-target="#pills-pengurus" type="button" role="tab">
                            <i class="bi bi-person-badge me-2"></i> Jam Pengurus
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-anggota-tab" data-bs-toggle="pill" data-bs-target="#pills-anggota" type="button" role="tab">
                            <i class="bi bi-people me-2"></i> Jam Anggota
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-pengurus" role="tabpanel">
                        <div class="alert alert-light-primary border-0 small mb-4">
                            <i class="bi bi-info-circle me-1"></i> Isi <b>00:00</b> jika hari tersebut libur.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-input align-middle">
                                <thead class="text-center text-muted small text-uppercase">
                                    <tr>
                                        <th width="15%" class="text-start">Hari</th>
                                        <th width="20%">Mulai Masuk</th>
                                        <th width="20%">Batas Masuk</th>
                                        <th width="20%">Mulai Pulang</th>
                                        <th width="20%">Batas Pulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($urutanHari as $hari): 
                                        $row = isset($pengurus_settings[$hari]) ? $pengurus_settings[$hari] : null;
                                        if(!$row) continue;
                                    ?>
                                    <tr>
                                        <td><span class="day-badge"><?= $hari ?></span></td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-success" 
                                                name="settings[<?= $row['id'] ?>][jam_masuk_mulai]" value="<?= $row['jam_masuk_mulai'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-danger" 
                                                name="settings[<?= $row['id'] ?>][jam_masuk_akhir]" value="<?= $row['jam_masuk_akhir'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-warning" 
                                                name="settings[<?= $row['id'] ?>][jam_pulang_mulai]" value="<?= $row['jam_pulang_mulai'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-primary" 
                                                name="settings[<?= $row['id'] ?>][jam_pulang_akhir]" value="<?= $row['jam_pulang_akhir'] ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-anggota" role="tabpanel">
                        <div class="alert alert-light-success border-0 small mb-4">
                            <i class="bi bi-info-circle me-1"></i> Pengaturan jam untuk Anggota.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless table-input align-middle">
                                <thead class="text-center text-muted small text-uppercase">
                                    <tr>
                                        <th width="15%" class="text-start">Hari</th>
                                        <th width="20%">Mulai Masuk</th>
                                        <th width="20%">Batas Masuk</th>
                                        <th width="20%">Mulai Pulang</th>
                                        <th width="20%">Batas Pulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($urutanHari as $hari): 
                                        $row = isset($anggota_settings[$hari]) ? $anggota_settings[$hari] : null;
                                        if(!$row) continue;
                                    ?>
                                    <tr>
                                        <td><span class="day-badge text-success"><?= $hari ?></span></td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-success" 
                                                name="settings[<?= $row['id'] ?>][jam_masuk_mulai]" value="<?= $row['jam_masuk_mulai'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-danger" 
                                                name="settings[<?= $row['id'] ?>][jam_masuk_akhir]" value="<?= $row['jam_masuk_akhir'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-warning" 
                                                name="settings[<?= $row['id'] ?>][jam_pulang_mulai]" value="<?= $row['jam_pulang_mulai'] ?>">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control form-control-sm text-primary" 
                                                name="settings[<?= $row['id'] ?>][jam_pulang_akhir]" value="<?= $row['jam_pulang_akhir'] ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <button type="submit" class="btn btn-primary btn-save-float">
            <i class="bi bi-save2-fill me-2"></i> Simpan Semua Perubahan
        </button>
    </form>
</div>
<?= $this->endSection() ?>