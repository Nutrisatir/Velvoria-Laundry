<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if (! $session->get('logged_in')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        $userRole = $session->get('user_role');

        if ($arguments !== null && ! in_array($userRole, $arguments, true)) {
            $session->setFlashdata('error', 'Anda tidak memiliki akses ke halaman tersebut.');

            return match ($userRole) {
                'admin'    => redirect()->to('/admin/dashboard'),
                'customer' => redirect()->to('/customer/dashboard'),
                default    => redirect()->to('/'),
            };
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
