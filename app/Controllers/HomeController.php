<?php

namespace App\Controllers;

class HomeController extends BaseController {
    
    public function index() {

        return redirect()->to(url_to('welcome'));
        
    }

    public function welcome(): string {

        return view('landing');
        
    }

    public function user_home_management() {

        $session = session();
        $role = $session->get('user_data')['role'];
        
        switch ($role) {
            case 'admin':
                return redirect()->to(url_to('admin_home'));
            case 'teacher':
                return redirect()->to(url_to('teacher_home'));
            case 'student':
                return redirect()->to(url_to('student_home'));
            default:
                return redirect()->to(url_to('logout'));
        }
        
    }

    public function error404() {
        return redirect()->to(url_to('welcome'));
    }
}
