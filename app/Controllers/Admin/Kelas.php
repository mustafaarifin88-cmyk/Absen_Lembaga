<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use ZipArchive;

class Kelas extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->siswaModel = new SiswaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kelas',
            'kelas' => $this->kelasModel->findAll()
        ];
        return view('admin/kelas/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Kelas',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/kelas/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_kelas' => 'required',
            'jurusan'    => 'required'
        ])) {
            return redirect()->to('/admin/kelas/new')->withInput();
        }

        $this->kelasModel->save([
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'jurusan'    => $this->request->getPost('jurusan')
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Kelas',
            'kelas' => $this->kelasModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/kelas/form', $data);
    }

    public function update($id = null)
    {
        if (!$this->validate([
            'nama_kelas' => 'required',
            'jurusan'    => 'required'
        ])) {
            return redirect()->back()->withInput();
        }

        $this->kelasModel->update($id, [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'jurusan'    => $this->request->getPost('jurusan')
        ]);

        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $this->kelasModel->delete($id);
        return redirect()->to('/admin/kelas')->with('success', 'Data kelas berhasil dihapus.');
    }

    public function students($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (!$kelas) return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan');

        $siswa = $this->siswaModel->where('kelas_id', $id)->orderBy('nama_lengkap', 'ASC')->findAll();

        $data = [
            'title' => 'Daftar Siswa - ' . $kelas['nama_kelas'],
            'kelas' => $kelas,
            'siswa' => $siswa
        ];

        return view('admin/kelas/students', $data);
    }

    public function downloadQr($id)
    {
        $kelas = $this->kelasModel->find($id);
        if (!$kelas) return redirect()->back()->with('error', 'Kelas tidak ditemukan');

        $siswa = $this->siswaModel->where('kelas_id', $id)->findAll();
        if (empty($siswa)) return redirect()->back()->with('error', 'Tidak ada siswa di kelas ini');

        $zip = new ZipArchive();
        $zipName = 'QR_Code_' . str_replace(' ', '_', $kelas['nama_kelas']) . '.zip';
        $zipPath = WRITEPATH . 'uploads/' . $zipName;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP');
        }

        $writer = new PngWriter();

        foreach ($siswa as $s) {
            $qrCode = QrCode::create($s['nisn'])->setSize(300)->setMargin(10);
            $result = $writer->write($qrCode);
            
            $fileName = $s['nama_lengkap'] . '_' . $s['nisn'] . '.png';
            
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