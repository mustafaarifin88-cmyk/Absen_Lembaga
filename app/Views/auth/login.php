<?php 
    helper('theme'); 
    $theme = get_theme_setting(); 
    $bgStyle = ($theme['login_bg_type'] == 'image') 
        ? "url('".base_url('uploads/theme/'.$theme['login_bg_value'])."')" 
        : $theme['login_bg_value'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            background: <?= $bgStyle ?>;
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 10px;
        }
        .btn-login {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            background: #25396f;
            border: none;
        }
        .btn-login:hover { background: #1e3a8a; }
        .eye-icon {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 13px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark">Selamat Datang</h3>
            <p class="text-muted">Silakan login ke akun Anda</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger text-sm"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group mb-3">
                <div class="position-relative">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    <i class="bi bi-person position-absolute" style="right: 15px; top: 13px; color: #aaa;"></i>
                </div>
            </div>
            <div class="form-group mb-4">
                <div class="position-relative">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <i class="bi bi-eye-slash eye-icon" id="togglePassword"></i>
                </div>
            </div>
            <button class="btn btn-primary btn-block btn-login w-100 shadow">Masuk</button>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>