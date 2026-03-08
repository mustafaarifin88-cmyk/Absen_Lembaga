<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .card-header-modern {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        color: #607080;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid #eef2f7;
        padding: 15px;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-hadir { background: #e6fffa; color: #00b894; border: 1px solid #00b894; }
    .status-terlambat { background: #fffbe6; color: #faad14; border: 1px solid #faad14; }
    .status-alfa { background: #fff1f0; color: #ff4d4f; border: 1px solid #ff4d4f; }
    .status-sakit { background: #e6f7ff; color: #1890ff; border: 1px solid #1890ff; }
    .status-izin { background: #f9f0ff; color: #722ed1; border: 1px solid #722ed1; }
    .status-cepat-pulang { background: #fff2e8; color: #fa541c; border: 1px solid #fa541c; }

    .time-badge {
        font-family: 'Courier New', Courier, monospace;
        font-weight: 700;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }

    .nav-pills-custom .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        color: #607080;
        background: #fff;
        border: 1px solid #eef2f7;
        margin-right: 10px;
        transition: all 0.3s;
    }

    .nav-pills-custom .nav-link.active {
        background: #435ebe;
        color: white;
        border-color: #435ebe;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.3);
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Absensi Harian</h3>
            <p class="text-subtitle text-muted">Pantau kehadiran Pengurus dan Anggota hari ini.</p>
        </div>
    </div>
</div>

<div class="page-content">
    
    <div class="card card-modern mb-4">
        <div class="card-body p-4">
            <form method="get" action="">
                <div class="row g-3 align-items-end">
                    <div class="col-md-12 mb-2">
                        <ul class="nav nav-pills nav-pills-custom" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button type="submit" name="tab" value="pengurus" class="nav-link <?= ($tab == 'pengurus') ? 'active' : '' ?>">
                                    <i class="bi bi-person-badge-fill me-2"></i> Pengurus
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="submit" name="tab" value="anggota" class="nav-link <?= ($tab == 'anggota') ? 'active' : '' ?>">
                                    <i class="bi bi-people-fill me-2"></i> Anggota
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Mulai</label>
                        <input type="date" name="tgl_awal" class="form-control rounded-3" value="<?= $tgl_awal ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" class="form-control rounded-3" value="<?= $tgl_akhir ?>">
                    </div>
                    
                    <?php if($tab == 'anggota'): ?>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Filter RT</label>
                        <select name="rt_id" class="form-select rounded-3">
                            <option value="">-- Semua RT --</option>
                            <?php foreach($rt as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= ($rt_id == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rt'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3">
                            <i class="bi bi-search me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-modern">
        <div class="card-header card-header-modern">
            <h5 class="mb-0 text-white"><i class="bi bi-list-ul me-2"></i> Hasil Data Absensi</h5>
            <span class="badge bg-white text-primary rounded-pill px-3 py-2">
                <?= ($tab == 'pengurus') ? 'Data Pengurus' : 'Data Anggota' ?>
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0" id="table1">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%" class="text-center">Tanggal</th>
                            <th>Nama Lengkap</th>
                            <th><?= ($tab == 'pengurus') ? 'Jabatan' : 'RT' ?></th>
                            <th class="text-center">Jam Masuk</th>
                            <th class="text-center">Jam Pulang</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $data = ($tab == 'pengurus') ? $absensi_pengurus : $absensi_anggota;
                        if(empty($data)): 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="<?= base_url('assets/images/empty.svg') ?>" alt="Empty" style="width: 100px; opacity: 0.5;">
                                    <p class="text-muted mt-3">Tidak ada data absensi ditemukan.</p>
                                </td>
                            </tr>
                        <?php else: foreach($data as $key => $row): ?>
                        <tr>
                            <td class="text-center"><?= $key + 1 ?></td>
                            <td class="text-center">
                                <span class="fw-bold text-dark"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span><br>
                                <small class="text-muted"><?= $row['hari_indo'] ?></small>
                            </td>
                            <td>
                                <span class="fw-bold"><?= $row['nama_lengkap'] ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?= $row['jabatan_or_rt'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($row['jam_masuk'] && $row['jam_masuk'] != '-'): ?>
                                    <span class="time-badge text-success"><?= $row['jam_masuk'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($row['jam_pulang'] && $row['jam_pulang'] != '-'): ?>
                                    <span class="time-badge text-primary"><?= $row['jam_pulang'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $statusClass = 'status-' . strtolower(str_replace(' ', '-', $row['status']));
                                    if ($row['status'] == 'Cepat Pulang') $statusClass = 'status-cepat-pulang';
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= strtoupper($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/datatables.js') ?>"></script>

<?= $this->endSection() ?>