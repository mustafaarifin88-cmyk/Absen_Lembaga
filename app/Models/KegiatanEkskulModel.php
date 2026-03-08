<?php
namespace App\Models;
use CodeIgniter\Model;

class KegiatanEkskulModel extends Model {
    protected $table = 'kegiatan_ekskul';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_ekskul', 'hari', 'jam_mulai', 'jam_akhir'];
}