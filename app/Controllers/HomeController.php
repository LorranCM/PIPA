<?php

namespace App\Controllers;

class HomeController extends BaseController {
    
    public function index() {

        return redirect()->to(url_to('welcome'));
        
    }

    public function welcome(): string {

        return view('landing');
        
    }

    public function user_management() {

        $session = session();
        $role = $session->get('user_data')['role'];
        switch ($role) {
            case 'admin':
                return view('admin/user_management');
            case 'editor':
                return view('editor/user_management');
            default:
                return redirect()->to(url_to('welcome'));
        }
        
    }
}
