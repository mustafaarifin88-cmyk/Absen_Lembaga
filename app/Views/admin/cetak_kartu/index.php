<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Libraries -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Font Custom */
    @font-face {
        font-family: 'Montserrat';
        src: url('<?= base_url("assets/fonts/Montserrat-Bold.ttf") ?>') format('truetype');
        font-weight: bold;
    }
    @font-face {
        font-family: 'Inter';
        src: url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    }

    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        height: 100%;
    }

    .card-header-custom {
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        border-radius: 15px 15px 0 0;
    }

    /* Loading Overlay */
    #loading-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    #loading-overlay.show { display: flex; }
    
    .spinner-border-custom {
        width: 3rem; height: 3rem;
        color: #435ebe;
    }

    /* Canvas (Hidden) */
    #cardCanvas {
        display: none; /* Sembunyikan canvas */
    }
</style>

<div class="page-heading">
    <h3>Cetak Kartu ID (Client-Side)</h3>
    <p class="text-muted">Generate kartu instan menggunakan Browser Anda.</p>
</div>

<div class="page-content">
    
    <!-- Data dari PHP ke JS -->
    <script>
        const BASE_URL = "<?= base_url() ?>/";
        const PENGURUS_DATA = <?= json_encode($pengurus) ?>;
        const ANGGOTA_DATA = <?= json_encode($anggota) ?>;
        const ORGANISASI = <?= json_encode($organisasi) ?>;
    </script>

    <div class="row">
        <!-- Cetak Pengurus -->
        <div class="col-md-6 mb-4">
            <div class="card glass-card">
                <div class="card-header-custom">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge-fill me-2"></i> Kartu Pengurus</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Cetak kartu ID untuk seluruh pengurus organisasi.</p>
                    <div class="alert alert-light-primary border-0 small">
                        <i class="bi bi-info-circle me-1"></i> Total Pengurus: <strong><?= count($pengurus) ?></strong>
                    </div>
                    <form id="formCetakPengurus">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-printer-fill me-2"></i> Download ZIP Pengurus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cetak Anggota -->
        <div class="col-md-6 mb-4">
            <div class="card glass-card">
                <div class="card-header-custom">
                    <h5 class="fw-bold text-danger mb-0"><i class="bi bi-people-fill me-2"></i> Kartu Anggota</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small">Cetak kartu ID anggota berdasarkan RT.</p>
                    <form id="formCetakAnggota">
                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Pilih RT</label>
                            <select id="rt_id" class="form-select">
                                <option value="" disabled selected>-- Pilih RT --</option>
                                <option value="all_anggota">Semua RT (Download Semua)</option>
                                <?php foreach ($rt as $r) : ?>
                                    <option value="<?= $r['id'] ?>"><?= $r['nama_rt'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-printer-fill me-2"></i> Download ZIP Anggota
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Canvas Hidden -->
<canvas id="cardCanvas"></canvas>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="spinner-border spinner-border-custom mb-3" role="status"></div>
    <h5 id="loading-text" class="fw-bold text-dark">Memproses...</h5>
    <small class="text-muted">Mohon tunggu, browser sedang membuat kartu.</small>
</div>

<script>
    const canvas = document.getElementById('cardCanvas');
    const ctx = canvas.getContext('2d');
    const loadingOverlay = document.getElementById('loading-overlay');
    const loadingText = document.getElementById('loading-text');

    // Dimensi Kartu (ID Card Standar 85.6mm x 53.98mm)
    // Kita gunakan resolusi tinggi untuk cetak
    const CARD_WIDTH = 1011; 
    const CARD_HEIGHT = 638;

    // Konstanta Styling
    const FRAME_COLOR_PENGURUS = '#435ebe'; // Biru
    const FRAME_COLOR_ANGGOTA = '#dc3545';  // Merah
    const BORDER_RADIUS = 40; // Sudut membulat
    
    // Font Setup
    const FONT_TITLE = 'bold 36px Montserrat, sans-serif'; // Header
    const FONT_NAME = 'bold 42px Montserrat, sans-serif';
    const FONT_LABEL = '24px Montserrat, sans-serif';
    const FONT_VALUE = 'bold 28px Montserrat, sans-serif';
    const FONT_SMALL = '20px Montserrat, sans-serif';

    function showLoading(text = 'Sedang memproses kartu...') {
        loadingText.innerText = text;
        loadingOverlay.classList.add('show');
    }

    function hideLoading() {
        loadingOverlay.classList.remove('show');
    }

    function roundRect(ctx, x, y, w, h, r) {
        if (w < 2 * r) r = w / 2;
        if (h < 2 * r) r = h / 2;
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.arcTo(x + w, y, x + w, y + h, r);
        ctx.arcTo(x + w, y + h, x, y + h, r);
        ctx.arcTo(x, y + h, x, y, r);
        ctx.arcTo(x, y, x + w, y, r);
        ctx.closePath();
    }

    // Fungsi Utama Menggambar Kartu
    function drawCard(data, tipe) {
        return new Promise(async (resolve, reject) => {
            canvas.width = CARD_WIDTH;
            canvas.height = CARD_HEIGHT;
            ctx.clearRect(0, 0, CARD_WIDTH, CARD_HEIGHT);

            const FRAME_COLOR = tipe === 'pengurus' ? FRAME_COLOR_PENGURUS : FRAME_COLOR_ANGGOTA;
            const ORG_NAME = ORGANISASI ? ORGANISASI.nama_organisasi.toUpperCase() : 'ORGANISASI';

            // 1. Background Fill (Putih)
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, CARD_WIDTH, CARD_HEIGHT);

            // 2. Header Background (Warna Utama)
            ctx.fillStyle = FRAME_COLOR;
            ctx.fillRect(0, 0, CARD_WIDTH, 160);

            // 3. Nama Organisasi (Di Header)
            ctx.fillStyle = '#ffffff';
            ctx.font = FONT_TITLE;
            ctx.textAlign = 'center';
            ctx.fillText(ORG_NAME, CARD_WIDTH / 2, 90);
            
            // Sub-header (Kartu Identitas)
            ctx.font = FONT_LABEL;
            ctx.fillStyle = 'rgba(255,255,255,0.8)';
            ctx.fillText('KARTU IDENTITAS ' + tipe.toUpperCase(), CARD_WIDTH / 2, 130);

            // 4. Footer Bar
            ctx.fillStyle = FRAME_COLOR;
            ctx.fillRect(0, CARD_HEIGHT - 30, CARD_WIDTH, 30);

            // 5. Foto Profil (Lingkaran)
            const photoSize = 250;
            const photoX = 80;
            const photoY = 220;
            const photoCenterCallbackX = photoX + photoSize/2;
            const photoCenterCallbackY = photoY + photoSize/2;

            // Path Foto
            let fotoFile = data.foto || 'default.jpg';
            let fotoDir = tipe === 'pengurus' ? 'uploads/foto_pengurus/' : 'uploads/foto_anggota/';
            // Fallback path jika default
            if(fotoFile === 'default.jpg') {
                // Gunakan placeholder atau path assets jika ada, disini kita pakai relative path
            }
            
            const imageUrl = BASE_URL + fotoDir + fotoFile;

            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                // Gambar Foto Clipping Circle
                ctx.save();
                ctx.beginPath();
                ctx.arc(photoCenterCallbackX, photoCenterCallbackY, photoSize / 2, 0, Math.PI * 2, true);
                ctx.closePath();
                ctx.clip();

                ctx.drawImage(img, photoX, photoY, photoSize, photoSize);
                ctx.restore();

                // Border Foto
                ctx.beginPath();
                ctx.arc(photoCenterCallbackX, photoCenterCallbackY, photoSize / 2, 0, Math.PI * 2, true);
                ctx.lineWidth = 8;
                ctx.strokeStyle = FRAME_COLOR;
                ctx.stroke();

                drawTextDetails();
            };
            
            img.onerror = () => {
                // Placeholder jika foto gagal
                ctx.fillStyle = '#e2e8f0';
                ctx.beginPath();
                ctx.arc(photoCenterCallbackX, photoCenterCallbackY, photoSize / 2, 0, Math.PI * 2, true);
                ctx.fill();
                
                ctx.fillStyle = '#94a3b8';
                ctx.font = FONT_LABEL;
                ctx.textAlign = 'center';
                ctx.fillText('No Photo', photoCenterCallbackX, photoCenterCallbackY);
                
                drawTextDetails();
            };
            img.src = imageUrl;

            function drawTextDetails() {
                const textX = 380;
                let textY = 250;
                const lineHeight = 50;

                // Nama Lengkap
                ctx.fillStyle = '#1e293b'; // Slate 800
                ctx.font = FONT_NAME;
                ctx.textAlign = 'left';
                ctx.fillText(data.nama_lengkap.toUpperCase(), textX, textY);
                
                // Garis bawah nama
                ctx.beginPath();
                ctx.moveTo(textX, textY + 15);
                ctx.lineTo(textX + 400, textY + 15);
                ctx.lineWidth = 3;
                ctx.strokeStyle = FRAME_COLOR;
                ctx.stroke();

                textY += 70;

                // Detail 1: Jabatan / RT
                ctx.fillStyle = '#64748b'; // Slate 500
                ctx.font = FONT_LABEL;
                const label1 = tipe === 'pengurus' ? 'JABATAN' : 'RUKUN TETANGGA';
                ctx.fillText(label1, textX, textY);
                
                textY += 35;
                ctx.fillStyle = '#334155'; // Slate 700
                ctx.font = FONT_VALUE;
                const val1 = tipe === 'pengurus' ? data.jabatan : (data.nama_rt || '-');
                ctx.fillText(val1, textX, textY);

                textY += lineHeight + 15;

                // Detail 2: ID System (Untuk QR Text)
                ctx.fillStyle = '#64748b';
                ctx.font = FONT_LABEL;
                ctx.fillText('ID SYSTEM', textX, textY);
                
                textY += 35;
                ctx.fillStyle = '#334155';
                ctx.font = FONT_VALUE;
                // Generate ID Display (PENGURUS-1, ANGGOTA-5)
                const idDisplay = tipe === 'pengurus' ? `PENGURUS-${data.id}` : `ANGGOTA-${data.id}`;
                ctx.fillText(idDisplay, textX, textY);

                // QR Code Generation
                const qrSize = 180;
                const qrX = CARD_WIDTH - qrSize - 50;
                const qrY = CARD_HEIGHT - qrSize - 60;

                // Isi QR Code
                const qrContent = idDisplay; // Sesuai format scan: PENGURUS-1 atau ANGGOTA-1

                // Canvas sementara untuk QR
                const qrCanvasTmp = document.createElement('canvas');
                
                QRCode.toCanvas(qrCanvasTmp, qrContent, { 
                    errorCorrectionLevel: 'H', 
                    width: qrSize,
                    margin: 1,
                    color: {
                        dark: '#000000',
                        light: '#ffffff'
                    }
                }, function (error) {
                    if (error) {
                        console.error(error);
                        ctx.fillStyle = '#ef4444';
                        ctx.fillText('QR Error', qrX, qrY + qrSize/2);
                    } else {
                        // Draw QR ke Canvas Utama
                        ctx.drawImage(qrCanvasTmp, qrX, qrY);
                        
                        // Border QR
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = '#cbd5e1';
                        ctx.strokeRect(qrX, qrY, qrSize, qrSize);
                    }

                    // Selesai, return data URL
                    resolve(canvas.toDataURL('image/jpeg', 0.9));
                });
            }
        });
    }

    // --- ZIP Processor ---
    async function processPrintAndZip(dataArray, filePrefix) {
        if (!dataArray || dataArray.length === 0) {
            Swal.fire('Perhatian', 'Tidak ada data untuk dicetak.', 'warning');
            return;
        }

        showLoading(`Memulai proses... (0/${dataArray.length})`);

        const zip = new JSZip();
        // Tentukan tipe berdasarkan properti (jabatan = pengurus, rt_id = anggota)
        const tipe = dataArray[0].hasOwnProperty('jabatan') ? 'pengurus' : 'anggota';

        for (let i = 0; i < dataArray.length; i++) {
            const item = dataArray[i];
            
            // Nama file aman
            const safeName = item.nama_lengkap.replace(/[^a-z0-9]/gi, '_').toLowerCase();
            const fileName = `${filePrefix}_${item.id}_${safeName}.jpg`;
            
            try {
                loadingText.innerText = `Memproses ${i + 1} dari ${dataArray.length} kartu...`;
                
                const dataURL = await drawCard(item, tipe);
                const base64Data = dataURL.split(',')[1];
                
                zip.file(fileName, base64Data, { base64: true });
            } catch (error) {
                console.error(`Gagal: ${item.nama_lengkap}`, error);
            }
        }

        loadingText.innerText = 'Mengompres file ZIP...';
        
        const zipBlob = await zip.generateAsync({ type: 'blob' });
        saveAs(zipBlob, `${filePrefix}_Kartu.zip`);

        hideLoading();
        Swal.fire({
            icon: 'success',
            title: 'Selesai!',
            text: `${dataArray.length} kartu berhasil di-generate.`,
            timer: 3000,
            showConfirmButton: false
        });
    }

    // --- Event Listeners ---

    // Cetak Pengurus
    document.getElementById('formCetakPengurus').addEventListener('submit', function(e) {
        e.preventDefault();
        processPrintAndZip(PENGURUS_DATA, 'PENGURUS');
    });

    // Cetak Anggota
    document.getElementById('formCetakAnggota').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const rtId = document.getElementById('rt_id').value;
        
        if (!rtId) {
            Swal.fire('Peringatan', 'Silakan pilih RT terlebih dahulu.', 'warning');
            return;
        }

        let filtered = [];
        let prefix = 'ANGGOTA';

        if (rtId === 'all_anggota') {
            filtered = ANGGOTA_DATA;
            prefix = 'ALL_ANGGOTA';
        } else {
            // Filter data Anggota berdasarkan RT ID
            filtered = ANGGOTA_DATA.filter(a => a.rt_id == rtId);
            
            // Cari nama RT untuk nama file
            if(filtered.length > 0) {
                const rtName = filtered[0].nama_rt.replace(/[^a-z0-9]/gi, '');
                prefix = `ANGGOTA_${rtName}`;
            }
        }
        
        processPrintAndZip(filtered, prefix);
    });

</script>

<?= $this->endSection() ?>