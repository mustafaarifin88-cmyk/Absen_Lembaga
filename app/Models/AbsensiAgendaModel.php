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

        // 1. Ambil Data Absensi yang Sudah Ada (Hadir/Sakit/Izin/Alfa Manual)
        $builder = $db->table($this->table);
        $builder->select('absensi_agenda.*, anggota.nama_lengkap as nama_anggota, pengurus.nama_lengkap as nama_pengurus');
        $builder->join('anggota', 'anggota.id = absensi_agenda.user_id AND absensi_agenda.user_type = "anggota"', 'left');
        $builder->join('pengurus', 'pengurus.id = absensi_agenda.user_id AND absensi_agenda.user_type = "pengurus"', 'left');
        $builder->where('absensi_agenda.tanggal >=', $tglAwal);
        $builder->where('absensi_agenda.tanggal <=', $tglAkhir);
        
        if ($kategori != 'semua') {
            $builder->where('absensi_agenda.kategori', $kategori);
        }
        if ($agendaId) {
            $builder->where('absensi_agenda.agenda_id', $agendaId);
        }
        $existingData = $builder->get()->getResultArray();

        // Map data existing untuk pencarian cepat
        $attendanceMap = [];
        foreach ($existingData as $row) {
            // Key unik: Tanggal_AgendaID_UserType_UserID
            $key = $row['tanggal'] . '_' . $row['agenda_id'] . '_' . $row['user_type'] . '_' . $row['user_id'];
            $attendanceMap[$key] = $row;
        }

        // 2. Ambil Semua User (Pengurus & Anggota)
        $pengurus = $db->table('pengurus')->get()->getResultArray();
        $anggota = $db->table('anggota')->get()->getResultArray();
        
        $allUsers = [];
        foreach($pengurus as $p) {
            $allUsers[] = [
                'user_type' => 'pengurus',
                'user_id' => $p['id'],
                'nama_lengkap' => $p['nama_lengkap'],
                'nama_pengurus' => $p['nama_lengkap'],
                'nama_anggota' => null
            ];
        }
        foreach($anggota as $a) {
            $allUsers[] = [
                'user_type' => 'anggota',
                'user_id' => $a['id'],
                'nama_lengkap' => $a['nama_lengkap'],
                'nama_pengurus' => null,
                'nama_anggota' => $a['nama_lengkap']
            ];
        }

        // 3. Ambil Jadwal Agenda yang Relevan
        $agendas = [];
        if ($kategori == 'ippm' || $kategori == 'semua') {
            $qIppm = $db->table('agenda_ippm');
            if($agendaId && $kategori == 'ippm') $qIppm->where('id', $agendaId);
            $resIppm = $qIppm->get()->getResultArray();
            foreach($resIppm as $r) { $r['kategori'] = 'ippm'; $agendas[] = $r; }
        }
        if ($kategori == 'masyarakat' || $kategori == 'semua') {
            $qMas = $db->table('agenda_masyarakat');
            if($agendaId && $kategori == 'masyarakat') $qMas->where('id', $agendaId);
            $resMas = $qMas->get()->getResultArray();
            foreach($resMas as $r) { $r['kategori'] = 'masyarakat'; $agendas[] = $r; }
        }

        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        // 4. Generate Laporan (Cross Join: Tanggal x Agenda x User)
        $finalData = [];
        $currentDate = strtotime($tglAwal);
        $endDate = strtotime($tglAkhir);

        while ($currentDate <= $endDate) {
            $dateStr = date('Y-m-d', $currentDate);
            $dayNameIng = date('l', $currentDate);
            $dayNameInd = $mapHari[$dayNameIng];

            foreach ($agendas as $agenda) {
                // Cek apakah agenda ini jadwalnya hari ini
                if ($agenda['hari'] == $dayNameInd) {
                    
                    foreach ($allUsers as $user) {
                        $key = $dateStr . '_' . $agenda['id'] . '_' . $user['user_type'] . '_' . $user['user_id'];
                        
                        if (isset($attendanceMap[$key])) {
                            // Jika ada data absensi, gunakan itu
                            $finalData[] = $attendanceMap[$key];
                        } else {
                            // Jika tidak ada, buat data Alfa Virtual
                            $finalData[] = [
                                'id' => null, // ID null menandakan data virtual
                                'tanggal' => $dateStr,
                                'nama_agenda' => $agenda['nama_agenda'],
                                'user_type' => $user['user_type'],
                                'user_id' => $user['user_id'],
                                'nama_pengurus' => $user['nama_pengurus'],
                                'nama_anggota' => $user['nama_anggota'],
                                'jam_absen' => '-',
                                'status' => 'Alfa',
                                'kategori' => $agenda['kategori'],
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

        // Urutkan data: Tanggal DESC, Nama Agenda ASC, Nama User ASC
        usort($finalData, function($a, $b) {
            $t1 = strtotime($b['tanggal']); // DESC Date
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
}