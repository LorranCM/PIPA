<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VerifyLogout implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null) {
       
        $session = session();
        if (!$session->get('loggedin')) {
            return redirect()->to(url_to('welcome'));
        }
        
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // ...
    }
}