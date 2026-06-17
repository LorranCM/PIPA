<?php

namespace App\Controllers;

class StudentController extends BaseController {

    public function index() {
        
        $import_path = base_url('assets/js/fullcalendar/student_featured_date.js');

        $script = 
        '
        <script type=\'module\'>
            import { student_event_click } from \'' . $import_path . '\';
            window.date_click_function = student_event_click;
        </script>
        ';
        
        return $script . view('user_home');
        
    }

}
