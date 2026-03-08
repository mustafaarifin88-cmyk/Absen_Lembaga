const canvas = document.getElementById('cardCanvas');
const ctx = canvas.getContext('2d');
const loadingOverlay = document.getElementById('loading-overlay');
const loadingText = document.getElementById('loading-text');

// Dimensi Kartu (Contoh: 85mm x 54mm untuk ID Card standar, dikonversi ke pixel)
const CARD_WIDTH = 600;
const CARD_HEIGHT = 400;

// Konstanta Styling
const FRAME_COLOR_GURU = '#435ebe'; // Biru (Bingkai Guru)
const FRAME_COLOR_SISWA = '#dc3545'; // Merah (Bingkai Siswa)
const BORDER_RADIUS = 30;
const FONT_NORMAL = '18px Inter';
const FONT_BOLD = 'bold 22px Inter';
const FONT_SMALL = '14px Inter';
const FONT_TITLE = 'bold 28px Inter';

/**
 * Menampilkan overlay loading.
 * @param {string} text Teks yang ditampilkan di bawah spinner.
 */
function showLoading(text = 'Sedang memproses kartu...') {
    loadingText.innerText = text;
    loadingOverlay.classList.add('show');
}

/**
 * Menyembunyikan overlay loading.
 */
function hideLoading() {
    loadingOverlay.classList.remove('show');
}

/**
 * Menggambar sudut membulat untuk bingkai.
 * @param {number} x Koordinat X
 * @param {number} y Koordinat Y
 * @param {number} w Lebar
 * @param {number} h Tinggi
 * @param {number} r Radius
 */
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

/**
 * Menggambar kartu absen untuk satu individu (Guru/Siswa).
 * @param {object} data Data individu (nama, jabatan/kelas, nip/nisn, qr_code, foto, tipe)
 * @param {string} tipe 'guru' atau 'siswa'
 * @returns {Promise<string>} Data URL gambar PNG.
 */
