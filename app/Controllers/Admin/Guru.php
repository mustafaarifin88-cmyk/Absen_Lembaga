<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Guru extends BaseController
{
    protected $guruModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Guru',
            'guru'  => $this->guruModel->findAll()
        ];
        return view('admin/guru/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Guru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/guru/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nama_guru' => 'required',
            'jabatan'   => 'required',
            'no_wa'     => 'required',
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/admin/guru/new')->withInput();
        }

        $nip = $this->request->getPost('nip');
        if (empty($nip)) {
            $nip = '-';
        }

        $fileFoto = $this->request->getFile('foto');
        $namaFoto = 'default.jpg';

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_guru', $namaFoto);
        }

        $this->guruModel->save([
            'nip'       => $nip,
            'nama_guru' => $this->request->getPost('nama_guru'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'no_wa'     => $this->request->getPost('no_wa'),
            'foto'      => $namaFoto
        ]);

        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Guru',
            'guru'  => $this->guruModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/guru/form', $data);
    }

    public function update($id = null)
    {
        if (!$this->validate([
            'nama_guru' => 'required',
            'jabatan'   => 'required',
            'no_wa'     => 'required',
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $nip = $this->request->getPost('nip');
        if (empty($nip)) {
            $nip = '-';
        }

        $guruLama = $this->guruModel->find($id);
        $namaFoto = $guruLama['foto'];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if ($guruLama['foto'] != 'default.jpg' && file_exists('uploads/foto_guru/' . $guruLama['foto'])) {
                unlink('uploads/foto_guru/' . $guruLama['foto']);
            }
            
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_guru', $namaFoto);
        }

        $this->guruModel->update($id, [
            'nip'       => $nip,
            'nama_guru' => $this->request->getPost('nama_guru'),
            'jabatan'   => $this->request->getPost('jabatan'),
            'no_wa'     => $this->request->getPost('no_wa'),
            'foto'      => $namaFoto
        ]);

        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $guru = $this->guruModel->find($id);
        
        if ($guru['qr_code'] && file_exists('uploads/qr/' . $guru['qr_code'])) {
            unlink('uploads/qr/' . $guru['qr_code']);
        }

        if ($guru['foto'] != 'default.jpg' && file_exists('uploads/foto_guru/' . $guru['foto'])) {
            unlink('uploads/foto_guru/' . $guru['foto']);
        }
        
        $this->guruModel->delete($id);
        return redirect()->to('/admin/guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function generateQr($id)
    {
        $guru = $this->guruModel->find($id);
        if (!$guru) {
            return redirect()->to('/admin/guru')->with('error', 'Guru tidak ditemukan.');
        }

        $codeContents = $guru['nip'];
        if ($codeContents == '-' || empty($codeContents)) {
            $codeContents = 'GURU-' . $guru['id'];
        }

        $writer = new PngWriter();
        $qrCode = QrCode::create($codeContents)
            ->setSize(300)
            ->setMargin(10);

        $result = $writer->write($qrCode);

        $fileName = 'qr_guru_' . $guru['id'] . '.png';
        $path = FCPATH . 'uploads/qr/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $result->saveToFile($path . $fileName);

        $this->guruModel->update($id, ['qr_code' => $fileName]);

        return redirect()->to('/admin/guru')->with('success', 'QR Code berhasil digenerate.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Guru');
        
        $headers = ['NIP (Boleh Kosong)', 'Nama Lengkap', 'Jabatan', 'No WA (628xxx)'];
        $sheet->fromArray([$headers], NULL, 'A1');

        $styleArray = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF435EBE']],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($styleArray);
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setCellValue('A2', '198501012010011001');
        $sheet->setCellValue('B2', 'Budi Santoso, S.Pd');
        $sheet->setCellValue('C2', 'Guru Matematika');
        $sheet->setCellValue('D2', '6281234567890');

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Guru.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');

        if ($file) {
            $ext = $file->getClientExtension();
            if (!in_array($ext, ['xls', 'xlsx'])) {
                return redirect()->to('/admin/guru')->with('error', 'Format file harus .xls atau .xlsx');
            }

            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                $jumlahSukses = 0;
                $batchData = [];

                foreach ($data as $key => $row) {
                    if ($key == 0) continue;

                    $nip      = $row[0];
                    $nama     = $row[1];
                    $jabatan = $row[2];
                    $wa       = $row[3];

                    if ($nama && $jabatan && $wa) {
                        $wa = preg_replace('/[^0-9]/', '', $wa);
                        if (substr($wa, 0, 1) == '0') $wa = '62' . substr($wa, 1);

                        if (empty($nip)) $nip = '-';

                        $batchData[] = [
                            'nip'        => $nip,
                            'nama_guru' => $nama,
                            'jabatan'    => $jabatan,
                            'no_wa'      => $wa,
                            'qr_code'    => null,
                            'foto'       => 'default.jpg'
                        ];
                        $jumlahSukses++;
                    }
                }

                if (!empty($batchData)) {
                    $this->guruModel->ignore(true)->insertBatch($batchData);
                }

                return redirect()->to('/admin/guru')->with('success', "Import berhasil! $jumlahSukses data guru diproses.");

            } catch (\Exception $e) {
                return redirect()->to('/admin/guru')->with('error', 'Gagal membaca file: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/guru')->with('error', 'Tidak ada file yang diupload.');
    }
}