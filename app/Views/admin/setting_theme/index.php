<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Pengaturan Tampilan</h3>
</div>
<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('admin/setting-theme/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header fw-bold">Background Login</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Tipe Background</label>
                            <select name="login_bg_type" class="form-select" onchange="toggleInput('login', this.value)">
                                <option value="color" <?= ($theme['login_bg_type'] == 'color') ? 'selected' : '' ?>>Warna (Gradient/Solid)</option>
                                <option value="image" <?= ($theme['login_bg_type'] == 'image') ? 'selected' : '' ?>>Gambar</option>
                            </select>
                        </div>
                        <div id="login_input_color" class="<?= ($theme['login_bg_type'] == 'image') ? 'd-none' : '' ?>">
                            <label>Kode Warna CSS / Gradient</label>
                            <input type="text" name="login_bg_color" class="form-control" value="<?= htmlspecialchars($theme['login_bg_value']) ?>" placeholder="e.g: #ffffff or linear-gradient(...)">
                        </div>
                        <div id="login_input_image" class="<?= ($theme['login_bg_type'] == 'color') ? 'd-none' : '' ?>">
                            <label>Upload Gambar</label>
                            <input type="file" name="login_bg_image" class="form-control" accept="image/*">
                            <?php if($theme['login_bg_type'] == 'image'): ?>
                                <small>Gambar saat ini aktif.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header fw-bold">Background Sidebar</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Tipe Background</label>
                            <select name="sidebar_bg_type" class="form-select" onchange="toggleInput('sidebar', this.value)">
                                <option value="color" <?= ($theme['sidebar_bg_type'] == 'color') ? 'selected' : '' ?>>Warna Solid</option>
                                <option value="image" <?= ($theme['sidebar_bg_type'] == 'image') ? 'selected' : '' ?>>Gambar</option>
                            </select>
                        </div>
                        <div id="sidebar_input_color" class="<?= ($theme['sidebar_bg_type'] == 'image') ? 'd-none' : '' ?>">
                            <label>Kode Warna Hex</label>
                            <input type="color" name="sidebar_bg_color" class="form-control form-control-color w-100" value="<?= ($theme['sidebar_bg_type']=='color') ? $theme['sidebar_bg_value'] : '#ffffff' ?>">
                        </div>
                        <div id="sidebar_input_image" class="<?= ($theme['sidebar_bg_type'] == 'color') ? 'd-none' : '' ?>">
                            <label>Upload Gambar</label>
                            <input type="file" name="sidebar_bg_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Perubahan</button>
    </form>
</div>

<script>
function toggleInput(section, type) {
    if(type == 'color') {
        document.getElementById(section + '_input_color').classList.remove('d-none');
        document.getElementById(section + '_input_image').classList.add('d-none');
    } else {
        document.getElementById(section + '_input_color').classList.add('d-none');
        document.getElementById(section + '_input_image').classList.remove('d-none');
    }
}
</script>
<?= $this->endSection() ?>