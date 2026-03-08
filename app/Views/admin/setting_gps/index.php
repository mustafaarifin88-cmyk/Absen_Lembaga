<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<style>
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
        padding: 25px 30px;
        color: white;
        position: relative;
    }

    .card-header-gradient::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    #map {
        height: 500px;
        width: 100%;
        border-radius: 15px;
        z-index: 1;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.05);
        border: 2px solid #eef2f7;
    }

    .form-label-custom {
        font-weight: 700;
        color: #607080;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .input-group-text-modern {
        background: #f8f9fa;
        border: 2px solid #eef2f7;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #435ebe;
    }

    .form-control-modern {
        border: 2px solid #eef2f7;
        border-radius: 0 12px 12px 0;
        padding: 10px 15px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .form-control-modern:focus {
        border-color: #435ebe;
        box-shadow: none;
    }

    .btn-save {
        background: linear-gradient(90deg, #435ebe, #25396f);
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(67, 94, 190, 0.3);
        transition: all 0.3s;
        color: white;
        width: 100%;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 94, 190, 0.4);
        color: white;
    }

    .btn-location {
        background: #eef2ff;
        color: #435ebe;
        border: 1px solid #435ebe;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        transition: all 0.3s;
        width: 100%;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-location:hover {
        background: #435ebe;
        color: white;
    }

    .radius-badge {
        background: #e9ecef;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        color: #495057;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 text-primary fw-bold">Lokasi Sekolah</h3>
            <p class="text-subtitle text-muted">Atur titik koordinat dan radius area absensi.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Setting GPS</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-content">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-lg-8 mb-4">
            <div class="card card-modern h-100">
                <div class="card-body p-3">
                    <div id="map"></div>
                    <div class="mt-3 d-flex align-items-center text-muted small">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                        <span>Klik pada peta untuk memindahkan titik lokasi sekolah secara manual. Lingkaran biru menunjukkan batas area radius.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card card-modern">
                <div class="card-header-gradient">
                    <h5 class="text-white fw-bold mb-0"><i class="bi bi-geo-alt me-2"></i> Koordinat</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/setting-gps/save') ?>" method="post">
                        <?= csrf_field() ?>

                        <button type="button" class="btn-location" onclick="getLocation()">
                            <i class="bi bi-crosshair"></i> Ambil Lokasi Saya Saat Ini
                        </button>

                        <div class="mb-3">
                            <label class="form-label-custom">Latitude</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern">Lat</span>
                                <input type="text" id="latitude" name="latitude" 
                                       class="form-control form-control-modern" 
                                       value="<?= isset($setting['latitude']) ? $setting['latitude'] : '-6.200000' ?>" required readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom">Longitude</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern">Lng</span>
                                <input type="text" id="longitude" name="longitude" 
                                       class="form-control form-control-modern" 
                                       value="<?= isset($setting['longitude']) ? $setting['longitude'] : '106.816666' ?>" required readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label-custom mb-0">Radius Absensi</label>
                                <span class="radius-badge" id="radius-val"><?= isset($setting['radius_meter']) ? $setting['radius_meter'] : '200' ?> m</span>
                            </div>
                            <div class="input-group">
                                <input type="number" id="radius" name="radius_meter" 
                                       class="form-control form-control-modern" 
                                       style="border-radius: 12px 0 0 12px; border-left: 2px solid #eef2f7;"
                                       value="<?= isset($setting['radius_meter']) ? $setting['radius_meter'] : '200' ?>" 
                                       oninput="updateRadius(this.value)" required>
                                <span class="input-group-text bg-white border-2 border-start-0" style="border-color: #eef2f7; border-radius: 0 12px 12px 0;">Meter</span>
                            </div>
                            <input type="range" class="form-range mt-2" min="10" max="1000" step="10" 
                                   value="<?= isset($setting['radius_meter']) ? $setting['radius_meter'] : '200' ?>" 
                                   oninput="syncRadiusInput(this.value)">
                        </div>

                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-save-fill me-2"></i> Simpan Koordinat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var initialLat = <?= isset($setting['latitude']) ? $setting['latitude'] : '-6.200000' ?>;
    var initialLng = <?= isset($setting['longitude']) ? $setting['longitude'] : '106.816666' ?>;
    var initialRadius = <?= isset($setting['radius_meter']) ? $setting['radius_meter'] : '200' ?>;

    var map = L.map('map').setView([initialLat, initialLng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
    var circle = L.circle([initialLat, initialLng], {
        color: '#435ebe',
        fillColor: '#435ebe',
        fillOpacity: 0.2,
        radius: initialRadius
    }).addTo(map);

    function updateMap(lat, lng) {
        marker.setLatLng([lat, lng]);
        circle.setLatLng([lat, lng]);
        map.panTo([lat, lng]);
        
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    }

    map.on('click', function(e) {
        updateMap(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        var position = marker.getLatLng();
        updateMap(position.lat, position.lng);
    });

    function updateRadius(val) {
        var radius = parseInt(val);
        circle.setRadius(radius);
        document.getElementById('radius-val').innerText = radius + ' m';
        document.querySelector('input[type="range"]').value = radius;
    }

    function syncRadiusInput(val) {
        document.getElementById('radius').value = val;
        updateRadius(val);
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                updateMap(lat, lng);
                map.setView([lat, lng], 18);
            }, function(error) {
                alert("Gagal mengambil lokasi. Pastikan GPS aktif.");
            });
        } else {
            alert("Browser tidak mendukung Geolocation.");
        }
    }
</script>

<?= $this->endSection() ?>