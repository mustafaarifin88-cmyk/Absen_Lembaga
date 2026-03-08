<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKegiatanTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_sholat' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'jam_akhir' => [
                'type' => 'TIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kegiatan_sholat');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_ekskul' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'jam_akhir' => [
                'type' => 'TIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kegiatan_ekskul');

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_type' => [
                'type'       => 'ENUM',
                'constraint' => ['guru', 'siswa'],
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kategori' => [
                'type'       => 'ENUM',
                'constraint' => ['sholat', 'ekskul'],
            ],
            'kegiatan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nama_kegiatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam_absen' => [
                'type' => 'TIME',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Hadir',
            ],
            'lokasi_lat' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'lokasi_long' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('absensi_kegiatan');
    }

    public function down()
    {
        $this->forge->dropTable('absensi_kegiatan');
        $this->forge->dropTable('kegiatan_ekskul');
        $this->forge->dropTable('kegiatan_sholat');
    }
}