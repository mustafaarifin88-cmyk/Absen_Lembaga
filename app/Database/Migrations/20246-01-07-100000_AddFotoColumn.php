<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoColumn extends Migration
{
    public function up()
    {
        // 1. Proses untuk Tabel GURU
        // Cek apakah kolom 'foto' sudah ada di tabel 'guru'
        if (! $this->db->fieldExists('foto', 'guru')) {
            $fields = [
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => 'default.jpg',
                    'after'      => 'no_wa', // Menempatkan kolom setelah no_wa
                    'null'       => true,
                ],
            ];
            $this->forge->addColumn('guru', $fields);
        }

        // 2. Proses untuk Tabel SISWA
        // Cek apakah kolom 'foto' sudah ada di tabel 'siswa'
        if (! $this->db->fieldExists('foto', 'siswa')) {
            $fields = [
                'foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'default'    => 'default.jpg',
                    'after'      => 'qr_code', // Menempatkan kolom setelah qr_code
                    'null'       => true,
                ],
            ];
            $this->forge->addColumn('siswa', $fields);
        }
    }

    public function down()
    {
        // Rollback: Hapus kolom foto jika ada
        if ($this->db->fieldExists('foto', 'guru')) {
            $this->forge->dropColumn('guru', 'foto');
        }

        if ($this->db->fieldExists('foto', 'siswa')) {
            $this->forge->dropColumn('siswa', 'foto');
        }
    }
}