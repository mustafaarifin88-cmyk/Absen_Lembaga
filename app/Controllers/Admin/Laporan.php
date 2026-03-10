<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\OrganisasiModel;
use App\Models\RtModel;
use App\Models\PengurusModel;
use App\Models\AnggotaModel;

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
        
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $rtId = $this->request->getGet('rt_id');

        $laporan = $absensiModel->getLaporan($tglAwal, $tglAkhir, $type, $rtId);

        foreach ($laporan as &$row) {
            if ($type == 'pengurus') {
                $row['nama_lengkap'] = $row['nama_pengurus'];
                $pengurus = (new PengurusModel())->find($row['user_id']);
                $row['jabatan_or_rt'] = $pengurus ? $pengurus['jabatan'] : '-';
            } else {
                $row['nama_lengkap'] = $row['nama_anggota'];
                $row['jabatan_or_rt'] = $row['nama_rt'];
            }
        }

        $data = [
            'title' => 'Cetak Detail Absensi Rapat ' . ucfirst($type),
            'laporan' => $laporan,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'organisasi' => $organisasiModel->first()
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
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $rtId = $this->request->getGet('rt_id');

        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();
        
        $users = [];
        if ($type == 'pengurus') {
            $users = (new PengurusModel())->findAll();
        } else {
            if ($rtId) {
                $users = (new AnggotaModel())->where('rt_id', $rtId)->findAll();
            } else {
                $users = (new AnggotaModel())->findAll();
            }
        }

        $rekap = $absensiModel->getRekapBulanan($bulan, $tahun, $type, $rtId);

        $rapatDatesQuery = $this->db->table('absensi')
            ->select('tanggal')
            ->where('user_type', $type)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('tanggal')
            ->get()->getResultArray();
        $rapatDates = array_column($rapatDatesQuery, 'tanggal');

        $data = [
            'title' => 'Cetak Rekap Absensi Rapat ' . ucfirst($type),
            'users' => $users,
            'rekap' => $rekap,
            'organisasi' => $organisasiModel->first(),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'rapatDates' => $rapatDates,
            'namaBulan' => ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        ];

        return view($view, $data);
    }

    public function cetakMatriksBulan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $rtId = $this->request->getGet('rt_id');
        $userType = $this->request->getGet('user_type') ?? 'anggota';

        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();

        $users = [];
        if ($userType == 'pengurus') {
            $users = (new PengurusModel())->findAll();
        } else {
            if ($rtId) {
                $users = (new AnggotaModel())->where('rt_id', $rtId)->findAll();
            } else {
                $users = (new AnggotaModel())->findAll();
            }
        }

        $rekap = $absensiModel->getRekapBulanan($bulan, $tahun, $userType, $rtId);

        $rapatDatesQuery = $this->db->table('absensi')
            ->select('tanggal')
            ->where('user_type', $userType)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->groupBy('tanggal')
            ->get()->getResultArray();
        $rapatDates = array_column($rapatDatesQuery, 'tanggal');

        $rtName = '';
        if ($rtId) {
            $rtData = (new RtModel())->find($rtId);
            if ($rtData) $rtName = $rtData['nama_rt'];
        }

        $data = [
            'users' => $users,
            'rekap' => $rekap,
            'organisasi' => $organisasiModel->first(),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'rapatDates' => $rapatDates,
            'info_rt' => $rtName,
            'namaBulan' => ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        ];

        return view('admin/laporan/cetak_matriks_bulan', $data);
    }

    public function cetakMatriksTahun()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $rtId = $this->request->getGet('rt_id');
        $userType = $this->request->getGet('user_type') ?? 'anggota';

        $absensiModel = new AbsensiModel();
        $organisasiModel = new OrganisasiModel();

        $users = [];
        if ($userType == 'pengurus') {
            $users = (new PengurusModel())->findAll();
        } else {
            if ($rtId) {
                $users = (new AnggotaModel())->where('rt_id', $rtId)->findAll();
            } else {
                $users = (new AnggotaModel())->findAll();
            }
        }

        $rekapTahun = $absensiModel->getRekapTahunan($tahun, $userType, $rtId);

        $rapatCountPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $count = $this->db->table('absensi')
                ->select('tanggal')
                ->where('user_type', $userType)
                ->where('MONTH(tanggal)', $m)
                ->where('YEAR(tanggal)', $tahun)
                ->groupBy('tanggal')
                ->countAllResults();
            $rapatCountPerMonth[$m] = $count;
        }

        $rtName = '';
        if ($rtId) {
            $rtData = (new RtModel())->find($rtId);
            if ($rtData) $rtName = $rtData['nama_rt'];
        }

        $data = [
            'users' => $users,
            'rekap' => $rekapTahun,
            'organisasi' => $organisasiModel->first(),
            'tahun' => $tahun,
            'rapatCountPerMonth' => $rapatCountPerMonth,
            'info_rt' => $rtName
        ];

        return view('admin/laporan/cetak_matriks_tahun', $data);
    }
}