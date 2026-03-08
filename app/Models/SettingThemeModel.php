<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingThemeModel extends Model
{
    protected $table            = 'setting_theme';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['login_bg_type', 'login_bg_value', 'sidebar_bg_type', 'sidebar_bg_value'];
}