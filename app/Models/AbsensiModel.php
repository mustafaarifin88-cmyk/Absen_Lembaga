<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'user_type', 'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 
        'status', 'keterangan', 'lokasi_lat', 'lokasi_long'
    ];

    public function getLaporan($tglAwal, $tglAkhir, $userType, $rtId = null)
    {
        $db = \Config\Database::connect();

        $targetPeserta = ($userType == 'pengurus') ? 'Pengurus' : 'Anggota';
        $jadwal = $db->table('jadwal_rapat')
            ->where('tanggal >=', $tglAwal)
            ->where('tanggal <=', $tglAkhir)
            ->groupStart()
                ->where('peserta', 'Semua')
                ->orWhere('peserta', $targetPeserta)
            ->groupEnd()
            ->get()->getResultArray();
        $jadwalDates = array_column($jadwal, 'tanggal');

        $users = [];
        if ($userType == 'pengurus') {
            $users = $db->table('pengurus')->get()->getResultArray();
        } else {
            $builder = $db->table('anggota')->select('anggota.*, rt.nama_rt')->join('rt', 'rt.id = anggota.rt_id', 'left');
            if ($rtId) $builder->where('anggota.rt_id', $rtId);
            $users = $builder->get()->getResultArray();
        }

        $builder = $db->table($this->table);
        $builder->select('absensi.*, anggota.nama_lengkap as nama_anggota, pengurus.nama_lengkap as nama_pengurus, rt.nama_rt');
        $builder->join('anggota', 'anggota.id = absensi.user_id AND absensi.user_type = "anggota"', 'left');
        $builder->join('pengurus', 'pengurus.id = absensi.user_id AND absensi.user_type = "pengurus"', 'left');
        $builder->join('rt', 'rt.id = anggota.rt_id', 'left');
        $builder->where('absensi.user_type', $userType);
        $builder->where('absensi.tanggal >=', $tglAwal);
        $builder->where('absensi.tanggal <=', $tglAkhir);
        if ($rtId && $userType == 'anggota') {
            $builder->where('anggota.rt_id', $rtId);
        }
        $absensiAktual = $builder->get()->getResultArray();

        $mapAbsensi = [];
        foreach($absensiAktual as $abs) {
            $mapAbsensi[$abs['tanggal']][$abs['user_id']] = $abs;
        }

        $finalData = [];
        $currentDate = strtotime($tglAwal);
        $endDate = strtotime($tglAkhir);

        while ($currentDate <= $endDate) {
            $dateStr = date('Y-m-d', $currentDate);
            $isRapat = in_array($dateStr, $jadwalDates);

            if ($isRapat || isset($mapAbsensi[$dateStr])) {
                foreach ($users as $user) {
                    if (isset($mapAbsensi[$dateStr][$user['id']])) {
                        $finalData[] = $mapAbsensi[$dateStr][$user['id']];
                    } else if ($isRapat) {
                        $finalData[] = [
                            'id' => null, 
                            'user_type' => $userType,
                            'user_id' => $user['id'],
                            'tanggal' => $dateStr,
                            'jam_masuk' => '-',
                            'jam_pulang' => '-',
                            'status' => 'Alfa',
                            'keterangan' => 'Belum Absen',
                            'lokasi_lat' => '-',
                            'lokasi_long' => '-',
                            'nama_anggota' => $userType == 'anggota' ? $user['nama_lengkap'] : null,
                            'nama_pengurus' => $userType == 'pengurus' ? $user['nama_lengkap'] : null,
                            'nama_rt' => $userType == 'anggota' ? (isset($user['nama_rt']) ? $user['nama_rt'] : '-') : '-'
                        ];
                    }
                }
            }
            $currentDate = strtotime("+1 day", $currentDate);
        }

        usort($finalData, function($a, $b) {
            $t1 = strtotime($b['tanggal']);
            $t2 = strtotime($a['tanggal']);
            if ($t1 != $t2) return $t1 - $t2;
            
            if ($a['jam_masuk'] != '-' && $b['jam_masuk'] == '-') return -1;
            if ($a['jam_masuk'] == '-' && $b['jam_masuk'] != '-') return 1;

            return strcmp($b['jam_masuk'], $a['jam_masuk']);
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
                $rekap[$uid][$bln]['T']++;
                $rekap[$uid][$bln]['H']++;
            }
            elseif ($sts == 'Cepat Pulang') {
                $rekap[$uid][$bln]['H']++;
            }
        }

        return $rekap;
    }
}