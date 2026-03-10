<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalRapatModel extends Model
{
    protected $table            = 'jadwal_rapat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['nama_rapat', 'tanggal', 'jam_mulai', 'jam_akhir', 'peserta'];
}