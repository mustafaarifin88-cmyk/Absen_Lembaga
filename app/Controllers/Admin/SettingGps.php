<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingGpsModel;

class SettingGps extends BaseController
{
    public function index()
    {
        $gpsModel = new SettingGpsModel();
        $setting = $gpsModel->first();

        $data = [
            'title' => 'Setting Lokasi Absensi',
            'setting' => $setting,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/setting_gps/index', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'radius_meter' => 'required|integer'
        ])) {
            return redirect()->back()->withInput();
        }

        $gpsModel = new SettingGpsModel();
        $setting = $gpsModel->first();

        $data = [
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius_meter' => $this->request->getPost('radius_meter')
        ];

        if ($setting) {
            $gpsModel->update($setting['id'], $data);
        } else {
            $gpsModel->insert($data);
        }

        return redirect()->to('/admin/setting-gps')->with('success', 'Pengaturan lokasi berhasil disimpan.');
    }
}