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
        color: #555;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
    }

    .table-modern tbody tr:hover {
        background-color: #fcfdff;
    }

    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #eef2ff;
        color: #435ebe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .user-details h6 {
        margin: 0;
        font-weight: 700;
        color: #333;
        font-size: 0.95rem;
    }

    .user-details span {
        font-size: 0.8rem;
        color: #888;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }

    .status-hadir { background: #d1e7dd; color: #198754; }
    .status-terlambat { background: #f8d7da; color: #dc3545; }
    .status-sakit { background: #cff4fc; color: #0dcaf0; }
    .status-izin { background: #fff3cd; color: #ffc107; }
    .status-alfa { background: #e2e3e5; color: #383d41; }
    .status-cepat { background: #fff3cd; color: #d39e00; }

    .time-badge {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 4px 10px;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
    }

    .btn-maps {
        color: #435ebe;
        background: rgba(67, 94, 190, 0.1);
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-maps:hover {
        background: #435ebe;
        color: white;
    }

    .btn-edit-manual {
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn-edit-manual:hover {
        background: #ffc107;
        color: white;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Data Absensi</h3>
            <p class="text-subtitle text-muted">Rekapitulasi kehadiran guru dan siswa secara real-time.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('petugas/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Absensi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            })
        </script>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header-modern">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-check-fill fs-3 me-3"></i>
                <div>
                    <h5 class="mb-0 text-white">Riwayat Kehadiran</h5>
                    <small class="text-white text-opacity-75">Data terbaru ditampilkan paling atas</small>
                </div>
            </div>
            
            <?php if(session()->get('level') == 'admin'): ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/laporan') ?>" class="btn btn-light btn-sm fw-bold text-primary rounded-pill px-3">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table table-modern" id="table1">
                    <thead>
                        <tr>
                            <th>Waktu Absen</th>
                            <th>Nama Pengguna</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th class="text-center">Lokasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absensi as $row) : ?>
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark"><?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                        <small class="text-muted"><?= $row['jam_masuk'] ? date('H:i', strtotime($row['jam_masuk'])) : '-' ?> WIB</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-avatar">
                                            <?= substr($row['nama'], 0, 1) ?>
                                        </div>
                                        <div class="user-details">
                                            <h6><?= esc($row['nama']) ?></h6>
                                            <span>
                                                <i class="bi <?= $row['user_type'] == 'guru' ? 'bi-person-video3' : 'bi-backpack' ?> me-1"></i>
                                                <?= ucfirst($row['user_type']) ?> 
                                                <span class="mx-1">&bull;</span> 
                                                <?= esc($row['info_tambahan']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($row['status']) ?>">
                                        <?= strtoupper($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['jam_masuk']): ?>
                                        <span class="time-badge text-success"><i class="bi bi-box-arrow-in-right me-1"></i> <?= date('H:i', strtotime($row['jam_masuk'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['jam_pulang']): ?>
                                        <span class="time-badge text-primary"><i class="bi bi-box-arrow-left me-1"></i> <?= date('H:i', strtotime($row['jam_pulang'])) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small fst-italic">Belum Pulang</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($row['lokasi_lat'] && $row['lokasi_long']): ?>
                                        <a href="https://www.google.com/maps?q=<?= $row['lokasi_lat'] ?>,<?= $row['lokasi_long'] ?>" target="_blank" class="btn-maps" title="Lihat Lokasi Maps">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if(session()->get('level') == 'admin'): ?>
                                        <a href="<?= base_url('admin/absensi/edit/' . $row['id']) ?>" class="btn-edit-manual" title="Koreksi Manual">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    <?php else: ?>
                                        <i class="bi bi-lock-fill text-muted opacity-25"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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