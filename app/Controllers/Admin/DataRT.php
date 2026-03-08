<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RtModel;
use App\Models\AnggotaModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use ZipArchive;

class DataRT extends BaseController
{
    protected $rtModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->rtModel = new RtModel();
        $this->anggotaModel = new AnggotaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data RT',
            'rt' => $this->rtModel->findAll()
        ];
        return view('admin/data_rt/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah RT',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_rt/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_rt' => 'required'
        ])) {
            return redirect()->to('/admin/data-rt/new')->withInput();
        }

        $this->rtModel->save([
            'nama_rt' => $this->request->getPost('nama_rt')
        ]);

        return redirect()->to('/admin/data-rt')->with('success', 'Data RT berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit RT',
            'rt' => $this->rtModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/data_rt/form', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_rt' => 'required'
        ])) {
            return redirect()->back()->withInput();
        }

        $this->rtModel->update($id, [
            'nama_rt' => $this->request->getPost('nama_rt')
        ]);

        return redirect()->to('/admin/data-rt')->with('success', 'Data RT berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->rtModel->delete($id);
        return redirect()->to('/admin/data-rt')->with('success', 'Data RT berhasil dihapus.');
    }

    public function members($id)
    {
        $rt = $this->rtModel->find($id);
        if (!$rt) return redirect()->to('/admin/data-rt');

        $anggota = $this->anggotaModel->where('rt_id', $id)->findAll();

        $data = [
            'title' => 'Anggota ' . $rt['nama_rt'],
            'rt' => $rt,
            'anggota' => $anggota
        ];

        return view('admin/data_rt/members', $data);
    }

    public function downloadQr($id)
    {
        $rt = $this->rtModel->find($id);
        if (!$rt) return redirect()->back()->with('error', 'RT tidak ditemukan');

        $anggota = $this->anggotaModel->where('rt_id', $id)->findAll();
        if (empty($anggota)) return redirect()->back()->with('error', 'Tidak ada anggota di RT ini');

        $zip = new ZipArchive();
        $zipName = 'QR_Code_' . str_replace(' ', '_', $rt['nama_rt']) . '.zip';
        $zipPath = WRITEPATH . 'uploads/' . $zipName;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP');
        }

        $writer = new PngWriter();

        foreach ($anggota as $s) {
            $qrContent = "ANGGOTA-" . $s['id'];
            $qrCode = QrCode::create($qrContent)->setSize(300)->setMargin(10);
            $result = $writer->write($qrCode);
            
            $fileName = $s['nama_lengkap'] . '.png';
            
            $zip->addFromString($fileName, $result->getString());
        }

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipName);
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
        unlink($zipPath);
        exit;
    }
}