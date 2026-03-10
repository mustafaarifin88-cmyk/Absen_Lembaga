<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-menu {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
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
    .header-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
</style>

<div class="page-heading mb-4">
    <h3>Pusat Laporan Agenda Organisasi</h3>
    <p class="text-muted">Cetak rekap kehadiran agenda khusus IPPM maupun Masyarakat.</p>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card card-menu h-100">
                <div class="card-header-menu header-blue">
                    <i class="bi bi-list-task fs-4"></i> Laporan Detail (List)
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/laporan-agenda/cetak') ?>" method="post" target="_blank">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Agenda</label>
                            <select name="kategori" class="form-select" id="kat_detail" onchange="toggleAgenda('kat_detail', 'ag_ippm_detail', 'ag_mas_detail')">
                                <option value="ippm">IPPM (Keagamaan)</option>
                                <option value="masyarakat">Kemasyarakatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Agenda</label>
                            <select name="agenda_id" id="ag_ippm_detail" class="form-select">
                                <?php foreach($list_ippm as $i): ?><option value="<?= $i['id'] ?>"><?= $i['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                            <select name="agenda_id" id="ag_mas_detail" class="form-select d-none" disabled>
                                <?php foreach($list_masyarakat as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3"><label class="fw-bold">Dari</label><input type="date" name="tgl_awal" class="form-control" value="<?= date('Y-m-01') ?>" required></div>
                            <div class="col-6 mb-3"><label class="fw-bold">Sampai</label><input type="date" name="tgl_akhir" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-printer me-2"></i>Cetak PDF</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card card-menu h-100">
                <div class="card-header-menu header-green">
                    <i class="bi bi-grid-3x3 fs-4"></i> Matriks Bulanan
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/laporan-agenda/cetak-matriks-bulanan') ?>" method="post" target="_blank">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Peserta</label>
                            <select name="user_type" class="form-select">
                                <option value="anggota">Anggota</option>
                                <option value="pengurus">Pengurus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Agenda</label>
                            <select name="kategori" class="form-select" id="kat_bulan" onchange="toggleAgenda('kat_bulan', 'ag_ippm_bulan', 'ag_mas_bulan')">
                                <option value="ippm">IPPM (Keagamaan)</option>
                                <option value="masyarakat">Kemasyarakatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Agenda</label>
                            <select name="agenda_id" id="ag_ippm_bulan" class="form-select">
                                <?php foreach($list_ippm as $i): ?><option value="<?= $i['id'] ?>"><?= $i['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                            <select name="agenda_id" id="ag_mas_bulan" class="form-select d-none" disabled>
                                <?php foreach($list_masyarakat as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="fw-bold">Bulan</label>
                                <select name="bulan" class="form-select">
                                    <?php for($m=1; $m<=12; $m++): ?>
                                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= date('m')==$m?'selected':'' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="fw-bold">Tahun</label>
                                <select name="tahun" class="form-select">
                                    <?php for($y=date('Y'); $y>=2020; $y--): ?><option value="<?= $y ?>"><?= $y ?></option><?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-printer me-2"></i>Cetak Matriks Bulan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card card-menu h-100">
                <div class="card-header-menu header-purple">
                    <i class="bi bi-calendar3 fs-4"></i> Matriks Tahunan
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/laporan-agenda/cetak-matriks-tahunan') ?>" method="post" target="_blank">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Peserta</label>
                            <select name="user_type" class="form-select">
                                <option value="anggota">Anggota</option>
                                <option value="pengurus">Pengurus</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori Agenda</label>
                            <select name="kategori" class="form-select" id="kat_tahun" onchange="toggleAgenda('kat_tahun', 'ag_ippm_tahun', 'ag_mas_tahun')">
                                <option value="ippm">IPPM (Keagamaan)</option>
                                <option value="masyarakat">Kemasyarakatan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Agenda</label>
                            <select name="agenda_id" id="ag_ippm_tahun" class="form-select">
                                <?php foreach($list_ippm as $i): ?><option value="<?= $i['id'] ?>"><?= $i['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                            <select name="agenda_id" id="ag_mas_tahun" class="form-select d-none" disabled>
                                <?php foreach($list_masyarakat as $m): ?><option value="<?= $m['id'] ?>"><?= $m['nama_agenda'] ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Tahun</label>
                            <select name="tahun" class="form-select">
                                <?php for($y=date('Y'); $y>=2020; $y--): ?><option value="<?= $y ?>"><?= $y ?></option><?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="background:#667eea; border:none;"><i class="bi bi-printer me-2"></i>Cetak Matriks Tahun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAgenda(katId, ippmId, masId) {
        const kat = document.getElementById(katId).value;
        const ippm = document.getElementById(ippmId);
        const mas = document.getElementById(masId);

        if(kat === 'ippm') {
            ippm.classList.remove('d-none');
            ippm.disabled = false;
            ippm.name = 'agenda_id';
            
            mas.classList.add('d-none');
            mas.disabled = true;
        } else {
            mas.classList.remove('d-none');
            mas.disabled = false;
            mas.name = 'agenda_id';

            ippm.classList.add('d-none');
            ippm.disabled = true;
        }
    }
</script>
<?= $this->endSection() ?>