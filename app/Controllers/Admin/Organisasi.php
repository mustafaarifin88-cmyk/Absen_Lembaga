<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrganisasiModel;

class Organisasi extends BaseController
{
    public function index()
    {
        $organisasiModel = new OrganisasiModel();
        $organisasi = $organisasiModel->first();

        $data = [
            'title' => 'Profil Organisasi',
            'organisasi' => $organisasi,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/organisasi/index', $data);
    }

    public function update()
    {
        $organisasiModel = new OrganisasiModel();
        $organisasi = $organisasiModel->first();
        $id = $organisasi['id'];

        if (!$this->validate([
            'nama_organisasi' => 'required',
            'alamat_lengkap' => 'required',
            'kabupaten' => 'required',
            'kepala_instansi' => 'required',
            'logo' => 'max_size[logo,2048]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $data = [
            'nama_organisasi' => $this->request->getPost('nama_organisasi'),
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'kabupaten' => $this->request->getPost('kabupaten'),
            'kepala_instansi' => $this->request->getPost('kepala_instansi'),
        ];

        $fileLogo = $this->request->getFile('logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $oldLogo = $organisasi['logo'];
            if ($oldLogo != 'default_logo.png' && file_exists('uploads/logo/' . $oldLogo)) {
                unlink('uploads/logo/' . $oldLogo);
            }

            $namaLogo = $fileLogo->getRandomName();
            $fileLogo->move('uploads/logo', $namaLogo);
            $data['logo'] = $namaLogo;
        }

        $organisasiModel->update($id, $data);

        return redirect()->to('/admin/organisasi')->with('success', 'Profil organisasi berhasil diperbarui.');
    }
}