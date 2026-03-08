<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\PengurusModel;
use App\Models\RtModel;
use App\Models\OrganisasiModel;

class CetakKartu extends BaseController
{
    protected $anggotaModel;
    protected $pengurusModel;
    protected $rtModel;
    protected $organisasiModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
        $this->pengurusModel = new PengurusModel();
        $this->rtModel = new RtModel();
        $this->organisasiModel = new OrganisasiModel();
    }

    public function index()
    {
        // Ambil data anggota lengkap dengan nama RT
        $anggotaData = $this->anggotaModel
            ->select('anggota.*, rt.nama_rt')
            ->join('rt', 'rt.id = anggota.rt_id', 'left')
            ->findAll();

        return view('admin/cetak_kartu/index', [
            'title' => 'Cetak Kartu Absensi',
            'rt' => $this->rtModel->findAll(),
            'pengurus' => $this->pengurusModel->findAll(),
            'anggota' => $anggotaData,
            'organisasi' => $this->organisasiModel->first(),
            'segment' => 'cetak_kartu'
        ]);
    }
}