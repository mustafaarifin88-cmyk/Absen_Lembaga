<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AbsensiAgendaModel;
use App\Models\OrganisasiModel;
use App\Models\AgendaIppmModel;
use App\Models\AgendaMasyarakatModel;
use App\Models\AnggotaModel;
use App\Models\PengurusModel;
use App\Models\RtModel;

class LaporanAgenda extends BaseController {
    protected $absenAgendaModel;
    protected $organisasiModel;
    protected $ippmModel;
    protected $masyarakatModel;
    protected $anggotaModel;
    protected $pengurusModel;
    protected $rtModel;

    public function __construct() {
        $this->absenAgendaModel = new AbsensiAgendaModel();
        $this->organisasiModel = new OrganisasiModel();
        $this->ippmModel = new AgendaIppmModel();
        $this->masyarakatModel = new AgendaMasyarakatModel();
        $this->anggotaModel = new AnggotaModel();
        $this->pengurusModel = new PengurusModel();
        $this->rtModel = new RtModel();
    }

    public function index() {
        $data = [
            'title' => 'Laporan Agenda Organisasi',
            'list_ippm' => $this->ippmModel->findAll(),
            'list_masyarakat' => $this->masyarakatModel->findAll()
        ];
        return view('admin/laporan_agenda/index', $data);
    }

    public function cetak() {
        $tglAwal = $this->request->getPost('tgl_awal');
        $tglAkhir = $this->request->getPost('tgl_akhir');
        $kategori = $this->request->getPost('kategori');
        $agendaId = $this->request->getPost('agenda_id');

        $laporan = $this->absenAgendaModel->getLaporan($tglAwal, $tglAkhir, $kategori, $agendaId);
        $organisasi = $this->organisasiModel->first();

        $data = [
            'laporan' => $laporan,
            'organisasi' => $organisasi,
            'tgl_awal' => $tglAwal,
            'tgl_akhir' => $tglAkhir,
            'kategori' => $kategori
        ];

        if($kategori == 'ippm') {
            return view('admin/laporan_agenda/cetak_ippm', $data);
        } else {
            return view('admin/laporan_agenda/cetak_masyarakat', $data);
        }
    }

    public function cetakMatriksBulanan() {
        $bulan = $this->request->getPost('bulan') ?? date('m');
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $kategori = $this->request->getPost('kategori');
        $agendaId = $this->request->getPost('agenda_id');
        $userType = $this->request->getPost('user_type') ?? 'anggota';

        $users = [];
        if ($userType == 'pengurus') {
            $users = $this->pengurusModel->findAll();
        } else {
            $users = $this->anggotaModel->findAll();
        }

        $rekap = $this->absenAgendaModel->getRekapBulanan($bulan, $tahun, $userType, $kategori, $agendaId);

        $agendaName = '';
        $hariAgenda = '';
        if ($kategori == 'ippm') {
            $ag = $this->ippmModel->find($agendaId);
            if($ag) { $agendaName = $ag['nama_agenda']; $hariAgenda = $ag['hari']; }
        } else {
            $ag = $this->masyarakatModel->find($agendaId);
            if($ag) { $agendaName = $ag['nama_agenda']; $hariAgenda = $ag['hari']; }
        }

        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $agendaDates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for($d=1; $d<=$daysInMonth; $d++) {
            $currentDate = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
            $dayNameInggris = date('l', strtotime($currentDate));
            if ($mapHari[$dayNameInggris] == $hariAgenda) {
                $agendaDates[] = $currentDate;
            }
        }

        $data = [
            'users' => $users,
            'rekap' => $rekap,
            'organisasi' => $this->organisasiModel->first(),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'agendaDates' => $agendaDates,
            'agendaName' => $agendaName,
            'namaBulan' => ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        ];

        return view('admin/laporan_agenda/cetak_matriks_bulan', $data);
    }

