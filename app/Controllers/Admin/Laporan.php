<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\OrganisasiModel;
use App\Models\RtModel;
use App\Models\PengurusModel;
use App\Models\AnggotaModel;
use App\Models\SettingHariModel;
use App\Models\LiburNasionalModel;

class Laporan extends BaseController
{
    public function index()
    {
        $rtModel = new RtModel();
        $data = [
            'title' => 'Pusat Laporan Anggota',
            'rt' => $rtModel->findAll()
        ];
        return view('admin/laporan/index', $data);
    }

    public function filter()
    {
        $type = $this->request->getGet('type');
        $rtModel = new RtModel();
        
        $data = [
            'title' => 'Filter Laporan',
            'type' => $type,
            'rt' => $rtModel->findAll()
        ];

        return view('admin/laporan/filter', $data);
    }

    public function cetakPengurusDetail()
    {
        return $this->processDetail('pengurus', 'admin/laporan/cetak_pengurus_detail');
    }

    public function cetakAnggotaDetail()
    {
        return $this->processDetail('anggota', 'admin/laporan/cetak_anggota_detail');
    }

    private function processDetail($type, $view)
    {
        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();

        $tglAwal = $this->request->getPost('tgl_awal');
        $tglAkhir = $this->request->getPost('tgl_akhir');
        $rtId = $this->request->getPost('rt_id');

        $dataLaporan = $absensiModel->getLaporan($tglAwal, $tglAkhir, $type, $rtId);
        $organisasi = $organisasiModel->first();

        $data = [
            'laporan' => $dataLaporan,
            'organisasi' => $organisasi,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir
        ];

        return view($view, $data);
    }

    public function cetakPengurusRekap()
    {
        return $this->processRekap('pengurus', 'admin/laporan/cetak_pengurus_rekap');
    }

    public function cetakAnggotaRekap()
    {
        return $this->processRekap('anggota', 'admin/laporan/cetak_anggota_rekap');
    }

    private function processRekap($type, $view)
    {
        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();
        $rtModel = new RtModel();
        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();
        $liburModel = new LiburNasionalModel();

        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $rtId = $this->request->getPost('rt_id');

        $rekapAbsen = $absensiModel->getRekapBulanan($bulan, $tahun, $type, $rtId);
        $organisasi = $organisasiModel->first();

        $users = [];
        $infoRT = '';

        if ($type == 'pengurus') {
            $users = $pengurusModel->findAll();
        } else {
            if ($rtId) {
                $users = $anggotaModel->where('rt_id', $rtId)->findAll();
                $rt = $rtModel->find($rtId);
                $infoRT = $rt ? $rt['nama_rt'] : '';
            } else {
                $users = $anggotaModel->findAll();
            }
        }

        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        // Ambil Setting Hari (Mana yg Libur Rutin)
        $settingHariModel = new SettingHariModel();
        $settings = $settingHariModel->findAll();
        $hariEfektifNames = [];
        foreach($settings as $s) {
            if($s['tampilkan'] == 1) $hariEfektifNames[] = $s['nama_hari'];
        }
        
        // Ambil Libur Nasional
        $tglAwalBulan = "$tahun-$bulan-01";
        $tglAkhirBulan = date('Y-m-t', strtotime($tglAwalBulan));
        
        $liburData = $liburModel->where('tanggal_akhir >=', $tglAwalBulan)
                                ->where('tanggal_mulai <=', $tglAkhirBulan)
                                ->findAll();
        
        $listLiburNasional = [];
        foreach ($liburData as $l) {
            $period = new \DatePeriod(
                new \DateTime($l['tanggal_mulai']),
                new \DateInterval('P1D'),
                (new \DateTime($l['tanggal_akhir']))->modify('+1 day')
            );
            foreach ($period as $dt) {
                if ($dt->format('n') == $bulan && $dt->format('Y') == $tahun) {
                    $listLiburNasional[] = $dt->format('Y-m-d');
                }
            }
        }

        $data = [
            'users' => $users,
            'rekap' => $rekapAbsen,
            'organisasi' => $organisasi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'jumlah_hari' => cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun),
            'info_rt' => $infoRT,
            'hari_efektif' => $hariEfektifNames, 
            'libur_nasional' => $listLiburNasional,
            'namaBulan' => $namaBulan
        ];

        return view($view, $data);
    }

    public function cetakMatriksBulanan()
    {
        return $this->processRekap('anggota', 'admin/laporan/cetak_matriks_bulan');
    }

    public function cetakMatriksTahunan()
    {
        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();
        $rtModel = new RtModel();
        $anggotaModel = new AnggotaModel();
        $liburModel = new LiburNasionalModel();
        $settingHariModel = new SettingHariModel();

        $tahun = $this->request->getPost('tahun');
        $rtId = $this->request->getPost('rt_id');

        $users = [];
        $infoRT = '';
        if ($rtId) {
            $users = $anggotaModel->where('rt_id', $rtId)->findAll();
            $rt = $rtModel->find($rtId);
            $infoRT = $rt ? $rt['nama_rt'] : '';
        } else {
            $users = $anggotaModel->findAll();
        }

        $rekapTahun = $absensiModel->getRekapTahunan($tahun, 'anggota', $rtId);
        $organisasi = $organisasiModel->first();

        // --- Perbaikan Logika Hitung Hari Efektif Tahunan ---
        
        // 1. Ambil Hari Libur Rutin (Minggu, dll)
        $settingHari = $settingHariModel->findAll();
        $hariLiburRutin = []; 
        foreach($settingHari as $h) {
            if($h['tampilkan'] == 0) $hariLiburRutin[] = $h['nama_hari'];
        }

        // 2. Ambil Libur Nasional setahun penuh
        $liburNasional = $liburModel->where('YEAR(tanggal_mulai)', $tahun)
                                    ->orWhere('YEAR(tanggal_akhir)', $tahun)
                                    ->findAll();
        
        $arrLiburNasional = [];
        foreach ($liburNasional as $l) {
            $period = new \DatePeriod(
                new \DateTime($l['tanggal_mulai']),
                new \DateInterval('P1D'),
                (new \DateTime($l['tanggal_akhir']))->modify('+1 day')
            );
            foreach ($period as $dt) {
                if ($dt->format('Y') == $tahun) {
                    $arrLiburNasional[] = $dt->format('Y-m-d');
                }
            }
        }

        $effectiveDaysPerMonth = [];
        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        for($m=1; $m<=12; $m++) {
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $tahun);
            $effCount = 0;
            for($d=1; $d<=$daysInMonth; $d++) {
                // Gunakan format Y-m-d yang baku
                $currentDate = sprintf("%04d-%02d-%02d", $tahun, $m, $d);
                $dayNameInggris = date('l', strtotime($currentDate));
                $dayNameIndo = $mapHari[$dayNameInggris];

                // Cek Libur Rutin
                if(in_array($dayNameIndo, $hariLiburRutin)) {
                    continue; // Skip jika hari ini memang libur rutin (misal Minggu)
                }

                // Cek Libur Nasional
                if(in_array($currentDate, $arrLiburNasional)) {
                    continue; // Skip jika hari ini tanggal merah
                }

                $effCount++;
            }
            $effectiveDaysPerMonth[$m] = $effCount;
        }
        // -----------------------------------------------------------

        $data = [
            'users' => $users,
            'rekap' => $rekapTahun,
            'organisasi' => $organisasi,
            'tahun' => $tahun,
            'info_rt' => $infoRT,
            'effective_days' => $effectiveDaysPerMonth
        ];

        return view('admin/laporan/cetak_matriks_tahun', $data);
    }
}