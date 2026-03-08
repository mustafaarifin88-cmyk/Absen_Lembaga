<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganisasiModel extends Model
{
    protected $table            = 'organisasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_organisasi',
        'alamat_lengkap',
        'kabupaten',
        'logo',
        'kepala_instansi'
    ];

    protected $useTimestamps = false;
}