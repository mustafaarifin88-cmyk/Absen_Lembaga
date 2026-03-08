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
            'title' => 'Koreksi Kehadiran Anggota',
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
            'title' => 'Koreksi Kehadiran',
            'rt' => $rtModel->findAll(),
            'tampil_data' => true,
            'laporan' => $dataLaporan,
            'user_type' => $userType,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'rt_id' => $rtId,
            'active_tab' => $userType
        ];

        return view('admin/koreksi/index', $data);
    }

    public function delete($id)
    {
        $absensiModel = new AbsensiModel();
        $absensiModel->delete($id);
        return redirect()->back()->with('success', 'Data absensi berhasil direset (Kembali ke Alfa).');
    }

    public function bulkAction()
    {
        $absensiModel = new AbsensiModel();
        
        $action = $this->request->getPost('action');
        $selectedIds = $this->request->getPost('selected_id'); 
        $userType = $this->request->getPost('user_type');
        $tanggal = $this->request->getPost('tanggal');
        
        $status = $this->request->getPost('status');
        $jamMasukInput = $this->request->getPost('jam_masuk');
        $jamPulangInput = $this->request->getPost('jam_pulang');
        $keterangan = $this->request->getPost('keterangan');

        if (!$selectedIds) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $countSuccess = 0;

        foreach ($selectedIds as $userId) {
            
            $existing = $absensiModel->where('user_type', $userType)
                                     ->where('user_id', $userId)
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
                    
                    if (!empty($jamPulangInput)) {
                        $data['jam_pulang'] = $jamPulangInput;
                    } else {
                        $data['jam_pulang'] = null; 
                    }
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

        $msg = ($action == 'delete') ? "$countSuccess Data berhasil dihapus (Reset ke Alfa)." : "$countSuccess Data berhasil diperbarui.";
        return redirect()->back()->with('success', $msg);
    }
}