<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingWhatsappModel extends Model
{
    protected $table            = 'setting_whatsapp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['wa_gateway_url', 'wa_api_token'];
    protected $useTimestamps    = false;
}