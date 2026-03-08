<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->findAll()
        ];
        return view('admin/users/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah User',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/users/create', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'username'     => 'required|is_unique[users.username]',
            'password'     => 'required|min_length[6]',
            'level'        => 'required',
            'foto'         => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/admin/users/new')->withInput();
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'level'        => $this->request->getPost('level'),
            'foto'         => 'default.jpg'
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_profil', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        $this->userModel->insert($data);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit User',
            'user'  => $this->userModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/users/edit', $data);
    }

    public function update($id = null)
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'username'     => 'required|is_unique[users.username,id,' . $id . ']',
            'level'        => 'required',
            'foto'         => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $user = $this->userModel->find($id);
        
        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'level'        => $this->request->getPost('level'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $oldFoto = $user['foto'];
            if ($oldFoto != 'default.jpg' && file_exists('uploads/foto_profil/' . $oldFoto)) {
                unlink('uploads/foto_profil/' . $oldFoto);
            }
            
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_profil', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        
        if ($user['foto'] != 'default.jpg' && file_exists('uploads/foto_profil/' . $user['foto'])) {
            unlink('uploads/foto_profil/' . $user['foto']);
        }
        
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }
}