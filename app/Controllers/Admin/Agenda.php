<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AgendaIppmModel;
use App\Models\AgendaMasyarakatModel;

class Agenda extends BaseController
{
    protected $ippmModel;
    protected $masyarakatModel;

    public function __construct()
    {
        $this->ippmModel = new AgendaIppmModel();
        $this->masyarakatModel = new AgendaMasyarakatModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Agenda Organisasi',
            'ippm' => $this->ippmModel->findAll(),
            'masyarakat' => $this->masyarakatModel->orderBy('hari', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/agenda/index', $data);
    }

    public function saveIppm()
    {
        $this->ippmModel->save([
            'nama_agenda' => $this->request->getPost('nama_agenda'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda IPPM berhasil disimpan');
    }

    public function updateIppm()
    {
        $id = $this->request->getPost('id');
        $this->ippmModel->update($id, [
            'nama_agenda' => $this->request->getPost('nama_agenda'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda IPPM diperbarui');
    }

    public function deleteIppm($id)
    {
        $this->ippmModel->delete($id);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda IPPM dihapus');
    }

    public function saveMasyarakat()
    {
        $this->masyarakatModel->save([
            'nama_agenda' => $this->request->getPost('nama_agenda'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda Kemasyarakatan disimpan');
    }

    public function updateMasyarakat()
    {
        $id = $this->request->getPost('id');
        $this->masyarakatModel->update($id, [
            'nama_agenda' => $this->request->getPost('nama_agenda'),
            'hari' => $this->request->getPost('hari'),
            'jam_mulai' => $this->request->getPost('jam_mulai'),
            'jam_akhir' => $this->request->getPost('jam_akhir'),
        ]);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda Kemasyarakatan diperbarui');
    }

    public function deleteMasyarakat($id)
    {
        $this->masyarakatModel->delete($id);
        return redirect()->to('/admin/agenda')->with('success', 'Agenda Kemasyarakatan dihapus');
    }
}