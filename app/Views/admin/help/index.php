<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-help {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }

    .card-header-help {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 40px;
        color: white;
        text-align: center;
        position: relative;
    }

    .nav-pills-custom .nav-link {
        background: #f8f9fa;
        color: #607080;
        font-weight: 700;
        padding: 15px 30px;
        border-radius: 50px;
        margin: 0 10px;
        border: 2px solid #eef2f7;
        transition: all 0.3s;
    }

    .nav-pills-custom .nav-link.active {
        background: #435ebe;
        color: white;
        border-color: #435ebe;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.3);
        transform: translateY(-2px);
    }

    .timeline-steps {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    .timeline-step {
        align-items: center;
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 1rem;
    }

    .timeline-content {
        width: 100%;
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: transform 0.3s;
    }

    .timeline-content:hover {
        transform: translateY(-5px);
        border-color: #435ebe;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #eef2ff;
        color: #435ebe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .step-title {
        font-weight: 700;
        color: #25396f;
        margin-bottom: 10px;
    }

    .step-desc {
        font-size: 0.9rem;
        color: #607080;
        line-height: 1.6;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Pusat Bantuan</h3>
            <p class="text-subtitle text-muted">Panduan lengkap penggunaan aplikasi absensi.</p>
        </div>
    </div>
</div>

<div class="page-content">
    
    <div class="row justify-content-center mb-5">
        <div class="col-12 text-center">
            <ul class="nav nav-pills nav-pills-custom justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button">
                        <i class="bi bi-shield-lock-fill me-2"></i> Panduan Admin
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-petugas-tab" data-bs-toggle="pill" data-bs-target="#pills-petugas" type="button">
                        <i class="bi bi-person-badge-fill me-2"></i> Panduan Petugas
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="pills-tabContent">
        
        <!-- PANDUAN ADMIN -->
        <div class="tab-pane fade show active" id="pills-admin" role="tabpanel">
            <div class="row">
                <!-- Langkah 1 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">1</div>
                        <h5 class="step-title">Konfigurasi Awal</h5>
                        <p class="step-desc">
                            Lakukan pengaturan dasar terlebih dahulu:
                            <ul class="ps-3 mt-2">
                                <li>Menu <b>Profil Sekolah</b>: Isi data sekolah dan upload logo.</li>
                                <li>Menu <b>Setting GPS</b>: Tentukan titik lokasi sekolah dan radius absensi.</li>
                                <li>Menu <b>Setting Jam</b>: Atur jam masuk dan pulang.</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">2</div>
                        <h5 class="step-title">Koneksi WhatsApp</h5>
                        <p class="step-desc">
                            Hubungkan aplikasi dengan WhatsApp Gateway:
                            <ul class="ps-3 mt-2">
                                <li>Buka terminal laptop, jalankan <code>node server.js</code>.</li>
                                <li>Jalankan <code>ngrok http 3000</code>.</li>
                                <li>Copy URL Ngrok ke menu <b>Koneksi WhatsApp</b>.</li>
                                <li>Scan QR Code di menu <b>Server WhatsApp</b>.</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">3</div>
                        <h5 class="step-title">Input Data Master</h5>
                        <p class="step-desc">
                            Masukkan data pengguna aplikasi:
                            <ul class="ps-3 mt-2">
                                <li>Menu <b>Data Kelas</b>: Tambahkan kelas & jurusan.</li>
                                <li>Menu <b>Data Guru</b> & <b>Data Siswa</b>: Tambahkan data secara manual atau Import Excel.</li>
                                <li>Klik tombol <b>Generate QR</b> untuk setiap user.</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <!-- Langkah 4 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">4</div>
                        <h5 class="step-title">Distribusi QR Code</h5>
                        <p class="step-desc">
                            Setelah data lengkap:
                            <ul class="ps-3 mt-2">
                                <li>Masuk ke Data Kelas.</li>
                                <li>Klik nama kelas untuk melihat siswa.</li>
                                <li>Klik tombol <b>ZIP</b> untuk mendownload semua QR Code siswa dalam satu kelas.</li>
                                <li>Cetak dan bagikan kartu QR ke siswa/guru.</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <!-- Langkah 5 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">5</div>
                        <h5 class="step-title">Monitoring & Koreksi</h5>
                        <p class="step-desc">
                            Kegiatan harian admin:
                            <ul class="ps-3 mt-2">
                                <li>Pantau kehadiran di <b>Dashboard</b>.</li>
                                <li>Jika ada siswa sakit/izin/lupa absen, gunakan menu <b>Koreksi Kehadiran</b>.</li>
                                <li>Pilih tanggal dan user, lalu ubah status dari Alfa menjadi Hadir/Sakit/Izin.</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <!-- Langkah 6 -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="timeline-content h-100">
                        <div class="step-icon">6</div>
                        <h5 class="step-title">Cetak Laporan</h5>
                        <p class="step-desc">
                            Di akhir bulan/semester:
                            <ul class="ps-3 mt-2">
                                <li>Masuk menu <b>Cetak Laporan</b>.</li>
                                <li>Pilih Tab Guru atau Siswa.</li>
                                <li>Pilih <b>Detail</b> untuk laporan harian lengkap.</li>
                                <li>Pilih <b>Rekap Matriks</b> untuk laporan bulanan (Tanggal 1-31).</li>
                            </ul>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANDUAN PETUGAS -->
        <div class="tab-pane fade" id="pills-petugas" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-help mb-4">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-light-primary p-3 rounded-circle me-3 text-primary">
                                    <i class="bi bi-qr-code-scan fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">1. Melakukan Absensi (Scan)</h5>
                                    <p class="text-muted">
                                        Petugas piket bertugas membuka halaman <b>Scan QR Absen</b> di laptop/tablet sekolah.
                                        Pastikan kamera aktif dan browser diizinkan mengakses lokasi (GPS).
                                        Siswa/Guru cukup menunjukkan kartu QR ke kamera.
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-light-success p-3 rounded-circle me-3 text-success">
                                    <i class="bi bi-check-circle-fill fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">2. Verifikasi Data</h5>
                                    <p class="text-muted">
                                        Setelah scan berhasil, sistem akan memunculkan Nama, Foto, dan Jam Masuk/Pulang.
                                        Sistem juga otomatis mengirim notifikasi WhatsApp ke Orang Tua.
                                        Jika gagal (merah), periksa apakah lokasi sesuai radius atau QR valid.
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex align-items-start">
                                <div class="bg-light-warning p-3 rounded-circle me-3 text-warning">
                                    <i class="bi bi-table fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">3. Cek Data Harian</h5>
                                    <p class="text-muted">
                                        Petugas bisa melihat siapa saja yang sudah hadir hari ini melalui menu <b>Data Absensi</b>.
                                        Gunakan kolom pencarian untuk mencari nama siswa tertentu.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>