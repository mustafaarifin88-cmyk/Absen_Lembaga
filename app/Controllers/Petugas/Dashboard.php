<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $model = new AbsensiModel();
        $today = date('Y-m-d');

        $data = [
            'title' => 'Dashboard Petugas',
            'total_hadir' => $model->where('tanggal', $today)->where('status', 'Hadir')->countAllResults(),
            'total_terlambat' => $model->where('tanggal', $today)->where('status', 'Terlambat')->countAllResults(),
            'total_cepat_pulang' => $model->where('tanggal', $today)->where('status', 'Cepat Pulang')->countAllResults(),
            'total_izin_sakit' => $model->where('tanggal', $today)->groupStart()->where('status', 'Izin')->orWhere('status', 'Sakit')->groupEnd()->countAllResults(),
        ];

        return view('petugas/dashboard', $data);
    }
}