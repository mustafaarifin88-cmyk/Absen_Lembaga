<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingTheme extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'login_bg_type' => [
                'type'       => 'ENUM',
                'constraint' => ['color', 'image'],
                'default'    => 'color',
            ],
            'login_bg_value' => [
                'type'       => 'TEXT', // Hex color atau nama file
                'null'       => true,
            ],
            'sidebar_bg_type' => [
                'type'       => 'ENUM',
                'constraint' => ['color', 'image'],
                'default'    => 'color',
            ],
            'sidebar_bg_value' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('setting_theme');

        // Insert Default Data
        $this->db->table('setting_theme')->insert([
            'login_bg_type'    => 'color',
            'login_bg_value'   => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)', // Default Biru
            'sidebar_bg_type'  => 'color',
            'sidebar_bg_value' => '#ffffff', // Default Putih
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('setting_theme');
    }
}