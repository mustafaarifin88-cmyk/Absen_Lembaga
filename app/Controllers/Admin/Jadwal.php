<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KegiatanSholatModel;
use App\Models\KegiatanEkskulModel;

class Jadwal extends BaseController
{
    protected $sholatModel;
    protected $ekskulModel;

    public function __construct()
    {
        $this->sholatModel = new KegiatanSholatModel();
        $this->ekskulModel = new KegiatanEkskulModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Jadwal Sholat & Ekskul',
            'sholat' => $this->sholatModel->findAll(),
            'ekskul' => $this->ekskulModel->orderBy('hari', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/jadwal/index', $data);
    }

    public function saveSholat()
    {
        $this->sholatModel->save([
            'nama_sholat' => $this->request->getPost('nama_sholat'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Sholat disimpan');
    }

    public function updateSholat()
    {
        $id = $this->request->getPost('id');
        $this->sholatModel->update($id, [
            'nama_sholat' => $this->request->getPost('nama_sholat'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Sholat diperbarui');
    }

    public function deleteSholat($id)
    {
        $this->sholatModel->delete($id);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Sholat dihapus');
    }

    public function saveEkskul()
    {
        $this->ekskulModel->save([
            'nama_ekskul' => $this->request->getPost('nama_ekskul'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Ekskul disimpan');
    }

    public function updateEkskul()
    {
        $id = $this->request->getPost('id');
        $this->ekskulModel->update($id, [
            'nama_ekskul' => $this->request->getPost('nama_ekskul'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Ekskul diperbarui');
    }

    public function deleteEkskul($id)
    {
        $this->ekskulModel->delete($id);
        return redirect()->to('/admin/jadwal')->with('success', 'Jadwal Ekskul dihapus');
    }
}