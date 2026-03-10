<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable-jquery.css') ?>">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .page-title-modern {
        font-weight: 800;
        background: -webkit-linear-gradient(135deg, #435ebe, #607080);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }
    .card-modern {
        border: none;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.04);
        background: #fff;
        overflow: hidden;
    }
    .card-header-modern {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
    }
    .btn-add-modern {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 12px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-add-modern:hover {
        background: #fff;
        color: #435ebe;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .table-modern thead th {
        background-color: #f8f9fa;
        color: #607080;
        border-bottom: 2px solid #eef2f7;
        padding: 18px 15px;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    .table-modern tbody td {
        padding: 18px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f4f8;
        font-size: 0.95rem;
        color: #444;
    }
    .table-modern tbody tr:hover {
        background-color: #f8faff;
    }
    .badge-modern {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .bg-light-primary { background: #e2eafc; color: #435ebe; }
    .bg-light-success { background: #d1e7dd; color: #0f5132; }
    .bg-light-warning { background: #fff3cd; color: #664d03; }
    .btn-action-modern {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        border: none;
    }
    .btn-edit { background: #fff3cd; color: #ffc107; }
    .btn-edit:hover { background: #ffc107; color: #fff; transform: scale(1.1); }
    .btn-delete { background: #f8d7da; color: #dc3545; }
    .btn-delete:hover { background: #dc3545; color: #fff; transform: scale(1.1); }
    .modal-content-modern {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }
    .modal-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px 30px;
        color: white;
        border: none;
    }
    .form-control-modern {
        border: 2px solid #eef2f7;
        border-radius: 14px;
        padding: 12px 18px;
        font-weight: 500;
        color: #333;
        transition: all 0.3s;
    }
    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 4px rgba(67, 94, 190, 0.1);
        background: #fcfdff;
    }
    .form-label-modern {
        font-weight: 700;
        color: #607080;
        margin-bottom: 8px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title-modern mb-1">Jadwal Rapat</h2>
            <p class="text-subtitle text-muted mb-0">Kelola dan atur jadwal rapat organisasi secara dinamis.</p>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if(session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    <?php endif; ?>

    <div class="card card-modern">
        <div class="card-header-modern">
            <h5 class="mb-0 fw-bold text-white"><i class="bi bi-calendar-event-fill me-2"></i> Daftar Rapat</h5>
            <button class="btn btn-add-modern" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern mb-0" id="tableJadwal">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Rapat</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Waktu Absensi</th>
                            <th>Peserta</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jadwal as $key => $j): ?>
                        <tr>
                            <td class="text-center fw-bold text-muted"><?= $key+1 ?></td>
                            <td>
                                <span class="fw-bold text-dark fs-6"><?= esc($j['nama_rapat']) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-primary rounded p-2 me-3 text-primary">
                                        <i class="bi bi-calendar-date fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark"><?= date('d F Y', strtotime($j['tanggal'])) ?></span>
                                        <span class="small text-muted"><?= date('l', strtotime($j['tanggal'])) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light-warning text-dark px-3 py-2 border border-warning border-opacity-25 rounded-pill">
                                    <i class="bi bi-clock me-1"></i> <?= substr($j['jam_mulai'],0,5) ?> - <?= substr($j['jam_akhir'],0,5) ?> WIB
                                </span>
                            </td>
                            <td>
                                <?php 
                                    $badgeClass = 'bg-light-primary';
                                    if($j['peserta'] == 'Pengurus') $badgeClass = 'bg-light-success';
                                    if($j['peserta'] == 'Anggota') $badgeClass = 'bg-light-warning';
                                ?>
                                <span class="badge-modern <?= $badgeClass ?>"><?= $j['peserta'] ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn-action-modern btn-edit" onclick='editData(<?= json_encode($j) ?>)' title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn-action-modern btn-delete ms-1" onclick="confirmDelete(<?= $j['id'] ?>)" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                <form id="delete-form-<?= $j['id'] ?>" action="<?= base_url('admin/jadwal-rapat/delete/'.$j['id']) ?>" method="get" class="d-none"></form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/jadwal-rapat/save') ?>" method="post" class="modal-content modal-content-modern">
            <?= csrf_field() ?>
            <div class="modal-header modal-header-gradient">
                <h5 class="modal-title text-white fw-bold"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Rapat</h5>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label-modern">Nama / Agenda Rapat</label>
                    <input type="text" name="nama_rapat" class="form-control form-control-modern" placeholder="Contoh: Rapat Evaluasi Bulanan" required>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" class="form-control form-control-modern" required>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label-modern">Jam Mulai Absen</label>
                        <input type="time" name="jam_mulai" class="form-control form-control-modern" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-modern">Jam Akhir Absen</label>
                        <input type="time" name="jam_akhir" class="form-control form-control-modern" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label-modern">Peserta Rapat</label>
                    <select name="peserta" class="form-select form-control-modern">
                        <option value="Semua">Semua (Pengurus & Anggota)</option>
                        <option value="Pengurus">Hanya Pengurus</option>
                        <option value="Anggota">Hanya Anggota</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 12px; background: linear-gradient(135deg, #435ebe 0%, #25396f 100%); border: none;">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= base_url('admin/jadwal-rapat/update') ?>" method="post" class="modal-content modal-content-modern">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Jadwal Rapat</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label-modern">Nama / Agenda Rapat</label>
                    <input type="text" name="nama_rapat" id="edit_nama" class="form-control form-control-modern" required>
                </div>
                <div class="mb-4">
                    <label class="form-label-modern">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" id="edit_tanggal" class="form-control form-control-modern" required>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <label class="form-label-modern">Jam Mulai Absen</label>
                        <input type="time" name="jam_mulai" id="edit_mulai" class="form-control form-control-modern" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-modern">Jam Akhir Absen</label>
                        <input type="time" name="jam_akhir" id="edit_akhir" class="form-control form-control-modern" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label-modern">Peserta Rapat</label>
                    <select name="peserta" id="edit_peserta" class="form-select form-control-modern">
                        <option value="Semua">Semua (Pengurus & Anggota)</option>
                        <option value="Pengurus">Hanya Pengurus</option>
                        <option value="Anggota">Hanya Anggota</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light fw-bold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2" style="border-radius: 12px; border: none;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#tableJadwal').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json",
                "emptyTable": '<div class="text-center py-4 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i><h6 class="fw-bold">Belum ada jadwal rapat</h6><p class="small">Klik tombol tambah jadwal untuk membuat rapat baru.</p></div>'
            },
            "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
            "drawCallback": function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-primary');
            }
        });
    });

    function editData(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_nama').value = data.nama_rapat;
        document.getElementById('edit_tanggal').value = data.tanggal;
        document.getElementById('edit_mulai').value = data.jam_mulai;
        document.getElementById('edit_akhir').value = data.jam_akhir;
        document.getElementById('edit_peserta').value = data.peserta;
        var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        myModal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Jadwal?',
            text: "Data jadwal rapat ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4c4c',
            cancelButtonColor: '#8592a3',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>