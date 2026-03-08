<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengurusModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Pengurus extends BaseController
{
    protected $pengurusModel;

    public function __construct()
    {
        $this->pengurusModel = new PengurusModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pengurus',
            'pengurus'  => $this->pengurusModel->findAll()
        ];
        return view('admin/pengurus/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Pengurus',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/pengurus/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'jabatan'   => 'required',
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/admin/pengurus/new')->withInput();
        }

        $namaFoto = 'default.jpg';
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $pathFoto = FCPATH . 'uploads/foto_pengurus/';
            if (!is_dir($pathFoto)) mkdir($pathFoto, 0755, true);
            $fileFoto->move($pathFoto, $namaFoto);
        }

        $this->pengurusModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'qr_code'   => null,
            'foto'      => $namaFoto
        ]);

        $insertId = $this->pengurusModel->getInsertID();
        $qrContent = "PENGURUS-" . $insertId;

        $writer = new PngWriter();
        $qrCode = QrCode::create($qrContent)->setSize(300)->setMargin(10);
        $result = $writer->write($qrCode);
        
        $qrName = 'QR_P_' . $insertId . '.png';
        $qrPath = FCPATH . 'uploads/qr/';
        
        // FIX: Buat folder jika belum ada
        if (!is_dir($qrPath)) {
            mkdir($qrPath, 0755, true);
        }

        $result->saveToFile($qrPath . $qrName);

        $this->pengurusModel->update($insertId, ['qr_code' => $qrName]);

        return redirect()->to('/admin/pengurus')->with('success', 'Data pengurus berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Pengurus',
            'pengurus'  => $this->pengurusModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/pengurus/form', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_lengkap' => 'required',
            'jabatan'   => 'required',
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $pengurus = $this->pengurusModel->find($id);
        $namaFoto = $pengurus['foto'];
        
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if ($namaFoto != 'default.jpg' && file_exists(FCPATH . 'uploads/foto_pengurus/' . $namaFoto)) {
                unlink(FCPATH . 'uploads/foto_pengurus/' . $namaFoto);
            }
            $namaFoto = $fileFoto->getRandomName();
            $pathFoto = FCPATH . 'uploads/foto_pengurus/';
            if (!is_dir($pathFoto)) mkdir($pathFoto, 0755, true);
            $fileFoto->move($pathFoto, $namaFoto);
        }

        $this->pengurusModel->update($id, [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'foto'      => $namaFoto
        ]);

        return redirect()->to('/admin/pengurus')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function delete($id)
    {
        $pengurus = $this->pengurusModel->find($id);
        
        if ($pengurus['foto'] != 'default.jpg' && file_exists(FCPATH . 'uploads/foto_pengurus/' . $pengurus['foto'])) {
            unlink(FCPATH . 'uploads/foto_pengurus/' . $pengurus['foto']);
        }
        if ($pengurus['qr_code'] && file_exists(FCPATH . 'uploads/qr/' . $pengurus['qr_code'])) {
            unlink(FCPATH . 'uploads/qr/' . $pengurus['qr_code']);
        }

        $this->pengurusModel->delete($id);
        return redirect()->to('/admin/pengurus')->with('success', 'Data pengurus berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        // FIX: Bersihkan buffer output
        if (ob_get_length()) ob_end_clean();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Nama Lengkap');
        $sheet->setCellValue('B1', 'Jabatan');
        
        foreach(range('A','B') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'template_import_pengurus.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
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

                foreach ($data as $key => $row) {
                    if ($key == 0) continue;

                    $nama     = $row[0];
                    $jabatan = $row[1];

                    if ($nama && $jabatan) {
                        $this->pengurusModel->save([
                            'nama_lengkap' => $nama,
                            'jabatan'    => $jabatan,
                            'qr_code'    => null,
                            'foto'       => 'default.jpg'
                        ]);

                        // Generate QR saat import
                        $insertId = $this->pengurusModel->getInsertID();
                        $qrContent = "PENGURUS-" . $insertId;

                        $writer = new PngWriter();
                        $qrCode = QrCode::create($qrContent)->setSize(300)->setMargin(10);
                        $result = $writer->write($qrCode);
                        
                        $qrName = 'QR_P_' . $insertId . '.png';
                        $qrPath = FCPATH . 'uploads/qr/';
                        if (!is_dir($qrPath)) mkdir($qrPath, 0755, true);
                        
                        $result->saveToFile($qrPath . $qrName);
                        $this->pengurusModel->update($insertId, ['qr_code' => $qrName]);

                        $jumlahSukses++;
                    }
                }

                return redirect()->to('/admin/pengurus')->with('success', "Import berhasil! $jumlahSukses data pengurus diproses.");

            } catch (\Exception $e) {
                return redirect()->to('/admin/pengurus')->with('error', 'Gagal membaca file: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/pengurus')->with('error', 'Tidak ada file yang diupload.');
    }
}