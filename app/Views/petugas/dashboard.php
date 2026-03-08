<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-stat {
        border: none;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .stat-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    .bg-shape {
        position: absolute;
        top: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        z-index: 1;
    }

    .bg-shape-2 {
        position: absolute;
        bottom: -30px;
        left: -10px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        z-index: 1;
    }

    .bg-gradient-blue { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); }
    .bg-gradient-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-orange { background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); }
    .bg-gradient-purple { background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%); }

    .welcome-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-left: 5px solid #435ebe;
    }
</style>

<div class="page-heading">
    <h3>Dashboard Petugas</h3>
    <p class="text-subtitle text-muted">Ringkasan data absensi hari ini.</p>
</div>

<div class="page-content">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold text-primary mb-1">Halo, <?= session()->get('nama') ?>!</h4>
                    <p class="text-muted mb-0">Selamat bertugas. Jangan lupa pantau kehadiran anggota hari ini.</p>
                </div>
                <div class="d-none d-md-block text-end">
                    <h2 class="fw-bold text-dark mb-0" id="digital-clock"><?= date('H:i:s') ?></h2>
                    <p class="text-muted small"><?= date('l, d F Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card card-stat bg-gradient-green">
                <div class="bg-shape"></div>
                <div class="bg-shape-2"></div>
                <div class="card-body stat-content">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check-circle-fill fs-3 me-2"></i>
                        <span class="fw-bold text-uppercase small" style="letter-spacing: 1px; opacity: 0.8;">Hadir</span>
                    </div>
                    <h2 class="mb-0 fw-bold"><?= $total_hadir ?></h2>
                    <small class="opacity-75">Orang</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card card-stat bg-gradient-orange">
                <div class="bg-shape"></div>
                <div class="bg-shape-2"></div>
                <div class="card-body stat-content">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-alarm-fill fs-3 me-2"></i>
                        <span class="fw-bold text-uppercase small" style="letter-spacing: 1px; opacity: 0.8;">Terlambat</span>
                    </div>
                    <h2 class="mb-0 fw-bold"><?= $total_terlambat ?></h2>
                    <small class="opacity-75">Orang</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card card-stat bg-gradient-purple">
                <div class="bg-shape"></div>
                <div class="bg-shape-2"></div>
                <div class="card-body stat-content">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-box-arrow-left fs-3 me-2"></i>
                        <span class="fw-bold text-uppercase small" style="letter-spacing: 1px; opacity: 0.8;">Cepat Plg</span>
                    </div>
                    <h2 class="mb-0 fw-bold"><?= $total_cepat_pulang ?></h2>
                    <small class="opacity-75">Orang</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card card-stat bg-gradient-blue">
                <div class="bg-shape"></div>
                <div class="bg-shape-2"></div>
                <div class="card-body stat-content">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-info-circle-fill fs-3 me-2"></i>
                        <span class="fw-bold text-uppercase small" style="letter-spacing: 1px; opacity: 0.8;">Izin / Sakit</span>
                    </div>
                    <h2 class="mb-0 fw-bold"><?= $total_izin_sakit ?></h2>
                    <small class="opacity-75">Orang</small>
                </div>
            </div>
        </div>
    </div>

    <section class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">Menu Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?= base_url('scan') ?>" class="btn btn-outline-primary w-100 p-4 rounded-4 text-start hover-scale">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-primary p-3 rounded-circle me-3">
                                        <i class="bi bi-qr-code-scan fs-2"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Scan QR Code</h5>
                                        <p class="text-muted small mb-0">Buka pemindai untuk absen masuk/pulang</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?= base_url('petugas/data-absensi') ?>" class="btn btn-outline-success w-100 p-4 rounded-4 text-start hover-scale">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-success p-3 rounded-circle me-3">
                                        <i class="bi bi-table fs-2"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Data Absensi</h5>
                                        <p class="text-muted small mb-0">Lihat rekap kehadiran harian</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3 p-3 bg-light-primary rounded-circle text-primary d-inline-block">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Status Keamanan</h5>
                    <p class="text-muted small mb-0">Akun Anda terhubung sebagai Petugas. Akses terbatas pada pemindaian dan monitoring data harian.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let serverHour = <?= date('H') ?>;
        let serverMinute = <?= date('i') ?>;
        let serverSecond = <?= date('s') ?>;

        function updateClock() {
            serverSecond++;
            if (serverSecond >= 60) {
                serverSecond = 0;
                serverMinute++;
            }
            if (serverMinute >= 60) {
                serverMinute = 0;
                serverHour++;
            }
            if (serverHour >= 24) {
                serverHour = 0;
            }
            let h = String(serverHour).padStart(2, '0');
            let m = String(serverMinute).padStart(2, '0');
            let s = String(serverSecond).padStart(2, '0');
            let clockElement = document.getElementById('digital-clock');
            if (clockElement) {
                clockElement.textContent = `${h}:${m}:${s}`;
            }
        }
        setInterval(updateClock, 1000);
    });
</script>

<?= $this->endSection() ?>