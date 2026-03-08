<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingJamModel extends Model
{
    protected $table            = 'setting_jam';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type',
        'hari', // Tambahan baru
        'jam_masuk_mulai',
        'jam_masuk_akhir',
        'jam_pulang_mulai',
        'jam_pulang_akhir'
    ];
}