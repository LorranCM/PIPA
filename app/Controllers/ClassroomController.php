<?php

namespace App\Controllers;

use App\Models\ClassroomModel;
use App\Models\UserModel;

class ClassroomController extends BaseController {

    public function search_class($id) {

        
        $load_data_script = '
        <script type=\'module\'>
            import { get_classroom_calendar } from \'' . base_url("assets/js/fullcalendar/load_calendar_data.js") . '\';
            window.load_calendar_data = get_classroom_calendar.bind(null, \''. $id . '\');
        </script>
        <script type=\'module\'>
            import { renderDocuments } from \'' . base_url("assets/js/display_documents.js") . '\';
            document.addEventListener("DOMContentLoaded", renderDocuments.bind(null, \'' . $id . '\',\''. session()->get('user_data')['role'] . '\'));
        </script>
        ';
        
        $import_path = base_url('assets/js/fullcalendar/student_featured_date.js');
        $script = 
        '
        <script type=\'module\'>
            import { student_event_click } from \'' . $import_path . '\';
            window.date_click_function = student_event_click;
        </script>
        ';
        
        if (session()->get('user_data')['role'] === 'teacher') {
            $import_path = base_url('assets/js/fullcalendar/teacher_featured_date.js');

            $script = 
            '
            <script type=\'module\'>
                import { teacher_event_click } from \'' . $import_path . '\';
                window.date_click_function = teacher_event_click;
            </script>
            ';

        }

        $classrooms = new ClassroomModel();
        $classroom = $classrooms->find($id);
        $isOwner = $classroom['tenured-teacher'] == session()->get('user_data')['uid'];

        session()->setFlashdata('isOwner', $isOwner);
        session()->setFlashdata('classroom_id', $id);

        return $load_data_script. $script . view('classroom');

    }

    public function get_classrooms_basic_info () {

        $classrooms = new ClassroomModel();
        $users = new UserModel();

        $uid = session()->get('user_data')['uid'];
        $user = $users->find($uid);
        $classroom_ids = $user['classrooms'];
        $classrooms_new_list = [];

        foreach ($classroom_ids as $classroom_id) {

            $classroom = $classrooms->find($classroom_id);
            $curricular_unit = $classroom['curricular-unit'];
            $teacher = $users->find($classroom['tenured-teacher']);
            $teacher_name = $teacher['name'];

            $classrooms_new_list[] = [
                'id' => $classroom_id,
                'teacher-name' => $teacher_name,
                'curricular-unit' => $curricular_unit
            ];

        }

        return $this->response->setJSON([
            'classrooms' => $classrooms_new_list
        ]);

    }

    public function get_documents($id) {

        $classrooms = new ClassroomModel();
        $classroom = $classrooms->find($id);

        return $this->response->setJSON([
            'documents' => $classroom['documents']
        ]);

    }

}
