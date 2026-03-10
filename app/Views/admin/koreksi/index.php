<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px 30px;
        color: white;
    }
    .nav-pills .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        color: #607080;
        background: #f8f9fa;
        border: 1px solid #eef2f7;
        margin-right: 10px;
    }
    .nav-pills .nav-link.active {
        background: #435ebe;
        color: white;
        border-color: #435ebe;
    }
    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        min-width: 90px;
        text-align: center;
    }
    .status-hadir { background: #d1e7dd; color: #198754; }
    .status-terlambat { background: #fff3cd; color: #ffc107; }
    .status-sakit { background: #cff4fc; color: #0dcaf0; }
    .status-izin { background: #e2e3e5; color: #383d41; }
    .status-alfa { background: #f8d7da; color: #dc3545; }
    .table-modern thead th {
        background: #f8f9fa;
        color: #607080;
        border-bottom: 2px solid #eef2f7;
        padding: 15px;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f4f8;
    }
</style>

<div class="page-heading mb-4">
    <h3>Koreksi Kehadiran Rapat</h3>
    <p class="text-muted">Perbaiki data absen jika ada kesalahan atau input manual bagi yang belum scan.</p>
</div>

<div class="page-content">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-modern mb-4">
        <div class="card-body p-4">
            <ul class="nav nav-pills mb-4">
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab == 'pengurus') ? 'active' : '' ?>" href="#" onclick="switchTab('pengurus')"><i class="bi bi-person-badge-fill me-2"></i>Data Pengurus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active_tab == 'anggota') ? 'active' : '' ?>" href="#" onclick="switchTab('anggota')"><i class="bi bi-people-fill me-2"></i>Data Anggota</a>
                </li>
            </ul>

            <form action="<?= base_url('admin/koreksi/filter') ?>" method="get" id="filterForm">
                <input type="hidden" name="user_type" id="user_type" value="<?= $active_tab ?>">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?? date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?? date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-3 mb-3 <?= ($active_tab == 'pengurus') ? 'd-none' : '' ?>" id="filterRtBox">
                        <label class="form-label fw-bold">Pilih RT</label>
                        <select name="rt_id" class="form-select">
                            <option value="">-- Semua RT --</option>
                            <?php foreach($rt as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= (isset($rt_id) && $rt_id == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rt'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-search me-2"></i> Tampilkan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($tampil_data): ?>
    <div class="card card-modern">
        <div class="card-header-gradient d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="bi bi-list-check me-2"></i>Hasil Pencarian</h5>
        </div>
        <div class="card-body p-0">
            <form action="<?= base_url('admin/koreksi/bulkAction') ?>" method="post" id="bulkForm">
                <?= csrf_field() ?>
                <input type="hidden" name="user_type" value="<?= $active_tab ?>">
                <input type="hidden" name="action_type" id="bulk_action_input" value="">
                
                <input type="hidden" name="status" id="bulk_status_input">
                <input type="hidden" name="jam_masuk" id="bulk_jam_masuk_input">
                <input type="hidden" name="jam_pulang" id="bulk_jam_pulang_input">
                <input type="hidden" name="keterangan" id="bulk_keterangan_input">

                <div class="p-3 bg-light border-bottom d-flex gap-2">
                    <button type="button" class="btn btn-success fw-bold" onclick="showBulkModal()"><i class="bi bi-pencil-square me-2"></i>Koreksi Data Terpilih</button>
                    <button type="button" class="btn btn-danger fw-bold" onclick="submitDelete()"><i class="bi bi-trash-fill me-2"></i>Reset ke Alfa</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">
                                    <input class="form-check-input" type="checkbox" id="checkAll" onchange="toggleAllCheckboxes(this)">
                                </th>
                                <th width="10%">Tanggal</th>
                                <th width="20%">Nama</th>
                                <th width="15%">Info (Jabatan/RT)</th>
                                <th width="15%">Waktu Absen</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="20%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($laporan)): ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada data jadwal/absensi pada periode ini.</td></tr>
                            <?php else: ?>
                                <?php foreach($laporan as $row): 
                                    $statusClass = 'status-alfa';
                                    if ($row['status'] == 'Hadir') $statusClass = 'status-hadir';
                                    elseif ($row['status'] == 'Terlambat') $statusClass = 'status-terlambat';
                                    elseif ($row['status'] == 'Sakit') $statusClass = 'status-sakit';
                                    elseif ($row['status'] == 'Izin') $statusClass = 'status-izin';
                                    
                                    $nama = ($active_tab == 'pengurus') ? $row['nama_pengurus'] : $row['nama_anggota'];
                                    $info = ($active_tab == 'pengurus') ? '-' : $row['nama_rt'];
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input row-checkbox" name="selected_data[]" value="<?= $row['user_id'] ?>|<?= $row['tanggal'] ?>">
                                    </td>
                                    <td><span class="fw-bold"><?= date('d M Y', strtotime($row['tanggal'])) ?></span></td>
                                    <td><span class="fw-bold text-dark"><?= esc($nama) ?></span></td>
                                    <td><?= $info ?></td>
                                    <td>
                                        <small class="d-block text-success fw-bold"><i class="bi bi-box-arrow-in-right"></i> Masuk: <?= $row['jam_masuk'] ?></small>
                                        <small class="d-block text-primary fw-bold"><i class="bi bi-box-arrow-right"></i> Pulang: <?= $row['jam_pulang'] ?: '-' ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge <?= $statusClass ?>"><?= strtoupper($row['status']) ?></span>
                                    </td>
                                    <td class="small text-muted"><?= esc($row['keterangan']) ?: '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="bulkEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header bg-success text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Koreksi Data Absensi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning small">
                    <i class="bi bi-info-circle-fill me-2"></i>Data yang Anda centang akan diubah secara massal.
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Ubah Status Menjadi</label>
                    <select id="modal_status" class="form-select" onchange="toggleTimeInputs()">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                    </select>
                </div>

                <div class="row" id="timeInputs">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-success">Jam Masuk</label>
                        <input type="time" id="modal_jam_masuk" class="form-control" value="07:00">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold text-primary">Jam Pulang (Opsional)</label>
                        <input type="time" id="modal_jam_pulang" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan / Keterangan</label>
                    <textarea id="modal_keterangan" class="form-control" rows="2" placeholder="Cth: Koreksi Manual Admin"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success fw-bold px-4" onclick="submitBulkEdit()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function switchTab(type) {
        document.getElementById('user_type').value = type;
        if(type === 'pengurus') {
            document.getElementById('filterRtBox').classList.add('d-none');
        } else {
            document.getElementById('filterRtBox').classList.remove('d-none');
        }
        document.getElementById('filterForm').submit();
    }

    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
    }

    function getCheckedCount() {
        return document.querySelectorAll('.row-checkbox:checked').length;
    }

    function toggleTimeInputs() {
        const stat = document.getElementById('modal_status').value;
        const timeBox = document.getElementById('timeInputs');
        if(stat === 'Hadir' || stat === 'Terlambat') {
            timeBox.style.display = 'flex';
        } else {
            timeBox.style.display = 'none';
        }
    }

    function showBulkModal() {
        if(getCheckedCount() === 0) {
            Swal.fire('Peringatan', 'Silakan centang data terlebih dahulu!', 'warning');
            return;
        }
        var myModal = new bootstrap.Modal(document.getElementById('bulkEditModal'));
        myModal.show();
    }

    function submitBulkEdit() {
        document.getElementById('bulk_status_input').value = document.getElementById('modal_status').value;
        document.getElementById('bulk_jam_masuk_input').value = document.getElementById('modal_jam_masuk').value;
        document.getElementById('bulk_jam_pulang_input').value = document.getElementById('modal_jam_pulang').value;
        document.getElementById('bulk_keterangan_input').value = document.getElementById('modal_keterangan').value;
        
        document.getElementById('bulk_action_input').value = 'update';
        document.getElementById('bulkForm').submit();
    }

    function submitDelete() {
        if(getCheckedCount() === 0) {
            Swal.fire('Peringatan', 'Silakan centang data terlebih dahulu!', 'warning');
            return;
        }
        Swal.fire({
            title: 'Hapus & Reset ke Alfa?',
            text: "Data absen yang dicentang akan kembali menjadi Alfa.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulk_action_input').value = 'delete';
                document.getElementById('bulkForm').submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>