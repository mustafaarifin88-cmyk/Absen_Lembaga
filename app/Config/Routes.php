<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::index');

$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('process', 'Auth::process');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Cetak Kartu
    $routes->get('cetak_kartu', 'Admin\CetakKartu::index');
    $routes->post('cetak_kartu/proses_pengurus', 'Admin\CetakKartu::prosesPengurus');
    $routes->post('cetak-kartu/proses-pengurus', 'Admin\CetakKartu::prosesPengurus');
    $routes->post('cetak_kartu/proses_anggota', 'Admin\CetakKartu::prosesAnggota');
    $routes->post('cetak-kartu/proses-anggota', 'Admin\CetakKartu::prosesAnggota');
    
    // Profil Admin
    $routes->get('profil', 'Admin\Profil::index');
    $routes->post('profil/update', 'Admin\Profil::update');
    
    // Manajemen Users (Admin/Petugas)
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/new', 'Admin\Users::new');
    $routes->post('users/create', 'Admin\Users::create');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');

    // Profil Organisasi (Ex Sekolah)
    $routes->get('organisasi', 'Admin\Organisasi::index');
    $routes->post('organisasi/update', 'Admin\Organisasi::update');

    // Setting GPS
    $routes->get('setting-gps', 'Admin\SettingGps::index');
    $routes->post('setting-gps/save', 'Admin\SettingGps::save');

    // Setting Jam (Pengurus & Anggota)
    $routes->get('setting-jam', 'Admin\SettingJam::index');
    $routes->post('setting-jam/update', 'Admin\SettingJam::update');

    // Setting Tema
    $routes->get('setting-theme', 'Admin\SettingTheme::index');
    $routes->post('setting-theme/update', 'Admin\SettingTheme::update');

    // Setting Print
    $routes->get('setting-print', 'Admin\SettingPrint::index');
    $routes->post('setting-print/save', 'Admin\SettingPrint::save');

    // Hari Libur
    $routes->get('libur-nasional', 'Admin\LiburNasional::index');
    $routes->get('libur-nasional/new', 'Admin\LiburNasional::new');
    $routes->post('libur-nasional/create', 'Admin\LiburNasional::create');
    $routes->get('libur-nasional/delete/(:num)', 'Admin\LiburNasional::delete/$1');

    // Data Pengurus (Ex Guru)
    $routes->get('pengurus', 'Admin\Pengurus::index');
    $routes->get('pengurus/new', 'Admin\Pengurus::new');
    $routes->post('pengurus/create', 'Admin\Pengurus::create');
    $routes->get('pengurus/edit/(:num)', 'Admin\Pengurus::edit/$1');
    $routes->post('pengurus/update/(:num)', 'Admin\Pengurus::update/$1');
    $routes->get('pengurus/delete/(:num)', 'Admin\Pengurus::delete/$1');
    $routes->post('pengurus/import', 'Admin\Pengurus::import');
    $routes->get('pengurus/download-template', 'Admin\Pengurus::downloadTemplate');

    // Data Anggota (Ex Siswa)
    $routes->get('anggota', 'Admin\Anggota::index');
    $routes->get('anggota/new', 'Admin\Anggota::new');
    $routes->post('anggota/create', 'Admin\Anggota::create');
    $routes->get('anggota/edit/(:num)', 'Admin\Anggota::edit/$1');
    $routes->post('anggota/update/(:num)', 'Admin\Anggota::update/$1');
    $routes->get('anggota/delete/(:num)', 'Admin\Anggota::delete/$1');
    $routes->post('anggota/import', 'Admin\Anggota::import');
    $routes->get('anggota/download-template', 'Admin\Anggota::downloadTemplate');

    // Data RT (Ex Kelas)
    $routes->get('data-rt', 'Admin\DataRT::index');
    $routes->get('data-rt/new', 'Admin\DataRT::new');
    $routes->post('data-rt/create', 'Admin\DataRT::create');
    $routes->get('data-rt/edit/(:num)', 'Admin\DataRT::edit/$1');
    $routes->post('data-rt/update/(:num)', 'Admin\DataRT::update/$1');
    $routes->get('data-rt/delete/(:num)', 'Admin\DataRT::delete/$1');
    $routes->get('data-rt/members/(:num)', 'Admin\DataRT::members/$1');
    $routes->get('data-rt/download-qr/(:num)', 'Admin\DataRT::downloadQr/$1');

    // Agenda (Ex Jadwal)
    $routes->get('agenda', 'Admin\Agenda::index');
    $routes->post('agenda/save-ippm', 'Admin\Agenda::saveIppm');
    $routes->post('agenda/update-ippm', 'Admin\Agenda::updateIppm');
    $routes->get('agenda/delete-ippm/(:num)', 'Admin\Agenda::deleteIppm/$1');
    $routes->post('agenda/save-masyarakat', 'Admin\Agenda::saveMasyarakat');
    $routes->post('agenda/update-masyarakat', 'Admin\Agenda::updateMasyarakat');
    $routes->get('agenda/delete-masyarakat/(:num)', 'Admin\Agenda::deleteMasyarakat/$1');

    // Laporan Kehadiran
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/filter', 'Admin\Laporan::filter'); // <-- Tambahkan Baris Ini
    $routes->post('laporan/cetak-pengurus-rekap', 'Admin\Laporan::cetakPengurusRekap');
    $routes->post('laporan/cetak-pengurus-detail', 'Admin\Laporan::cetakPengurusDetail');
    $routes->post('laporan/cetak-anggota-rekap', 'Admin\Laporan::cetakAnggotaRekap');
    $routes->post('laporan/cetak-anggota-detail', 'Admin\Laporan::cetakAnggotaDetail');
    // Matriks Report (Baru)
    $routes->post('laporan/cetak-matriks-bulanan', 'Admin\Laporan::cetakMatriksBulanan');
    $routes->post('laporan/cetak-matriks-tahunan', 'Admin\Laporan::cetakMatriksTahunan');

    // Laporan Agenda (Ex Laporan Kegiatan)
    $routes->get('laporan-agenda', 'Admin\LaporanAgenda::index');
    $routes->post('laporan-agenda/cetak', 'Admin\LaporanAgenda::cetak');

    // Koreksi Kehadiran
    $routes->get('koreksi', 'Admin\Koreksi::index');
    $routes->get('koreksi/filter', 'Admin\Koreksi::filter');
    $routes->post('koreksi/bulkAction', 'Admin\Koreksi::bulkAction');
    $routes->get('koreksi/delete/(:num)', 'Admin\Koreksi::delete/$1');

    // Edit Absensi Manual
    $routes->get('absensi/edit/(:num)', 'Admin\Absensi::edit/$1');
    $routes->post('absensi/update-manual', 'Admin\Absensi::updateManual');
    $routes->get('absensi/input', 'Admin\Absensi::input');
    $routes->post('absensi/save-manual', 'Admin\Absensi::saveManual');

    // Koreksi Agenda
    $routes->get('koreksi-agenda', 'Admin\LaporanAgenda::koreksi');
    $routes->post('koreksi-agenda/save', 'Admin\LaporanAgenda::saveKoreksi');
    $routes->get('koreksi-agenda/delete/(:num)', 'Admin\LaporanAgenda::deleteKoreksi/$1');

    // Updater
    $routes->get('updater', 'Admin\Updater::index');
    $routes->get('updater/init', 'Admin\Updater::initUpdate');
    $routes->get('updater/extract', 'Admin\Updater::extractFiles');
    $routes->get('updater/status', 'Admin\Updater::checkStatus');

    // Help
    $routes->get('help', 'Admin\Help::index');
});

$routes->group('petugas', ['filter' => 'auth:petugas'], function($routes) {
    $routes->get('dashboard', 'Petugas\Dashboard::index');
    $routes->get('data-absensi', 'Petugas\DataAbsensi::index');
});

// Route umum untuk Scan
$routes->get('scan', 'Absensi::scanPage', ['filter' => 'auth']);
$routes->post('absensi/process-scan', 'Absensi::processScan');
$routes->post('absensi/process-scan-agenda', 'Absensi::processScanAgenda');

$routes->get('tools', 'Tools::index');