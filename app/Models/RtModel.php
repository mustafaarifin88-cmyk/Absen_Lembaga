<?php

namespace App\Models;

use CodeIgniter\Model;

class RtModel extends Model
{
    protected $table            = 'rt';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_rt'
    ];

    protected $useTimestamps = false;
}