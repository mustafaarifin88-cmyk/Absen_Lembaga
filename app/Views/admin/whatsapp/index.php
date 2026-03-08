<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .card-modern {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        background: #fff;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        padding: 40px 30px;
        color: white;
        text-align: center;
        position: relative;
    }

    .card-header-modern::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 40px;
        background: #fff;
        border-radius: 50% 50% 0 0;
    }

    .icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2.5rem;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-block;
        margin-top: 10px;
    }

    .status-bg-connected { background: #d1e7dd; color: #0f5132; }
    .status-bg-disconnected { background: #f8d7da; color: #842029; }
    .status-bg-waiting { background: #fff3cd; color: #664d03; }

    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 600;
        border-radius: 50px;
        padding: 10px 25px;
        transition: all 0.3s;
    }

    .nav-pills .nav-link.active {
        background-color: #128C7E;
        color: white;
        box-shadow: 0 5px 15px rgba(18, 140, 126, 0.4);
    }

    .pairing-code-display {
        font-family: 'Courier New', monospace;
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: 8px;
        color: #128C7E;
        background: #e6fffa;
        padding: 20px;
        border-radius: 16px;
        border: 2px dashed #128C7E;
        margin: 20px 0;
        display: inline-block;
    }

    .btn-action {
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Server WhatsApp</h3>
            <p class="text-subtitle text-muted">Hubungkan nomor server untuk mengirim notifikasi absensi.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card card-modern">
                
                <div class="card-header-modern">
                    <div class="icon-wrapper">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Gateway Connection</h4>
                    <p class="mb-0 opacity-75 small">Target: <?= $status_url ?></p>
                </div>

                <div class="card-body p-5 text-center">
                    
                    <!-- Loading State -->
                    <div id="status-container">
                        <div class="spinner-border text-success" role="status" id="loading-spinner" style="width: 3rem; height: 3rem;"></div>
                        <h5 id="status-text" class="mt-3 text-muted fw-bold">Menghubungkan ke Server...</h5>
                    </div>

                    <!-- Area Belum Terhubung (QR & Pairing) -->
                    <div id="scan-area" style="display: none;">
                        <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="pills-qr-tab" data-bs-toggle="pill" data-bs-target="#pills-qr" type="button">
                                    <i class="bi bi-qr-code me-2"></i> Scan QR
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="pills-phone-tab" data-bs-toggle="pill" data-bs-target="#pills-phone" type="button">
                                    <i class="bi bi-phone me-2"></i> Nomor HP
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-qr" role="tabpanel">
                                <div class="d-flex justify-content-center mb-4">
                                    <div id="qrcode" style="padding: 15px; border: 1px solid #eee; border-radius: 15px; background: #fff;"></div>
                                </div>
                                <div class="alert alert-light-success text-start d-inline-block border-0 shadow-sm">
                                    <i class="bi bi-info-circle-fill me-2 text-success"></i> 
                                    Buka WhatsApp > Perangkat Tertaut > Tautkan Perangkat
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-phone" role="tabpanel">
                                <div id="form-pairing">
                                    <div class="mb-4 text-start">
                                        <label class="form-label fw-bold text-muted">Nomor WhatsApp Server</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0 fw-bold text-muted"></span>
                                            <input type="number" id="input-phone" class="form-control form-control-lg border-start-0 ps-0" placeholder="6281234567890">
                                        </div>
                                        <small class="text-muted">Gunakan awalah tanpa tanda + contoh "0812..." atau "62812..."</small>
                                    </div>
                                    <button onclick="requestPairing()" class="btn btn-success btn-action w-100">
                                        Dapatkan Kode Pairing
                                    </button>
                                </div>

                                <div id="result-pairing" style="display: none;">
                                    <p class="text-muted mb-2">Masukkan kode ini di HP Anda:</p>
                                    <div id="display-code" class="pairing-code-display">XXXX-XXXX</div>
                                    <div>
                                        <button onclick="location.reload()" class="btn btn-link text-muted text-decoration-none">Gunakan nomor lain</button>
                                    </div>
                                    <div class="alert alert-info mt-4 text-start small border-0 bg-light-info text-info">
                                        <strong>Langkah-langkah:</strong><br>
                                        1. Buka WhatsApp di HP > Perangkat Tertaut<br>
                                        2. Klik "Tautkan Perangkat"<br>
                                        3. Klik <strong>"Tautkan dengan nomor telepon saja"</strong> (di bagian bawah layar)<br>
                                        4. Masukkan kode di atas
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Terhubung -->
                    <div id="connected-container" style="display: none;">
                        <div class="mb-3">
                             <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-2">Terhubung!</h3>
                        <p class="text-muted mb-4">Server WhatsApp siap digunakan untuk notifikasi absensi.</p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button onclick="checkStatus()" class="btn btn-outline-success btn-action">
                                <i class="bi bi-arrow-clockwise me-2"></i> Cek Status
                            </button>
                            <button onclick="logoutWhatsApp()" class="btn btn-danger btn-action">
                                <i class="bi bi-box-arrow-right me-2"></i> Putuskan
                            </button>
                        </div>
                    </div>

                    <!-- Area Error (Dengan Pesan Detail) -->
                    <div id="error-container" style="display: none;">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-danger">Koneksi Gagal</h4>
                        <p class="text-muted small mb-2">Gagal menghubungi: <br><code><?= $status_url ?></code></p>
                        
                        <div class="alert alert-danger text-start small border-0 py-2">
                            <strong>Pesan Error:</strong> <span id="error-message">Unknown Error</span>
                        </div>
                        
                        <div class="alert alert-light text-start small border py-2 bg-light">
                            <strong>Saran Perbaikan:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                <li>Pastikan URL di menu "Setting WhatsApp" benar (https, bukan http jika pakai ngrok).</li>
                                <li>Pastikan <code>node server.js</code> berjalan di terminal laptop.</li>
                                <li>Cek apakah Ngrok masih aktif/belum expired.</li>
                            </ul>
                        </div>
                        
                        <a href="<?= base_url('admin/setting-whatsapp') ?>" class="btn btn-primary btn-action mt-2">
                            <i class="bi bi-gear-fill me-2"></i> Cek URL Konfigurasi
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const RAW_URL = '<?= $status_url ?>';
    const BASE_URL = RAW_URL ? RAW_URL : 'http://localhost:3000/api/status'; 
    
    const ROOT_URL = BASE_URL.replace(/\/api\/.*$/, ''); 
    const STATUS_URL = ROOT_URL + '/api/status';
    const PAIRING_URL = ROOT_URL + '/api/pairing';
    const LOGOUT_URL = ROOT_URL + '/api/logout';

    let currentQR = '';
    let pollingInterval = null;

    function checkStatus() {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000); 

        fetch(STATUS_URL, {
            signal: controller.signal,
            headers: { 
                "ngrok-skip-browser-warning": "true",
                "Content-Type": "application/json"
            }
        })
        .then(response => {
            clearTimeout(timeoutId);
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") === -1) {
                throw new Error("Server tidak mengembalikan JSON. Mungkin URL salah atau expired.");
            }
            if (!response.ok) {
                throw new Error('Server Error: ' + response.status + ' ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('loading-spinner').style.display = 'none';
            document.getElementById('status-text').style.display = 'none';
            document.getElementById('error-container').style.display = 'none';

            if (data.status === 'connected') {
                document.getElementById('scan-area').style.display = 'none';
                document.getElementById('connected-container').style.display = 'block';
                if (pollingInterval) clearInterval(pollingInterval);
            } 
            else if (data.qr_code) {
                document.getElementById('connected-container').style.display = 'none';
                document.getElementById('scan-area').style.display = 'block';

                if (currentQR !== data.qr_code) {
                    currentQR = data.qr_code;
                    document.getElementById('qrcode').innerHTML = "";
                    new QRCode(document.getElementById("qrcode"), {
                        text: data.qr_code, width: 220, height: 220,
                        colorDark : "#128C7E", colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.L
                    });
                }
            } 
            else {
                // Scanning tapi belum ada QR, atau disconnected
                document.getElementById('scan-area').style.display = 'none';
                document.getElementById('connected-container').style.display = 'none';
                document.getElementById('loading-spinner').style.display = 'inline-block';
                document.getElementById('status-text').style.display = 'block';
                document.getElementById('status-text').innerText = "Menunggu Inisialisasi... (" + data.status + ")";
            }
        })
        .catch(error => {
            document.getElementById('loading-spinner').style.display = 'none';
            document.getElementById('status-text').style.display = 'none';
            document.getElementById('scan-area').style.display = 'none';
            document.getElementById('connected-container').style.display = 'none';
            
            document.getElementById('error-container').style.display = 'block';
            document.getElementById('error-message').innerText = error.message;
            
            if (pollingInterval) clearInterval(pollingInterval);
        });
    }

    function requestPairing() {
        const phoneInput = document.getElementById('input-phone').value;
        if (!phoneInput) {
            Swal.fire('Error', 'Masukkan nomor HP terlebih dahulu', 'error');
            return;
        }

        // PERBAIKAN: Format Nomor HP agar Valid untuk Baileys
        let formattedPhone = phoneInput.replace(/\D/g, ''); // Hapus semua non-angka
        if (formattedPhone.startsWith('0')) {
            formattedPhone = '62' + formattedPhone.slice(1);
        }
        
        // Pastikan nomor minimal 10 digit (validasi sederhana)
        if (formattedPhone.length < 10) {
             Swal.fire('Error', 'Nomor HP tidak valid (terlalu pendek)', 'warning');
             return;
        }

        Swal.fire({
            title: 'Meminta Kode...',
            text: 'Sedang menghubungi WhatsApp...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        // Timeout lebih lama untuk pairing code (30 detik)
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 30000);

        fetch(PAIRING_URL, {
            method: 'POST',
            signal: controller.signal,
            headers: { 
                "Content-Type": "application/json",
                "ngrok-skip-browser-warning": "true" 
            },
            body: JSON.stringify({ phone: formattedPhone })
        })
        .then(response => {
            clearTimeout(timeoutId);
            return response.json();
        })
        .then(data => {
            Swal.close();
            if (data.status === 'success') {
                document.getElementById('form-pairing').style.display = 'none';
                document.getElementById('result-pairing').style.display = 'block';
                
                // Format Kode agar mudah dibaca (ABC-DEF)
                let rawCode = data.code;
                // Coba format jika code berbentuk string panjang tanpa spasi
                if (rawCode && !rawCode.includes('-')) {
                    rawCode = rawCode.match(/.{1,4}/g).join('-');
                }

                document.getElementById('display-code').innerText = rawCode;
                Swal.fire('Berhasil!', 'Kode pairing diterima.', 'success');
            } else {
                Swal.fire('Gagal', data.message || 'Gagal mendapatkan kode', 'error');
            }
        })
        .catch(error => {
            if (error.name === 'AbortError') {
                Swal.fire('Timeout', 'Server WhatsApp lambat merespon. Coba restart server.js', 'error');
            } else {
                Swal.fire('Error', 'Gagal menghubungi server: ' + error.message, 'error');
            }
        });
    }

    function logoutWhatsApp() {
        if (pollingInterval) clearInterval(pollingInterval);
        
        Swal.fire({
            title: 'Logout?', text: "Koneksi akan diputus.", icon: 'warning',
            showCancelButton: true, confirmButtonText: 'Ya, Putuskan', cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading(); } });
                
                fetch(LOGOUT_URL, { 
                    method: 'POST',
                    headers: { "Content-Type": "application/json", "ngrok-skip-browser-warning": "true" }
                })
                .then(r => r.json())
                .then(d => {
                    Swal.fire('Logout Berhasil', '', 'success').then(() => location.reload());
                })
                .catch(e => {
                    Swal.fire('Error', e.message, 'error');
                });
            } else {
                pollingInterval = setInterval(checkStatus, 3000);
            }
        });
    }

    pollingInterval = setInterval(checkStatus, 3000);
    checkStatus();
</script>
<?= $this->endSection() ?>