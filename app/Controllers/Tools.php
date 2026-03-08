<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Tools extends BaseController
{
    public function index()
    {
        // Password yang diminta
        $password = '123456';
        
        // Membuat Hash
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Menampilkan hasil
        echo "<h3>Password Generator</h3>";
        echo "Password Asli: <b>" . $password . "</b><br>";
        echo "Hash Algoritma (Bcrypt): <br>";
        echo "<textarea cols='100' rows='3' style='margin-top:10px;'>" . $hash . "</textarea>";
        echo "<br><br><small>Copy hash di dalam kotak text area di atas dan masukkan ke kolom password di database (tabel users).</small>";
    }
}