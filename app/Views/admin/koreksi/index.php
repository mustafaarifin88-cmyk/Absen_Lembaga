<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-menu {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .card-header-menu {
        padding: 20px;
        color: white;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .header-blue { background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); }
    .header-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .nav-pills .nav-link {
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        color: #607080;
        background: #fff;
        border: 2px solid #eef2f7;
        margin-right: 10px;
        transition: all 0.3s;
    }
    .nav-pills .nav-link.active {
        background: #435ebe;
        color: #fff;
        border-color: #435ebe;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.3);
    }
</style>

<div class="page-heading mb-4">
    <h3>Koreksi Kehadiran</h3>
    <p class="text-muted">Perbaiki data absensi atau reset kehadiran jika terjadi kesalahan.</p>
</div>

<div class="page-content">
    
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url('admin/koreksi/filter') ?>" method="get">
                <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($active_tab == 'pengurus') ? 'active' : '' ?>" onclick="selectType('pengurus')" type="button">Pengurus</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= ($active_tab == 'anggota') ? 'active' : '' ?>" onclick="selectType('anggota')" type="button">Anggota</button>
                    </li>
                </ul>
                
                <input type="hidden" name="user_type" id="user_type" value="<?= $active_tab ?>">

                <div class="row g-3 justify-content-center align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tanggal Mulai</label>
                        <input type="date" name="tgl_awal" class="form-control rounded-3" value="<?= $tgl_awal ?? date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" class="form-control rounded-3" value="<?= $tgl_akhir ?? date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="col-md-3" id="rt-select-container" style="display: <?= ($active_tab == 'anggota') ? 'block' : 'none' ?>;">
                        <label class="form-label fw-bold small text-uppercase text-muted">Pilih RT</label>
                        <select name="rt_id" class="form-select rounded-3">
                            <option value="">Semua RT</option>
                            <?php foreach($rt as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= (isset($rt_id) && $rt_id == $r['id']) ? 'selected' : '' ?>><?= $r['nama_rt'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2">
                            <i class="bi bi-search me-2"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if($tampil_data): ?>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Hasil Data</h5>
            <div>
                <button type="button" class="btn btn-danger btn-sm rounded-3" onclick="submitDelete()">
                    <i class="bi bi-trash-fill me-2"></i> Hapus Terpilih
                </button>
                <button type="button" class="btn btn-success btn-sm rounded-3 ms-2" onclick="showBulkEditModal()">
                    <i class="bi bi-pencil-square me-2"></i> Edit Terpilih
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulkForm" action="<?= base_url('admin/koreksi/bulkAction') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="user_type" value="<?= $active_tab ?>">
                <input type="hidden" name="tanggal" value="<?= $tgl_awal ?>">
                <input type="hidden" name="action" id="bulk_action_input">
                
                <input type="hidden" name="status" id="bulk_status_input">
                <input type="hidden" name="jam_masuk" id="bulk_jam_masuk_input">
                <input type="hidden" name="jam_pulang" id="bulk_jam_pulang_input">
                <input type="hidden" name="keterangan" id="bulk_keterangan_input">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3" width="5%"><input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleCheckboxes(this)"></th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Nama Lengkap</th>
                                <th class="py-3"><?= ($active_tab == 'pengurus') ? 'Jabatan' : 'RT' ?></th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center">Jam Masuk</th>
                                <th class="py-3 text-center">Jam Pulang</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($laporan)): ?>
                                <tr><td colspan="8" class="text-center py-5 text-muted fw-bold">Data tidak ditemukan.</td></tr>
                            <?php else: ?>
                                <?php foreach($laporan as $row): ?>
                                <tr>
                                    <td class="px-4"><input type="checkbox" name="selected_id[]" value="<?= $row['user_id'] ?>" class="form-check-input row-checkbox"></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td class="fw-bold"><?= $row['nama_lengkap'] ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $row['jabatan_or_rt'] ?></span></td>
                                    <td class="text-center">
                                        <?php 
                                            $badge = 'secondary';
                                            if($row['status'] == 'Hadir') $badge = 'success';
                                            elseif($row['status'] == 'Terlambat') $badge = 'warning';
                                            elseif($row['status'] == 'Sakit') $badge = 'info';
                                            elseif($row['status'] == 'Izin') $badge = 'primary';
                                            elseif($row['status'] == 'Alfa') $badge = 'danger';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>"><?= $row['status'] ?></span>
                                    </td>
                                    <td class="text-center"><?= $row['jam_masuk'] ?: '-' ?></td>
                                    <td class="text-center"><?= $row['jam_pulang'] ?: '-' ?></td>
                                    <td class="text-center">
                                        <?php if($row['id']): ?>
                                            <a href="<?= base_url('admin/absensi/edit/' . $row['id']) ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="<?= base_url('admin/koreksi/delete/' . $row['id']) ?>" onclick="return confirm('Reset data absensi ini menjadi Alfa?')" class="btn btn-sm btn-outline-danger rounded-circle ms-1" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <?php else: ?>
                                            <span class="text-muted small fs-7">Belum Absen</span>
                                        <?php endif; ?>
                                    </td>
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

<!-- Modal Bulk Edit -->
<div class="modal fade" id="bulkEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Status Kehadiran</label>
                    <select id="modal_status" class="form-select">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" id="modal_jam_masuk" class="form-control">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Jam Pulang</label>
                        <input type="time" id="modal_jam_pulang" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea id="modal_keterangan" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitBulkEdit()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script>
    function selectType(type) {
        document.getElementById('user_type').value = type;
        const rtContainer = document.getElementById('rt-select-container');
        
        if (type === 'anggota') {
            rtContainer.style.display = 'block';
        } else {
            rtContainer.style.display = 'none';
        }
        
        // Update active tab visual
        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
        event.target.classList.add('active');
    }

    function toggleCheckboxes(source) {
        document.querySelectorAll('.row-checkbox').forEach(c => c.checked = source.checked);
    }

    function getCheckedCount() {
        return document.querySelectorAll('.row-checkbox:checked').length;
    }

    function showBulkEditModal() {
        if(getCheckedCount() === 0) {
            Swal.fire('Peringatan', 'Silakan pilih data terlebih dahulu!', 'warning');
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
            Swal.fire('Peringatan', 'Silakan pilih data terlebih dahulu!', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Hapus Data Terpilih?',
            text: "Data absensi yang dipilih akan dihapus (Reset ke Alfa).",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulk_action_input').value = 'delete';
                document.getElementById('bulkForm').submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>