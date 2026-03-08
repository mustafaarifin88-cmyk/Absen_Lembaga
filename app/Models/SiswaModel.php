<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nisn',
        'nama_lengkap',
        'kelas_id',
        'no_wa_ortu',
        'qr_code',
        'foto'
    ];

    protected $useTimestamps = false;

    public function getSiswaWithKelas($id = null)
    {
        $builder = $this->table($this->table);
        $builder->select('siswa.*, kelas.nama_kelas, kelas.jurusan');
        $builder->join('kelas', 'kelas.id = siswa.kelas_id');

        if ($id) {
            return $builder->where('siswa.id', $id)->first();
        }

        return $builder->orderBy('id', 'DESC')->get()->getResultArray();
    }
}