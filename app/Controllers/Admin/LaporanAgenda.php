<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\AbsensiAgendaModel;
use App\Models\OrganisasiModel;
use App\Models\AgendaIppmModel;
use App\Models\AgendaMasyarakatModel;
use App\Models\AnggotaModel;
use App\Models\RtModel;

class LaporanAgenda extends BaseController {
    protected $absenAgendaModel;
    protected $organisasiModel;
    protected $ippmModel;
    protected $masyarakatModel;
    protected $anggotaModel;
    protected $rtModel;

    public function __construct() {
        $this->absenAgendaModel = new AbsensiAgendaModel();
        $this->organisasiModel = new OrganisasiModel();
        $this->ippmModel = new AgendaIppmModel();
        $this->masyarakatModel = new AgendaMasyarakatModel();
        $this->anggotaModel = new AnggotaModel();
        $this->rtModel = new RtModel();
    }

    public function index() {
        $data = ['title' => 'Laporan Agenda Organisasi'];
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

        if ($kategori == 'ippm') {
            return view('admin/laporan_agenda/cetak_ippm', $data);
        } else if ($kategori == 'masyarakat') {
            return view('admin/laporan_agenda/cetak_masyarakat', $data);
        }

        return view('admin/laporan_agenda/cetak', $data);
    }

    public function koreksi() {
        $kategori = $this->request->getGet('kategori');
        $tanggal = $this->request->getGet('tanggal');
        $agendaId = $this->request->getGet('agenda_id');
        $rtId = $this->request->getGet('rt_id');
        
        $ippm = $this->ippmModel->findAll();
        $masyarakat = $this->masyarakatModel->findAll();
        $rt = $this->rtModel->findAll();

        $peserta = [];
        if($kategori && $tanggal && $agendaId && $rtId) {
            $peserta = $this->anggotaModel->where('rt_id', $rtId)->findAll();
            
            foreach($peserta as &$p) {
                $absen = $this->absenAgendaModel->where([
                    'user_id' => $p['id'],
                    'tanggal' => $tanggal,
                    'kategori' => $kategori,
                    'agenda_id' => $agendaId
                ])->first();

                if($absen) {
                    $p['status_agenda'] = $absen['status'];
                    $p['id_absen'] = $absen['id'];
                } else {
                    $p['status_agenda'] = 'Alfa';
                    $p['id_absen'] = null;
                }
            }
        }

        $data = [
            'title' => 'Koreksi Agenda',
            'ippm' => $ippm,
            'masyarakat' => $masyarakat,
            'rt' => $rt,
            'peserta' => $peserta,
            'kategori_select' => $kategori,
            'tanggal_select' => $tanggal,
            'agenda_select' => $agendaId,
            'rt_select' => $rtId
        ];

        return view('admin/laporan_agenda/koreksi', $data);
    }

    public function saveKoreksi() {
        $id_absen = $this->request->getPost('id_absen');
        $user_id = $this->request->getPost('user_id');
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
                    'user_type' => 'anggota',
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

        return redirect()->to(base_url('admin/koreksi-agenda') . "?tanggal=$tgl&kategori=$kategori&agenda_id=$agenda_id&rt_id=" . $this->request->getPost('rt_id'))
                         ->with('success', 'Status berhasil diperbarui.');
    }
}