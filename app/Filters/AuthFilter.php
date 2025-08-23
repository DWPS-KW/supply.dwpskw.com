<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        $currentURI = service('uri')->getSegment(1);
        $excludedURIs = ['login', 'doLogin', 'logout', 'auth'];

        // Allow public routes without login
        if (in_array($currentURI, $excludedURIs)) {
            return;
        }

        // Redirect unauthenticated users
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $session = Services::session();
        $currentURI = service('uri')->getSegment(1);

        // Redirect logged-in users away from login page
        if ($session->get('isLoggedIn') && in_array($currentURI, ['login', 'doLogin'])) {
            return redirect()->to(site_url('employees'));
        }
    }
}
