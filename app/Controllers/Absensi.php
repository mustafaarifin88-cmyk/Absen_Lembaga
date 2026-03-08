<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\PengurusModel;
use App\Models\AnggotaModel;
use App\Models\SettingGpsModel;
use App\Models\SettingJamModel;
use App\Models\AgendaIppmModel;
use App\Models\AgendaMasyarakatModel;
use App\Models\AbsensiAgendaModel;
use CodeIgniter\API\ResponseTrait;

class Absensi extends BaseController
{
    use ResponseTrait;

    public function scanPage()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        $ippmModel = new AgendaIppmModel();
        $masyarakatModel = new AgendaMasyarakatModel();
        
        $hariInggris = date('l');
        $map = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $map[$hariInggris];

        $listIppm = $ippmModel->where('hari', $hariIni)->findAll();
        $listMasyarakat = $masyarakatModel->where('hari', $hariIni)->findAll();

        $data = [
            'title' => 'Scan QR Code',
            'list_ippm' => $listIppm,
            'list_masyarakat' => $listMasyarakat
        ];

        return view('absensi/scan', $data);
    }

    public function processScan()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('Akses ditolak');
        }

        $qrCode = $this->request->getPost('qr_code');
        $latUser = $this->request->getPost('latitude');
        $longUser = $this->request->getPost('longitude');

        $gpsModel = new SettingGpsModel();
        $settingGps = $gpsModel->first();
        
        if ($settingGps) {
            $jarak = $this->calculateDistance($latUser, $longUser, $settingGps['latitude'], $settingGps['longitude']);
            if ($jarak > $settingGps['radius_meter']) {
                return $this->fail(['message' => 'Anda berada di luar jangkauan lokasi absensi (' . round($jarak) . ' meter).']);
            }
        }

        $userType = '';
        $userId = 0;

        if (strpos($qrCode, 'PENGURUS-') === 0) {
            $userType = 'pengurus';
            $userId = str_replace('PENGURUS-', '', $qrCode);
        } elseif (strpos($qrCode, 'ANGGOTA-') === 0) {
            $userType = 'anggota';
            $userId = str_replace('ANGGOTA-', '', $qrCode);
        } else {
            return $this->fail(['message' => 'QR Code tidak dikenali.']);
        }

        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();
        $userData = null;

        if ($userType == 'pengurus') {
            $userData = $pengurusModel->find($userId);
        } else {
            $userData = $anggotaModel->find($userId);
        }

        if (!$userData) {
            return $this->fail(['message' => 'Data pengguna tidak ditemukan.']);
        }

        $hariInggris = date('l');
        $map = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $hariIni = $map[$hariInggris];
        $jamSekarang = date('H:i:s');
        $tglSekarang = date('Y-m-d');

        $settingJamModel = new SettingJamModel();
        $jamSetting = $settingJamModel->where('type', $userType)->where('hari', $hariIni)->first();

        if (!$jamSetting) {
            return $this->fail(['message' => 'Tidak ada jadwal absensi untuk hari ini.']);
        }

        if ($jamSetting['jam_masuk_mulai'] == '00:00:00' && $jamSetting['jam_pulang_akhir'] == '00:00:00') {
             return $this->fail(['message' => 'Hari Libur / Tidak ada jadwal.']);
        }

        $absensiModel = new AbsensiModel();
        $cekAbsen = $absensiModel->where('user_id', $userId)
                                 ->where('user_type', $userType)
                                 ->where('tanggal', $tglSekarang)
                                 ->first();

        $status = '';
        $mode = ''; 

        if (!$cekAbsen) {
            if ($jamSekarang >= $jamSetting['jam_masuk_mulai'] && $jamSekarang <= $jamSetting['jam_masuk_akhir']) {
                $status = 'Hadir';
            } elseif ($jamSekarang > $jamSetting['jam_masuk_akhir'] && $jamSekarang <= $jamSetting['jam_pulang_mulai']) {
                $status = 'Terlambat';
            } else {
                return $this->fail(['message' => 'Belum waktunya absen masuk atau sudah lewat.']);
            }

            $absensiModel->save([
                'user_type' => $userType,
                'user_id' => $userId,
                'tanggal' => $tglSekarang,
                'jam_masuk' => $jamSekarang,
                'status' => $status,
                'lokasi_lat' => $latUser,
                'lokasi_long' => $longUser
            ]);
            
            $mode = 'Masuk';

        } else {
            if ($cekAbsen['jam_pulang'] != null) {
                return $this->fail(['message' => 'Anda sudah melakukan absen pulang hari ini.']);
            }

            if ($jamSekarang >= $jamSetting['jam_pulang_mulai'] && $jamSekarang <= $jamSetting['jam_pulang_akhir']) {
                $absensiModel->update($cekAbsen['id'], [
                    'jam_pulang' => $jamSekarang
                ]);
                $mode = 'Pulang';
            } elseif ($jamSekarang < $jamSetting['jam_pulang_mulai']) {
                $absensiModel->update($cekAbsen['id'], [
                    'jam_pulang' => $jamSekarang,
                    'status' => 'Cepat Pulang',
                    'keterangan' => ($cekAbsen['keterangan'] ? $cekAbsen['keterangan'] . ' ' : '') . '(Cepat Pulang)'
                ]);
                $mode = 'Cepat Pulang';
            } else {
                return $this->fail(['message' => 'Batas waktu absen pulang sudah habis.']);
            }
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Absen ' . $mode . ' Berhasil',
            'nama' => $userData['nama_lengkap'],
            'jam' => $jamSekarang
        ]);
    }

    public function processScanAgenda()
    {
        if (!$this->request->isAJAX()) return $this->failForbidden();

        $qrCode = $this->request->getPost('qr_code');
        $kategori = $this->request->getPost('kategori'); 
        $agendaId = $this->request->getPost('agenda_id');
        $lat = $this->request->getPost('latitude');
        $long = $this->request->getPost('longitude');

        $userType = '';
        $userId = 0;

        if (strpos($qrCode, 'PENGURUS-') === 0) {
            $userType = 'pengurus';
            $userId = str_replace('PENGURUS-', '', $qrCode);
        } elseif (strpos($qrCode, 'ANGGOTA-') === 0) {
            $userType = 'anggota';
            $userId = str_replace('ANGGOTA-', '', $qrCode);
        } else {
            return $this->fail(['message' => 'QR Code salah.']);
        }

        $pengurusModel = new PengurusModel();
        $anggotaModel = new AnggotaModel();
        
        $userData = ($userType == 'pengurus') ? $pengurusModel->find($userId) : $anggotaModel->find($userId);
        if (!$userData) return $this->fail(['message' => 'User tidak ditemukan.']);

        $agendaName = '';
        if ($kategori == 'ippm') {
            $m = new AgendaIppmModel();
            $d = $m->find($agendaId);
            if($d) $agendaName = $d['nama_agenda'];
        } else {
            $m = new AgendaMasyarakatModel();
            $d = $m->find($agendaId);
            if($d) $agendaName = $d['nama_agenda'];
        }

        if (!$agendaName) return $this->fail(['message' => 'Agenda tidak valid.']);

        $absenAgendaModel = new AbsensiAgendaModel();
        $today = date('Y-m-d');
        
        $exist = $absenAgendaModel->where([
            'user_id' => $userId,
            'user_type' => $userType,
            'kategori' => $kategori,
            'agenda_id' => $agendaId,
            'tanggal' => $today
        ])->first();

        if ($exist) return $this->fail(['message' => 'Anda sudah absen agenda ini.']);

        $absenAgendaModel->save([
            'user_type' => $userType,
            'user_id' => $userId,
            'kategori' => $kategori,
            'agenda_id' => $agendaId,
            'nama_agenda' => $agendaName,
            'tanggal' => $today,
            'jam_absen' => date('H:i:s'),
            'status' => 'Hadir',
            'lokasi_lat' => $lat,
            'lokasi_long' => $long
        ]);

        return $this->respond([
            'status' => 200,
            'message' => 'Absensi Agenda Berhasil',
            'nama' => $userData['nama_lengkap'],
            'jam' => date('H:i:s')
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper("K");

        if ($unit == "K") {
            return ($miles * 1.609344) * 1000;
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}