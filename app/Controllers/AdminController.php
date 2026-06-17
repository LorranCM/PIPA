<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClassroomModel;

class AdminController extends BaseController
{
    public function index()
    {
        return view('admin_home');
    }

    public function create_user_form() {

        return view('create_user');

    }
    
    public function create_classroom_form() {

        return view('create_classroom');

    }

    public function users()
    {
        $userModel = new UserModel();

        $documents = $userModel->find_all();

        $items = [];

        foreach ($documents as $doc) {
            $name = $doc['name'] ?? "undefined";
            $lastname = $doc['lastname'] ?? "undefined";
            $registration = $doc['registration'] ?? "undefined";
            $id = $doc->id();
            $items[] = [
                'display' => $name . " " . $lastname . "   " . $registration,
                'id' => $id
            ];
        };

        return view('list_users', ['items' => $items]);
    }

    public function classrooms()
    {
        $classroomModel = new ClassroomModel();

        $documents = $classroomModel->find_all();

        $items = [];

        foreach ($documents as $doc) {
            $curricular_unit = $doc['curricular-unit'];
            $id = $doc->id();
            $items[] = [
                'display' => $curricular_unit,
                'id' => $id
            ];
        }

        return view('list_classrooms', ['items' => $items ]);
    }

    public function user($uid) {

        $userModel = new UserModel();

        $user = $userModel->find($uid);

        return view('user_form', [
            'user' => $user,
            'id' => $uid
        ]);
    }

    public function classroom($id) {

        $classroomModel = new ClassroomModel();

        $classroom = $classroomModel->find($id);

        return view('classroom_form', [
            'classroom' => $classroom,
            'id' => $id
        ]);
    }

