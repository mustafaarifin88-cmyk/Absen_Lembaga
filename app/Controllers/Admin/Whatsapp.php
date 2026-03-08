<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingWhatsappModel; // Load Model Setting

class Whatsapp extends BaseController
{
    public function index()
    {
        $model = new SettingWhatsappModel();
        $setting = $model->first();

        // Jika belum ada setting, default ke localhost
        $gatewayUrl = 'http://localhost:3000/api/send-message';
        
        if ($setting && !empty($setting['wa_gateway_url'])) {
            $gatewayUrl = $setting['wa_gateway_url'];
        }

        // Ubah endpoint '/send-message' menjadi '/status' untuk pengecekan
        // Contoh: https://ngrok.app/api/send-message -> https://ngrok.app/api/status
        $statusUrl = str_replace('/send-message', '/status', $gatewayUrl);

        $data = [
            'title' => 'Server WhatsApp',
            'status_url' => $statusUrl // Kirim URL dinamis ke View
        ];
        
        return view('admin/whatsapp/index', $data);
    }
}