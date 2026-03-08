<?php
helper('theme');
use App\Models\OrganisasiModel;

$theme = get_theme_setting();
$sidebarBg = ($theme['sidebar_bg_type'] == 'image') 
    ? "url('".base_url('uploads/theme/'.$theme['sidebar_bg_value'])."')" 
    : $theme['sidebar_bg_value'];

$uri = service('uri');
$segment = $uri->getTotalSegments() > 1 ? $uri->getSegment(2) : '';
$role = session()->get('level');
$foto = session()->get('foto') ? session()->get('foto') : 'default.jpg';
$fotoPath = 'uploads/foto_profil/' . $foto;

$orgModel = new OrganisasiModel();
$orgData = $orgModel->first();
$logoOrg = ($orgData && !empty($orgData['logo'])) ? $orgData['logo'] : 'default_logo.png';
$namaOrg = ($orgData && !empty($orgData['nama_organisasi'])) ? $orgData['nama_organisasi'] : 'Absensi Org';
?>

<style>
    .sidebar-wrapper {
        background: <?= $sidebarBg ?> !important;
        background-size: cover !important;
        background-position: center !important;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        border-radius: 0 20px 20px 0;
        transition: width 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.3s;
        z-index: 1000;
        overflow-x: hidden;
        width: 300px; /* Lebar default sidebar */
    }

    .btn-minimize {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(67, 94, 190, 0.1);
        border: none;
        color: #435ebe;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-minimize:hover {
        background: #435ebe;
        color: white;
    }

    .sidebar-header {
        padding: 2rem 1rem;
        text-align: center;
    }

    /* === PERBAIKAN UKURAN LOGO === */
    .sidebar-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .sidebar-logo img {
        width: 150px; /* Setengah dari lebar sidebar (300px / 2) */
        height: 150px; /* Tinggi disamakan agar square/circle rapi */
        object-fit: contain;
        border-radius: 12px;
        filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1));
        transition: all 0.3s ease; /* Animasi halus */
    }

    .app-name {
        font-weight: 800;
        font-size: 1.1rem;
        color: #435ebe;
        margin-top: 15px;
        display: block;
        letter-spacing: 0.5px;
        line-height: 1.2;
        transition: all 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .menu {
        padding: 0 1rem;
        margin-top: 1rem;
    }

    .sidebar-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        margin: 1.5rem 0 0.5rem 1rem;
        letter-spacing: 1px;
    }

    .sidebar-item {
        margin-bottom: 5px;
        list-style: none;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 12px;
        color: #607080;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .sidebar-link i {
        font-size: 1.1rem;
        margin-right: 12px;
        color: #435ebe;
        transition: all 0.3s;
    }

    .sidebar-link:hover {
        background-color: rgba(67, 94, 190, 0.08);
        color: #435ebe;
        transform: translateX(5px);
    }

    .sidebar-item.active .sidebar-link {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        color: white;
        box-shadow: 0 5px 15px rgba(67, 94, 190, 0.3);
    }

    .sidebar-item.active .sidebar-link i {
        color: white;
    }

    /* === MINI SIDEBAR STYLES === */
    body.mini-sidebar .sidebar-wrapper {
        width: 80px;
    }
    
    /* Paksa logo mengecil saat minimize */
    body.mini-sidebar .sidebar-logo img {
        width: 40px !important;
        height: 40px !important;
    }
    
    body.mini-sidebar .app-name, 
    body.mini-sidebar .sidebar-title,
    body.mini-sidebar .sidebar-link span {
        display: none !important;
        opacity: 0;
    }

    body.mini-sidebar .sidebar-header {
        padding: 1rem 0;
    }

    body.mini-sidebar .sidebar-link {
        justify-content: center;
        padding: 12px 0;
    }

    body.mini-sidebar .sidebar-link i {
        margin-right: 0;
        font-size: 1.4rem;
    }

    body.mini-sidebar .sidebar-link::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: 0.3s;
        z-index: 9999;
        margin-left: 10px;
    }

    body.mini-sidebar .sidebar-link:hover::after {
        opacity: 1;
    }

    .btn-logout-sidebar {
        background: #ffeaea !important;
        color: #ff4c4c !important;
    }
    
    .btn-logout-sidebar i {
        color: #ff4c4c !important;
    }

    .btn-logout-sidebar:hover {
        background: #ff4c4c !important;
        color: white !important;
    }
    
    .btn-logout-sidebar:hover i {
        color: white !important;
    }
