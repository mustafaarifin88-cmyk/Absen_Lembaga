<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table            = 'anggota';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_lengkap',
        'rt_id',
        'qr_code',
        'foto'
    ];

    protected $useTimestamps = false;

    public function getAnggotaWithRt($id = null)
    {
        $builder = $this->table($this->table);
        $builder->select('anggota.*, rt.nama_rt');
        $builder->join('rt', 'rt.id = anggota.rt_id');

        if ($id) {
            return $builder->where('anggota.id', $id)->first();
        }

        return $builder->orderBy('id', 'DESC')->get()->getResultArray();
    }
}