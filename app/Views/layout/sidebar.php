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
$namaOrg = ($orgData && !empty($orgData['nama_organisasi'])) ? $orgData['nama_organisasi'] : 'Sistem Absensi';
?>

<style>
    .sidebar-wrapper {
        background: <?= $sidebarBg ?> !important;
        background-size: cover !important;
        background-position: center !important;
        box-shadow: 4px 0 25px rgba(0, 0, 0, 0.05);
        border-radius: 0 24px 24px 0;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
    }
    
    .sidebar-wrapper::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.85);
        z-index: -1;
        border-radius: 0 24px 24px 0;
    }

    body.theme-dark .sidebar-wrapper::before {
        background: rgba(30, 30, 45, 0.85);
    }

    .sidebar-header {
        padding: 30px 25px 20px 25px;
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-container img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .logo-text {
        font-weight: 800;
        font-size: 1.1rem;
        line-height: 1.2;
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    body.theme-dark .logo-text {
        background: linear-gradient(135deg, #fff 0%, #a0a0a0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .user-profile-sidebar {
        background: linear-gradient(135deg, rgba(67, 94, 190, 0.05) 0%, rgba(67, 94, 190, 0.1) 100%);
        padding: 20px;
        border-radius: 16px;
        margin: 0 20px 20px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(67, 94, 190, 0.1);
        transition: all 0.3s ease;
    }

    .user-profile-sidebar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 94, 190, 0.1);
    }

    .user-profile-sidebar img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .user-info-sidebar h6 {
        margin: 0;
        font-weight: 800;
        font-size: 0.95rem;
        color: #25396f;
    }

    body.theme-dark .user-info-sidebar h6 {
        color: #fff;
    }

    .user-info-sidebar p {
        margin: 0;
        font-size: 0.75rem;
        color: #607080;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar-menu {
        padding: 0 15px;
    }

    .sidebar-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #a0a0a0;
        margin: 25px 0 10px 20px;
    }

    .menu .sidebar-item {
        margin-bottom: 5px;
    }

    .menu .sidebar-link {
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
        color: #4a5568;
        font-weight: 600;
    }

    .menu .sidebar-link i {
        font-size: 1.2rem;
        transition: all 0.3s ease;
        color: #8c98a4;
    }

    .menu .sidebar-item.active > .sidebar-link {
        background: linear-gradient(135deg, #435ebe 0%, #30458b 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(67, 94, 190, 0.25);
    }

    .menu .sidebar-item.active > .sidebar-link i {
        color: #fff;
    }

    .menu .sidebar-item:not(.active) > .sidebar-link:hover {
        background: rgba(67, 94, 190, 0.08);
        color: #435ebe;
        transform: translateX(5px);
    }

    .menu .sidebar-item:not(.active) > .sidebar-link:hover i {
        color: #435ebe;
    }

    .submenu {
        background: transparent !important;
        padding-left: 45px !important;
        margin-top: 5px;
    }

    .submenu .submenu-item a {
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s ease;
        display: block;
        position: relative;
    }

    .submenu .submenu-item a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 6px;
        height: 6px;
        background: #ced4da;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .submenu .submenu-item.active a {
        color: #435ebe;
        font-weight: 700;
        background: rgba(67, 94, 190, 0.05);
    }

    .submenu .submenu-item.active a::before {
        background: #435ebe;
        width: 8px;
        height: 8px;
        box-shadow: 0 0 10px rgba(67, 94, 190, 0.5);
    }

    .submenu .submenu-item a:hover {
        color: #435ebe;
        padding-left: 20px;
    }

    .submenu .submenu-item a:hover::before {
        background: #435ebe;
    }

    .btn-logout-sidebar {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        margin-top: 30px;
    }

    .btn-logout-sidebar i {
        color: #dc3545 !important;
    }

    .btn-logout-sidebar:hover {
        background: #dc3545 !important;
        color: #fff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.25) !important;
    }

    .btn-logout-sidebar:hover i {
        color: #fff !important;
    }

    .theme-toggle {
        background: rgba(0,0,0,0.05);
        padding: 5px 15px;
        border-radius: 50px;
    }
    body.theme-dark .theme-toggle {
        background: rgba(255,255,255,0.1);
    }
