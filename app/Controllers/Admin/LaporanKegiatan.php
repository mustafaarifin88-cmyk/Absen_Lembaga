<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AbsensiKegiatanModel;
use App\Models\SekolahModel;
use App\Models\KegiatanSholatModel;
use App\Models\KegiatanEkskulModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;

class LaporanKegiatan extends BaseController {
    protected $absenKegiatanModel;
    protected $sekolahModel;
    protected $sholatModel;
    protected $ekskulModel;
    protected $siswaModel;
    protected $kelasModel;

    public function __construct() {
        $this->absenKegiatanModel = new AbsensiKegiatanModel();
        $this->sekolahModel = new SekolahModel();
        $this->sholatModel = new KegiatanSholatModel();
        $this->ekskulModel = new KegiatanEkskulModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    public function index() {
        $data = ['title' => 'Laporan Kegiatan Tambahan'];
        return view('admin/laporan_kegiatan/index', $data);
    }

    public function cetak() {
        $tglAwal = $this->request->getPost('tgl_awal');
        $tglAkhir = $this->request->getPost('tgl_akhir');
        $kategori = $this->request->getPost('kategori');

        $data = [
            'laporan' => $this->absenKegiatanModel->getLaporan($tglAwal, $tglAkhir, $kategori),
            'sekolah' => $this->sekolahModel->first(),
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'kategori' => $kategori
        ];
        return view('admin/laporan_kegiatan/cetak', $data);
    }

    // --- LOGIKA KOREKSI KEGIATAN ---
    
    public function koreksi() {
        $tgl = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kategori = $this->request->getGet('kategori'); // sholat/ekskul
        $kegiatan_id = $this->request->getGet('kegiatan_id');
        $kelas_id = $this->request->getGet('kelas_id');

        $dataSiswa = [];
        $dataAbsensi = [];
        
        // Load data master kegiatan untuk dropdown filter
        $listSholat = $this->sholatModel->findAll();
        $listEkskul = $this->ekskulModel->findAll();
        $listKelas = $this->kelasModel->findAll();

        // Nama Kegiatan Terpilih
        $namaKegiatan = '';
        if ($kategori == 'sholat' && $kegiatan_id) {
            $k = $this->sholatModel->find($kegiatan_id);
            if ($k) $namaKegiatan = $k['nama_sholat'];
        } elseif ($kategori == 'ekskul' && $kegiatan_id) {
            $k = $this->ekskulModel->find($kegiatan_id);
            if ($k) $namaKegiatan = $k['nama_ekskul'];
        }

        if ($tgl && $kategori && $kegiatan_id) {
            // 1. Ambil Siswa (Filter Kelas jika ada, default tampilkan semua siswa aktif)
            $builder = $this->siswaModel->select('siswa.id, siswa.nama_lengkap, siswa.nisn, kelas.nama_kelas, kelas.jurusan')
                                        ->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
            if ($kelas_id) {
                $builder->where('siswa.kelas_id', $kelas_id);
            }
            $dataSiswa = $builder->orderBy('nama_lengkap', 'ASC')->findAll();

            // 2. Ambil Data Absensi yang SUDAH ADA di tanggal & kegiatan tersebut
            $absenExist = $this->absenKegiatanModel->where('tanggal', $tgl)
                                                   ->where('kategori', $kategori)
                                                   ->where('kegiatan_id', $kegiatan_id)
                                                   ->where('user_type', 'siswa')
                                                   ->findAll();
            
            // Map Absensi by User ID
            $mapAbsen = [];
            foreach($absenExist as $row) {
                $mapAbsen[$row['user_id']] = $row;
            }

            // 3. Gabungkan Data (Jika tidak ada di map, status = Alfa)
            foreach($dataSiswa as $s) {
                $status = 'Alfa'; // Default
                $jam = '-';
                $idAbsen = null;
                $ket = '';

                if(isset($mapAbsen[$s['id']])) {
                    $status = $mapAbsen[$s['id']]['status'];
                    $jam = $mapAbsen[$s['id']]['jam_absen'];
                    $idAbsen = $mapAbsen[$s['id']]['id'];
                    $ket = isset($mapAbsen[$s['id']]['keterangan']) ? $mapAbsen[$s['id']]['keterangan'] : ''; // Pastikan field keterangan ada di DB jika perlu
                }

                $dataAbsensi[] = [
                    'user_id' => $s['id'],
                    'nama_lengkap' => $s['nama_lengkap'],
                    'nisn' => $s['nisn'],
                    'kelas' => $s['nama_kelas'] . ' ' . $s['jurusan'],
                    'status' => $status,
                    'jam_absen' => $jam,
                    'id_absen' => $idAbsen,
                    'keterangan' => $ket
                ];
            }
        }

        $data = [
            'title' => 'Koreksi Absensi Kegiatan',
            'list_sholat' => $listSholat,
            'list_ekskul' => $listEkskul,
            'list_kelas' => $listKelas,
            'data_absensi' => $dataAbsensi,
            'p_tgl' => $tgl,
            'p_kategori' => $kategori,
            'p_kegiatan' => $kegiatan_id,
            'p_kelas' => $kelas_id,
            'p_nama_kegiatan' => $namaKegiatan
        ];
        return view('admin/laporan_kegiatan/koreksi', $data);
    }

    public function saveKoreksi() {
        $id_absen = $this->request->getPost('id_absen');
        $user_id = $this->request->getPost('user_id');
        $status = $this->request->getPost('status');
        
        // Data konteks (diperlukan jika insert baru)
        $tgl = $this->request->getPost('tanggal');
        $kategori = $this->request->getPost('kategori');
        $kegiatan_id = $this->request->getPost('kegiatan_id');
        $nama_kegiatan = $this->request->getPost('nama_kegiatan');

        if($id_absen) {
            // Update Existing
            $this->absenKegiatanModel->update($id_absen, ['status' => $status]);
        } else {
            // Insert Baru (Koreksi dari Alfa ke Hadir/Sakit/Izin)
            // Jika status Alfa, dan data belum ada, tidak perlu insert (karena defaultnya memang Alfa secara logika view)
            // Tapi jika admin ingin mencatat 'Sakit'/'Izin' secara eksplisit, kita insert.
            if($status != 'Alfa') {
                $this->absenKegiatanModel->save([
                    'user_type' => 'siswa', // Default siswa untuk fitur ini
                    'user_id' => $user_id,
                    'kategori' => $kategori,
                    'kegiatan_id' => $kegiatan_id,
                    'nama_kegiatan' => $nama_kegiatan,
                    'tanggal' => $tgl,
                    'jam_absen' => date('H:i:s'), // Set jam saat ini atau 00:00:00
                    'status' => $status,
                    'lokasi_lat' => '-',
                    'lokasi_long' => '-'
                ]);
            }
        }

        return redirect()->to(base_url('admin/koreksi-kegiatan') . "?tanggal=$tgl&kategori=$kategori&kegiatan_id=$kegiatan_id&kelas_id=" . $this->request->getPost('kelas_id'))
                         ->with('success', 'Status berhasil diperbarui.');
    }

    public function deleteKoreksi($id) {
        $this->absenKegiatanModel->delete($id);
        return redirect()->back()->with('success', 'Data absensi kegiatan dihapus (Kembali ke Alfa).');
    }
}