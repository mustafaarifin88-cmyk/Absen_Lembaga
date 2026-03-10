<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiAgendaModel extends Model
{
    protected $table = 'absensi_agenda';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_type', 'user_id', 'kategori', 'agenda_id', 
        'nama_agenda', 'tanggal', 'jam_absen', 'status', 'lokasi_lat', 'lokasi_long'
    ];

    public function getLaporan($tglAwal, $tglAkhir, $kategori, $agendaId = null)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('absensi_agenda.*, anggota.nama_lengkap as nama_anggota, pengurus.nama_lengkap as nama_pengurus');
        $builder->join('anggota', 'anggota.id = absensi_agenda.user_id AND absensi_agenda.user_type = "anggota"', 'left');
        $builder->join('pengurus', 'pengurus.id = absensi_agenda.user_id AND absensi_agenda.user_type = "pengurus"', 'left');
        $builder->where('absensi_agenda.tanggal >=', $tglAwal);
        $builder->where('absensi_agenda.tanggal <=', $tglAkhir);
        $builder->where('absensi_agenda.kategori', $kategori);
        if ($agendaId) {
            $builder->where('absensi_agenda.agenda_id', $agendaId);
        }
        $absensiAktual = $builder->get()->getResultArray();

        $mapAbsensi = [];
        foreach($absensiAktual as $abs) {
            $mapAbsensi[$abs['tanggal']][$abs['agenda_id']][$abs['user_type']][$abs['user_id']] = $abs;
        }

        $allUsers = [];
        $pengurus = $db->table('pengurus')->get()->getResultArray();
        foreach($pengurus as $p) $allUsers[] = ['user_type'=>'pengurus', 'user_id'=>$p['id'], 'nama_pengurus'=>$p['nama_lengkap'], 'nama_anggota'=>null];
        
        $anggota = $db->table('anggota')->get()->getResultArray();
        foreach($anggota as $a) $allUsers[] = ['user_type'=>'anggota', 'user_id'=>$a['id'], 'nama_pengurus'=>null, 'nama_anggota'=>$a['nama_lengkap']];

        $tabelAgenda = ($kategori == 'ippm') ? 'agenda_ippm' : 'agenda_masyarakat';
        $builderAgenda = $db->table($tabelAgenda);
        if ($agendaId) $builderAgenda->where('id', $agendaId);
        $listAgenda = $builderAgenda->get()->getResultArray();

        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $finalData = [];
        $currentDate = strtotime($tglAwal);
        $endDate = strtotime($tglAkhir);

        while ($currentDate <= $endDate) {
            $dateStr = date('Y-m-d', $currentDate);
            $dayNameInggris = date('l', $currentDate);
            $dayNameIndo = $mapHari[$dayNameInggris];

            foreach ($listAgenda as $agenda) {
                if ($agenda['hari'] == $dayNameIndo) {
                    foreach ($allUsers as $user) {
                        if (isset($mapAbsensi[$dateStr][$agenda['id']][$user['user_type']][$user['user_id']])) {
                            $finalData[] = $mapAbsensi[$dateStr][$agenda['id']][$user['user_type']][$user['user_id']];
                        } else {
                            $finalData[] = [
                                'id' => null,
                                'nama_agenda' => $agenda['nama_agenda'],
                                'user_type' => $user['user_type'],
                                'user_id' => $user['user_id'],
                                'nama_pengurus' => $user['nama_pengurus'],
                                'nama_anggota' => $user['nama_anggota'],
                                'jam_absen' => '-',
                                'status' => 'Alfa',
                                'kategori' => $kategori,
                                'agenda_id' => $agenda['id'],
                                'lokasi_lat' => '-',
                                'lokasi_long' => '-'
                            ];
                        }
                    }
                }
            }
            $currentDate = strtotime("+1 day", $currentDate);
        }

        usort($finalData, function($a, $b) {
            $t1 = strtotime($b['tanggal']); 
            $t2 = strtotime($a['tanggal']);
            if ($t1 != $t2) return $t1 - $t2;
            
            $ag = strcmp($a['nama_agenda'], $b['nama_agenda']);
            if ($ag != 0) return $ag;

            $n1 = $a['user_type'] == 'pengurus' ? $a['nama_pengurus'] : $a['nama_anggota'];
            $n2 = $b['user_type'] == 'pengurus' ? $b['nama_pengurus'] : $b['nama_anggota'];
            return strcmp($n1, $n2);
        });

        return $finalData;
    }

    public function getRekapBulanan($bulan, $tahun, $userType, $kategori, $agendaId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('absensi_agenda.user_id, DAY(absensi_agenda.tanggal) as hari, absensi_agenda.status');
        $builder->where('absensi_agenda.user_type', $userType);
        $builder->where('absensi_agenda.kategori', $kategori);
        $builder->where('absensi_agenda.agenda_id', $agendaId);
        $builder->where('MONTH(absensi_agenda.tanggal)', $bulan);
        $builder->where('YEAR(absensi_agenda.tanggal)', $tahun);
        $query = $builder->get()->getResultArray();

        $rekap = [];
        foreach ($query as $row) {
            $rekap[$row['user_id']][$row['hari']] = $row['status'];
        }
        return $rekap;
    }

    public function getRekapTahunan($tahun, $userType, $kategori, $agendaId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('absensi_agenda.user_id, MONTH(absensi_agenda.tanggal) as bulan, absensi_agenda.status');
        $builder->where('absensi_agenda.user_type', $userType);
        $builder->where('absensi_agenda.kategori', $kategori);
        $builder->where('absensi_agenda.agenda_id', $agendaId);
        $builder->where('YEAR(absensi_agenda.tanggal)', $tahun);
        $query = $builder->get()->getResultArray();

        $rekap = [];
        foreach ($query as $row) {
            $uid = $row['user_id'];
            $bln = $row['bulan'];
            $sts = $row['status'];

            if (!isset($rekap[$uid][$bln])) {
                $rekap[$uid][$bln] = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
            }

            if ($sts == 'Hadir') $rekap[$uid][$bln]['H']++;
            elseif ($sts == 'Sakit') $rekap[$uid][$bln]['S']++;
            elseif ($sts == 'Izin') $rekap[$uid][$bln]['I']++;
            elseif ($sts == 'Alfa') $rekap[$uid][$bln]['A']++;
        }
        return $rekap;
    }
}