<?php
    $jam = date('H');
    if ($jam >= 5 && $jam < 11) {
        $sapaan = "Selamat Pagi";
        $iconSapaan = "bi-sun-fill text-warning";
    } elseif ($jam >= 11 && $jam < 15) {
        $sapaan = "Selamat Siang";
        $iconSapaan = "bi-brightness-high-fill text-warning";
    } elseif ($jam >= 15 && $jam < 18) {
        $sapaan = "Selamat Sore";
        $iconSapaan = "bi-sunset-fill text-warning";
    } else {
        $sapaan = "Selamat Malam";
        $iconSapaan = "bi-moon-stars-fill text-primary";
    }
    
    $hariIni = date('d F Y');
    $namaUser = strtok(session()->get('nama'), " ");
?>

<style>
    .navbar-glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 20px;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .navbar-glass:hover {
        box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.15);
    }

    .btn-toggle-sidebar {
        border: none;
        background: transparent;
        color: #435ebe;
        font-size: 1.5rem;
        transition: transform 0.2s;
    }
    
    .btn-toggle-sidebar:hover {
        transform: scale(1.1);
    }

    .user-img-nav {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border: 2px solid #435ebe;
        padding: 2px;
    }

    .custom-dropdown-menu {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 15px;
        margin-top: 15px;
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.2s;
        font-weight: 600;
        color: #607080;
    }

    .dropdown-item:hover {
        background-color: #f2f7ff;
        color: #435ebe;
        transform: translateX(5px);
    }
    
    .date-badge {
        background: rgba(67, 94, 190, 0.1);
        color: #435ebe;
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<header>
    <nav class="navbar navbar-expand navbar-light navbar-glass">
        <div class="container-fluid">
            
            <a href="#" class="burger-btn d-block d-xl-none me-3">
                <i class="bi bi-justify fs-3 text-primary"></i>
            </a>

            <button id="btnToggleSidebar" class="btn-toggle-sidebar d-none d-xl-block me-3">
                <i class="bi bi-grid-fill"></i>
            </button>

            <div class="d-none d-md-flex align-items-center">
                <div class="me-3">
                    <i class="<?= $iconSapaan ?> fs-4 align-middle me-2"></i>
                    <span class="fw-bold text-gray-600"><?= $sapaan ?>, <?= $namaUser ?>!</span>
                </div>
                <div class="date-badge">
                    <i class="bi bi-calendar-event-fill"></i> <?= $hariIni ?>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="ms-auto dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" class="d-flex align-items-center text-decoration-none">
                        <div class="user-menu d-flex align-items-center">
                            <div class="user-name text-end me-3 d-none d-lg-block">
                                <h6 class="mb-0 text-gray-600 fw-bold"><?= session()->get('nama') ?></h6>
                                <p class="mb-0 text-sm text-gray-500 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?= session()->get('level') ?></p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="<?= base_url('uploads/foto_profil/' . (session()->get('foto') ? session()->get('foto') : 'default.jpg')) ?>" class="rounded-circle user-img-nav">
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header text-gray-400 text-uppercase fs-7 ls-1">Account</h6>
                        </li>
                        
                        <?php if(session()->get('level') == 'admin'): ?>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/profil') ?>">
                                <i class="bi bi-person-circle me-3 text-primary"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('admin/setting-gps') ?>">
                                <i class="bi bi-geo-alt-fill me-3 text-warning"></i> Lokasi Kantor
                            </a>
                        </li>
                        <?php endif; ?>

                        <li><hr class="dropdown-divider border-light"></li>
                        
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="<?= base_url('auth/logout') ?>">
                                <i class="bi bi-box-arrow-right me-3"></i> Log Out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>