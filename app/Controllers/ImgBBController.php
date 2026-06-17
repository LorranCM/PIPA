<?php

namespace App\Controllers;

use App\Models\ClassroomModel;

class ImgBBController extends BaseController {

    public function ulpoad_document() {

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $file = $this->request->getFile('file');
        $classroom_id = $this->request->getPost('classroom_id');

        $apiKey = '77d6681ae43ba8cc2e844b3272f28c5b';

        $imageData = base64_encode(
            file_get_contents($file->getTempName())
        );

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.imgbb.com/1/upload?key={$apiKey}",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'image' => $imageData
            ]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao enviar para o ImgBB.'
            ]);
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (
            !isset($result['success']) ||
            !$result['success']
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Falha no upload.'
            ]);
        }

        $fileUrl = $result['data']['url'];
        $deleteUrl = $result['data']['delete_url'];

        $classrooms = new ClassroomModel();
        $classroom_documents = (array) $classrooms->find($classroom_id)['documents'];
        $classroom_documents[$fileUrl] = [
            'name' => $title,
            'description' => $description,
            'deleteUrl' => $deleteUrl
        ];
        $classrooms->update($classroom_id, ['documents' => $classroom_documents]);

        return $this->response->setJSON([
            'success' => true,
        ]);

    }

    public function delete_document() {

        $data = $this->request->getJSON(true);

        $classroom_id = $data['classroom_id'];
        $link = $data['link'];

        $classrooms = new ClassroomModel();

        $classroom = $classrooms->find($classroom_id);
        $documents = $classroom['documents'];

        $deleteUrl = $documents[$link]['deleteUrl'];
        unset($documents[$link]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $deleteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);

        $classrooms->update($classroom_id, [
            'documents' => $documents
        ]);

        return $this->response->setJSON([
            'success' => true,
            'classroom_id' => $classroom_id
        ]);
    }
    
}