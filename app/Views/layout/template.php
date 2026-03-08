<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Absensi Organisasi' : 'Absensi Organisasi' ?></title>
    <link rel="icon" href="<?= base_url('assets/icon.ico') ?>" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/iconly.css') ?>">
    
    <style>
        body {
            background-color: #f2f7ff;
            transition: background-color 0.3s ease;
        }

        #main {
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #main-content {
            padding: 2rem;
            flex: 1;
            animation: fadeIn 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mini Sidebar Adjustment */
        body.mini-sidebar #main {
            margin-left: 80px;
        }

        /* Mobile Adjustment */
        @media (max-width: 1199.98px) {
            #main { margin-left: 0; }
            body.mini-sidebar #main { margin-left: 0; }
        }
    </style>
</head>

<body>
    <div id="app">
        <?= $this->include('layout/sidebar') ?>
        
        <div id="main">
            <?= $this->include('layout/navbar') ?>
            
            <div id="main-content">
                <?= $this->renderSection('content') ?>
                
                <footer>
                    <div class="footer clearfix mb-0 text-muted mt-5">
                        <div class="float-start">
                            <p>2026 &copy; Absensi Organisasi</p>
                        </div>
                        <div class="float-end">
                            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="#">Dev Team</a></p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    
    <script src="<?= base_url('assets/static/js/components/dark.js') ?>"></script>
    <script src="<?= base_url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi kartu saat load
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = `all 0.5s ease ${index * 0.1}s`;
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            });

            // Logika Otomatis Tutup Sidebar di Mobile
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1200) {
                setTimeout(() => {
                    if (sidebar && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                    }
                }, 1000);
            }
        });
    </script>
</body>

</html>