</style>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <a href="<?= base_url($role.'/dashboard') ?>" class="logo-container text-decoration-none">
                    <img src="<?= base_url('uploads/logo/' . $logoOrg) ?>" alt="Logo">
                    <div class="logo-text"><?= esc($namaOrg) ?></div>
                </a>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                <div class="theme-toggle d-flex gap-2 align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="18" height="18" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21"><g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path><g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g></g></svg>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" class="iconify iconify--mdi" width="18" height="18" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path></svg>
                </div>
            </div>
        </div>

        <a href="<?= base_url($role == 'admin' ? 'admin/profil' : '#') ?>" class="text-decoration-none">
            <div class="user-profile-sidebar">
                <img src="<?= base_url($fotoPath) ?>" alt="User Avatar">
                <div class="user-info-sidebar">
                    <h6><?= esc(session()->get('nama')) ?></h6>
                    <p><?= esc($role) ?></p>
                </div>
            </div>
        </a>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Navigasi</li>
                
                <?php if($role == 'admin'): ?>
                <li class="sidebar-item <?= ($segment == 'dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/dashboard') ?>" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-item <?= in_array($segment, ['organisasi', 'data-rt', 'pengurus', 'anggota', 'cetak-kartu']) ? 'active' : '' ?> has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-database-fill-gear"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['organisasi', 'data-rt', 'pengurus', 'anggota', 'cetak-kartu']) ? 'active' : '' ?>">
                        <li class="submenu-item <?= ($segment == 'organisasi') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/organisasi') ?>">Profil Organisasi</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'data-rt') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/data-rt') ?>">Data Divisi / RT</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'pengurus') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/pengurus') ?>">Data Pengurus</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'anggota') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/anggota') ?>">Data Anggota</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'cetak-kartu') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/cetak_kartu') ?>">Cetak Kartu QR</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item <?= in_array($segment, ['agenda', 'jadwal-rapat']) ? 'active' : '' ?> has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-calendar2-week-fill"></i>
                        <span>Kegiatan & Agenda</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['agenda', 'jadwal-rapat']) ? 'active' : '' ?>">
                        <li class="submenu-item <?= ($segment == 'agenda') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/agenda') ?>">Agenda Kegiatan</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'jadwal-rapat') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/jadwal-rapat') ?>">Jadwal Rapat</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-title">Laporan & Koreksi</li>
                
                <li class="sidebar-item <?= in_array($segment, ['laporan', 'koreksi']) ? 'active' : '' ?> has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        <span>Absensi Rapat</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['laporan', 'koreksi']) ? 'active' : '' ?>">
                        <li class="submenu-item <?= ($segment == 'laporan') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/laporan') ?>">Pusat Laporan Rapat</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'koreksi') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/koreksi') ?>">Koreksi Data Rapat</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item <?= in_array($segment, ['laporan-agenda', 'koreksi-agenda']) ? 'active' : '' ?> has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-clipboard-check-fill"></i>
                        <span>Laporan Agenda</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['laporan-agenda', 'koreksi-agenda']) ? 'active' : '' ?>">
                        <li class="submenu-item <?= ($segment == 'laporan-agenda') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/laporan-agenda') ?>">Cetak Laporan Agenda</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'koreksi-agenda') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/koreksi-agenda') ?>">Koreksi Absen Agenda</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-title">Konfigurasi</li>

                <li class="sidebar-item <?= in_array($segment, ['setting-jam', 'setting-gps', 'whatsapp', 'setting-theme', 'users', 'updater', 'help']) ? 'active' : '' ?> has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-gear-wide-connected"></i>
                        <span>Sistem & Akses</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['setting-jam', 'setting-gps', 'whatsapp', 'setting-theme', 'users', 'updater', 'help']) ? 'active' : '' ?>">
                        <li class="submenu-item <?= ($segment == 'setting-jam') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/setting-jam') ?>">Aturan Jam Absen</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'setting-gps') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/setting-gps') ?>">Lokasi & Radius GPS</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'whatsapp') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/whatsapp') ?>">Server WhatsApp</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'setting-theme') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/setting-theme') ?>">Tampilan & Tema</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'users') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/users') ?>">Manajemen Akun</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'updater') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/updater') ?>">Update Aplikasi</a>
                        </li>
                        <li class="submenu-item <?= ($segment == 'help') ? 'active' : '' ?>">
                            <a href="<?= base_url('admin/help') ?>">Bantuan / Panduan</a>
                        </li>
                    </ul>
                </li>
                
                <?php else: ?>
                <li class="sidebar-item <?= ($segment == 'dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url('petugas/dashboard') ?>" class='sidebar-link' data-tooltip="Dashboard">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item <?= ($segment == 'data-absensi') ? 'active' : '' ?>">
                    <a href="<?= base_url('petugas/data-absensi') ?>" class='sidebar-link' data-tooltip="Data Absensi Rapat">
                        <i class="bi bi-table"></i>
                        <span>Data Absensi Rapat</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="sidebar-item">
                    <a href="<?= base_url('auth/logout') ?>" class='sidebar-link btn-logout-sidebar' data-tooltip="Keluar dari Aplikasi">
                        <i class="bi bi-power"></i>
                        <span>Logout System</span>
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