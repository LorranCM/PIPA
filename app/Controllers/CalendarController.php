<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EventModel;
use App\Models\ClassroomModel;

class CalendarController extends BaseController {

    
    private function get_calendar_data_by_userID($uid) {

        $users = new UserModel();
        $events = new EventModel();
        $classrooms = new ClassroomModel();

        $user = $users->find($uid);
        $role = $user['role'];

        $event_ids = $user['calendar'];
        $response_events = [];

        foreach ($event_ids as $event_id) {

            $event = $events->find($event_id);
            if (!$event->exists()) {
                unset($event_ids[array_search($event_id, $event_ids)]);
                $event_ids = array_values($event_ids);
                continue;
            }

            $event_participants = $event['participants'];
            if (!in_array($uid, $event_participants) && $role == 'student') {
                $event_participants[] = $uid;
                $events->update($event_id, ['participants' => $event_participants]);
            }

            $event_data = $event->data();
            $classroom_id = $event_data['classroom-id'];

            $classroom = $classrooms->find($classroom_id);
            $curricular_unit = $classroom['curricular-unit'];
            $teacher_id = $classroom['tenured-teacher'];
            if ($teacher_id === "") {
                $teacher_id = "placeholder-teacher-id";
            }

            $tenured_teacher = $users->find($teacher_id);
            if ($tenured_teacher->exists()) {
                $teacher_name = $tenured_teacher['name'];
            } else {
                $teacher_name = "Unknown Teacher";
            }

            $event_data['curricular-unit'] = $curricular_unit;
            $event_data['classroom-id'] = $classroom_id;
            $event_data['teacher'] = $teacher_name;
            $event_data['teacher-id'] = $teacher_id;
            $event_data['event-id'] = $event_id;
    
            $response_events[] = $event_data;

        }
        
        $users->update($uid, ['calendar' => $event_ids]);
    
        return $response_events;

    }

    public function get_calendar_data_by_classroomID($id) {

        return 1;

    }

    public function get_user_calendar_data($id = null) {

        if (!$id) $id = session()->get('user_data')['uid'];
        $events = $this->get_calendar_data_by_userID($id);
        return $this->response->setJSON([
            'events' => $events,
            'uid' => $id
        ]);

    }

}
