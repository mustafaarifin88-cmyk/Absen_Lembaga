<?php
use App\Models\OrganisasiModel;
$orgModel = new OrganisasiModel();
$org = $orgModel->first();
$namaOrg = $org ? $org['nama_organisasi'] : 'Sistem Absensi';
$logoOrg = $org && $org['logo'] ? base_url('uploads/logo/'.$org['logo']) : base_url('assets/images/logo.png');
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<audio id="beep-sound" src="<?= base_url('assets/beep.mp3') ?>" preload="auto"></audio>

<style>
    .page-wrapper-scan {
        max-width: 800px;
        margin: 0 auto;
    }
    .header-scan {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        border-radius: 24px;
        padding: 30px;
        color: white;
        text-align: center;
        box-shadow: 0 15px 35px rgba(67, 94, 190, 0.2);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .header-scan::before {
        content: '';
        position: absolute;
        top: -50px;
        left: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .logo-scan {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 16px;
        border: 3px solid rgba(255,255,255,0.3);
        margin-bottom: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .card-scanner {
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        background: #fff;
        padding: 30px;
    }
    .form-select-modern {
        border: 2px solid #eef2f7;
        border-radius: 14px;
        padding: 12px 15px;
        font-weight: 600;
        color: #435ebe;
        transition: all 0.3s;
        cursor: pointer;
    }
    .form-select-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
    }
    .scan-container {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: #000;
        margin-top: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    #reader {
        width: 100%;
        border-radius: 20px;
    }
    #reader video {
        object-fit: cover;
        border-radius: 20px;
    }
    #reader__dashboard_section_csr span {
        color: white !important;
    }
    #reader__dashboard_section_swaplink {
        color: #435ebe !important;
        text-decoration: none;
        font-weight: bold;
        background: white;
        padding: 5px 10px;
        border-radius: 8px;
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
        opacity: 0.8;
    }
    .scan-line {
        position: absolute;
        width: 100%;
        height: 3px;
        background: #38ef7d;
        box-shadow: 0 0 15px #38ef7d;
        animation: scan 2s infinite linear;
        z-index: 11;
    }
    @keyframes scan {
        0% { top: 0%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .status-text {
        font-size: 0.9rem;
        font-weight: 700;
        color: #607080;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
        display: block;
    }
</style>

<div class="page-wrapper-scan">
    <div class="header-scan">
        <img src="<?= $logoOrg ?>" alt="Logo" class="logo-scan">
        <h3 class="fw-bold mb-1 text-white"><?= esc($namaOrg) ?></h3>
        <p class="mb-0 opacity-75">Portal Pemindaian QR Code Kehadiran Terpadu</p>
    </div>

    <div class="card-scanner">
        <div class="row">
            <div class="col-md-12 mb-4">
                <span class="status-text"><i class="bi bi-funnel-fill me-1"></i> Mode Absensi</span>
                <select id="tipe_absen" class="form-select form-select-modern" onchange="toggleAgendaOptions()">
                    <option value="rapat">Rapat Organisasi (Default)</option>
                    <option value="ippm">Agenda IPPM (Keagamaan)</option>
                    <option value="masyarakat">Agenda Kemasyarakatan</option>
                </select>
            </div>
            
            <div class="col-md-12 mb-4 d-none" id="container_ippm">
                <span class="status-text"><i class="bi bi-journal-bookmark-fill me-1"></i> Pilih Agenda IPPM Hari Ini</span>
                <select id="agenda_ippm" class="form-select form-select-modern">
                    <option value="">-- Pilih Agenda --</option>
                    <?php if(isset($list_ippm)): foreach($list_ippm as $i): ?>
                        <option value="<?= $i['id'] ?>" data-name="<?= $i['nama_agenda'] ?>"><?= $i['nama_agenda'] ?> (<?= substr($i['jam_mulai'],0,5) ?>)</option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="col-md-12 mb-4 d-none" id="container_masyarakat">
                <span class="status-text"><i class="bi bi-people-fill me-1"></i> Pilih Agenda Masyarakat Hari Ini</span>
                <select id="agenda_masyarakat" class="form-select form-select-modern">
                    <option value="">-- Pilih Agenda --</option>
                    <?php if(isset($list_masyarakat)): foreach($list_masyarakat as $m): ?>
                        <option value="<?= $m['id'] ?>" data-name="<?= $m['nama_agenda'] ?>"><?= $m['nama_agenda'] ?> (<?= substr($m['jam_mulai'],0,5) ?>)</option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </div>

        <div class="scan-container">
            <div id="reader"></div>
            <div class="scan-overlay"></div>
            <div class="scan-line"></div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted small fw-bold"><i class="bi bi-upc-scan me-1"></i> Arahkan QR Code anggota/pengurus tepat ke area kamera.</p>
        </div>
    </div>
</div>

<script>
    let isProcessing = false;
    let userLat = null;
    let userLng = null;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            userLat = position.coords.latitude;
            userLng = position.coords.longitude;
        });
    }

    function toggleAgendaOptions() {
        const tipe = document.getElementById('tipe_absen').value;
        const cIppm = document.getElementById('container_ippm');
        const cMas = document.getElementById('container_masyarakat');
        
        cIppm.classList.add('d-none');
        cMas.classList.add('d-none');
        
        if(tipe === 'ippm') cIppm.classList.remove('d-none');
        if(tipe === 'masyarakat') cMas.classList.remove('d-none');
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        isProcessing = true;
        
        document.getElementById('beep-sound').play();
        const tipe = document.getElementById('tipe_absen').value;
        let url = '<?= base_url('absensi/process-scan') ?>';
        let bodyData = new URLSearchParams({
            qr_code: decodedText,
            latitude: userLat,
            longitude: userLng
        });

        if (tipe === 'ippm' || tipe === 'masyarakat') {
            url = '<?= base_url('absensi/process-scan-agenda') ?>';
            let sel = document.getElementById(tipe === 'ippm' ? 'agenda_ippm' : 'agenda_masyarakat');
            let idAgenda = sel.value;
            if(!idAgenda) {
                Swal.fire('Error', 'Silakan pilih nama agenda terlebih dahulu!', 'warning').then(() => { isProcessing = false; });
                return;
            }
            let namaAgenda = sel.options[sel.selectedIndex].getAttribute('data-name');
            
            bodyData = new URLSearchParams({
                qr_code: decodedText,
                latitude: userLat,
                longitude: userLng,
                kategori: tipe,
                agenda_id: idAgenda,
                nama_agenda: namaAgenda
            });
        }

        Swal.fire({
            title: 'Memproses Data...',
            html: 'Mencocokkan QR Code ke sistem.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: bodyData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `<h4 class="fw-bold text-success">${data.nama}</h4><p class="mb-1">${data.message}</p><span class="badge bg-primary fs-6">${data.jam} WIB</span>`,
                    timer: 3500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => { isProcessing = false; });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.messages ? JSON.stringify(data.messages) : (data.message || 'Terjadi kesalahan.'),
                    timer: 3500,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => { isProcessing = false; });
            }
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error Server',
                text: 'Tidak dapat terhubung ke server.',
                timer: 3000,
                showConfirmButton: false
            }).then(() => { isProcessing = false; });
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 15, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 },
        false
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>
<?= $this->endSection() ?>