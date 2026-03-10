<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\RtModel;

class DataAbsensi extends BaseController
{
    public function index()
    {
        $absensiModel = new AbsensiModel();
        $rtModel = new RtModel();
        
        $tab = $this->request->getGet('tab') ?? 'pengurus';
        $tglAwal = $this->request->getGet('tgl_awal') ?? date('Y-m-d');
        $tglAkhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-d');
        $rtId = $this->request->getGet('rt_id');

        $absensi = [];
        if ($tab == 'pengurus') {
            $absensi = $absensiModel->getLaporan($tglAwal, $tglAkhir, 'pengurus');
        } elseif ($tab == 'anggota') {
            $absensi = $absensiModel->getLaporan($tglAwal, $tglAkhir, 'anggota', $rtId);
        }

        $data = [
            'title' => 'Data Absensi Rapat',
            'rt' => $rtModel->findAll(),
            'tab' => $tab,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'rt_id' => $rtId,
            'absensi' => $absensi
        ];

        return view('petugas/data_absensi', $data);
    }
}