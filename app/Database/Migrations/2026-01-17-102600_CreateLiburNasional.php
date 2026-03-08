<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLiburNasional extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nama_libur' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
            ],
            'tanggal_akhir' => [
                'type' => 'DATE',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('libur_nasional');
    }

    public function down()
    {
        $this->forge->dropTable('libur_nasional');
    }
}