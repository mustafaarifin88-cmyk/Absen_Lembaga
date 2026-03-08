<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel; // Menggunakan UserModel

class Auth extends BaseController
{
    public function __construct()
    {
        helper(['theme']);
    }

    public function index()
    {
        if (session()->get('logged_in')) {
            if (session()->get('level') == 'admin') {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/petugas/dashboard');
            }
        }
        return view('auth/login');
    }

    public function process()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $dataUser = $userModel->where('username', $username)->first();

        if ($dataUser) {
            if (password_verify($password, $dataUser['password'])) {
                session()->set([
                    'id'       => $dataUser['id'],
                    'username' => $dataUser['username'],
                    'nama'     => $dataUser['nama_lengkap'],
                    'level'    => $dataUser['level'],
                    'foto'     => $dataUser['foto'],
                    'logged_in'=> true
                ]);
                
                if ($dataUser['level'] == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/petugas/dashboard');
                }
            } else {
                return redirect()->back()->with('error', 'Password Salah');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}