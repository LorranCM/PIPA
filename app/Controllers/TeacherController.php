<?php

namespace App\Controllers;

class TeacherController extends BaseController {

    public function index() {

        $import_path = base_url('assets/js/fullcalendar/teacher_featured_date.js');

        $script = 
        '
        <script type=\'module\'>
            import { teacher_event_click } from \'' . $import_path . '\';
            window.date_click_function = teacher_event_click;
        </script>
        ';
        
        return $script . view('user_home');
        
    }

}
