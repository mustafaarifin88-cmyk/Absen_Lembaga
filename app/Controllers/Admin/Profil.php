<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('id');

        if (!$userId) {
            return redirect()->to('/auth/logout');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/logout');
        }

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/profil/index', $data);
    }

    public function update()
    {
        $userId = $this->request->getPost('id');
        
        if (!$userId) {
            return redirect()->back()->with('error', 'ID User tidak valid.');
        }

        $userLama = $this->userModel->find($userId);
        if (!$userLama) {
            return redirect()->back()->with('error', 'User tidak ditemukan di database.');
        }

        // Aturan Validasi
        $rules = [
            'nama_lengkap' => [
                'rules' => 'required',
                'errors' => ['required' => 'Nama lengkap wajib diisi.']
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username,id,' . $userId . ']',
                'errors' => [
                    'required' => 'Username wajib diisi.',
                    'is_unique' => 'Username ini sudah dipakai user lain.'
                ]
            ],
            'foto' => [
                'rules' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto maksimal 2MB.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG.'
                ]
            ]
        ];

        // Validasi Password jika diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = [
                'rules' => 'min_length[4]',
                'errors' => ['min_length' => 'Password minimal 4 karakter.']
            ];
            $rules['password_confirm'] = [
                'rules' => 'matches[password]',
                'errors' => ['matches' => 'Konfirmasi password tidak sesuai.']
            ];
        }

        if (!$this->validate($rules)) {
            // Tampilkan error validasi agar admin tahu
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . implode(', ', $validation->getErrors()));
        }

        // Handle Upload Foto
        $fileFoto = $this->request->getFile('foto');
        $namaFoto = $userLama['foto'];

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Hapus foto lama jika bukan default
            if ($userLama['foto'] != 'default.jpg' && file_exists('uploads/foto_profil/' . $userLama['foto'])) {
                @unlink('uploads/foto_profil/' . $userLama['foto']);
            }
            
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_profil', $namaFoto);
        }

        // Data yang akan diupdate
        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'foto'         => $namaFoto
        ];

        // Update password jika diisi
        $passBaru = $this->request->getPost('password');
        if (!empty($passBaru)) {
            $dataUpdate['password'] = password_hash($passBaru, PASSWORD_DEFAULT);
        }

        // Eksekusi Update
        $updated = $this->userModel->update($userId, $dataUpdate);

        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ke database.');
        }

        // Update Session agar sidebar langsung berubah
        session()->set([
            'id'       => $userId,
            'nama'     => $dataUpdate['nama_lengkap'],
            'username' => $dataUpdate['username'],
            'foto'     => $namaFoto,
            'level'    => $userLama['level'], // Pertahankan level lama
            'logged_in'=> true
        ]);

        return redirect()->to('/admin/profil')->with('success', 'Profil berhasil diperbarui.');
    }
}