</style>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-center align-items-center">
                <div class="sidebar-logo">
                    <img src="<?= base_url('uploads/logo/' . $logoOrg) ?>" alt="Logo Organisasi">
                </div>
            </div>
            <span class="app-name"><?= $namaOrg ?></span>
        </div>
        
        <div class="sidebar-menu">
            <ul class="menu">
                
                <li class="sidebar-title">Menu Utama</li>

                <?php if ($role == 'admin') : ?>
                <li class="sidebar-item <?= ($segment == 'dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/dashboard') ?>" class='sidebar-link' data-tooltip="Dashboard">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'organisasi') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/organisasi') ?>" class='sidebar-link' data-tooltip="Profil Organisasi">
                        <i class="bi bi-building"></i>
                        <span>Profil Organisasi</span>
                    </a>
                </li>

                <li class="sidebar-title">Data Master</li>

                <li class="sidebar-item <?= ($segment == 'data-rt') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/data-rt') ?>" class='sidebar-link' data-tooltip="Data RT">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Data RT</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'pengurus') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/pengurus') ?>" class='sidebar-link' data-tooltip="Data Pengurus">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Data Pengurus</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'anggota') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/anggota') ?>" class='sidebar-link' data-tooltip="Data Anggota">
                        <i class="bi bi-people-fill"></i>
                        <span>Data Anggota</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'agenda') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/agenda') ?>" class='sidebar-link' data-tooltip="Agenda Organisasi">
                        <i class="bi bi-calendar-week-fill"></i>
                        <span>Agenda Organisasi</span>
                    </a>
                </li>

                <li class="sidebar-title">Absensi & Laporan</li>

                <li class="sidebar-item">
                    <a href="<?= base_url('scan') ?>" class='sidebar-link' data-tooltip="Scan QR Code">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan Absensi</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'koreksi') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/koreksi') ?>" class='sidebar-link' data-tooltip="Koreksi Absensi">
                        <i class="bi bi-pencil-square"></i>
                        <span>Koreksi Absensi</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'laporan') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/laporan') ?>" class='sidebar-link' data-tooltip="Laporan Kehadiran">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Laporan Kehadiran</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'laporan-agenda') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/laporan-agenda') ?>" class='sidebar-link' data-tooltip="Laporan Agenda">
                        <i class="bi bi-clipboard-data-fill"></i>
                        <span>Laporan Agenda</span>
                    </a>
                </li>

                <li class="sidebar-title">Pengaturan</li>

                <li class="sidebar-item <?= ($segment == 'cetak_kartu') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/cetak_kartu') ?>" class='sidebar-link' data-tooltip="Cetak Kartu">
                        <i class="bi bi-credit-card-2-front-fill"></i>
                        <span>Cetak Kartu</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'setting-jam') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/setting-jam') ?>" class='sidebar-link' data-tooltip="Jam Absensi">
                        <i class="bi bi-clock-fill"></i>
                        <span>Jam Absensi</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'libur-nasional') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/libur-nasional') ?>" class='sidebar-link' data-tooltip="Hari Libur">
                        <i class="bi bi-calendar-x-fill"></i>
                        <span>Hari Libur</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'setting-gps') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/setting-gps') ?>" class='sidebar-link' data-tooltip="Lokasi GPS">
                        <i class="bi bi-geo-fill"></i>
                        <span>Lokasi GPS</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'users') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/users') ?>" class='sidebar-link' data-tooltip="Manajemen User">
                        <i class="bi bi-people-fill"></i>
                        <span>User System</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'setting-print') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/setting-print') ?>" class='sidebar-link' data-tooltip="Setting Print">
                        <i class="bi bi-printer-fill"></i>
                        <span>Setting Print</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'setting-theme') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/setting-theme') ?>" class='sidebar-link' data-tooltip="Tampilan">
                        <i class="bi bi-palette-fill"></i>
                        <span>Tampilan & Tema</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'updater') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/updater') ?>" class='sidebar-link' data-tooltip="Update System">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                        <span>Update System</span>
                    </a>
                </li>

                <?php endif; ?>

                <?php if ($role == 'petugas') : ?>
                <li class="sidebar-item <?= ($segment == 'dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url('petugas/dashboard') ?>" class='sidebar-link' data-tooltip="Dashboard">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="<?= base_url('scan') ?>" class='sidebar-link' data-tooltip="Scan QR Code">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan Absensi</span>
                    </a>
                </li>

                <li class="sidebar-item <?= ($segment == 'data-absensi') ? 'active' : '' ?>">
                    <a href="<?= base_url('petugas/data-absensi') ?>" class='sidebar-link' data-tooltip="Data Absensi">
                        <i class="bi bi-table"></i>
                        <span>Data Absensi</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="sidebar-item mt-3">
                    <a href="<?= base_url('auth/logout') ?>" class='sidebar-link btn-logout-sidebar' data-tooltip="Logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const toggleBtn = document.getElementById('btnToggleSidebar');
        const sidebar = document.getElementById('sidebar');

        function toggleMiniSidebar(forceState = null) {
            if (forceState === true) {
                body.classList.add('mini-sidebar');
            } else if (forceState === false) {
                body.classList.remove('mini-sidebar');
            } else {
                body.classList.toggle('mini-sidebar');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMiniSidebar();
            });
        }
    });
</script>