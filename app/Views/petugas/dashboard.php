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

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }

    .clock-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.2);
    }

    .digital-clock {
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: 2px;
        font-family: 'Courier New', Courier, monospace;
        text-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .date-display {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
        margin-top: 5px;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Dashboard Petugas</h3>
            <p class="text-subtitle text-muted">Selamat datang, awasi jalannya absensi hari ini.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-8">
            <div class="row">
                <div class="col-6 col-md-6 mb-4">
                    <div class="card card-stat" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="bg-shape"></div>
                        <div class="bg-shape-2"></div>
                        <div class="card-body stat-content d-flex align-items-center p-4">
                            <div class="stat-icon me-4">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 opacity-75 fw-bold text-uppercase">Hadir</h6>
                                <h2 class="mb-0 fw-bold"><?= $total_hadir ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-6 mb-4">
                    <div class="card card-stat" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);">
                        <div class="bg-shape"></div>
                        <div class="bg-shape-2"></div>
                        <div class="card-body stat-content d-flex align-items-center p-4">
                            <div class="stat-icon me-4">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 opacity-75 fw-bold text-uppercase">Terlambat</h6>
                                <h2 class="mb-0 fw-bold"><?= $total_terlambat ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-6 mb-4">
                    <div class="card card-stat" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="bg-shape"></div>
                        <div class="bg-shape-2"></div>
                        <div class="card-body stat-content d-flex align-items-center p-4">
                            <div class="stat-icon me-4">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 opacity-75 fw-bold text-uppercase">Cepat Pulang</h6>
                                <h2 class="mb-0 fw-bold"><?= $total_cepat_pulang ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-6 mb-4">
                    <div class="card card-stat" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);">
                        <div class="bg-shape"></div>
                        <div class="bg-shape-2"></div>
                        <div class="card-body stat-content d-flex align-items-center p-4">
                            <div class="stat-icon me-4">
                                <i class="bi bi-bandaid"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 opacity-75 fw-bold text-uppercase">Izin / Sakit</h6>
                                <h2 class="mb-0 fw-bold"><?= $total_izin_sakit ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="clock-card mb-4">
                <div class="digital-clock" id="digital-clock">--:--:--</div>
                <div class="date-display">
                    <i class="bi bi-calendar-event me-2"></i> <?= date('l, d F Y') ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4 text-center">
                    <div class="mb-3 p-3 bg-light-primary rounded-circle text-primary d-inline-block">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Status Keamanan</h5>
                    <p class="text-muted small mb-0">Akun Anda terhubung sebagai Petugas. Akses terbatas pada pemindaian dan monitoring data rapat.</p>
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