<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SekolahModel;

class Sekolah extends BaseController
{
    public function index()
    {
        $sekolahModel = new SekolahModel();
        $sekolah = $sekolahModel->first();

        $data = [
            'title' => 'Profil Sekolah',
            'sekolah' => $sekolah,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/sekolah/index', $data);
    }

    public function update()
    {
        $sekolahModel = new SekolahModel();
        $sekolah = $sekolahModel->first();
        $id = $sekolah['id'];

        if (!$this->validate([
            'nama_sekolah' => 'required',
            'alamat_lengkap' => 'required',
            'kabupaten' => 'required',
            'kepala_sekolah' => 'required',
            'nip_kepsek' => 'required',
            'logo' => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_sekolah' => $this->request->getPost('nama_sekolah'),
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'kepala_sekolah' => $this->request->getPost('kepala_sekolah'),
            'nip_kepsek' => $this->request->getPost('nip_kepsek'),
        ];

        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $oldLogo = $sekolah['logo'];
            if ($oldLogo != 'default_logo.png' && file_exists('uploads/logo/' . $oldLogo)) {
                unlink('uploads/logo/' . $oldLogo);
            }

            $namaLogo = $fileLogo->getRandomName();
            $fileLogo->move('uploads/logo', $namaLogo);
            $data['logo'] = $namaLogo;
        }

        $sekolahModel->update($id, $data);

        return redirect()->to('/admin/sekolah')->with('success', 'Profil sekolah berhasil diperbarui.');
    }
}