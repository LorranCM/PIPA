<?php

namespace App\Models;

use Google\Cloud\Firestore\FirestoreClient;
use App\Models\UserModel;

class EventModel {

    public FirestoreClient $db;

    public function __construct() {

        $this->db = service('firestore')->db;
        
    }

    public function find($id) {
        
        $doc_ref = $this->db->collection('Events')->document($id);
        $snapshot = $doc_ref->snapshot();
        return $snapshot;

    }

    public function create($data, $teacher_id) {

        $users = new UserModel();

        $new_event = $this->db->collection('Events')->add($data);
        $new_event_id = $new_event->id();

        $uid = session()->get('user_data')['uid'];
        $self_calendar    = $users->find($uid)['calendar'];
        $teacher_calendar = $users->find($teacher_id)['calendar'];

        $self_calendar[] = $new_event_id;
        $teacher_calendar[] = $new_event_id;

        $users->update($uid, [
            'calendar' => $self_calendar
        ]);
        $users->update($teacher_id, [
            'calendar' => $teacher_calendar
        ]);

    }

    public function update($id, $data) {

        $doc_ref = $this->db->collection('Events')->document($id);
        $doc_ref->set($data, ['merge' => true]);

    }

    public function delete($id) {

        $doc_ref = $this->db->collection('Events')->document($id);
        $doc_ref->delete();

    }

}