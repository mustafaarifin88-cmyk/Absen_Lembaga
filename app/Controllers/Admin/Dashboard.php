<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengurusModel;
use App\Models\AnggotaModel;
use App\Models\AbsensiModel;
use App\Models\RtModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();
        $absensiModel = new AbsensiModel();
        $rtModel = new RtModel();
        
        $tanggalHariIni = date('Y-m-d');

        $data = [
            'title' => 'Dashboard Admin',
            'total_pengurus' => $pengurusModel->countAllResults(),
            'total_anggota' => $anggotaModel->countAllResults(),
            'total_rt' => $rtModel->countAllResults(),
            
            'hadir_hari_ini' => $absensiModel->where('tanggal', $tanggalHariIni)
                                             ->where('status', 'Hadir')
                                             ->countAllResults(),
                                             
            'terlambat_hari_ini' => $absensiModel->where('tanggal', $tanggalHariIni)
                                                 ->where('status', 'Terlambat')
                                                 ->countAllResults(),
                                                 
            'cepat_pulang_hari_ini' => $absensiModel->where('tanggal', $tanggalHariIni)
                                                    ->where('status', 'Cepat Pulang')
                                                    ->countAllResults(),
                                                    
            'izin_sakit_hari_ini' => $absensiModel->where('tanggal', $tanggalHariIni)
                                                  ->groupStart()
                                                      ->where('status', 'Izin')
                                                      ->orWhere('status', 'Sakit')
                                                  ->groupEnd()
                                                  ->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
}