<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\PengurusModel;
use App\Models\AnggotaModel;
use App\Models\SettingJamModel;

class Absensi extends BaseController
{
    public function edit($id)
    {
        $absensiModel = new AbsensiModel();
        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();

        $absensi = $absensiModel->find($id);

        if (!$absensi) {
            return redirect()->back()->with('error', 'Data absensi tidak ditemukan.');
        }

        $namaUser = 'User Tidak Dikenal';
        
        if ($absensi['user_type'] == 'pengurus') {
            $pengurus = $pengurusModel->find($absensi['user_id']);
            if ($pengurus) {
                $namaUser = $pengurus['nama_lengkap'];
            }
        } else {
            $anggota = $anggotaModel->find($absensi['user_id']);
            if ($anggota) {
                $namaUser = $anggota['nama_lengkap'];
            }
        }

        $data = [
            'title' => 'Edit Absensi Manual',
            'absensi' => $absensi,
            'nama_user' => $namaUser,
            'validation' => \Config\Services::validation()
        ];

        return view('absensi/manual_edit', $data);
    }

    public function updateManual()
    {
        $absensiModel = new AbsensiModel();
        $settingJamModel = new SettingJamModel();

        $id = $this->request->getPost('id');
        $userId = $this->request->getPost('user_id');
        $userType = $this->request->getPost('user_type');
        $tanggal = $this->request->getPost('tanggal');
        $jamMasukInput = $this->request->getPost('jam_masuk');
        $jamPulangInput = $this->request->getPost('jam_pulang');
        $statusInput = $this->request->getPost('status');
        $keteranganInput = $this->request->getPost('keterangan');

        $hariInggris = date('l', strtotime($tanggal));
        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIndo = $mapHari[$hariInggris];

        $jamSetting = $settingJamModel->where('type', $userType)->where('hari', $hariIndo)->first();

        if (in_array($statusInput, ['Hadir', 'Terlambat']) && $jamSetting) {
            $statusInput = 'Hadir'; 
            
            if ($jamMasukInput > $jamSetting['jam_masuk_akhir']) {
                $statusInput = 'Terlambat';
                if (strpos($keteranganInput, 'Terlambat') === false) {
                    $keteranganInput .= " [Terlambat Manual]";
                }
            }
        }

        if (!empty($jamPulangInput) && $jamSetting) {
            if ($jamPulangInput < $jamSetting['jam_pulang_mulai']) {
                if (strpos($keteranganInput, 'Cepat Pulang') === false) {
                    $keteranganInput = trim($keteranganInput . " (Cepat Pulang)");
                }
            }
        }

        if(empty($jamPulangInput)) $jamPulangInput = null;

        $absensiModel->update($id, [
            'jam_masuk' => $jamMasukInput,
            'jam_pulang' => $jamPulangInput,
            'status' => $statusInput,
            'keterangan' => trim($keteranganInput)
        ]);

        return redirect()->to('/admin/koreksi')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function input()
    {
        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();

        $data = [
            'title' => 'Input Absensi Manual',
            'pengurus' => $pengurusModel->findAll(),
            'anggota' => $anggotaModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('absensi/manual_input', $data);
    }

    public function saveManual()
    {
        $absensiModel = new AbsensiModel();
        $settingJamModel = new SettingJamModel();

        $userId = $this->request->getPost('user_id');
        $userType = $this->request->getPost('user_type');
        $tanggal = $this->request->getPost('tanggal');
        $jamMasukInput = $this->request->getPost('jam_masuk');
        $jamPulangInput = $this->request->getPost('jam_pulang');
        $statusInput = $this->request->getPost('status');
        $keteranganInput = $this->request->getPost('keterangan');

        $hariInggris = date('l', strtotime($tanggal));
        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIndo = $mapHari[$hariInggris];

        $jamSetting = $settingJamModel->where('type', $userType)->where('hari', $hariIndo)->first();

        if (in_array($statusInput, ['Hadir', 'Terlambat']) && $jamSetting) {
            $statusInput = 'Hadir'; 
            
            if ($jamMasukInput > $jamSetting['jam_masuk_akhir']) {
                $statusInput = 'Terlambat';
                if (strpos($keteranganInput, 'Terlambat') === false) {
                    $keteranganInput .= " [Terlambat Manual]";
                }
            }
        }

        if (!empty($jamPulangInput) && $jamSetting) {
            if ($jamPulangInput < $jamSetting['jam_pulang_mulai']) {
                if (strpos($keteranganInput, 'Cepat Pulang') === false) {
                    $keteranganInput = trim($keteranganInput . " (Cepat Pulang)");
                }
            }
        }

        if(empty($jamPulangInput)) $jamPulangInput = null;

        $absensiModel->insert([
            'user_id' => $userId,
            'user_type' => $userType,
            'tanggal' => $tanggal,
            'jam_masuk' => $jamMasukInput,
            'jam_pulang' => $jamPulangInput,
            'status' => $statusInput,
            'keterangan' => trim($keteranganInput),
            'lokasi_lat' => '-',
            'lokasi_long' => '-'
        ]);

        return redirect()->to('/admin/koreksi')->with('success', 'Data absensi manual berhasil ditambahkan.');
    }
}