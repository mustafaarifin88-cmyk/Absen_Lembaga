<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/');
        }

        if (! empty($arguments)) {
            $level = session()->get('level');
            if (! in_array($level, $arguments)) {
                if ($level == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } else {
                    return redirect()->to('/petugas/dashboard');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}