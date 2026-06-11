<?php

namespace App\Controllers;

use App\Libraries\FirestoreService;
use App\Models\UserModel;

class AuthController extends BaseController {

    public function login(): string {
        // Tela de login
        return view('auth/login');
        
    }

    public function recovery(): string {
        // Tela de recuperação de senha
        return view('auth/recovery');
        
    }

    public function validation() {
        // Validação do token gerado pelo front do login
        
        $data = $this->request->getJSON(true);
        $token = $data['token'] ?? 'missing-token';

        $uid = (new FirestoreService())->validate_token($token);

        if ($uid === null) {
            return $this->response->setJSON([
                'success' => false,
            ]);
        }

        $users = new UserModel();
        $user_snapshot = $users->find($uid, true);
        $user_data = $user_snapshot->data();
        $user_data['uid'] = $uid;

        session()->set('user_data', $user_data);
        session()->set('loggedin', true);

        return $this->response->setJSON([
            'success' => true,
            'redirect' => url_to('welcome')
        ]);
    }

    public function logout() {
        // Encerramento da sessão
        session()->destroy();
        return redirect()->to(url_to('index'));
    }
}
