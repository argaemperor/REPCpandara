<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Optional: Batasi berdasarkan level
        if ($arguments) {
            $userLevel = $session->get('level');
            if (! in_array($userLevel, $arguments)) {
                return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
            }
        }
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan
    }
}