    public function cetakMatriksTahunan() {
        $tahun = $this->request->getPost('tahun') ?? date('Y');
        $kategori = $this->request->getPost('kategori');
        $agendaId = $this->request->getPost('agenda_id');
        $userType = $this->request->getPost('user_type') ?? 'anggota';

        $users = [];
        if ($userType == 'pengurus') {
            $users = $this->pengurusModel->findAll();
        } else {
            $users = $this->anggotaModel->findAll();
        }

        $rekapTahun = $this->absenAgendaModel->getRekapTahunan($tahun, $userType, $kategori, $agendaId);

        $agendaName = '';
        $hariAgenda = '';
        if ($kategori == 'ippm') {
            $ag = $this->ippmModel->find($agendaId);
            if($ag) { $agendaName = $ag['nama_agenda']; $hariAgenda = $ag['hari']; }
        } else {
            $ag = $this->masyarakatModel->find($agendaId);
            if($ag) { $agendaName = $ag['nama_agenda']; $hariAgenda = $ag['hari']; }
        }

        $mapHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $agendaCountPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $count = 0;
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $tahun);
            for($d=1; $d<=$daysInMonth; $d++) {
                $currentDate = sprintf("%04d-%02d-%02d", $tahun, $m, $d);
                if ($mapHari[date('l', strtotime($currentDate))] == $hariAgenda) {
                    $count++;
                }
            }
            $agendaCountPerMonth[$m] = $count;
        }

        $data = [
            'users' => $users,
            'rekap' => $rekapTahun,
            'organisasi' => $this->organisasiModel->first(),
            'tahun' => $tahun,
            'agendaCountPerMonth' => $agendaCountPerMonth,
            'agendaName' => $agendaName
        ];

        return view('admin/laporan_agenda/cetak_matriks_tahun', $data);
    }

    public function koreksi() {
        $p_kategori = $this->request->getGet('kategori');
        $p_tanggal = $this->request->getGet('tanggal');
        $p_agenda = $this->request->getGet('agenda_id');
        
        $laporan = [];
        if($p_kategori && $p_tanggal) {
            $laporan = $this->absenAgendaModel->getLaporan($p_tanggal, $p_tanggal, $p_kategori, $p_agenda);
        }

        $data = [
            'title' => 'Koreksi Absen Agenda',
            'laporan' => $laporan,
            'p_kategori' => $p_kategori,
            'p_tanggal' => $p_tanggal,
            'p_agenda' => $p_agenda,
            'list_ippm' => $this->ippmModel->findAll(),
            'list_masyarakat' => $this->masyarakatModel->findAll()
        ];
        return view('admin/laporan_agenda/koreksi', $data);
    }

    public function saveKoreksi() {
        $id_absen = $this->request->getPost('id_absen');
        $user_id = $this->request->getPost('user_id');
        $user_type = $this->request->getPost('user_type');
        $status = $this->request->getPost('status');
        
        $kategori = $this->request->getPost('kategori');
        $tgl = $this->request->getPost('tanggal');
        $agenda_id = $this->request->getPost('agenda_id');

        $agendaName = '';
        if($kategori == 'ippm') {
            $ag = $this->ippmModel->find($agenda_id);
            $agendaName = $ag['nama_agenda'];
        } else {
            $ag = $this->masyarakatModel->find($agenda_id);
            $agendaName = $ag['nama_agenda'];
        }

        if($id_absen) {
            $this->absenAgendaModel->update($id_absen, ['status' => $status]);
        } else {
            if($status != 'Alfa') {
                $this->absenAgendaModel->save([
                    'user_type' => $user_type,
                    'user_id' => $user_id,
                    'kategori' => $kategori,
                    'agenda_id' => $agenda_id,
                    'nama_agenda' => $agendaName,
                    'tanggal' => $tgl,
                    'jam_absen' => date('H:i:s'),
                    'status' => $status,
                    'lokasi_lat' => '-',
                    'lokasi_long' => '-'
                ]);
            }
        }

        return redirect()->to(base_url('admin/koreksi-agenda') . "?tanggal=$tgl&kategori=$kategori&agenda_id=$agenda_id")
                         ->with('success', 'Status berhasil diperbarui.');
    }

    public function deleteKoreksi($id) {
        $this->absenAgendaModel->delete($id);
        return redirect()->back()->with('success', 'Data absensi dihapus.');
    }
}