<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Jadwal Kegiatan Tambahan</h3>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">Jadwal Sholat</h5>
                    <button class="btn btn-sm btn-light text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalSholat">+ Tambah</button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Nama</th><th>Waktu</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($sholat as $s): ?>
                            <tr>
                                <td><?= $s['nama_sholat'] ?></td>
                                <td><?= substr($s['jam_mulai'],0,5) ?> - <?= substr($s['jam_akhir'],0,5) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editSholat(<?= htmlspecialchars(json_encode($s)) ?>)"><i class="bi bi-pencil"></i></button>
                                    <a href="<?= base_url('admin/jadwal/delete-sholat/'.$s['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">Jadwal Ekskul</h5>
                    <button class="btn btn-sm btn-light text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalEkskul">+ Tambah</button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Nama</th><th>Hari/Jam</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($ekskul as $e): ?>
                            <tr>
                                <td><?= $e['nama_ekskul'] ?></td>
                                <td><?= $e['hari'] ?>, <?= substr($e['jam_mulai'],0,5) ?> - <?= substr($e['jam_akhir'],0,5) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editEkskul(<?= htmlspecialchars(json_encode($e)) ?>)"><i class="bi bi-pencil"></i></button>
                                    <a href="<?= base_url('admin/jadwal/delete-ekskul/'.$e['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSholat" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= base_url('admin/jadwal/save-sholat') ?>" method="post">
            <div class="modal-header"><h5 class="modal-title">Tambah Sholat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Sholat</label><input type="text" name="nama_sholat" class="form-control" required placeholder="Contoh: Sholat Zuhur"></div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditSholat" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= base_url('admin/jadwal/update-sholat') ?>" method="post">
            <div class="modal-header"><h5 class="modal-title">Edit Sholat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_sholat_id">
                <div class="mb-3"><label>Nama Sholat</label><input type="text" name="nama_sholat" id="edit_sholat_nama" class="form-control" required></div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" id="edit_sholat_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" id="edit_sholat_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEkskul" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= base_url('admin/jadwal/save-ekskul') ?>" method="post">
            <div class="modal-header"><h5 class="modal-title">Tambah Ekskul</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Ekskul</label><input type="text" name="nama_ekskul" class="form-control" required placeholder="Contoh: Pramuka"></div>
                <div class="mb-3"><label>Hari</label>
                    <select name="hari" class="form-select" required>
                        <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">Simpan</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditEkskul" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" action="<?= base_url('admin/jadwal/update-ekskul') ?>" method="post">
            <div class="modal-header"><h5 class="modal-title">Edit Ekskul</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="edit_ekskul_id">
                <div class="mb-3"><label>Nama Ekskul</label><input type="text" name="nama_ekskul" id="edit_ekskul_nama" class="form-control" required></div>
                <div class="mb-3"><label>Hari</label>
                    <select name="hari" id="edit_ekskul_hari" class="form-select" required>
                        <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" id="edit_ekskul_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" id="edit_ekskul_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">Update</button></div>
        </form>
    </div>
</div>

<script>
    function editSholat(data) {
        document.getElementById('edit_sholat_id').value = data.id;
        document.getElementById('edit_sholat_nama').value = data.nama_sholat;
        document.getElementById('edit_sholat_mulai').value = data.jam_mulai;
        document.getElementById('edit_sholat_akhir').value = data.jam_akhir;
        var myModal = new bootstrap.Modal(document.getElementById('modalEditSholat'));
        myModal.show();
    }

    function editEkskul(data) {
        document.getElementById('edit_ekskul_id').value = data.id;
        document.getElementById('edit_ekskul_nama').value = data.nama_ekskul;
        document.getElementById('edit_ekskul_hari').value = data.hari;
        document.getElementById('edit_ekskul_mulai').value = data.jam_mulai;
        document.getElementById('edit_ekskul_akhir').value = data.jam_akhir;
        var myModal = new bootstrap.Modal(document.getElementById('modalEditEkskul'));
        myModal.show();
    }
</script>
<?= $this->endSection() ?>