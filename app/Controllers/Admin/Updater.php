<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use ZipArchive;

class Updater extends BaseController
{
    protected $updateConfig;
    protected $db;
    protected $progressFile;

    public function __construct()
    {
        $this->updateConfig = new \Config\Updater();
        $this->db = \Config\Database::connect();
        // Pastikan path menggunakan '/' agar kompatibel di semua OS
        $this->progressFile = WRITEPATH . 'uploads/update_status.json';
    }

    private function setProgress($percent, $message, $status = 'processing')
    {
        $data = json_encode([
            'percent' => $percent,
            'message' => $message,
            'status'  => $status
        ]);
        @file_put_contents($this->progressFile, $data);
    }

    public function index()
    {
        // Ambil versi saat ini, handle jika tabel kosong
        $query = $this->db->table('app_version')->orderBy('id', 'DESC')->get()->getRow();
        $currentVersion = $query ? $query->version : '1.0.0';

        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->get($this->updateConfig->updateUrl, ['timeout' => 5]);
            $serverData = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $serverData = null;
        }

        $data = [
            'title' => 'Cek Pembaruan Sistem',
            'current_version' => $currentVersion,
            'server_data' => $serverData,
            'has_update' => ($serverData && version_compare($serverData['latest_version'], $currentVersion, '>'))
        ];

        return view('admin/updater/index', $data);
    }

    public function checkStatus()
    {
        if (file_exists($this->progressFile)) {
            // Matikan cache agar data selalu fresh
            header("Cache-Control: no-cache, must-revalidate");
            return $this->response->setJSON(json_decode(file_get_contents($this->progressFile), true));
        }
        return $this->response->setJSON(['percent' => 0, 'message' => 'Menunggu...', 'status' => 'waiting']);
    }

    public function initUpdate()
    {
        $downloadUrl = $this->request->getGet('url');
        if (!$downloadUrl) return $this->response->setJSON(['status' => 'error', 'message' => 'URL tidak valid']);

        $zipFile = WRITEPATH . 'uploads/update.zip';
        
        // Pastikan folder uploads ada
        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0777, true);
        }

        $this->setProgress(0, 'Memulai koneksi ke server...');

        $fp = fopen($zipFile, 'w+');
        $ch = curl_init($downloadUrl);
        
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_FILE, $fp); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        
        // Progress Callback
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $download_size, $downloaded, $upload_size, $uploaded) {
            if ($download_size > 0) {
                $percent = round(($downloaded / $download_size) * 100);
                // Update file json maksimal setiap kelipatan 5% agar tidak spam IO disk
                if ($percent % 5 == 0 || $percent == 100) {
                    $this->setProgress($percent, "Mendownload: $percent%");
                }
            }
        });

        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        if ($data) {
            $this->setProgress(100, 'Download Selesai. Mempersiapkan ekstraksi...', 'downloaded');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            $this->setProgress(0, 'Gagal Download: ' . $error, 'error');
            return $this->response->setJSON(['status' => 'error', 'message' => $error]);
        }
    }

    public function extractFiles()
    {
        $zipFile = WRITEPATH . 'uploads/update.zip';
        $newVersion = $this->request->getGet('version');

        if (!file_exists($zipFile)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'File update tidak ditemukan']);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            
            $totalFiles = $zip->numFiles;
            
            for ($i = 0; $i < $totalFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                
                // Jangan timpa file konfigurasi sensitif
                if (strpos($filename, '.env') !== false || strpos($filename, 'app/Config/Database.php') !== false) {
                    continue;
                }

                $zip->extractTo(ROOTPATH, array($filename));
                
                // Update progress setiap 10 file agar tidak terlalu berat
                if ($i % 10 == 0) {
                    $percent = round((($i + 1) / $totalFiles) * 100);
                    $this->setProgress($percent, "Menimpa file: $filename");
                }
            }
            
            $zip->close();
            
            // --- LOGIKA PERBAIKAN DI SINI ---
            
            // 1. Update Database TERLEBIH DAHULU (Prioritas Utama)
            // Cek apakah versi sudah ada, jika belum insert, jika sudah update
            $exists = $this->db->table('app_version')->countAllResults();
            if ($exists > 0) {
                // Update row terakhir
                $lastId = $this->db->table('app_version')->orderBy('id', 'DESC')->get()->getRow()->id;
                $this->db->table('app_version')->where('id', $lastId)->update(['version' => $newVersion]);
            } else {
                $this->db->table('app_version')->insert(['version' => $newVersion]);
            }

            // 2. Jalankan Migrasi (Dalam Try-Catch agar tidak mematikan script jika gagal)
            $migrasiPesan = "";
            try {
                $migrate = \Config\Services::migrations();
                $migrate->latest();
            } catch (\Throwable $e) {
                // Catat error tapi biarkan status completed karena file sudah terupdate
                $migrasiPesan = " (Migrasi DB skip: " . $e->getMessage() . ")";
                log_message('error', 'Update Migration Error: ' . $e->getMessage());
            }

            // 3. Hapus file ZIP (Gunakan @ untuk suppress error jika file terkunci oleh sistem)
            @unlink($zipFile);
            @unlink($this->progressFile);

            return $this->response->setJSON([
                'status' => 'completed', 
                'message' => 'Update Berhasil ke v' . $newVersion . $migrasiPesan
            ]);

        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal membuka file ZIP (Corrupt)']);
        }
    }
}