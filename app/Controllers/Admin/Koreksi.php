<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\RtModel;

class Koreksi extends BaseController
{
    public function index()
    {
        $rtModel = new RtModel();
        $data = [
            'title' => 'Koreksi Kehadiran Rapat',
            'rt' => $rtModel->findAll(),
            'tampil_data' => false,
            'active_tab' => 'pengurus'
        ];
        return view('admin/koreksi/index', $data);
    }

    public function filter()
    {
        $absensiModel = new AbsensiModel();
        $rtModel = new RtModel();

        $userType = $this->request->getGet('user_type');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $rtId = $this->request->getGet('rt_id');

        $dataLaporan = [];
        if ($userType && $tglAwal && $tglAkhir) {
            $dataLaporan = $absensiModel->getKoreksiData($tglAwal, $tglAkhir, $userType, $rtId);
        }

        $data = [
            'title' => 'Koreksi Kehadiran Rapat',
            'rt' => $rtModel->findAll(),
            'laporan' => $dataLaporan,
            'tampil_data' => true,
            'active_tab' => $userType,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'rt_id' => $rtId
        ];

        return view('admin/koreksi/index', $data);
    }

    public function bulkAction()
    {
        $action = $this->request->getPost('action_type');
        $selectedData = $this->request->getPost('selected_data'); 
        $userType = $this->request->getPost('user_type');

        if (empty($selectedData)) {
            return redirect()->back()->with('error', 'Pilih minimal satu data untuk diproses.');
        }

        $status = $this->request->getPost('status');
        $jamMasukInput = $this->request->getPost('jam_masuk');
        $jamPulangInput = $this->request->getPost('jam_pulang');
        $keterangan = $this->request->getPost('keterangan');

        $absensiModel = new AbsensiModel();
        $countSuccess = 0;

        foreach ($selectedData as $dataItem) {
            list($userId, $tanggal) = explode('|', $dataItem);

            $existing = $absensiModel->where('user_id', $userId)
                                     ->where('user_type', $userType)
                                     ->where('tanggal', $tanggal)
                                     ->first();

            if ($action == 'delete') {
                if ($existing) {
                    $absensiModel->delete($existing['id']);
                    $countSuccess++;
                }
            } elseif ($action == 'update') {
                
                $data = [
                    'status' => $status,
                    'keterangan' => $keterangan
                ];

                if ($status == 'Hadir' || $status == 'Terlambat') {
                    $data['jam_masuk'] = !empty($jamMasukInput) ? $jamMasukInput : '07:00:00';
                    $data['jam_pulang'] = !empty($jamPulangInput) ? $jamPulangInput : null;
                } else {
                    $data['jam_masuk'] = null;
                    $data['jam_pulang'] = null;
                }

                if ($existing) {
                    $absensiModel->update($existing['id'], $data);
                } else {
                    $data['user_id'] = $userId;
                    $data['user_type'] = $userType;
                    $data['tanggal'] = $tanggal;
                    $data['lokasi_lat'] = '-';
                    $data['lokasi_long'] = '-';
                    $absensiModel->insert($data);
                }
                $countSuccess++;
            }
        }

        $msg = ($action == 'delete') ? "$countSuccess Data berhasil dihapus (Di-reset)." : "$countSuccess Data berhasil diperbarui.";
        return redirect()->back()->with('success', $msg);
    }
}