    public function update_user($id) {

        $userModel = new UserModel();
        $userSnapshot = $userModel->find($id);

        $userData = $userSnapshot->data();

        // 1. Dados comuns que sempre vêm do formulário
        $dataToUpdate = [
            'name'                => $this->request->getPost('name'),
            'lastname'            => $this->request->getPost('lastname'),
            'registration'        => $this->request->getPost('registration'),
            'entry-semester'      => $this->request->getPost('entry-semester'),
            'registration-status' => $this->request->getPost('registration-status'),
        ];

        // 2. Condicional do Campo Course (Só atualiza se não for professor)
        // Se for professor, o campo 'course' nem vai para o Firestore, mantendo o que já estava lá (se houver)
        if (($userData['role'] ?? '') !== 'teacher') {
            $dataToUpdate['course'] = $this->request->getPost('course');
        }

        // 3. Tratamento do Array de Salas (Classrooms)
        $classrooms = $this->request->getPost('classrooms') ?? [];
        $dataToUpdate['classrooms'] = array_values(array_filter($classrooms, function($value) {
            return trim($value) !== '';
        }));

        // 4. Tratamento da Disponibilidade (Apenas para professores)
        // 4. Tratamento da Disponibilidade (Apenas para professores)
        if (($userData['role'] ?? '') === 'teacher') {
            $availabilityActive = $this->request->getPost('availability_active') ?? [];
            $availabilityTimes  = $this->request->getPost('availability') ?? [];
            
            $availabilityMap = [];
            $diasSemana = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            foreach ($diasSemana as $day) {
                // Alimenta o mapa APENAS se o dia foi marcado E possui um horário
                if (isset($availabilityActive[$day]) && !empty($availabilityTimes[$day])) {
                    $availabilityMap[$day] = $availabilityTimes[$day];
                }
            }

            // O campo 'availability' receberá apenas as chaves ativas (ex: ['wednesday' => '07:00-11:00'])
            // Qualquer chave antiga que não esteja aqui será eliminada do mapa no Firestore
            $dataToUpdate['availability'] = $availabilityMap;
        }

        // 5. Salva as alterações
        try {
            $userModel->update_fields($id, $dataToUpdate);
            
            return redirect()->route('admin_users')->with('success', 'Dados atualizados com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin_users')->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

   public function update_classroom($id) {

        $curricularUnit = $this->request->getPost('curricular_unit');
        $tenuredTeacher = $this->request->getPost('tenured_teacher');

        if (empty($curricularUnit) || empty($tenuredTeacher)) {
            return redirect()->redirect('admin_classrooms')->with('error', 'Todos os campos são obrigatórios.');
        }

        $data = [
            'curricular-unit' => $curricularUnit,
            'tenured-teacher' => $tenuredTeacher,
        ];

        $classroomModel = new ClassroomModel();
        
        if ($classroomModel->update($id, $data)) {
            return redirect()->route('admin_classrooms')->with('success', 'Sala atualizada com sucesso!');
        }

        // 5. Caso ocorra erro na persistência
        return redirect()->route('admin_classrooms')->with('error', 'Não foi possível atualizar a sala. Tente novamente.');

    }

    public function store_user() {

        $userModel = new UserModel();
        
        $email    = $this->request->getPost('registration');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        // Agora capturamos nome e sobrenome para TODOS os perfis
        $userData = [
            'role'         => $role,
            'registration' => $this->request->getPost('registration'),
            'name'         => $this->request->getPost('name'),
            'lastname'     => $this->request->getPost('lastname'),
        ];

        $rawAvailability = $this->request->getPost('availability'); // Ex: ['monday' => '08:00', 'friday' => '']
        $cleanAvailability = [];

        // Condicionais para dados extras
        if ($role === 'student') {
            $userData['course'] = $this->request->getPost('course');

        } elseif ($role === 'teacher' && is_array($rawAvailability)) {
            foreach ($rawAvailability as $day => $time) {
                // Só adiciona ao banco se o horário não estiver vazio
                if (!empty(trim($time))) {
                    $cleanAvailability[$day] = trim($time);
                }
            }
            $userData['availability'] = $cleanAvailability;
        }
                
        if ($role !== 'admin') {       
            $userData['classrooms'] = $this->request->getPost('classrooms');
            $userData['entry-semester'] = $this->request->getPost('entry-semester');
            $userData['registration-status'] = $this->request->getPost('registration-status');
            $userData['calendar'] = [];
            $userData['profile-picture-rel'] = "";
        }

        $uid = $userModel->create($userData, $email, $password);

        if ($uid) {
            return redirect()->route('admin_users')->with('success', 'Usuário criado com sucesso!');
        } else {
            return redirect()->route('admin_users')->with('error', 'Erro ao criar usuário.');
        }

    }

    public function store_classroom() {

        $data = [
            'curricular-unit' => $this->request->getPost('curricular_unit'),
            'tenured-teacher' => $this->request->getPost('tenured_teacher'),
            'documents' => []
        ];

        $classroomModel = new ClassroomModel();
        
        if ($classroomModel->create($data)) {
            return redirect()->route('admin_classrooms')
                            ->with('success', 'Sala criada com sucesso!');
        } else {
            return redirect()->route('admin_classrooms')
                            ->with('error', 'Erro ao salvar sala.');
        }
    }

    public function delete_user($id) {
        $userModel = new UserModel();
        
        // Tenta excluir
        $deleted = $userModel->delete($id);

        if ($deleted) {
            // Redireciona para a rota da listagem com mensagem de sucesso
            return redirect()->route('admin_users')->with('success', 'Usuário excluído com sucesso!');
        } else {
            return redirect()->route('admin_users')->with('error', 'Erro ao excluir usuário.');
        }
    }

    public function delete_classroom($id) {

        $classroomModel = new ClassroomModel();
        
        // Tenta excluir
        $deleted = $classroomModel->delete($id);

        if ($deleted) {
            return redirect()->route('admin_classrooms')->with('success', 'Sala excluída com sucesso!');
        } else {
            return redirect()->route('admin_classrooms')->with('error', 'Erro ao excluir sala.');
        }
    }

}