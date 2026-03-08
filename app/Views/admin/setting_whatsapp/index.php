<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); /* Warna WA */
        padding: 30px;
        color: white;
    }
    .form-control-modern {
        border: 2px solid #eef2f7;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    .form-control-modern:focus {
        border-color: #25D366;
        box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.1);
    }
    .btn-save {
        background: #128C7E;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 700;
        transition: all 0.3s;
    }
    .btn-save:hover {
        background: #075E54;
        transform: translateY(-2px);
        color: white;
    }
</style>

<div class="page-heading mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1 text-success fw-bold">Konfigurasi WhatsApp</h3>
            <p class="text-subtitle text-muted">Atur koneksi ke Server WhatsApp Gateway (Ngrok/VPS).</p>
        </div>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>Swal.fire({icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, showConfirmButton: false})</script>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern">
                <div class="card-header-gradient">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-whatsapp fs-1 me-3"></i>
                        <div>
                            <h5 class="mb-0 text-white">Gateway Connection</h5>
                            <small class="text-white text-opacity-75">Pastikan server Node.js & Ngrok sudah berjalan.</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-5">
                    <form action="<?= base_url('admin/setting-whatsapp/save') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">URL Gateway (Ngrok)</label>
                            <input type="url" name="wa_gateway_url" class="form-control form-control-modern" 
                                   placeholder="Contoh: https://abcd-1234.ngrok-free.app/api/send-message"
                                   value="<?= $setting['wa_gateway_url'] ?>" required>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i> Masukkan URL lengkap dari Ngrok (diakhiri <code>/api/send-message</code>).
                            </small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Security Token</label>
                            <input type="text" name="wa_api_token" class="form-control form-control-modern" 
                                   placeholder="Token Rahasia (Samakan dengan server.js)"
                                   value="<?= $setting['wa_api_token'] ?>" required>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i> <code>Jangan Ganti Token Ini Karena Sudah di Setting di Server.js nya (Kalau Mau Ganti Ini ganti Juga di File Server.js nya)</code>
                            </small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-save shadow-lg">
                                <i class="bi bi-save-fill me-2"></i> Simpan Konfigurasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>