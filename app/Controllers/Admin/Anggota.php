<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\RtModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Anggota extends BaseController
{
    protected $anggotaModel;
    protected $rtModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
        $this->rtModel = new RtModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Anggota',
            'anggota' => $this->anggotaModel->getAnggotaWithRt()
        ];
        return view('admin/anggota/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Anggota',
            'rt' => $this->rtModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/anggota/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_lengkap'  => 'required',
            'rt_id'         => 'required',
            'foto'          => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/admin/anggota/new')->withInput();
        }

        // Handle Foto
        $namaFoto = 'default.jpg';
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $pathFoto = FCPATH . 'uploads/foto_anggota/';
            if (!is_dir($pathFoto)) mkdir($pathFoto, 0755, true); // Buat folder jika belum ada
            $fileFoto->move($pathFoto, $namaFoto);
        }

        // Simpan Data Awal
        $this->anggotaModel->save([
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'rt_id'         => $this->request->getPost('rt_id'),
            'qr_code'       => null, 
            'foto'          => $namaFoto
        ]);

        $insertId = $this->anggotaModel->getInsertID();
        
        // Generate QR Code
        $qrContent = "ANGGOTA-" . $insertId;
        $writer = new PngWriter();
        $qrCode = QrCode::create($qrContent)->setSize(300)->setMargin(10);
        $result = $writer->write($qrCode);
        
        $qrName = 'QR_A_' . $insertId . '.png';
        $qrPath = FCPATH . 'uploads/qr/';
        
        // Pastikan folder QR ada
        if (!is_dir($qrPath)) {
            mkdir($qrPath, 0755, true);
        }
        
        $result->saveToFile($qrPath . $qrName);

        // Update QR Name ke Database
        $this->anggotaModel->update($insertId, ['qr_code' => $qrName]);

        return redirect()->to('/admin/anggota')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Anggota',
            'anggota' => $this->anggotaModel->find($id),
            'rt' => $this->rtModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/anggota/form', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_lengkap'  => 'required',
            'rt_id'         => 'required',
            'foto'          => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $anggota = $this->anggotaModel->find($id);
        $namaFoto = $anggota['foto'];
        
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if ($namaFoto != 'default.jpg' && file_exists(FCPATH . 'uploads/foto_anggota/' . $namaFoto)) {
                unlink(FCPATH . 'uploads/foto_anggota/' . $namaFoto);
            }
            $namaFoto = $fileFoto->getRandomName();
            $pathFoto = FCPATH . 'uploads/foto_anggota/';
            if (!is_dir($pathFoto)) mkdir($pathFoto, 0755, true);
            $fileFoto->move($pathFoto, $namaFoto);
        }

        $this->anggotaModel->update($id, [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'rt_id'         => $this->request->getPost('rt_id'),
            'foto'          => $namaFoto
        ]);

        return redirect()->to('/admin/anggota')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function delete($id)
    {
        $anggota = $this->anggotaModel->find($id);
        if ($anggota['foto'] != 'default.jpg' && file_exists(FCPATH . 'uploads/foto_anggota/' . $anggota['foto'])) {
            unlink(FCPATH . 'uploads/foto_anggota/' . $anggota['foto']);
        }
        if ($anggota['qr_code'] && file_exists(FCPATH . 'uploads/qr/' . $anggota['qr_code'])) {
            unlink(FCPATH . 'uploads/qr/' . $anggota['qr_code']);
        }

        $this->anggotaModel->delete($id);
        return redirect()->to('/admin/anggota')->with('success', 'Data anggota berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        // Bersihkan output buffer agar file excel tidak corrupt
        if (ob_get_length()) ob_end_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Nama Lengkap');
        $sheet->setCellValue('B1', 'ID RT');

        $rtData = $this->rtModel->findAll();
        $row = 2;
        $sheet->setCellValue('D1', 'Referensi ID RT (Jangan Dihapus):');
        foreach ($rtData as $r) {
            $sheet->setCellValue('D' . $row, $r['id'] . ' : ' . $r['nama_rt']);
            $row++;
        }

        // Auto size kolom
        foreach(range('A','D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_import_anggota.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit; // Penting untuk menghentikan script
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                $jumlahSukses = 0;
                $batchData = [];

                foreach ($data as $key => $row) {
                    if ($key == 0) continue; 

                    $nama  = $row[0];
                    $rtId = $row[1];

                    if ($nama && $rtId) {
                        $this->anggotaModel->save([
                            'nama_lengkap'  => $nama,
                            'rt_id'         => $rtId,
                            'qr_code'       => null,
                            'foto'          => 'default.jpg'
                        ]);

                        // Generate QR Code saat import
                        $insertId = $this->anggotaModel->getInsertID();
                        $qrContent = "ANGGOTA-" . $insertId;
                        
                        $writer = new PngWriter();
                        $qrCode = QrCode::create($qrContent)->setSize(300)->setMargin(10);
                        $result = $writer->write($qrCode);
                        
                        $qrName = 'QR_A_' . $insertId . '.png';
                        $qrPath = FCPATH . 'uploads/qr/';
                        if (!is_dir($qrPath)) mkdir($qrPath, 0755, true);
                        
                        $result->saveToFile($qrPath . $qrName);
                        $this->anggotaModel->update($insertId, ['qr_code' => $qrName]);

                        $jumlahSukses++;
                    }
                }

                return redirect()->to('/admin/anggota')->with('success', "Import berhasil! $jumlahSukses data anggota diproses.");

            } catch (\Exception $e) {
                return redirect()->to('/admin/anggota')->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/anggota')->with('error', 'Tidak ada file yang diupload.');
    }
}