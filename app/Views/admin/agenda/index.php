<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Agenda Organisasi</h3>
</div>
<div class="page-content">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="text-white mb-0">Agenda IPPM</h5>
                    <button class="btn btn-sm btn-light text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalIppm">+ Tambah</button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Nama Agenda</th><th>Hari</th><th>Waktu</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($ippm as $i): ?>
                            <tr>
                                <td><?= $i['nama_agenda'] ?></td>
                                <td><?= $i['hari'] ?></td>
                                <td><?= substr($i['jam_mulai'],0,5) ?> - <?= substr($i['jam_akhir'],0,5) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editIppm(<?= htmlspecialchars(json_encode($i)) ?>)"><i class="bi bi-pencil"></i></button>
                                    <a href="<?= base_url('admin/agenda/delete-ippm/' . $i['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')"><i class="bi bi-trash"></i></a>
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
                    <h5 class="text-white mb-0">Agenda Kemasyarakatan</h5>
                    <button class="btn btn-sm btn-light text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalMasyarakat">+ Tambah</button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped mb-0">
                        <thead><tr><th>Nama Agenda</th><th>Hari</th><th>Waktu</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($masyarakat as $m): ?>
                            <tr>
                                <td><?= $m['nama_agenda'] ?></td>
                                <td><?= $m['hari'] ?></td>
                                <td><?= substr($m['jam_mulai'],0,5) ?> - <?= substr($m['jam_akhir'],0,5) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editMasyarakat(<?= htmlspecialchars(json_encode($m)) ?>)"><i class="bi bi-pencil"></i></button>
                                    <a href="<?= base_url('admin/agenda/delete-masyarakat/' . $m['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data?')"><i class="bi bi-trash"></i></a>
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

<div class="modal fade" id="modalIppm" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/agenda/save-ippm') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header bg-primary text-white"><h5 class="modal-title text-white">Tambah Agenda IPPM</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Agenda</label><input type="text" name="nama_agenda" class="form-control" required></div>
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalMasyarakat" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/agenda/save-masyarakat') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header bg-success text-white"><h5 class="modal-title text-white">Tambah Agenda Masyarakat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Agenda</label><input type="text" name="nama_agenda" class="form-control" required></div>
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" class="form-select" required>
                        <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
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

<div class="modal fade" id="modalEditIppm" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/agenda/update-ippm') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_ippm_id">
            <div class="modal-header bg-warning"><h5 class="modal-title">Edit Agenda IPPM</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Agenda</label><input type="text" name="nama_agenda" id="edit_ippm_nama" class="form-control" required></div>
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" id="edit_ippm_hari" class="form-select" required>
                        <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" id="edit_ippm_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" id="edit_ippm_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-warning">Update</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditMasyarakat" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('admin/agenda/update-masyarakat') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_masyarakat_id">
            <div class="modal-header bg-warning"><h5 class="modal-title">Edit Agenda Masyarakat</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nama Agenda</label><input type="text" name="nama_agenda" id="edit_masyarakat_nama" class="form-control" required></div>
                <div class="mb-3">
                    <label>Hari</label>
                    <select name="hari" id="edit_masyarakat_hari" class="form-select" required>
                        <option value="Senin">Senin</option><option value="Selasa">Selasa</option><option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option><option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option><option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label>Mulai</label><input type="time" name="jam_mulai" id="edit_masyarakat_mulai" class="form-control" required></div>
                    <div class="col-6"><label>Akhir</label><input type="time" name="jam_akhir" id="edit_masyarakat_akhir" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-warning">Update</button></div>
        </form>
    </div>
</div>

<script>
    function editIppm(data) {
        document.getElementById('edit_ippm_id').value = data.id;
        document.getElementById('edit_ippm_nama').value = data.nama_agenda;
        document.getElementById('edit_ippm_hari').value = data.hari;
        document.getElementById('edit_ippm_mulai').value = data.jam_mulai;
        document.getElementById('edit_ippm_akhir').value = data.jam_akhir;
        var myModal = new bootstrap.Modal(document.getElementById('modalEditIppm'));
        myModal.show();
    }

    function editMasyarakat(data) {
        document.getElementById('edit_masyarakat_id').value = data.id;
        document.getElementById('edit_masyarakat_nama').value = data.nama_agenda;
        document.getElementById('edit_masyarakat_hari').value = data.hari;
        document.getElementById('edit_masyarakat_mulai').value = data.jam_mulai;
        document.getElementById('edit_masyarakat_akhir').value = data.jam_akhir;
        var myModal = new bootstrap.Modal(document.getElementById('modalEditMasyarakat'));
        myModal.show();
    }
</script>
<?= $this->endSection() ?>