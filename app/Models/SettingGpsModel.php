<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingGpsModel extends Model
{
    protected $table            = 'setting_gps';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'latitude',
        'longitude',
        'radius_meter'
    ];

    protected $useTimestamps = false;
}