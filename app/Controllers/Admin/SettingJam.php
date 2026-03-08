<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingJamModel;

class SettingJam extends BaseController
{
    protected $settingJamModel;

    public function __construct()
    {
        $this->settingJamModel = new SettingJamModel();
    }

    public function index()
    {
        $settings = $this->settingJamModel->findAll();
        
        $pengurusSettings = [];
        $anggotaSettings = [];
        
        $urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        foreach ($settings as $row) {
            if ($row['type'] == 'pengurus') {
                $pengurusSettings[$row['hari']] = $row;
            } else {
                $anggotaSettings[$row['hari']] = $row;
            }
        }

        $data = [
            'title' => 'Setting Jam Absensi Per Hari',
            'pengurus_settings' => $pengurusSettings,
            'anggota_settings' => $anggotaSettings,
            'urutanHari' => $urutanHari,
            'validation' => \Config\Services::validation()
        ];
        return view('admin/setting_jam/index', $data);
    }

    public function update()
    {
        $postData = $this->request->getPost('settings');

        if ($postData) {
            $batchUpdate = [];
            foreach ($postData as $id => $fields) {
                $batchUpdate[] = [
                    'id' => $id,
                    'jam_masuk_mulai'  => $fields['jam_masuk_mulai'],
                    'jam_masuk_akhir'  => $fields['jam_masuk_akhir'],
                    'jam_pulang_mulai' => $fields['jam_pulang_mulai'],
                    'jam_pulang_akhir' => $fields['jam_pulang_akhir'],
                ];
            }

            if (!empty($batchUpdate)) {
                $this->settingJamModel->updateBatch($batchUpdate, 'id');
            }

            return redirect()->to('/admin/setting-jam')->with('success', 'Pengaturan jam berhasil disimpan.');
        }

        return redirect()->to('/admin/setting-jam')->with('error', 'Tidak ada data yang disimpan.');
    }
}