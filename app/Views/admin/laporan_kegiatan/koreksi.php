<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-filter {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        border: 1px solid #eef2f7;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .status-hadir { background: #d1e7dd; color: #198754; }
    .status-alfa { background: #f8d7da; color: #dc3545; }
    .status-sakit { background: #cff4fc; color: #0dcaf0; }
    .status-izin { background: #fff3cd; color: #ffc107; }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Koreksi Kegiatan</h3>
            <p class="text-subtitle text-muted">Ubah status kehadiran Sholat & Ekskul (Alfa -> Sakit/Izin/Hadir).</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Koreksi Kegiatan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- FILTER SECTION -->
    <div class="card card-filter mb-4">
        <div class="card-body p-4">
            <form action="" method="get">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="fw-bold mb-2 small text-uppercase text-muted">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $p_tgl ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2 small text-uppercase text-muted">Kategori</label>
                        <select name="kategori" id="kategori_filter" class="form-select" onchange="toggleKegiatan()" required>
                            <option value="">-- Pilih --</option>
                            <option value="sholat" <?= $p_kategori == 'sholat' ? 'selected' : '' ?>>Sholat</option>
                            <option value="ekskul" <?= $p_kategori == 'ekskul' ? 'selected' : '' ?>>Ekstrakurikuler</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2 small text-uppercase text-muted">Nama Kegiatan</label>
                        <select name="kegiatan_id" id="kegiatan_sholat" class="form-select <?= $p_kategori == 'ekskul' ? 'd-none' : '' ?>">
                            <option value="">-- Pilih Sholat --</option>
                            <?php foreach($list_sholat as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= ($p_kategori == 'sholat' && $p_kegiatan == $s['id']) ? 'selected' : '' ?>><?= $s['nama_sholat'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="kegiatan_id" id="kegiatan_ekskul" class="form-select <?= $p_kategori != 'ekskul' ? 'd-none' : '' ?>" <?= $p_kategori != 'ekskul' ? 'disabled' : '' ?>>
                            <option value="">-- Pilih Ekskul --</option>
                            <?php foreach($list_ekskul as $e): ?>
                                <option value="<?= $e['id'] ?>" <?= ($p_kategori == 'ekskul' && $p_kegiatan == $e['id']) ? 'selected' : '' ?>><?= $e['nama_ekskul'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold mb-2 small text-uppercase text-muted">Kelas (Opsional)</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php foreach($list_kelas as $k): ?>
                                <option value="<?= $k['id'] ?>" <?= $p_kelas == $k['id'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?> - <?= $k['jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="bi bi-search me-2"></i> Tampilkan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <?php if(!empty($data_absensi)): ?>
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold">Daftar Kehadiran: <span class="text-primary"><?= $p_nama_kegiatan ?></span></h5>
            <small class="text-muted"><?= date('d F Y', strtotime($p_tgl)) ?></small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jam Absen</th>
                            <th>Status Saat Ini</th>
                            <th class="text-center">Aksi Koreksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_absensi as $row): ?>
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold text-dark"><?= $row['nama_lengkap'] ?></div>
                                <small class="text-muted"><?= $row['nisn'] ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $row['kelas'] ?></span></td>
                            <td><?= $row['jam_absen'] != '-' ? date('H:i', strtotime($row['jam_absen'])) : '-' ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($row['status']) ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td class="text-center" width="250">
                                <form action="<?= base_url('admin/koreksi-kegiatan/save') ?>" method="post" class="d-flex gap-2 justify-content-center">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_absen" value="<?= $row['id_absen'] ?>">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    
                                    <!-- Data Konteks untuk Insert Baru -->
                                    <input type="hidden" name="tanggal" value="<?= $p_tgl ?>">
                                    <input type="hidden" name="kategori" value="<?= $p_kategori ?>">
                                    <input type="hidden" name="kegiatan_id" value="<?= $p_kegiatan ?>">
                                    <input type="hidden" name="nama_kegiatan" value="<?= $p_nama_kegiatan ?>">
                                    <input type="hidden" name="kelas_id" value="<?= $p_kelas ?>">

                                    <select name="status" class="form-select form-select-sm" style="width: 100px;">
                                        <option value="Hadir" <?= $row['status'] == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="Sakit" <?= $row['status'] == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                        <option value="Izin" <?= $row['status'] == 'Izin' ? 'selected' : '' ?>>Izin</option>
                                        <option value="Alfa" <?= $row['status'] == 'Alfa' ? 'selected' : '' ?>>Alfa</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary" title="Simpan"><i class="bi bi-save"></i></button>
                                    
                                    <?php if($row['id_absen']): ?>
                                        <a href="<?= base_url('admin/koreksi-kegiatan/delete/' . $row['id_absen']) ?>" class="btn btn-sm btn-light text-danger" title="Hapus Data (Reset ke Alfa)" onclick="return confirm('Hapus data absensi ini?')"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif($p_kegiatan): ?>
        <div class="alert alert-info mt-4 text-center">
            <i class="bi bi-info-circle me-2"></i> Tidak ada data siswa ditemukan untuk kriteria ini.
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleKegiatan() {
        const kat = document.getElementById('kategori_filter').value;
        const sholat = document.getElementById('kegiatan_sholat');
        const ekskul = document.getElementById('kegiatan_ekskul');

        if(kat === 'sholat') {
            sholat.classList.remove('d-none');
            sholat.disabled = false;
            sholat.name = 'kegiatan_id'; // Pastikan name aktif
            
            ekskul.classList.add('d-none');
            ekskul.disabled = true;
        } else if(kat === 'ekskul') {
            ekskul.classList.remove('d-none');
            ekskul.disabled = false;
            ekskul.name = 'kegiatan_id'; // Pastikan name aktif

            sholat.classList.add('d-none');
            sholat.disabled = true;
        } else {
            sholat.classList.add('d-none');
            ekskul.classList.add('d-none');
        }
    }
</script>
<?= $this->endSection() ?>