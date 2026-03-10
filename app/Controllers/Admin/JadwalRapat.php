<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalRapatModel;

class JadwalRapat extends BaseController
{
    protected $jadwalRapatModel;

    public function __construct()
    {
        $this->jadwalRapatModel = new JadwalRapatModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Jadwal Rapat Organisasi',
            'jadwal' => $this->jadwalRapatModel->orderBy('tanggal', 'DESC')->findAll()
        ];
        return view('admin/jadwal_rapat/index', $data);
    }

    public function save()
    {
        $this->jadwalRapatModel->save([
            'nama_rapat' => $this->request->getPost('nama_rapat'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'jam_mulai'  => $this->request->getPost('jam_mulai'),
            'jam_akhir'  => $this->request->getPost('jam_akhir'),
            'peserta'    => $this->request->getPost('peserta')
        ]);
        return redirect()->to('/admin/jadwal-rapat')->with('success', 'Jadwal Rapat berhasil ditambahkan.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->jadwalRapatModel->update($id, [
            'nama_rapat' => $this->request->getPost('nama_rapat'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'jam_mulai'  => $this->request->getPost('jam_mulai'),
            'jam_akhir'  => $this->request->getPost('jam_akhir'),
            'peserta'    => $this->request->getPost('peserta')
        ]);
        return redirect()->to('/admin/jadwal-rapat')->with('success', 'Jadwal Rapat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->jadwalRapatModel->delete($id);
        return redirect()->to('/admin/jadwal-rapat')->with('success', 'Jadwal Rapat berhasil dihapus.');
    }
}