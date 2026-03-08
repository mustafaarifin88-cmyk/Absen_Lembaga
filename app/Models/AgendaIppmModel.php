<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaIppmModel extends Model
{
    protected $table = 'agenda_ippm';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_agenda', 'hari', 'jam_mulai', 'jam_akhir'];
}