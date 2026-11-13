<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');
        $isAjax  = strtolower((string) $request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest'
            || str_contains(strtolower((string) $request->getHeaderLine('Accept')), 'application/json');

        if (! $session->get('logged_in')) {
            if ($isAjax) {
                return service('response')->setStatusCode(401)->setJSON([
                    'success' => false,
                    'error'   => 'Sesi login berakhir. Silakan login kembali.',
                ]);
            }

            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');
            return redirect()->to('/login');
        }

        if ($arguments !== null) {
            $userRole     = $session->get('user_role');
            $allowedRoles = (array) $arguments;

            if (! in_array($userRole, $allowedRoles, true)) {
                if ($isAjax) {
                    return service('response')->setStatusCode(403)->setJSON([
                        'success' => false,
                        'error'   => 'Akses tidak diizinkan untuk akun ini.',
                    ]);
                }

                if ($userRole === 'admin') {
                    return redirect()->to('/admin/dashboard');
                }
                if ($userRole === 'customer') {
                    return redirect()->to('/customer/dashboard');
                }

                return redirect()->to('/');
            }
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Authenticated responses contain private account/order information.
        // Prevent browser and intermediary caches from showing one user's page to another.
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, private, max-age=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');
    }
}
