<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\LiburNasionalModel;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'user_type', 'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 
        'status', 'keterangan', 'lokasi_lat', 'lokasi_long'
    ];

    private function getHariIndo($dateStr) {
        $hariInggris = date('l', strtotime($dateStr));
        $map = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        return $map[$hariInggris];
    }

    private function getSettingHariArray() {
        $db = \Config\Database::connect();
        $settings = $db->table('setting_hari')->get()->getResultArray();
        $result = [];
        foreach ($settings as $s) {
            $result[$s['nama_hari']] = $s['tampilkan'];
        }
        return $result;
    }

    private function getLiburDates($startDate, $endDate) {
        $liburModel = new LiburNasionalModel();
        $liburData = $liburModel->where('tanggal_akhir >=', $startDate)
                                ->where('tanggal_mulai <=', $endDate)
                                ->findAll();
        $liburDates = [];
        foreach ($liburData as $l) {
            $period = new \DatePeriod(
                new \DateTime($l['tanggal_mulai']),
                new \DateInterval('P1D'),
                (new \DateTime($l['tanggal_akhir']))->modify('+1 day')
            );
            foreach ($period as $dt) {
                $liburDates[] = $dt->format('Y-m-d');
            }
        }
        return $liburDates;
    }

    public function getLaporan($tglAwal, $tglAkhir, $userType, $rtId = null)
    {
        $db = \Config\Database::connect();
        $settingHari = $this->getSettingHariArray();
        $liburDates = $this->getLiburDates($tglAwal, $tglAkhir);

        $users = [];
        if ($userType == 'pengurus') {
            $users = $db->table('pengurus')->get()->getResultArray();
        } else {
            $builder = $db->table('anggota');
            if ($rtId) {
                $builder->where('rt_id', $rtId);
            }
            $users = $builder->get()->getResultArray();
        }

        $absensiData = $this->where('user_type', $userType)
                            ->where('tanggal >=', $tglAwal)
                            ->where('tanggal <=', $tglAkhir)
                            ->findAll();

        $absensiMap = [];
        foreach ($absensiData as $absen) {
            $absensiMap[$absen['user_id']][$absen['tanggal']] = $absen;
        }

        $finalData = [];
        $currentDate = strtotime($tglAwal);
        $endDate = strtotime($tglAkhir);

        while ($currentDate <= $endDate) {
            $dateStr = date('Y-m-d', $currentDate);
            $hariIndo = $this->getHariIndo($dateStr);
            $isLiburNasional = in_array($dateStr, $liburDates);
            $isHariAktif = ($settingHari[$hariIndo] == 1);

            foreach ($users as $user) {
                $userId = $user['id'];
                $absen = isset($absensiMap[$userId][$dateStr]) ? $absensiMap[$userId][$dateStr] : null;

                if ($absen) {
                    $finalData[] = array_merge($absen, [
                        'nama_lengkap' => $user['nama_lengkap'],
                        'jabatan_or_rt' => ($userType == 'pengurus') ? $user['jabatan'] : 'Anggota',
                        'hari_indo' => $hariIndo
                    ]);
                } else {
                    if (!$isHariAktif || $isLiburNasional) {
                        continue; 
                    }
                    $finalData[] = [
                        'id' => null,
                        'user_type' => $userType,
                        'user_id' => $userId,
                        'nama_lengkap' => $user['nama_lengkap'],
                        'jabatan_or_rt' => ($userType == 'pengurus') ? $user['jabatan'] : 'Anggota',
                        'tanggal' => $dateStr,
                        'hari_indo' => $hariIndo,
                        'jam_masuk' => '-',
                        'jam_pulang' => '-',
                        'status' => 'Alfa',
                        'keterangan' => '',
                        'lokasi_lat' => null,
                        'lokasi_long' => null
                    ];
                }
            }
            $currentDate = strtotime("+1 day", $currentDate);
        }

        usort($finalData, function($a, $b) {
            if ($a['tanggal'] == $b['tanggal']) {
                return strcmp($a['nama_lengkap'], $b['nama_lengkap']);
            }
            return strcmp($a['tanggal'], $b['tanggal']);
        });

        return $finalData;
    }

    public function getKoreksiData($tglAwal, $tglAkhir, $userType, $rtId = null)
    {
        return $this->getLaporan($tglAwal, $tglAkhir, $userType, $rtId);
    }

    public function getRekapBulanan($bulan, $tahun, $userType, $rtId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('absensi.user_id, DAY(absensi.tanggal) as hari, absensi.status');
        
        if ($userType == 'anggota' && $rtId) {
            $builder->join('anggota', 'anggota.id = absensi.user_id', 'left');
            $builder->where('anggota.rt_id', $rtId);
        }

        $builder->where('absensi.user_type', $userType);
        $builder->where('MONTH(absensi.tanggal)', $bulan);
        $builder->where('YEAR(absensi.tanggal)', $tahun);
        
        $query = $builder->get()->getResultArray();

        $rekap = [];
        foreach ($query as $row) {
            $rekap[$row['user_id']][$row['hari']] = $row['status'];
        }

        return $rekap;
    }

    public function getRekapTahunan($tahun, $userType, $rtId = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('absensi.user_id, MONTH(absensi.tanggal) as bulan, absensi.status');

        if ($userType == 'anggota' && $rtId) {
            $builder->join('anggota', 'anggota.id = absensi.user_id', 'left');
            $builder->where('anggota.rt_id', $rtId);
        }

        $builder->where('absensi.user_type', $userType);
        $builder->where('YEAR(absensi.tanggal)', $tahun);

        $query = $builder->get()->getResultArray();

        $rekap = [];
        foreach ($query as $row) {
            $uid = $row['user_id'];
            $bln = $row['bulan'];
            $sts = $row['status'];

            if (!isset($rekap[$uid][$bln])) {
                $rekap[$uid][$bln] = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0, 'T' => 0];
            }

            if ($sts == 'Hadir') $rekap[$uid][$bln]['H']++;
            elseif ($sts == 'Sakit') $rekap[$uid][$bln]['S']++;
            elseif ($sts == 'Izin') $rekap[$uid][$bln]['I']++;
            elseif ($sts == 'Alfa') $rekap[$uid][$bln]['A']++;
            elseif ($sts == 'Terlambat') {
                $rekap[$uid][$bln]['H']++;
                $rekap[$uid][$bln]['T']++;
            }
        }

        return $rekap;
    }
}