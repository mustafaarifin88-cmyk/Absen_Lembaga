<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/');
        }

        if (session()->get('level') == 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/petugas/dashboard');
    }
}