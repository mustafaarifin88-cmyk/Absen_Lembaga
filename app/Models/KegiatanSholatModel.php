<?php
namespace App\Models;
use CodeIgniter\Model;

class KegiatanSholatModel extends Model {
    protected $table = 'kegiatan_sholat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_sholat', 'jam_mulai', 'jam_akhir'];
}