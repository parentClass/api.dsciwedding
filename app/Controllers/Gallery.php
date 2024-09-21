<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GalleryModel;

class Gallery extends BaseController
{
    public function list() {
        $orderedGallery = [];
        $galleryModel = new GalleryModel();

        // retrieve gallery ordered by created_at
        $galleries = $galleryModel->orderBy('created_at', 'desc')->findAll();

        // loop through records and add actual path
        foreach($galleries as $gallery) {
            array_push($orderedGallery, 'https://api.dsciwedding.com/uploads/wedding/' . $gallery['file_name']);
        }

        return json_encode($orderedGallery);
    }

    public function upload() {
        $galleryModel = new GalleryModel();

        $resp = [
            "wedding" => [
                "success" => [],
                "failed" => []
            ]
        ];

        $gallery = [];

        try {
            // Handle file uploads
            $files = $this->request->getFiles();
            
            foreach ($files['wedding'] as $file) {
                $originalName = $file->getName();
                
                if($file->getSize() > 0 && $file->getError() == 0) {
                    $uploadDir = FCPATH . 'uploads/wedding';

                    // Check if the directory exists or create it if not
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = $file->getRandomName();
                        $uploadResult = $file->move($uploadDir, $fileName);
    
                        if($uploadResult == 1) {
                            array_push($gallery, [
                                "file_name" => $fileName,
                                "path" => $uploadDir,
                                "category" => 'wedding'
                            ]);
                            array_push($resp['wedding']['success'], $originalName);
                        } else {
                            array_push($resp['wedding']['failed'], $originalName);
                        }
                    } else {
                        log_message('info', 'failed');
                    }
                } else {
                    if ($file->getError() == 1) {
                        array_push($resp['wedding']['failed'], $originalName . ' => should be less than  10MB');
                    }
                }
            }

            // should only insert to db if gallery has values
            if($gallery != null) {
                // insert to db
                $galleryModel->insertBatch($gallery);
            }

            return json_encode($resp);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'message' => 'Sorry, there seems to be a problem on uploading your image.',
                'exception' => $e->getMessage()
            ]);
        }
    }
}
