<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaMasyarakatModel extends Model
{
    protected $table = 'agenda_masyarakat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_agenda', 'hari', 'jam_mulai', 'jam_akhir'];
}