function drawCard(data, tipe) {
    return new Promise(async (resolve, reject) => {
        canvas.width = CARD_WIDTH;
        canvas.height = CARD_HEIGHT;
        ctx.clearRect(0, 0, CARD_WIDTH, CARD_HEIGHT);

        const FRAME_COLOR = tipe === 'guru' ? FRAME_COLOR_GURU : FRAME_COLOR_SISWA;

        // 1. Bingkai Utama dengan Sudut Membulat
        roundRect(ctx, 0, 0, CARD_WIDTH, CARD_HEIGHT, BORDER_RADIUS);
        ctx.fillStyle = FRAME_COLOR;
        ctx.fill();

        // 2. Area Konten Putih (diberi sedikit margin)
        const contentMargin = 15;
        const contentX = contentMargin;
        const contentY = contentMargin;
        const contentW = CARD_WIDTH - 2 * contentMargin;
        const contentH = CARD_HEIGHT - 2 * contentMargin;
        roundRect(ctx, contentX, contentY, contentW, contentH, BORDER_RADIUS - contentMargin / 2);
        ctx.fillStyle = 'white';
        ctx.fill();

        // 3. Header/Logo
        ctx.fillStyle = '#343a40';
        ctx.font = FONT_TITLE;
        ctx.textAlign = 'center';
        ctx.fillText('KARTU ABSENSI', CARD_WIDTH / 2, 50);

        // 4. Baris Pembatas
        ctx.strokeStyle = FRAME_COLOR;
        ctx.lineWidth = 4;
        ctx.beginPath();
        ctx.moveTo(contentMargin + 20, 70);
        ctx.lineTo(CARD_WIDTH - contentMargin - 20, 70);
        ctx.stroke();

        // 5. Area Foto (Kiri Atas)
        const photoSize = 120;
        const photoX = contentMargin + 30;
        const photoY = 100;

        // Tentukan path foto
        const fotoPath = tipe === 'guru' ? `uploads/foto_guru/${data.foto || 'default.jpg'}` : `uploads/foto_siswa/${data.foto || 'default.jpg'}`;
        const imageUrl = `${BASE_URL}${fotoPath}`;
        
        const img = new Image();
        img.crossOrigin = 'Anonymous'; 
        img.onload = () => {
            // Gambar Foto
            ctx.save();
            ctx.beginPath();
            ctx.arc(photoX + photoSize / 2, photoY + photoSize / 2, photoSize / 2, 0, Math.PI * 2);
            ctx.clip();
            ctx.drawImage(img, photoX, photoY, photoSize, photoSize);
            ctx.restore();

            // Bingkai Foto (lingkaran)
            ctx.strokeStyle = FRAME_COLOR;
            ctx.lineWidth = 5;
            ctx.beginPath();
            ctx.arc(photoX + photoSize / 2, photoY + photoSize / 2, photoSize / 2, 0, Math.PI * 2);
            ctx.stroke();

            // Lanjut ke elemen QR Code dan Teks setelah foto dimuat
            drawDetails();
        };
        img.onerror = () => {
            console.warn(`Gagal memuat foto untuk ${data.nama_lengkap}. Menggunakan placeholder.`);
            // Placeholder jika gagal memuat foto
            ctx.fillStyle = '#f8f9fa';
            ctx.fillRect(photoX, photoY, photoSize, photoSize);
            ctx.fillStyle = '#adb5bd';
            ctx.font = FONT_SMALL;
            ctx.textAlign = 'center';
            ctx.fillText('NO PHOTO', photoX + photoSize / 2, photoY + photoSize / 2 + 5);
            
            drawDetails();
        };
        img.src = imageUrl;

        function drawDetails() {
            // 6. Data Teks (Kanan Foto)
            const textX = photoX + photoSize + 30;
            let textY = 110;
            const lineHeight = 30;

            // Nama Lengkap
            ctx.fillStyle = FRAME_COLOR;
            ctx.font = FONT_BOLD;
            ctx.textAlign = 'left';
            ctx.fillText(data.nama_lengkap.toUpperCase(), textX, textY);
            textY += lineHeight;

            // NISN/NIP
            ctx.fillStyle = '#6c757d';
            ctx.font = FONT_NORMAL;
            const idLabel = tipe === 'guru' ? 'NIP' : 'NISN';
            const idValue = tipe === 'guru' ? data.nip : data.nisn;
            ctx.fillText(`${idLabel}: ${idValue}`, textX, textY);
            textY += lineHeight + 5;

            // Jabatan/Kelas
            const roleLabel = tipe === 'guru' ? 'JABATAN' : 'KELAS';
            const roleValue = tipe === 'guru' ? data.jabatan : `${data.nama_kelas} - ${data.jurusan}`;

            ctx.fillStyle = '#343a40';
            ctx.font = FONT_BOLD;
            ctx.fillText(roleLabel, textX, textY);
            textY += 20;
            ctx.fillStyle = FRAME_COLOR;
            ctx.font = FONT_BOLD;
            ctx.fillText(roleValue.toUpperCase(), textX, textY);


            // 7. QR Code (Kanan Bawah)
            const qrSize = 130;
            const qrX = CARD_WIDTH - contentMargin - qrSize - 30;
            const qrY = photoY + photoSize - qrSize + 10;
            // Gunakan NIP/NISN sebagai nilai QR Code
            const qrValue = tipe === 'guru' ? `GURU-${data.nip}` : `SISWA-${data.nisn}`;

            const qrCanvas = document.createElement('canvas');
            QRCode.toCanvas(qrCanvas, qrValue, { errorCorrectionLevel: 'H', width: qrSize, margin: 1 }, function (error) {
                if (error) {
                    console.error('QR Code error:', error);
                    ctx.fillStyle = '#ffc107';
                    ctx.fillRect(qrX, qrY, qrSize, qrSize);
                    ctx.fillStyle = '#343a40';
                    ctx.font = FONT_SMALL;
                    ctx.textAlign = 'center';
                    ctx.fillText('QR ERROR', qrX + qrSize / 2, qrY + qrSize / 2 + 5);
                } else {
                    ctx.drawImage(qrCanvas, qrX, qrY, qrSize, qrSize);
                }
                
                // 8. Footer Keterangan
                ctx.fillStyle = '#6c757d';
                ctx.font = FONT_SMALL;
                ctx.textAlign = 'center';
                ctx.fillText(`Scan QR untuk absensi kehadiran`, CARD_WIDTH / 2, CARD_HEIGHT - 20);

                // Selesai menggambar, resolve dengan Data URL JPG
                resolve(canvas.toDataURL('image/jpeg', 0.9));
            });
        }
    });
}

