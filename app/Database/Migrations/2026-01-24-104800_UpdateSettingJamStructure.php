<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSettingJamStructure extends Migration
{
    public function up()
    {
        // 1. Kosongkan tabel lama agar tidak konflik struktur
        $this->db->table('setting_jam')->truncate();

        // 2. Tambahkan kolom 'hari' jika belum ada
        if (! $this->db->fieldExists('hari', 'setting_jam')) {
            $this->forge->addColumn('setting_jam', [
                'hari' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'after'      => 'type',
                    'null'       => false,
                ],
            ]);
        }

        // 3. Siapkan data default (Sesuai dengan SQL manual Anda)
        $data = [];
        
        // Data Guru
        $data[] = ['type' => 'guru', 'hari' => 'Senin', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Selasa', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Rabu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Kamis', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Jumat', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '11:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Sabtu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '12:00:00', 'jam_pulang_akhir' => '15:00:00'];
        $data[] = ['type' => 'guru', 'hari' => 'Minggu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '12:00:00', 'jam_pulang_akhir' => '15:00:00'];

        // Data Siswa
        $data[] = ['type' => 'siswa', 'hari' => 'Senin', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Selasa', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Rabu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Kamis', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '14:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Jumat', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '11:00:00', 'jam_pulang_akhir' => '17:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Sabtu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '12:00:00', 'jam_pulang_akhir' => '15:00:00'];
        $data[] = ['type' => 'siswa', 'hari' => 'Minggu', 'jam_masuk_mulai' => '06:00:00', 'jam_masuk_akhir' => '07:15:00', 'jam_pulang_mulai' => '12:00:00', 'jam_pulang_akhir' => '15:00:00'];

        // 4. Insert Batch
        $this->db->table('setting_jam')->insertBatch($data);
    }

    public function down()
    {
        // Hapus kolom 'hari' jika rollback
        if ($this->db->fieldExists('hari', 'setting_jam')) {
            $this->forge->dropColumn('setting_jam', 'hari');
        }
        
        // Reset data ke default (opsional, tergantung kebutuhan rollback)
        $this->db->table('setting_jam')->truncate();
    }
}