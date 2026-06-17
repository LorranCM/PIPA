<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ClassroomModel;

class EventController extends BaseController {

    public function cancel_event($id) {
        
        $events = new EventModel();
        $events->delete($id);
        
        return $this->response->setJSON([
            'success' => true
        ]);
    }

    public function schedule_event($classroom_id, $date) {

        $events = new EventModel();
        $classrooms = new ClassroomModel();

        $uid = session()->get('user_data')['uid'];
        $teacher_id = $classrooms->find($classroom_id)['tenured-teacher'];
        $data = [
            'classroom-id' => $classroom_id,
            'date' => $date,
            'description' => 'none',
            'participants' => [$uid],
            'status' => 'pending'
        ];

        $events->create($data, $teacher_id);
        
        return $this->response->setJSON([
            'success' => true
        ]);

    }

    public function confirm_event($id) {
        
        $events = new EventModel();
        $events->update($id, ['status' => 'confirmed']);

        return $this->response->setJSON([
            'success' => true
        ]);
    }

}
