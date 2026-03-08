<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<audio id="beep-sound" src="<?= base_url('assets/beep.mp3') ?>" preload="auto"></audio>

<style>
    .scan-container {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        background: #000;
    }

    #reader {
        width: 100%;
        border-radius: 20px;
    }

    #reader video {
        object-fit: cover;
        border-radius: 20px;
    }

    .scan-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        background: 
            linear-gradient(to right, #435ebe 4px, transparent 4px) 0 0,
            linear-gradient(to right, #435ebe 4px, transparent 4px) 0 100%,
            linear-gradient(to left, #435ebe 4px, transparent 4px) 100% 0,
            linear-gradient(to left, #435ebe 4px, transparent 4px) 100% 100%,
            linear-gradient(to bottom, #435ebe 4px, transparent 4px) 0 0,
            linear-gradient(to bottom, #435ebe 4px, transparent 4px) 100% 0,
            linear-gradient(to top, #435ebe 4px, transparent 4px) 0 100%,
            linear-gradient(to top, #435ebe 4px, transparent 4px) 100% 100%;
        background-repeat: no-repeat;
        background-size: 40px 40px;
        z-index: 10;
        margin: 20px;
        width: calc(100% - 40px);
        height: calc(100% - 40px);
    }

    .scan-line {
        position: absolute;
        width: 100%;
        height: 3px;
        background: #00ff00;
        box-shadow: 0 0 10px #00ff00;
        animation: scan 2s infinite linear;
        top: 0;
        z-index: 5;
    }

    @keyframes scan {
        0% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }

    .card-mode {
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: none;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .nav-pills-custom .nav-link {
        color: #607080;
        font-weight: 700;
        padding: 15px;
        border-radius: 0;
        background: #f8f9fa;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
    }

    .nav-pills-custom .nav-link.active {
        background: #fff;
        color: #435ebe;
        border-bottom: 3px solid #435ebe;
    }

    .nav-pills-custom .nav-item {
        flex: 1;
        text-align: center;
    }
</style>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Scan QR Code</h3>
                <p class="text-muted">Arahkan kamera ke QR Code Kartu Anggota/Pengurus</p>
            </div>

            <div class="card card-mode">
                <ul class="nav nav-pills nav-pills-custom" id="mode-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="harian-tab" data-bs-toggle="pill" href="#mode-harian" role="tab" onclick="setMode('harian')">
                            <i class="bi bi-clock-history me-2"></i> Absen Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="agenda-tab" data-bs-toggle="pill" href="#mode-agenda" role="tab" onclick="setMode('agenda')">
                            <i class="bi bi-calendar-check me-2"></i> Absen Agenda
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content p-4" id="mode-tabContent">
                    <div class="tab-pane fade show active" id="mode-harian" role="tabpanel">
                        <div class="alert alert-light-primary border-0 small text-center mb-0">
                            <i class="bi bi-info-circle me-1"></i> Scan untuk Absen Masuk & Pulang Harian
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="mode-agenda" role="tabpanel">
                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Kategori Agenda</label>
                            <select id="kategori_agenda" class="form-select" onchange="toggleAgendaList()">
                                <option value="ippm">IPPM (Keagamaan)</option>
                                <option value="masyarakat">Kemasyarakatan</option>
                            </select>
                        </div>
                        
                        <div id="wrapper_ippm">
                            <label class="fw-bold small mb-1">Pilih Agenda IPPM</label>
                            <select id="agenda_ippm" class="form-select">
                                <?php if(empty($list_ippm)): ?>
                                    <option value="">Tidak ada agenda hari ini</option>
                                <?php else: ?>
                                    <?php foreach($list_ippm as $i): ?>
                                        <option value="<?= $i['id'] ?>">
                                            <?= $i['nama_agenda'] ?> (<?= substr($i['jam_mulai'],0,5) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div id="wrapper_masyarakat" class="d-none">
                            <label class="fw-bold small mb-1">Pilih Agenda Masyarakat</label>
                            <select id="agenda_masyarakat" class="form-select">
                                <?php if(empty($list_masyarakat)): ?>
                                    <option value="">Tidak ada agenda hari ini</option>
                                <?php else: ?>
                                    <?php foreach($list_masyarakat as $m): ?>
                                        <option value="<?= $m['id'] ?>">
                                            <?= $m['nama_agenda'] ?> (<?= substr($m['jam_mulai'],0,5) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="scan-container">
                <div id="reader"></div>
                <div class="scan-overlay"></div>
                <div class="scan-line"></div>
            </div>

            <div class="text-center mt-4">
                <a href="javascript:history.back()" class="btn btn-light rounded-pill px-4 fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    let isProcessing = false;
    let currentMode = 'harian'; 
    let currentLat = null;
    let currentLong = null;

    function setMode(mode) {
        currentMode = mode;
    }

    function toggleAgendaList() {
        const kat = document.getElementById('kategori_agenda').value;
        if(kat === 'ippm') {
            document.getElementById('wrapper_ippm').classList.remove('d-none');
            document.getElementById('wrapper_masyarakat').classList.add('d-none');
        } else {
            document.getElementById('wrapper_ippm').classList.add('d-none');
            document.getElementById('wrapper_masyarakat').classList.remove('d-none');
        }
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLat = position.coords.latitude;
                currentLong = position.coords.longitude;
            },
            (error) => {
                console.warn("Gagal mengambil lokasi: " + error.message);
            }
        );
    }

    function playBeep() {
        var audio = document.getElementById("beep-sound");
        if (audio) {
            audio.play().catch(e => console.log("Audio play error:", e));
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        isProcessing = true;
        playBeep();

        let formData = new FormData();
        formData.append('qr_code', decodedText);
        
        if (currentLat && currentLong) {
            formData.append('latitude', currentLat);
            formData.append('longitude', currentLong);
        } else {
            formData.append('latitude', '-');
            formData.append('longitude', '-');
        }

        let targetUrl = "<?= base_url('absensi/process-scan') ?>";

        if (currentMode === 'agenda') {
            targetUrl = "<?= base_url('absensi/process-scan-agenda') ?>";
            
            const kategori = document.getElementById('kategori_agenda').value;
            let agendaId = '';
            
            if(kategori === 'ippm') {
                agendaId = document.getElementById('agenda_ippm').value;
            } else {
                agendaId = document.getElementById('agenda_masyarakat').value;
            }

            if(!agendaId) {
                Swal.fire('Peringatan', 'Silakan pilih agenda terlebih dahulu, atau tidak ada agenda hari ini.', 'warning')
                    .then(() => isProcessing = false);
                return;
            }

            formData.append('kategori', kategori);
            formData.append('agenda_id', agendaId);
        }

        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(targetUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `<b>${data.nama}</b><br>${data.message}<br>Jam: ${data.jam}`,
                    timer: 4000,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    isProcessing = false;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.messages ? JSON.stringify(data.messages) : (data.message || 'Terjadi kesalahan.'),
                    timer: 4000,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    isProcessing = false;
                });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error Server',
                text: 'Tidak dapat terhubung ke server. Coba lagi.',
                timer: 4000,
                showConfirmButton: false
            }).then(() => {
                isProcessing = false;
            });
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, 
            qrbox: {width: 250, height: 250},
            aspectRatio: 1.0
        },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess);
</script>

<?= $this->endSection() ?>