/**
 * Memproses cetak untuk data yang diberikan dan menggabungkannya dalam ZIP.
 * @param {Array<object>} dataArray Data Guru atau Siswa.
 * @param {string} filePrefix Nama file prefix (Guru atau Siswa_Kelas).
 */
async function processPrintAndZip(dataArray, filePrefix) {
    if (dataArray.length === 0) {
        Swal.fire('Perhatian', 'Tidak ada data yang tersedia untuk dicetak.', 'warning');
        return;
    }

    showLoading(`Memproses 0 dari ${dataArray.length} kartu...`);

    const zip = new JSZip();
    // Tentukan tipe berdasarkan properti yang ada (nip untuk guru)
    const tipe = dataArray[0].hasOwnProperty('nip') ? 'guru' : 'siswa';

    for (let i = 0; i < dataArray.length; i++) {
        const item = dataArray[i];
        
        // Bersihkan nama file agar aman (ganti spasi dengan underscore)
        const safeName = item.nama_lengkap.replace(/\s+/g, '_').replace(/[^a-zA-Z0-9_]/g, '');
        const fileName = `${filePrefix}_${tipe === 'guru' ? item.nip : item.nisn}_${safeName}.jpg`;
        
        try {
            loadingText.innerText = `Memproses ${i + 1} dari ${dataArray.length} kartu (${item.nama_lengkap})...`;
            
            const dataURL = await drawCard(item, tipe);
            // Ambil data base64 setelah koma
            const base64Data = dataURL.split(',')[1]; 
            zip.file(fileName, base64Data, { base64: true });
        } catch (error) {
            console.error(`Gagal membuat kartu untuk ${item.nama_lengkap}:`, error);
        }
    }

    loadingText.innerText = 'Menggabungkan file dalam format ZIP...';
    
    // Generate ZIP
    const zipBlob = await zip.generateAsync({ type: 'blob' });
    
    // Simpan file ZIP
    saveAs(zipBlob, `${filePrefix}_Kartu_Absen.zip`);

    hideLoading();
    Swal.fire({
        icon: 'success',
        title: 'Selesai!',
        text: `${dataArray.length} kartu berhasil dibuat dan diunduh dalam file ZIP.`,
        timer: 3000,
        showConfirmButton: false
    });
}

// --- Event Listeners ---

document.getElementById('formCetakGuru').addEventListener('submit', function(e) {
    e.preventDefault();
    if (GURU_DATA && GURU_DATA.length > 0) {
        processPrintAndZip(GURU_DATA, 'GURU');
    } else {
        Swal.fire('Perhatian', 'Tidak ada data guru untuk dicetak.', 'warning');
    }
});

document.getElementById('formCetakSiswa').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const kelasId = document.getElementById('kelas_id').value;
    
    if (!kelasId) {
        Swal.fire('Peringatan', 'Silakan pilih kelas terlebih dahulu.', 'warning');
        return;
    }

    let filteredSiswa = [];
    let filePrefix = 'SISWA';

    if (kelasId === 'all_siswa') {
        filteredSiswa = SISWA_DATA;
        filePrefix = 'SISWA_ALL';
    } else {
        // Cari nama kelas untuk prefix file
        // Asumsi data Siswa sudah dijoin dengan Kelas
        const selectedKelas = SISWA_DATA.find(s => s.kelas_id == kelasId);
        if (selectedKelas) {
            // Gunakan nama kelas dan jurusan yang aman untuk nama file
            const safeKelas = selectedKelas.nama_kelas.replace(/\s+/g, '_');
            const safeJurusan = selectedKelas.jurusan.replace(/\s+/g, '_');
            filePrefix = `SISWA_${safeKelas}_${safeJurusan}`;
        }
        filteredSiswa = SISWA_DATA.filter(siswa => siswa.kelas_id == kelasId);
    }
    
    if (filteredSiswa.length > 0) {
        processPrintAndZip(filteredSiswa, filePrefix);
    } else {
        Swal.fire('Perhatian', 'Tidak ada siswa yang ditemukan di kelas ini.', 'warning');
    }
});