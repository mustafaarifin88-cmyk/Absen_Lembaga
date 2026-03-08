<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Help extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Panduan Penggunaan Sistem',
        ];
        return view('admin/help/index', $data);
    }
}