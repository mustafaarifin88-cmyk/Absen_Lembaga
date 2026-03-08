<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Dashboard Overview</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="bi bi-person-badge-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Pengurus</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_pengurus ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total Anggota</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_anggota ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total RT</h6>
                                    <h6 class="font-extrabold mb-0"><?= $total_rt ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Hadir Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0"><?= $hadir_hari_ini ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistik Kehadiran Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="p-3 bg-light-success rounded text-center">
                                        <h3 class="text-success mb-0"><?= $hadir_hari_ini ?></h3>
                                        <span class="text-muted fw-bold">Hadir Tepat Waktu</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="p-3 bg-light-warning rounded text-center">
                                        <h3 class="text-warning mb-0"><?= $terlambat_hari_ini ?></h3>
                                        <span class="text-muted fw-bold">Terlambat</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="p-3 bg-light-danger rounded text-center">
                                        <h3 class="text-danger mb-0"><?= $cepat_pulang_hari_ini ?></h3>
                                        <span class="text-muted fw-bold">Cepat Pulang</span>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="p-3 bg-light-info rounded text-center">
                                        <h3 class="text-info mb-0"><?= $izin_sakit_hari_ini ?></h3>
                                        <span class="text-muted fw-bold">Izin / Sakit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="<?= base_url('uploads/foto_profil/' . session()->get('foto')) ?>" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold"><?= session()->get('nama') ?></h5>
                            <h6 class="text-muted mb-0">Administrator</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Info Server</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="name ms-4">
                            <h5 class="mb-1">Tanggal</h5>
                            <h6 class="text-muted mb-0"><?= date('d F Y') ?></h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="name ms-4">
                            <h5 class="mb-1">Waktu</h5>
                            <h6 class="text-muted mb-0"><?= date('H:i') ?> WIB</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>