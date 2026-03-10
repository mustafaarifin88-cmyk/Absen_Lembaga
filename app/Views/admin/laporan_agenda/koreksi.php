<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-modern {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
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
            <h3 class="mb-1 text-primary fw-bold">Koreksi Absen Agenda</h3>
            <p class="text-subtitle text-muted">Perbarui status kehadiran agenda anggota dan pengurus.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card card-modern mb-4">
        <div class="card-body">
            <form action="<?= base_url('admin/koreksi-agenda') ?>" method="get">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="kategori" id="kategori_filter" class="form-select" onchange="toggleAgenda()">
                            <option value="ippm" <?= (isset($p_kategori) && $p_kategori == 'ippm') ? 'selected' : '' ?>>IPPM (Keagamaan)</option>
                            <option value="masyarakat" <?= (isset($p_kategori) && $p_kategori == 'masyarakat') ? 'selected' : '' ?>>Kemasyarakatan</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $p_tanggal ?? date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Pilih Agenda</label>
                        
                        <!-- Select IPPM -->
                        <select name="agenda_id" id="agenda_ippm" class="form-select <?= (isset($p_kategori) && $p_kategori == 'masyarakat') ? 'd-none' : '' ?>" <?= (isset($p_kategori) && $p_kategori == 'masyarakat') ? 'disabled' : '' ?>>
                            <option value="">-- Pilih Agenda IPPM --</option>
                            <?php if(isset($list_ippm)): foreach($list_ippm as $i): ?>
                                <option value="<?= $i['id'] ?>" <?= (isset($p_agenda) && $p_agenda == $i['id']) ? 'selected' : '' ?>><?= $i['nama_agenda'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>

                        <!-- Select Masyarakat -->
                        <select name="agenda_id" id="agenda_masyarakat" class="form-select <?= (!isset($p_kategori) || $p_kategori == 'ippm') ? 'd-none' : '' ?>" <?= (!isset($p_kategori) || $p_kategori == 'ippm') ? 'disabled' : '' ?>>
                            <option value="">-- Pilih Agenda Masyarakat --</option>
                            <?php if(isset($list_masyarakat)): foreach($list_masyarakat as $m): ?>
                                <option value="<?= $m['id'] ?>" <?= (isset($p_agenda) && $p_agenda == $m['id']) ? 'selected' : '' ?>><?= $m['nama_agenda'] ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-search me-2"></i> Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($laporan) && !empty($laporan)): ?>
    <div class="card card-modern">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tipe User</th>
                            <th>Waktu Absen</th>
                            <th>Status Saat Ini</th>
                            <th>Aksi Koreksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($laporan as $key => $row): 
                            $nama = $row['user_type'] == 'pengurus' ? $row['nama_pengurus'] : $row['nama_anggota'];
                            $jam = ($row['jam_absen'] && $row['jam_absen'] != '-') ? date('H:i', strtotime($row['jam_absen'])) : '-';
                            
                            $statusClass = 'status-alfa';
                            if ($row['status'] == 'Hadir') $statusClass = 'status-hadir';
                            if ($row['status'] == 'Sakit') $statusClass = 'status-sakit';
                            if ($row['status'] == 'Izin') $statusClass = 'status-izin';
                        ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td class="fw-bold"><?= esc($nama) ?></td>
                            <td><span class="badge bg-secondary"><?= strtoupper($row['user_type']) ?></span></td>
                            <td><?= $jam ?></td>
                            <td><span class="status-badge <?= $statusClass ?>"><?= $row['status'] ?></span></td>
                            <td>
                                <form action="<?= base_url('admin/koreksi-agenda/save') ?>" method="post" class="d-flex gap-2">
                                    <input type="hidden" name="id_absen" value="<?= isset($row['id']) && $row['id'] ? $row['id'] : '' ?>">
                                    <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                    <input type="hidden" name="user_type" value="<?= $row['user_type'] ?>">
                                    <input type="hidden" name="tanggal" value="<?= $p_tanggal ?>">
                                    <input type="hidden" name="kategori" value="<?= $p_kategori ?>">
                                    <input type="hidden" name="agenda_id" value="<?= $p_agenda ?>">
                                    
                                    <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                        <option value="Hadir" <?= $row['status'] == 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                        <option value="Sakit" <?= $row['status'] == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                        <option value="Izin" <?= $row['status'] == 'Izin' ? 'selected' : '' ?>>Izin</option>
                                        <option value="Alfa" <?= $row['status'] == 'Alfa' ? 'selected' : '' ?>>Alfa</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check2"></i> Simpan</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php elseif(isset($p_agenda) && $p_agenda): ?>
        <div class="alert alert-info mt-4 text-center">
            <i class="bi bi-info-circle me-2"></i> Tidak ada data untuk kriteria ini.
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleAgenda() {
        const kat = document.getElementById('kategori_filter').value;
        const ippm = document.getElementById('agenda_ippm');
        const mas = document.getElementById('agenda_masyarakat');

        if(kat === 'ippm') {
            ippm.classList.remove('d-none');
            ippm.disabled = false;
            ippm.name = 'agenda_id';
            
            mas.classList.add('d-none');
            mas.disabled = true;
        } else if(kat === 'masyarakat') {
            mas.classList.remove('d-none');
            mas.disabled = false;
            mas.name = 'agenda_id';

            ippm.classList.add('d-none');
            ippm.disabled = true;
        }
    }
</script>
<?= $this->endSection() ?>