<?php
namespace App\Models;
use CodeIgniter\Model;

class AbsensiKegiatanModel extends Model {
    protected $table = 'absensi_kegiatan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_type', 'user_id', 'kategori', 'kegiatan_id', 
        'nama_kegiatan', 'tanggal', 'jam_absen', 'status', 'lokasi_lat', 'lokasi_long'
    ];

    public function getLaporan($tglAwal, $tglAkhir, $kategori, $kegiatanId = null) {
        $builder = $this->db->table($this->table);
        $builder->select('absensi_kegiatan.*, siswa.nama_lengkap as nama_siswa, siswa.nisn, guru.nama_guru, guru.nip');
        
        // Join ke tabel user (left join karena bisa siswa atau guru)
        $builder->join('siswa', 'siswa.id = absensi_kegiatan.user_id AND absensi_kegiatan.user_type = "siswa"', 'left');
        $builder->join('guru', 'guru.id = absensi_kegiatan.user_id AND absensi_kegiatan.user_type = "guru"', 'left');

        $builder->where('absensi_kegiatan.tanggal >=', $tglAwal);
        $builder->where('absensi_kegiatan.tanggal <=', $tglAkhir);
        
        if($kategori != 'semua') {
            $builder->where('absensi_kegiatan.kategori', $kategori);
        }
        if($kegiatanId) {
            $builder->where('absensi_kegiatan.kegiatan_id', $kegiatanId);
        }

        return $builder->orderBy('tanggal DESC, jam_absen DESC')->get()->getResultArray();
    }
}