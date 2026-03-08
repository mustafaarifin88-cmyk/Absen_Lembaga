<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Siswa',
            'siswa' => $this->siswaModel->getSiswaWithKelas()
        ];
        return view('admin/siswa/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Siswa',
            'kelas' => $this->kelasModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'nisn'          => 'required|is_unique[siswa.nisn]',
            'nama_lengkap'  => 'required',
            'kelas_id'      => 'required',
            'no_wa_ortu'    => 'required',
            'foto'          => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/admin/siswa/new')->withInput();
        }

        $fileFoto = $this->request->getFile('foto');
        $namaFoto = 'default.jpg';

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_siswa', $namaFoto);
        }

        $this->siswaModel->save([
            'nisn'          => $this->request->getPost('nisn'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'no_wa_ortu'    => $this->request->getPost('no_wa_ortu'),
            'foto'          => $namaFoto
        ]);

        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Siswa',
            'siswa' => $this->siswaModel->find($id),
            'kelas' => $this->kelasModel->findAll(),
            'validation' => \Config\Services::validation()
        ];
        return view('admin/siswa/form', $data);
    }

    public function update($id = null)
    {
        if (!$this->validate([
            'nisn'          => 'required|is_unique[siswa.nisn,id,' . $id . ']',
            'nama_lengkap'  => 'required',
            'kelas_id'      => 'required',
            'no_wa_ortu'    => 'required',
            'foto'          => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->withInput();
        }

        $siswaLama = $this->siswaModel->find($id);
        $namaFoto = $siswaLama['foto'];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            if ($siswaLama['foto'] != 'default.jpg' && file_exists('uploads/foto_siswa/' . $siswaLama['foto'])) {
                unlink('uploads/foto_siswa/' . $siswaLama['foto']);
            }

            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto_siswa', $namaFoto);
        }

        $this->siswaModel->update($id, [
            'nisn'          => $this->request->getPost('nisn'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'kelas_id'      => $this->request->getPost('kelas_id'),
            'no_wa_ortu'    => $this->request->getPost('no_wa_ortu'),
            'foto'          => $namaFoto
        ]);

        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil diupdate.');
    }

    public function delete($id = null)
    {
        $siswa = $this->siswaModel->find($id);
        
        if ($siswa['qr_code'] && file_exists('uploads/qr/' . $siswa['qr_code'])) {
            unlink('uploads/qr/' . $siswa['qr_code']);
        }
        
        if ($siswa['foto'] != 'default.jpg' && file_exists('uploads/foto_siswa/' . $siswa['foto'])) {
            unlink('uploads/foto_siswa/' . $siswa['foto']);
        }

        $this->siswaModel->delete($id);
        return redirect()->to('/admin/siswa')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function generateQr($id)
    {
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan.');
        }

        $codeContents = $siswa['nisn'];
        
        $writer = new PngWriter();
        $qrCode = QrCode::create($codeContents)
            ->setSize(300)
            ->setMargin(10);

        $result = $writer->write($qrCode);

        $fileName = 'qr_siswa_' . $siswa['id'] . '.png';
        $path = FCPATH . 'uploads/qr/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $result->saveToFile($path . $fileName);

        $this->siswaModel->update($id, ['qr_code' => $fileName]);

        return redirect()->to('/admin/siswa')->with('success', 'QR Code berhasil digenerate.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');
        
        $headers = ['NISN', 'Nama Lengkap', 'ID Kelas', 'No WA Ortu (628xxx)'];
        $sheet->fromArray([$headers], NULL, 'A1');

        $styleArray = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF435EBE']],
        ];
        $sheet->getStyle('A1:D1')->applyFromArray($styleArray);
        
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setCellValue('A2', '1234567890');
        $sheet->setCellValue('B2', 'Siswa Contoh');
        $sheet->setCellValue('C2', '1'); 
        $sheet->setCellValue('D2', '6281234567890');

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Referensi ID Kelas');
        
        $sheet2->setCellValue('A1', 'ID Kelas');
        $sheet2->setCellValue('B1', 'Nama Kelas');
        $sheet2->setCellValue('C1', 'Jurusan');
        $sheet2->getStyle('A1:C1')->applyFromArray($styleArray);

        $dataKelas = $this->kelasModel->findAll();
        $row = 2;
        foreach ($dataKelas as $k) {
            $sheet2->setCellValue('A' . $row, $k['id']);
            $sheet2->setCellValue('B' . $row, $k['nama_kelas']);
            $sheet2->setCellValue('C' . $row, $k['jurusan']);
            $row++;
        }
        foreach (range('A', 'C') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Siswa.xlsx';

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
                return redirect()->to('/admin/siswa')->with('error', 'Format file harus .xls atau .xlsx');
            }

            try {
                $spreadsheet = IOFactory::load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                $jumlahSukses = 0;
                $batchData = [];

                foreach ($data as $key => $row) {
                    if ($key == 0) continue; 

                    $nisn  = $row[0];
                    $nama  = $row[1];
                    $kelas = $row[2];
                    $wa    = $row[3];

                    if ($nisn && $nama && $kelas) {
                        $wa = preg_replace('/[^0-9]/', '', $wa);
                        if (substr($wa, 0, 1) == '0') $wa = '62' . substr($wa, 1);

                        $batchData[] = [
                            'nisn'          => $nisn,
                            'nama_lengkap'  => $nama,
                            'kelas_id'      => $kelas,
                            'no_wa_ortu'    => $wa,
                            'qr_code'       => null,
                            'foto'          => 'default.jpg'
                        ];
                        $jumlahSukses++;
                    }
                }

                if (!empty($batchData)) {
                    $this->siswaModel->ignore(true)->insertBatch($batchData);
                }

                return redirect()->to('/admin/siswa')->with('success', "Import berhasil! $jumlahSukses data siswa diproses.");

            } catch (\Exception $e) {
                return redirect()->to('/admin/siswa')->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            }
        }

        return redirect()->to('/admin/siswa')->with('error', 'Tidak ada file yang diupload.');
    }
}