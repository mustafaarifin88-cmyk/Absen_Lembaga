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
        $builder = $this->db->table($this->table);
        $builder->select('absensi.*, anggota.nama_lengkap as nama_anggota, pengurus.nama_lengkap as nama_pengurus, rt.nama_rt');
        $builder->join('anggota', 'anggota.id = absensi.user_id AND absensi.user_type = "anggota"', 'left');
        $builder->join('pengurus', 'pengurus.id = absensi.user_id AND absensi.user_type = "pengurus"', 'left');
        $builder->join('rt', 'rt.id = anggota.rt_id', 'left');

        if ($userType) {
            $builder->where('absensi.user_type', $userType);
        }
        if ($rtId) {
            $builder->where('anggota.rt_id', $rtId);
        }
        
        $builder->where('absensi.tanggal >=', $tglAwal);
        $builder->where('absensi.tanggal <=', $tglAkhir);
        $builder->orderBy('absensi.tanggal', 'DESC');
        $builder->orderBy('absensi.jam_masuk', 'DESC');

        return $builder->get()->getResultArray();
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