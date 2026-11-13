<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GuestFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = service('session');

        if ($session->get('logged_in')) {
            $userRole = $session->get('user_role');

            return match ($userRole) {
                'admin', 'staff' => redirect()->to('/admin/dashboard'),
                'customer'       => redirect()->to('/customer/dashboard'),
                default          => redirect()->to('/'),
            };
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
