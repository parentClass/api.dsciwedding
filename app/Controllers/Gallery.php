<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Gallery extends BaseController
{
    public function upload() {
        $is_ceremony_upload_success = false;
        $is_reception_upload_success = false;
        $is_afterparty_upload_success = false;

        $resp = [
            "ceremony" => [
                "success" => [],
                "failed" => []
            ],
            "reception" => [
                "success" => [],
                "failed" => []
            ],
            "afterparty" => [
                "success" => [],
                "failed" => []
            ]
        ];

        try {
            // Handle file uploads
            $files = $this->request->getFiles();

            if ($files['ceremony'][0]->getSize() > 0) {
                foreach ($files['ceremony'] as $file) {
                    $originalName = $file->getName();
                    $uploadDir = WRITEPATH . 'uploads/ceremony';
    
                    // Check if the directory exists or create it if not
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = $file->getRandomName();
                        $uploadResult = $file->move($uploadDir, $fileName);
    
                        if($uploadResult == 1) {
                            array_push($resp['ceremony']['success'], $originalName);
                        } else {
                            array_push($resp['ceremony']['failed'], $originalName);
                        }
                    } else {
                        log_message('info', 'failed');
                    }
                }
            }

            if ($files['reception'][0]->getSize() > 0) {
                foreach ($files['reception'] as $file) {
                    $originalName = $file->getName();
                    $uploadDir = WRITEPATH . 'uploads/reception';
    
                    // Check if the directory exists or create it if not
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = $file->getRandomName();
                        $uploadResult = $file->move($uploadDir, $fileName);
    
                        if($uploadResult == 1) {
                            array_push($resp['reception']['success'], $originalName);
                        } else {
                            array_push($resp['reception']['failed'], $originalName);
                        }
                    } else {
                        log_message('info', 'failed');
                    }
                }
            }

            if ($files['afterparty'][0]->getSize() > 0) {
                foreach ($files['afterparty'] as $file) {
                    $originalName = $file->getName();
                    $uploadDir = WRITEPATH . 'uploads/afterparty';
    
                    // Check if the directory exists or create it if not
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    if ($file->isValid() && !$file->hasMoved()) {
                        $fileName = $file->getRandomName();
                        $uploadResult = $file->move($uploadDir, $fileName);
    
                        if($uploadResult == 1) {
                            array_push($resp['afterparty']['success'], $originalName);
                        } else {
                            array_push($resp['afterparty']['failed'], $originalName);
                        }
                    } else {
                        log_message('info', 'failed');
                    }
                }
            }

            return json_encode($resp);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
        }
    }
}
