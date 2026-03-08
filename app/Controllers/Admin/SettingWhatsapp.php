<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingWhatsappModel;

class SettingWhatsapp extends BaseController
{
    public function index()
    {
        $model = new SettingWhatsappModel();
        
        // Ambil data pertama
        $setting = $model->first();

        // Jika belum ada data, buat array kosong default
        if (!$setting) {
            $setting = [
                'id' => null,
                'wa_gateway_url' => '',
                'wa_api_token' => ''
            ];
        }

        $data = [
            'title' => 'Konfigurasi WhatsApp Gateway',
            'setting' => $setting,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/setting_whatsapp/index', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'wa_gateway_url' => 'required|valid_url',
            'wa_api_token'   => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'URL tidak valid atau data kurang lengkap.');
        }

        $model = new SettingWhatsappModel();
        $setting = $model->first();

        $data = [
            'wa_gateway_url' => $this->request->getPost('wa_gateway_url'),
            'wa_api_token'   => $this->request->getPost('wa_api_token'),
        ];

        if ($setting) {
            $model->update($setting['id'], $data);
        } else {
            $model->insert($data);
        }

        return redirect()->to('/admin/setting-whatsapp')->with('success', 'URL Gateway berhasil diperbarui!');
    }
}