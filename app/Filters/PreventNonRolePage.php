<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PreventNonRolePage implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null) {
       
        $routeName = service('router')->getMatchedRouteOptions()['as'];
        $session = session();
        $role = $session->get('user_data')['role'];
        switch ($role) {
            case 'student':
                $expectedRoute = 'student_home';
                break;
            case 'teacher':
                $expectedRoute = 'teacher_home';
                break;
            case 'admin':
                $expectedRoute = 'admin_home';
        }
        if ($routeName !== $expectedRoute) {
            return redirect()->to(url_to('home'));
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